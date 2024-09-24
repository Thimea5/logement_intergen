<template>
  <v-app>
    <header></header>
    <input type="checkbox" id="openSideMenu" class="openSideMenu">
    <label for="openSideMenu" class="menuIconToggle">
      <div class="hamb-line dia part-1"></div>
      <div class="hamb-line hor"></div>
      <div class="hamb-line dia part-2"></div>
    </label>
    <a href="./login" class="nav-elem fa fa-solid fa-circle-user fa-xl"></a>
    <nav>
      <ul>
        <li><a href="./">Accueil</a></li>
        <li><a href="./about">À Propos</a></li>
        <li><a href="./login">Connexion</a></li>
      </ul>
    </nav>

    <v-main>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>

import './assets/header.css';

export default {
  name: 'App'
}
</script>

<style>

 
</style>
