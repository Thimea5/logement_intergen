<template>
  <v-app>
    <v-app-bar app color="primary" dark>
      <v-menu transition="slide-x-transition">
        <template v-slot:activator="{ props }">
          <v-btn icon="mdi-dots-vertical" variant="text" v-bind="props"></v-btn>
        </template>

        <!-- Contenu du menu déroulant -->
        <v-list>
          <v-list-item @click="goToHomePage()">
            <v-list-item-title>Accueil</v-list-item-title>
          </v-list-item>
          <v-list-item @click="goToAboutPage()">
            <v-list-item-title>About</v-list-item-title>
          </v-list-item>
          <v-list-item @click="goToRegisterPage()">
            <v-list-item-title>Inscription</v-list-item-title>
          </v-list-item>
          <v-list-item @click="goToLoginPage()">
            <v-list-item-title>Connexion</v-list-item-title>
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
  export default {
    name: 'App',

    methods: {
      goToHomePage() {
        window.location.replace("/");
        //this.$router.push("/");
      },
      goToLoginPage() {
        //this.$router.push("/login");
        window.location.replace("/login");
      },
      goToRegisterPage() { 
        window.location.replace("/register");
        //this.$router.push("/register");
      },
      goToAboutPage() {
        window.location.replace("/about");
        //this.$router.push("/about");
      }
    }
  }
</script>

<style>
  /* Ajouté ici du style css dans la vue si besoin, 
  le mieux reste de faire un fichier à part dans les assets/ */
</style>
