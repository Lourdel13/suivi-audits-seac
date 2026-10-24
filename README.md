# Suivi des audits – exercice de candidature SEAC

Application web pour suivre les audits réalisés dans une dizaine d'usines.

## Lancer le projet

Prérequis : Node.js 18 ou plus.

```bash
npm install     # installe Vue et Vite
npm run dev     # lance le site sur http://localhost:5173
npm test        # lance les tests de la règle "en retard"
```

## Fonctionnalités

- Liste des audits (site, type, date prévue, statut), triée par date
- Filtres par site et par statut, plus un filtre « En retard »
- Audits en retard visibles en un coup d'œil : ligne rouge, badge « En retard » et compteur cliquable en haut de la page
- Ajout et modification d'un audit

## Technologies

- **Vue.js 3** avec **Vite** (Vue est utilisé chez SEAC selon la fiche de poste)
- Données sauvegardées dans le `localStorage` du navigateur : pas de serveur ni de base de données

## Organisation du code (esprit MVC)

| Fichier | Rôle |
|---------|------|
| `src/utils/audits.js` | Modèle : listes de référence et règles métier (date du jour, retard, format de date) |
| `src/data/auditsInitiaux.js` | Données de test fictives, chargées au premier lancement |
| `src/App.vue` (`<script setup>`) | Contrôleur : état, filtres, ajout et modification |
| `src/App.vue` (`<template>`) | Vue : l'affichage HTML |
| `tests/audits.test.js` | Tests de la logique métier |

## Hypothèses

- **En retard** = la date prévue est passée **et** l'audit n'est pas « Réalisé ». Un audit prévu aujourd'hui n'est pas encore en retard.
- Un audit « À planifier » a quand même une date : c'est l'échéance avant laquelle il doit être organisé.
- J'ai ajouté un champ **type** (Qualité, Sécurité, Environnement), qui n'était pas demandé mais me semble utile.
- Un seul utilisateur (le responsable) : pas de connexion.
- Les données restent dans le navigateur. Pour les réinitialiser, il faut vider le localStorage (outils de développement, onglet Application).

## Journal d'usage de l'IA

Voir [JOURNAL_IA.md](JOURNAL_IA.md).
