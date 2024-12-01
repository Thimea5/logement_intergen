<template>
  <v-main class="d-flex flex-column justify-content-between align-items-center">
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
      <v-app-title>Bonjour, {{ user.firstname }} {{ user.lastname }}</v-app-title>
    </v-app-bar>

    <v-container class="d-flex flex-column">
      <p class="text-danger" v-if="!user.complete">
        Veuillez compléter votre profile pour profiter de toutes les fonctionnalités de l'application
      </p>
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
    <v-btn class="w-75 rounded-pill mb-5" color="#4F685D" @click="modifStep1 = true"> Modifier mon profil </v-btn>

    <template>
      <v-dialog v-model="modifStep1" transition="dialog-bottom-transition" fullscreen>
        <v-card class="px-3 d-flex flex-column justify-content-between">
          <div>
            <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="modifStep1 = false"></v-btn>
            <h1 class="headline text-center">Modification de profil</h1>
          </div>
          <div v-if="isHost"></div>
          <div v-else="isHost"></div>
        </v-card>
      </v-dialog>
    </template>
  </v-main>
</template>

<script>
export default {
  name: "UserProfile",

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      modifStep1: false,
      isHost: false,
    };
  },
  mounted() {
    console.log("mounted");
    this.isHost = this.user.type == "host";
  },

  methods: {
    logOut() {
      sessionStorage.clear();

      window.location.replace("/");
    },
    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style>
.full-height {
  min-height: 100vh;
}
</style>
