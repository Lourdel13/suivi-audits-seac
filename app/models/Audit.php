<?php

class Audit
{
    // Valeur en base => libellé affiché
    const STATUTS = [
        'a_planifier' => 'À planifier',
        'planifie'    => 'Planifié',
        'realise'     => 'Réalisé',
    ];

    public static function lister(?int $siteId, ?string $statut): array
    {
        $sql = 'SELECT a.*, s.nom AS site_nom, s.ville AS site_ville
                FROM audits a JOIN sites s ON s.id = a.site_id
                WHERE 1 = 1';
        $params = [];
        if ($siteId !== null) {
            $sql .= ' AND a.site_id = ?';
            $params[] = $siteId;
        }
        if ($statut !== null) {
            $sql .= ' AND a.statut = ?';
            $params[] = $statut;
        }
        $sql .= ' ORDER BY a.date_prevue';

        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function trouver(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM audits WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch() ?: null;
    }

    public static function creer(array $d): void
    {
        $stmt = db()->prepare('INSERT INTO audits (site_id, date_prevue, statut, type_audit, commentaire)
                               VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$d['site_id'], $d['date_prevue'], $d['statut'], $d['type_audit'], $d['commentaire']]);
    }

    public static function modifier(int $id, array $d): void
    {
        $stmt = db()->prepare('UPDATE audits SET site_id = ?, date_prevue = ?, statut = ?, type_audit = ?, commentaire = ?
                               WHERE id = ?');
        $stmt->execute([$d['site_id'], $d['date_prevue'], $d['statut'], $d['type_audit'], $d['commentaire'], $id]);
    }

    // En retard = date prévue dépassée et pas encore réalisé.
    // Les dates au format AAAA-MM-JJ se comparent correctement en tant que chaînes.
    public static function estEnRetard(array $audit, string $aujourdhui): bool
    {
        return $audit['statut'] !== 'realise' && $audit['date_prevue'] < $aujourdhui;
    }

    // Vérifie les données envoyées par le formulaire. Retourne [données propres, erreurs].
    public static function valider(array $entree, array $idsSitesValides): array
    {
        $erreurs = [];

        $siteId = filter_var($entree['site_id'] ?? '', FILTER_VALIDATE_INT);
        if ($siteId === false || !in_array($siteId, $idsSitesValides, true)) {
            $erreurs['site_id'] = 'Choisissez un site.';
        }

        $date = trim($entree['date_prevue'] ?? '');
        $dt = DateTime::createFromFormat('Y-m-d', $date);
        if (!$dt || $dt->format('Y-m-d') !== $date) {
            $erreurs['date_prevue'] = 'Date invalide.';
        }

        $statut = $entree['statut'] ?? '';
        if (!isset(self::STATUTS[$statut])) {
            $erreurs['statut'] = 'Statut invalide.';
        }

        $type = trim($entree['type_audit'] ?? '');
        if (mb_strlen($type) > 100) {
            $erreurs['type_audit'] = '100 caractères maximum.';
        }

        $commentaire = trim($entree['commentaire'] ?? '');

        $donnees = [
            'site_id'     => $siteId,
            'date_prevue' => $date,
            'statut'      => $statut,
            'type_audit'  => $type,
            'commentaire' => $commentaire === '' ? null : $commentaire,
        ];
        return [$donnees, $erreurs];
    }
}
