<template>
  <v-main class="d-flex flex-column justify-content-between align-items-center">
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
      <v-app-title>Bonjour, {{ user.firstname }} {{ user.lastname }}</v-app-title>
    </v-app-bar>

    <v-container class="d-flex flex-column align-center">
      <p class="text-danger" v-if="!user.complete">
        Attention, veuillez compléter les informations de votre profil pour profiter de toutes les fonctionnalités de
        l'application
      </p>

      <v-card class="my-2" width="100%">
        <v-card-title>Informations</v-card-title>
        <v-card-text>
          <ul class="list-unstyled ms-5">
            <li><v-icon class="me-5 my-1">mdi-check-decagram</v-icon>Identité vérifié</li>
            <li><v-icon class="me-5 my-1">mdi-phone-outline</v-icon>{{ user.tel }}</li>
            <li><v-icon class="me-5 my-1">mdi-email-outline</v-icon>{{ user.mail }}</li>
            <li><v-icon class="me-5 my-1">mdi-calendar</v-icon>{{ user.birthdate }}</li>
          </ul>
        </v-card-text>
      </v-card>

      <v-card class="my-2" width="100%">
        <v-card-title class="headline">A propos de vous</v-card-title>
        <v-card-text
          >TODO ici ?
          <p>{{ user }}</p>
        </v-card-text>
      </v-card>

      <v-btn class="w-100 rounded-pill mb-5" color="#4F685D" @click="modifStep1 = true"> Modifier mon profil </v-btn>

      <v-btn v-if="user.type == 'host'" class="w-100 rounded-pill mb-5" color="#4F685D" @click="navigate('/view-post')">Mon logement</v-btn>

      <v-btn class="w-100 rounded-pill mb-2" color="#4F685D" @click="logOut()">Déconnexion</v-btn>
    </v-container>

    <template>
      <v-dialog v-model="modifStep1" transition="dialog-bottom-transition" fullscreen>
        <v-card class="px-3">
          <div class="d-flex flex-row my-2">
            <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="modifStep1 = false"></v-btn>
            <v-card-title class="my-2"> Modifier </v-card-title>
          </div>
          <v-card-text>
            <v-text-field clearable label="Adresse mail" v-model="user.mail"></v-text-field>
            <v-text-field clearable label="Prénom" v-model="user.firstname"></v-text-field>
            <v-text-field clearable label="Nom" v-model="user.lastname"></v-text-field>
            <v-date-input label="Date de naissance" v-model="dateModel"></v-date-input>
            <v-text-field clearable label="Téléphone" v-model="user.tel"></v-text-field>
            <v-btn class="w-100 rounded-pill mb-2" color="#4F685D" @click="editUserProfile()">Sauvegarder</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>
    </template>
  </v-main>
</template>

<script>
import { VDateInput } from "vuetify/labs/VDateInput";
import axios from "axios";

export default {
  name: "UserProfile",

  components: {
    VDateInput,
  },

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      modifStep1: false,
      isHost: false,
      dateModel: null,
    };
  },
  mounted() {
    //console.log("mounted");
    this.isHost = this.user.type == "host";
    //console.log(this.user.birthdate);
    this.dateModel = new Date(this.user.birthdate);
  },

  methods: {
    logOut() {
      sessionStorage.clear();

      window.location.replace("/");
    },
    goBack() {
      this.$router.go(-1);
    },
    editUserProfile() {
      this.user.birthdate =
        this.dateModel.getFullYear() + "-" + (this.dateModel.getMonth() + 1) + "-" + this.dateModel.getDate();

      const userModif = {
        mail: this.user.mail,
        firstname: this.user.firstname,
        lastname: this.user.lastname,
        birthdate: this.user.birthdate,
        tel: this.user.tel,
      };
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(
          apiUrl + "/services/editUser.php",
          { usr: userModif, id: this.user.id },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
        .then((result) => {
          //console.log(result);
        })
        .catch((error) => {
          console.error(error);
        });
    },
    navigate(path) {
      this.$router.push(path);
    }
  },
};
</script>

<style>
.full-height {
  min-height: 100vh;
}
</style>
