// Configuration de Vite, l'outil qui lance le serveur de développement et construit le site.
import { defineConfig } from 'vite' // fonction d'aide fournie par Vite (donne l'autocomplétion dans VS Code)
import vue from '@vitejs/plugin-vue' // plugin qui apprend à Vite à lire les fichiers .vue

export default defineConfig({
  plugins: [vue()], // on active le plugin Vue
  base: './' // chemins relatifs : permet de publier le site dans un sous-dossier (ex. GitHub Pages)
})
