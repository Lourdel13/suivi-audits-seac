# Suivi des audits — exercice de candidature SEAC

Application web pour qu'un responsable suive les audits de ses usines :
liste, filtres par site et statut, audits en retard mis en évidence, ajout et modification.

**Technologies :** PHP 8 (sans framework), MySQL/MariaDB via PDO, HTML/CSS. Architecture MVC.

## Lancer le projet (WAMP)

1. Placer le dossier dans `C:\wamp64\www\SiteSEAC` et démarrer WAMP.
2. Dans phpMyAdmin, onglet **Importer** : importer `database/schema.sql`, puis `database/seed.sql`.
3. Copier `config/config.example.php` en `config/config.php` et adapter les identifiants si besoin.
4. Ouvrir <http://localhost/SiteSEAC/public/>

Tests de la logique métier (sans base de données) :

```
php tests/test_audit.php
```

## Structure

```
public/            point d'entrée (index.php) et CSS, seul dossier exposé au navigateur
app/models/        accès aux données (requêtes SQL préparées) et règles métier
app/controllers/   lit la requête, appelle le modèle, choisit la vue
app/views/         gabarits HTML
database/          schéma et données fictives
tests/             tests de la logique (retard, validation)
```

## Charte graphique

Couleurs et polices reprises du site seac-gf.fr. Logo © SEAC, utilisé uniquement dans le cadre de cette candidature.

## Hypothèses

- **Date prévue** = date à laquelle l'audit doit avoir lieu, renseignée même pour un audit « à planifier » (échéance prévisionnelle).
- **En retard** = date prévue dépassée et statut différent de « réalisé ». Ce n'est pas stocké en base, c'est calculé à l'affichage.
- Un seul utilisateur (le responsable), donc pas de connexion pour cette version.

## Pistes non réalisées

Vue calendrier, tableau de bord chiffré, alerte avant échéance, export de la liste.
