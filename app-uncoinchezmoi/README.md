# app-uncoinchezmoi

## Stack Technique

Front-end : VueJS (+ Vuetify/Bootstrap, Vue Router, Axios, Pinia, MailJS etc.)

Back-end : Php (natif, composer si besoin, etc.) + MySql

## Aide Git

```sh
git add .
git status # étape de vérification (non obligatoire)

git commit -m "Message du commit"
git log # étape de vérification (non obligatoire)

git push origin <branch_name>
```

Quelques commandes en plus si besoin...

```sh
git branch # liste de toutes les branches en locales

git fetch
git pull origin <branch_name>

git checkout <branch_name>
git checkout -b <branch_name> # le -b permet de créer la branche
```

## Mémo commande

```sh
# le projet doit être sur wampserver, dans www/ (c'est pour pouvoir héberger la partie back-end)
npm create vue@latest
cd front-end
npm install
npm install vuetify
npm install @mdi/font
npm install axios
npm install vue-router
npm install vite-plugin-vuetify
npm install pinia
npm install jwt-decode
npm install leaflet ?


composer require firebase/php-jwt
composer require vlucas/phpdotenv

```

```sh
# côté front-end,
npm run dev # lancement du projet en local (port 5173 par défaut)

# côté back-end,
composer install
```

## Documentations

- Outils développement : [VSCode](https://code.visualstudio.com/)
- Documentation VueJS : [VueJS](https://vuejs.org/guide/introduction.html)
