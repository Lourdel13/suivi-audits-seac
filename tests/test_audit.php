<?php
// Tests de la logique métier, sans base de données.
// Lancer : php tests/test_audit.php
require __DIR__ . '/../app/models/Audit.php';

$echecs = 0;
function verifier(string $nom, bool $ok): void
{
    global $echecs;
    echo ($ok ? 'OK    ' : 'ECHEC ') . $nom . PHP_EOL;
    if (!$ok) $echecs++;
}

// estEnRetard
$auj = '2026-10-17';
verifier('planifié, date passée => retard', Audit::estEnRetard(['statut' => 'planifie', 'date_prevue' => '2026-10-16'], $auj));
verifier('à planifier, date passée => retard', Audit::estEnRetard(['statut' => 'a_planifier', 'date_prevue' => '2026-01-01'], $auj));
verifier('réalisé, date passée => pas en retard', !Audit::estEnRetard(['statut' => 'realise', 'date_prevue' => '2026-10-01'], $auj));
verifier('planifié aujourd\'hui => pas en retard', !Audit::estEnRetard(['statut' => 'planifie', 'date_prevue' => $auj], $auj));
verifier('planifié, date future => pas en retard', !Audit::estEnRetard(['statut' => 'planifie', 'date_prevue' => '2026-12-01'], $auj));

// valider
$valide = ['site_id' => '2', 'date_prevue' => '2026-11-03', 'statut' => 'planifie', 'type_audit' => 'Qualité', 'commentaire' => ''];
[$d, $err] = Audit::valider($valide, [1, 2, 3]);
verifier('saisie valide => aucune erreur', $err === []);
verifier('commentaire vide => null', $d['commentaire'] === null);

[, $err] = Audit::valider(['site_id' => '99'] + $valide, [1, 2, 3]);
verifier('site inexistant => erreur', isset($err['site_id']));
[, $err] = Audit::valider(['date_prevue' => '2026-02-30'] + $valide, [1, 2, 3]);
verifier('date impossible => erreur', isset($err['date_prevue']));
[, $err] = Audit::valider(['statut' => 'termine'] + $valide, [1, 2, 3]);
verifier('statut inconnu => erreur', isset($err['statut']));

echo $echecs ? "$echecs test(s) en échec" . PHP_EOL : 'Tous les tests passent' . PHP_EOL;
exit($echecs ? 1 : 0);
