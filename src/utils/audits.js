// Logique "métier" des audits, sans rien d'affichage : c'est la partie Modèle du MVC.
// Ces fonctions sont de simples fonctions JavaScript, donc faciles à tester (voir tests/audits.test.js).

// Liste des 10 usines suivies (noms fictifs basés sur des villes).
export const SITES = [
  'Bordeaux', 'Lille', 'Lyon', 'Marseille', 'Montpellier',
  'Nantes', 'Orléans', 'Rennes', 'Strasbourg', 'Toulouse'
]

// Types d'audit possibles (hypothèse de ma part : non imposé par le brief).
export const TYPES = ['Qualité', 'Sécurité', 'Environnement']

// Statuts : la clé (à gauche) est stockée dans les données, le libellé (à droite) est affiché à l'écran.
export const STATUTS = {
  a_planifier: 'À planifier',
  planifie: 'Planifié',
  realise: 'Réalisé'
}

// Renvoie la date du jour au format "AAAA-MM-JJ" (le même format que les dates des audits).
// On n'utilise pas toISOString() car elle donne l'heure UTC : entre minuit et 2h en France, on aurait la date de la veille.
export function aujourdhui() {
  const d = new Date() // date et heure actuelles, sur l'heure locale du PC
  const mois = String(d.getMonth() + 1).padStart(2, '0') // getMonth() commence à 0 (janvier = 0), d'où le +1 ; padStart ajoute un 0 devant : 3 -> "03"
  const jour = String(d.getDate()).padStart(2, '0') // jour du mois, sur 2 chiffres
  return `${d.getFullYear()}-${mois}-${jour}` // ex : "2026-10-20"
}

// Un audit est en retard si sa date prévue est passée ET qu'il n'est pas encore réalisé.
// Comparer deux textes "AAAA-MM-JJ" avec < fonctionne, car l'ordre alphabétique est le même que l'ordre chronologique.
export function estEnRetard(audit, dateDuJour) {
  return audit.statut !== 'realise' && audit.date < dateDuJour
}

// Transforme "2026-10-20" en "20/10/2026" pour l'affichage.
export function formaterDate(dateIso) {
  return dateIso.split('-').reverse().join('/') // découpe en ["2026","10","20"], inverse l'ordre, recolle avec des "/"
}
