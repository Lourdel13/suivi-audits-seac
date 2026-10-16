<?php

class AuditController
{
    // Liste des audits avec filtres site / statut
    public function liste(): void
    {
        $siteId = filter_input(INPUT_GET, 'site', FILTER_VALIDATE_INT) ?: null;
        $statut = $_GET['statut'] ?? '';
        if (!isset(Audit::STATUTS[$statut])) {
            $statut = null;
        }

        $aujourdhui = date('Y-m-d');
        $audits = Audit::lister($siteId, $statut);
        $nbRetard = 0;
        foreach ($audits as &$audit) {
            $audit['en_retard'] = Audit::estEnRetard($audit, $aujourdhui);
            $nbRetard += $audit['en_retard'] ? 1 : 0;
        }
        unset($audit);

        render('audits/liste', [
            'audits'   => $audits,
            'sites'    => Site::tous(),
            'siteId'   => $siteId,
            'statut'   => $statut,
            'nbRetard' => $nbRetard,
        ]);
    }

    // Formulaire d'ajout (sans id) ou de modification (avec id)
    public function formulaire(): void
    {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: null;
        $audit = $id ? Audit::trouver($id) : null;
        if ($id && !$audit) {
            http_response_code(404);
            exit('Audit introuvable.');
        }

        render('audits/formulaire', [
            'id'      => $id,
            'audit'   => $audit ?? ['site_id' => '', 'date_prevue' => '', 'statut' => 'a_planifier', 'type_audit' => '', 'commentaire' => ''],
            'sites'   => Site::tous(),
            'erreurs' => [],
        ]);
    }

    // Traitement du formulaire (POST)
    public function enregistrer(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
        if ($id && !Audit::trouver($id)) {
            http_response_code(404);
            exit('Audit introuvable.');
        }

        $sites = Site::tous();
        [$donnees, $erreurs] = Audit::valider($_POST, array_map('intval', array_column($sites, 'id')));

        if ($erreurs) {
            // On réaffiche le formulaire avec les valeurs saisies et les erreurs
            render('audits/formulaire', ['id' => $id, 'audit' => $_POST, 'sites' => $sites, 'erreurs' => $erreurs]);
            return;
        }

        if ($id) {
            Audit::modifier($id, $donnees);
        } else {
            Audit::creer($donnees);
        }
        header('Location: index.php');
        exit;
    }
}
