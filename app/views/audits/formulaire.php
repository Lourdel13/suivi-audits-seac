<h1><?= $id ? 'Modifier l\'audit' : 'Ajouter un audit' ?></h1>

<form method="post" action="index.php?action=enregistrer" class="formulaire">
    <?php if ($id): ?><input type="hidden" name="id" value="<?= e($id) ?>"><?php endif; ?>

    <label>Site
        <select name="site_id" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($sites as $s): ?>
                <option value="<?= e($s['id']) ?>" <?= ($audit['site_id'] ?? '') == $s['id'] ? 'selected' : '' ?>><?= e($s['nom']) ?> (<?= e($s['ville']) ?>)</option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($erreurs['site_id'])): ?><span class="erreur"><?= e($erreurs['site_id']) ?></span><?php endif; ?>
    </label>

    <label>Date prévue
        <input type="date" name="date_prevue" value="<?= e($audit['date_prevue'] ?? '') ?>" required>
        <?php if (isset($erreurs['date_prevue'])): ?><span class="erreur"><?= e($erreurs['date_prevue']) ?></span><?php endif; ?>
    </label>

    <label>Statut
        <select name="statut" required>
            <?php foreach (Audit::STATUTS as $valeur => $libelle): ?>
                <option value="<?= e($valeur) ?>" <?= ($audit['statut'] ?? '') === $valeur ? 'selected' : '' ?>><?= e($libelle) ?></option>
            <?php endforeach; ?>
        </select>
        <?php if (isset($erreurs['statut'])): ?><span class="erreur"><?= e($erreurs['statut']) ?></span><?php endif; ?>
    </label>

    <label>Type d'audit
        <input type="text" name="type_audit" maxlength="100" value="<?= e($audit['type_audit'] ?? '') ?>" placeholder="Sécurité, Qualité…">
        <?php if (isset($erreurs['type_audit'])): ?><span class="erreur"><?= e($erreurs['type_audit']) ?></span><?php endif; ?>
    </label>

    <label>Commentaire
        <textarea name="commentaire" rows="3"><?= e($audit['commentaire'] ?? '') ?></textarea>
    </label>

    <div>
        <button type="submit" class="bouton">Enregistrer</button>
        <a href="index.php">Annuler</a>
    </div>
</form>
