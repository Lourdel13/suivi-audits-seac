// Point d'entrée : c'est le premier fichier JavaScript exécuté par le navigateur.
import { createApp } from 'vue' // fonction de Vue qui crée une application
import App from './App.vue' // notre composant principal (toute l'interface)

createApp(App).mount('#app') // crée l'appli à partir de App.vue et l'affiche dans <div id="app"> de index.html
