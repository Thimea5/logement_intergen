<template>
  <v-app>
    <header>
      <input type="checkbox" id="openSideMenu" class="openSideMenu">
      <label for="openSideMenu" class="menuIconToggle">
        <div class="hamb-line dia part-1"></div>
        <div class="hamb-line hor"></div>
        <div class="hamb-line dia part-2"></div>
      </label>
      <div id="profile">
        <a v-if="!isLoggedIn" href="./login" class="nav-elem fas fa-user" id="noLog"></a>

        <a v-if="isLoggedIn" class="nav-elem dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown"
          aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-user"></i>
        </a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="./UserProfile">Profil <i class="fa-solid fa-user-gear"></i></a></li>
          <li><a class="dropdown-item d-flex justify-content-between align-items-center" href="#">Deconnexion <i class="fa-solid fa-arrow-right-from-bracket"></i></a>
          </li>
        </ul>

      </div>


      <nav>
        <ul>
          <li><a href="./">Accueil</a></li>
          <li><a href="./about">À Propos</a></li>
          <li><a href="./login">Connexion</a></li>
        </ul>
      </nav>
    </header>

    <v-main>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>

import './assets/header.css';

console.log("tamer: "+sessionStorage.getItem('user'))

export default {
  name: 'App',

  data() {
    return {
      isLoggedIn: sessionStorage.getItem('user') != null
    };
  },

}
</script>

<style>

 
</style>
