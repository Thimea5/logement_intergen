<template>
  <v-app>
    <v-app-bar :elevation="0">
      <v-menu transition="slide-x-transition">
        <template v-slot:activator="{ props }">
          <v-btn icon v-bind="props">
            <img src="../public/dark-favicon.ico" alt="Icon" style="width: 28px; height: 28px" />
          </v-btn>
        </template>

        <v-list>
          <v-list-item @click="navigate('/')">
            <v-list-item-title>Accueil</v-list-item-title>
          </v-list-item>
          <v-list-item @click="navigate('/about')">
            <v-list-item-title>About</v-list-item-title>
          </v-list-item>
          <v-list-item @click="navigate('/register')" v-if="!userStore.isAuthenticated">
            <v-list-item-title>Inscription</v-list-item-title>
          </v-list-item>
          <v-list-item @click="navigate('/login')" v-if="!userStore.isAuthenticated">
            <v-list-item-title>Connexion</v-list-item-title>
          </v-list-item>
          <v-list-item @click="navigate('/user-profile')" v-if="userStore.isAuthenticated">
            <v-list-item-title>Informations</v-list-item-title>
          </v-list-item>
          <v-list-item @click="navigate('/home-search')" v-if="userStore.isAuthenticated">
            <v-list-item-title>Recherche</v-list-item-title>
          </v-list-item>
          <v-list-item @click="navigate('/legal-notices')">
            <v-list-item-title>Mentions Légales</v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </v-app-bar>
    <router-view></router-view>

    <v-bottom-navigation v-if="userStore.isAuthenticated" color="indigo">
      <v-btn @click="navigate('/')">
        <v-icon>
          <img src="../public/dark-favicon.ico" alt="Icon" style="width: 16px; height: 16px" />
        </v-icon>
        Accueil
      </v-btn>
      <v-btn @click="navigate('/map')">
        <v-icon>mdi-magnify</v-icon>
        Rechercher
      </v-btn>
      <v-btn>
        <v-icon class="fa-solid fa-comments"></v-icon>
        Messages
      </v-btn>
      <v-btn @click="navigate('/user-profile')">
        <v-icon>mdi-account</v-icon>
        Profil
      </v-btn>
    </v-bottom-navigation>
  </v-app>
</template>

<script>
import { useUserStore } from "./stores/userStore";

export default {
  name: "App",

  data() {
    return {
      userStore: useUserStore(),
      isHomePage: true,
    };
  },

  mounted() {
    this.userStore.loadUserFromSession();
  },

  methods: {
    navigate(path) {
      this.$router.push(path);
    },
  },
};
</script>

<style>
/* Ajouté ici du style css dans la vue si besoin, 
  le mieux reste de faire un fichier à part dans les assets/ */
</style>
