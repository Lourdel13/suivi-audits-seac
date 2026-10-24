<!--
  Composant principal. Un fichier .vue contient 3 parties :
  1. <script setup> : les données et les fonctions (le Contrôleur du MVC)
  2. <template>     : le HTML affiché (la Vue du MVC)
  3. <style>        : le CSS
-->
<script setup>
// ref : crée une donnée "réactive" (quand elle change, l'écran se met à jour tout seul)
// computed : crée une valeur calculée à partir d'autres données (recalculée automatiquement)
// watch : exécute du code à chaque fois qu'une donnée change
import { ref, computed, watch } from 'vue'
import { auditsInitiaux } from './data/auditsInitiaux.js' // données de test du premier lancement
import { SITES, TYPES, STATUTS, aujourdhui, estEnRetard, formaterDate } from './utils/audits.js' // logique métier

// ---------- Données et sauvegarde ----------

const CLE_STOCKAGE = 'seac-audits' // nom sous lequel on range les audits dans le localStorage du navigateur
const sauvegarde = localStorage.getItem(CLE_STOCKAGE) // texte JSON déjà sauvegardé, ou null au tout premier lancement
const audits = ref(sauvegarde ? JSON.parse(sauvegarde) : auditsInitiaux) // la liste des audits ; JSON.parse retransforme le texte en tableau

// À chaque modification de la liste (ajout, modification), on la réécrit dans le localStorage.
// { deep: true } : surveille aussi les changements à l'intérieur des objets, pas seulement le tableau.
watch(audits, (nouvelleListe) => {
  localStorage.setItem(CLE_STOCKAGE, JSON.stringify(nouvelleListe)) // JSON.stringify transforme le tableau en texte
}, { deep: true })

const dateDuJour = aujourdhui() // calculée une seule fois au chargement de la page

// ---------- Filtres ----------

const filtreSite = ref('') // '' veut dire "tous les sites" ; relié au <select> par v-model
const filtreStatut = ref('') // '' = tous, sinon une clé de STATUTS, ou 'retard' pour ne voir que les retards

// Liste affichée : on part de tous les audits, on applique les filtres, puis on trie par date.
const auditsFiltres = computed(() => {
  return audits.value // .value : obligatoire en JavaScript pour lire le contenu d'un ref (pas dans le template)
    .filter((a) => filtreSite.value === '' || a.site === filtreSite.value) // garde l'audit si pas de filtre, ou si le site correspond
    .filter((a) => {
      if (filtreStatut.value === '') return true // pas de filtre : on garde tout
      if (filtreStatut.value === 'retard') return estEnRetard(a, dateDuJour) // filtre spécial "en retard"
      return a.statut === filtreStatut.value // sinon le statut doit correspondre
    })
    .sort((a, b) => a.date.localeCompare(b.date)) // tri par date croissante ; filter() a déjà créé une copie, donc on ne modifie pas la liste d'origine
})

// Nombre total d'audits en retard (tous sites confondus), affiché en haut de page.
const nbEnRetard = computed(() => audits.value.filter((a) => estEnRetard(a, dateDuJour)).length)

// ---------- Formulaire d'ajout / modification ----------

// null = formulaire fermé. Sinon, contient une COPIE de l'audit en cours de saisie.
// On travaille sur une copie pour que "Annuler" ne modifie rien dans la vraie liste.
const formulaire = ref(null)

function ouvrirAjout() {
  // Nouvel audit avec des valeurs par défaut ; id null = "c'est un ajout, pas une modification"
  formulaire.value = { id: null, site: SITES[0], type: TYPES[0], date: dateDuJour, statut: 'a_planifier' }
}

function ouvrirModification(audit) {
  formulaire.value = { ...audit } // { ...audit } copie toutes les propriétés de l'audit dans un nouvel objet
}

