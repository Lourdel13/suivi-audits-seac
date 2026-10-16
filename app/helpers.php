<?php

// Connexion PDO unique, créée au premier appel.
function db(): PDO
{
    static $pdo = null;
    if ($pdo === null) {
        $c = require __DIR__ . '/../config/config.php';
        $pdo = new PDO(
            "mysql:host={$c['host']};dbname={$c['dbname']};charset=utf8mb4",
            $c['user'],
            $c['pass'],
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }
    return $pdo;
}

// Échappe une valeur avant de l'afficher dans le HTML (protection XSS).
function e($valeur): string
{
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}

// Affiche une vue à l'intérieur du gabarit commun (layout.php).
function render(string $vue, array $variables = []): void
{
    extract($variables);
    ob_start();
    require __DIR__ . "/views/$vue.php";
    $contenu = ob_get_clean();
    require __DIR__ . '/views/layout.php';
}
