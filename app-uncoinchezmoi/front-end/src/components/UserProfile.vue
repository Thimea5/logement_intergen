<template>
  <v-main>
    <v-container class="d-flex flex-column">
      <h1>Bonjour, {{ this.user.firstname }} {{ user.lastname }}</h1>
      <p class="text-danger" v-if="!user.complete">Veuillez compléter votre profile pour profiter de toutes les fonctionnalités de l'application</p>
      <v-card class="my-5">
        <v-card-text>Coordonnées : </v-card-text>
        <ul class="list-unstyled ms-5">
          <li><v-icon class="me-5 my-5">mdi-phone-outline</v-icon>{{ user.tel }}</li>
          <li><v-icon class="me-5 my-5">mdi-email-outline</v-icon>{{ user.mail }}</li>
        </ul>
        <v-card-text>Identité vérifié ?</v-card-text>
      </v-card>

      <v-card class="my-5">
        <v-card-title class="headline">A propos de vous</v-card-title>
        <v-card-text>TODO ici</v-card-text>
      </v-card>
      <v-btn @click="logOut()" color="primary">Déconnexion</v-btn>
    </v-container>
  </v-main>
</template>

<script>
export default {
  name: "UserProfile",

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
    };
  },
  methods: {
    logOut() {
      sessionStorage.clear();

      window.location.replace("/");
      //this.$router.push("/");
    },
  },
};
</script>

<style>
.full-height {
  min-height: 100vh;
}
</style>
