<?php
// Point d'entrée unique : toutes les pages passent par ici (index.php?action=...).
date_default_timezone_set('Europe/Paris');

require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/models/Site.php';
require __DIR__ . '/../app/models/Audit.php';
require __DIR__ . '/../app/controllers/AuditController.php';

$controleur = new AuditController();
$action = $_GET['action'] ?? 'liste';

try {
    switch ($action) {
        case 'liste':
            $controleur->liste();
            break;
        case 'formulaire':
            $controleur->formulaire();
            break;
        case 'enregistrer':
            $controleur->enregistrer();
            break;
        default:
            http_response_code(404);
            echo 'Page introuvable.';
    }
} catch (PDOException $ex) {
    http_response_code(500);
    error_log($ex->getMessage());
    echo 'Erreur de base de données. Vérifiez config/config.php et que la base seac_audits est importée.';
}
