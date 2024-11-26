<template>
  <v-main>
    <v-container class="d-flex flex-column">
      <h1>{{ this.user.firstname }} {{ user.lastname }}</h1>
      <v-card>
        <v-card-text>Coordonnées : </v-card-text>
        <ul>
          <li>Téléphone : {{ user.telephone }}</li>
          <li>Adresse mail : {{ user.mail }}</li>
        </ul>
        <v-card-text>Identité vérifié ?</v-card-text>
      </v-card>

      <v-card>
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