function enregistrer() {
  const saisie = formulaire.value // raccourci pour lire plus facilement
  if (saisie.id === null) {
    audits.value.push({ ...saisie, id: Date.now() }) // ajout : Date.now() (millisecondes depuis 1970) sert d'identifiant unique
  } else {
    const index = audits.value.findIndex((a) => a.id === saisie.id) // position de l'audit d'origine dans la liste
    audits.value[index] = { ...saisie } // on remplace l'ancien par la version modifiée
  }
  formulaire.value = null // ferme le formulaire
}
</script>

<template>
  <main>
    <header>
      <div class="titre">
        <!-- Chemin relatif : Vite copie l'image dans le site final et corrige le chemin tout seul -->
        <img src="./assets/logo-seac.svg" alt="Logo SEAC" />
        <h1>Suivi des audits</h1>
      </div>
      <!-- {{ }} affiche la valeur d'une donnée. Le bouton active directement le filtre "en retard". -->
      <button v-if="nbEnRetard > 0" class="alerte" @click="filtreStatut = 'retard'">
        ⚠ {{ nbEnRetard }} audit(s) en retard
      </button>
    </header>

    <!-- Barre de filtres -->
    <section class="barre">
      <label>
        Site
        <!-- v-model relie le select à filtreSite dans les deux sens : choisir une option change filtreSite -->
        <select v-model="filtreSite">
          <option value="">Tous les sites</option>
          <!-- v-for répète la balise pour chaque site ; :key aide Vue à suivre chaque élément -->
          <option v-for="site in SITES" :key="site" :value="site">{{ site }}</option>
        </select>
      </label>
      <label>
        Statut
        <select v-model="filtreStatut">
          <option value="">Tous les statuts</option>
          <!-- Parcourt l'objet STATUTS : libelle = la valeur ("À planifier"), cle = la clé ("a_planifier") -->
          <option v-for="(libelle, cle) in STATUTS" :key="cle" :value="cle">{{ libelle }}</option>
          <option value="retard">En retard</option>
        </select>
      </label>
      <!-- @click : appelle la fonction au clic -->
      <button class="principal" @click="ouvrirAjout">+ Ajouter un audit</button>
    </section>

    <!-- Formulaire : n'existe dans la page que si formulaire n'est pas null (v-if) -->
    <!-- @submit.prevent : appelle enregistrer() et empêche le rechargement de la page (comportement par défaut d'un form) -->
    <form v-if="formulaire" class="formulaire" @submit.prevent="enregistrer">
      <h2>{{ formulaire.id === null ? 'Nouvel audit' : 'Modifier l\'audit' }}</h2>
      <label>
        Site
        <select v-model="formulaire.site" required>
          <option v-for="site in SITES" :key="site" :value="site">{{ site }}</option>
        </select>
      </label>
      <label>
        Type
        <select v-model="formulaire.type" required>
          <option v-for="type in TYPES" :key="type" :value="type">{{ type }}</option>
        </select>
      </label>
      <label>
        Date prévue
        <!-- type="date" : le navigateur affiche un calendrier et renvoie la date au format AAAA-MM-JJ -->
        <!-- required : le navigateur refuse d'envoyer le formulaire si le champ est vide -->
        <input type="date" v-model="formulaire.date" required />
      </label>
      <label>
        Statut
        <select v-model="formulaire.statut" required>
          <option v-for="(libelle, cle) in STATUTS" :key="cle" :value="cle">{{ libelle }}</option>
        </select>
      </label>
      <div class="actions">
        <button type="submit" class="principal">Enregistrer</button>
        <!-- type="button" : sinon un bouton dans un form envoie le formulaire -->
        <button type="button" @click="formulaire = null">Annuler</button>
      </div>
    </form>

    <!-- Tableau des audits -->
    <table>
      <thead>
        <tr>
          <th>Site</th>
          <th>Type</th>
          <th>Date prévue</th>
          <th>Statut</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <!-- :class ajoute la classe CSS "retard" seulement si estEnRetard(...) renvoie true -->
        <tr v-for="audit in auditsFiltres" :key="audit.id" :class="{ retard: estEnRetard(audit, dateDuJour) }">
          <td>{{ audit.site }}</td>
          <td>{{ audit.type }}</td>
          <td>{{ formaterDate(audit.date) }}</td>
          <td>
            <!-- La classe du badge dépend du statut, ex : "badge realise" -->
            <span class="badge" :class="audit.statut">{{ STATUTS[audit.statut] }}</span>
            <!-- Texte en plus de la couleur : quelqu'un qui distingue mal le rouge voit quand même le retard -->
            <span v-if="estEnRetard(audit, dateDuJour)" class="badge en-retard">En retard</span>
          </td>
          <td><button @click="ouvrirModification(audit)">Modifier</button></td>
        </tr>
        <!-- Message si les filtres ne laissent aucun résultat -->
        <tr v-if="auditsFiltres.length === 0">
          <td colspan="5" class="vide">Aucun audit ne correspond aux filtres.</td>
        </tr>
      </tbody>
    </table>
  </main>
</template>

<style>
/* Couleurs de la charte SEAC (relevées sur seac-gf.fr) : on les change ici, une seule fois */
:root {
  --vert-seac: #00692a; /* vert principal du site SEAC */
  --vert-seac-clair: #e6f0ea; /* version très claire, pour les fonds */
}

/* Styles globaux de la page */
body {
  margin: 0;
  font-family: system-ui, sans-serif; /* police par défaut du système */
  background: var(--vert-seac); /* fond vert SEAC ; les blocs blancs restent lisibles par-dessus */
  color: #1f2933;
}
main {
  max-width: 1000px; /* largeur maximale du contenu */
  margin: 0 auto; /* centre le contenu */
  padding: 16px;
}
header {
  display: flex; /* titre et alerte côte à côte */
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap; /* passe à la ligne sur petit écran */
  gap: 12px;
  background: white;
  padding: 12px 16px;
  border-radius: 8px;
  border-bottom: 4px solid var(--vert-seac); /* bandeau vert sous l'en-tête */
  margin-bottom: 16px;
}
.titre { display: flex; align-items: center; gap: 16px; } /* logo et titre côte à côte */
.titre img { height: 40px; }
h1 { margin: 0; color: var(--vert-seac); }
.barre, .formulaire {
  display: flex;
  flex-wrap: wrap;
  gap: 12px;
  align-items: flex-end;
  background: white;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
}
.formulaire h2 { width: 100%; margin: 0; font-size: 1.1rem; }
label { display: flex; flex-direction: column; gap: 4px; font-size: 0.9rem; }
select, input, button { font: inherit; padding: 6px 10px; border-radius: 6px; border: 1px solid #c3c9d1; }
button { background: white; cursor: pointer; }
button.principal { background: var(--vert-seac); color: white; border-color: var(--vert-seac); }
button.alerte { background: #fee2e2; color: #991b1b; border-color: #fca5a5; font-weight: bold; }
.actions { display: flex; gap: 8px; }

/* Tableau */
table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
th { background: var(--vert-seac-clair); color: var(--vert-seac); font-size: 0.85rem; }
tr.retard { background: #fef2f2; } /* ligne en retard : fond rouge clair */
tr.retard td:first-child { border-left: 4px solid #dc2626; } /* barre rouge à gauche de la ligne */
.vide { text-align: center; color: #6b7280; }

/* Badges de statut */
.badge { display: inline-block; padding: 2px 8px; border-radius: 999px; font-size: 0.8rem; margin-right: 4px; }
.badge.a_planifier { background: #e5e7eb; }
.badge.planifie { background: #dbeafe; color: #1e40af; }
.badge.realise { background: #dcfce7; color: #166534; }
.badge.en-retard { background: #dc2626; color: white; }

/* Sur téléphone, le tableau défile horizontalement au lieu de déborder */
@media (max-width: 640px) {
  table { display: block; overflow-x: auto; }
}
</style>
