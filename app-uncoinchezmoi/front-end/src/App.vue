<template>
  <v-app>
    <v-app-bar app color="#61442D" dark>
      <v-menu transition="slide-x-transition">
        <template v-slot:activator="{ props }">
          <v-btn icon="mdi-dots-vertical" variant="text" v-bind="props"></v-btn>
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
      
      <v-spacer></v-spacer>

      <v-btn icon>
        <i class="fa-solid fa-circle-user"></i>
      </v-btn>
    </v-app-bar>

    <router-view></router-view>
  </v-app>
</template>

<script>
  import { useUserStore } from './stores/userStore';

  export default {
    name: 'App',

    data() {
      return {
        userStore: useUserStore() 
      };
    },

    mounted() {
      this.userStore.loadUserFromSession(); 
    },

    methods: {
      navigate(path) {
        this.$router.push(path);
      }
    },
  }
</script>

<style>
  /* Ajouté ici du style css dans la vue si besoin, 
  le mieux reste de faire un fichier à part dans les assets/ */
</style>
