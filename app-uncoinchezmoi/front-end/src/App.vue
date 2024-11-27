<template>
  <v-app>
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
