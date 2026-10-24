// Tests de la règle "en retard", lancés avec : npm test
// On utilise le module de test intégré à Node (pas de dépendance à installer).
import { test } from 'node:test' // fonction qui déclare un test
import assert from 'node:assert' // fonctions de vérification (si une vérification échoue, le test échoue)
import { estEnRetard, formaterDate } from '../src/utils/audits.js' // les fonctions à tester

const AUJOURDHUI = '2026-10-20' // date du jour fixée, pour que le test donne toujours le même résultat

test('date passée et pas réalisé : en retard', () => {
  assert.strictEqual(estEnRetard({ date: '2026-10-19', statut: 'planifie' }, AUJOURDHUI), true)
  assert.strictEqual(estEnRetard({ date: '2026-10-19', statut: 'a_planifier' }, AUJOURDHUI), true)
})

test('date passée mais réalisé : pas en retard', () => {
  assert.strictEqual(estEnRetard({ date: '2026-01-01', statut: 'realise' }, AUJOURDHUI), false)
})

test("audit prévu aujourd'hui : pas encore en retard", () => {
  assert.strictEqual(estEnRetard({ date: '2026-10-20', statut: 'planifie' }, AUJOURDHUI), false)
})

test('affichage de la date en français', () => {
  assert.strictEqual(formaterDate('2026-10-20'), '20/10/2026')
})
