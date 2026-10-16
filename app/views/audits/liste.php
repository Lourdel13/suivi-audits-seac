<div class="entete">
    <h1>Audits</h1>
    <a class="bouton" href="index.php?action=formulaire">+ Ajouter un audit</a>
</div>

<form method="get" class="filtres">
    <label>Site
        <select name="site">
            <option value="">Tous</option>
            <?php foreach ($sites as $s): ?>
                <option value="<?= e($s['id']) ?>" <?= $siteId == $s['id'] ? 'selected' : '' ?>><?= e($s['nom']) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <label>Statut
        <select name="statut">
            <option value="">Tous</option>
            <?php foreach (Audit::STATUTS as $valeur => $libelle): ?>
                <option value="<?= e($valeur) ?>" <?= $statut === $valeur ? 'selected' : '' ?>><?= e($libelle) ?></option>
            <?php endforeach; ?>
        </select>
    </label>
    <button type="submit">Filtrer</button>
    <a href="index.php">Réinitialiser</a>
</form>

<?php if ($nbRetard > 0): ?>
    <p class="alerte"><?= $nbRetard ?> audit(s) en retard dans cette liste</p>
<?php endif; ?>

<table>
    <thead>
        <tr><th>Site</th><th>Type</th><th>Date prévue</th><th>Statut</th><th></th></tr>
    </thead>
    <tbody>
        <?php foreach ($audits as $a): ?>
            <tr class="<?= $a['en_retard'] ? 'retard' : '' ?>">
                <td><?= e($a['site_nom']) ?> <small>(<?= e($a['site_ville']) ?>)</small></td>
                <td><?= e($a['type_audit']) ?></td>
                <td>
                    <?= e(date('d/m/Y', strtotime($a['date_prevue']))) ?>
                    <?php if ($a['en_retard']): ?><span class="badge-retard">En retard</span><?php endif; ?>
                </td>
                <td><span class="statut statut-<?= e($a['statut']) ?>"><?= e(Audit::STATUTS[$a['statut']]) ?></span></td>
                <td><a href="index.php?action=formulaire&amp;id=<?= e($a['id']) ?>">Modifier</a></td>
            </tr>
        <?php endforeach; ?>
        <?php if (!$audits): ?>
            <tr><td colspan="5">Aucun audit ne correspond aux filtres.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
