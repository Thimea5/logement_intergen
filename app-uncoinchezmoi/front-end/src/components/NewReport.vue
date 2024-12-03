<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack()"></v-btn>
    </v-app-bar>

    <v-container class="d-flex align-center justify-center full-height">
      <div class="report-wrapper">
        <h1 class="headline mt-5 mb-5 title">Signalez un problème</h1>

        <form id="report-form" @submit.prevent="submitReport" class="d-flex flex-column justify-space-between">
          <!-- Champ pour décrire le problème -->
          <v-textarea clearable label="Décrivez le problème" variant="solo-filled" v-model="reportMessage"></v-textarea>

          <!-- Choisir une catégorie de signalement -->
          <v-select label="Catégorie" v-model="reportCategory" :items="categories" variant="solo-filled"></v-select>

          <!-- Champ pour l'adresse e-mail de l'utilisateur -->
          <v-text-field
            label="Votre e-mail"
            v-model="userEmail"
            variant="solo-filled"
            type="email"
            required
          ></v-text-field>

          <div class="text-center mt-5">
            <v-btn type="submit" color="#8DA399" rounded="xl" variant="flat" block class="report-button">
              Envoyer le signalement
            </v-btn>
          </div>
        </form>
      </div>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      userEmail: "", // L'email de l'utilisateur
      reportMessage: "", // Le message du signalement
      reportCategory: "", // La catégorie du signalement
      categories: ["Problème technique", "Contenu inapproprié", "Autre"], // Liste des catégories
    };
  },
  methods: {
    goBack() {
      this.$router.go(-1); // Retour à la page précédente
    },
    submitReport() {
      console.log("TRAVAUX EN COURS");
    },
  },
};
</script>

<style scoped>
.report-wrapper {
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.report-button {
  height: 56px;
  text-transform: none;
  font-size: 18pt;
}

.title {
  font-weight: bold;
  font-size: 32px;
  text-align: center;
}
</style>
