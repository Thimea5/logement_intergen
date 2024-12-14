<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
      <v-toolbar-title class="ms-5"> Partagez votre avis avec les autres </v-toolbar-title>
    </v-app-bar>

    <v-container class="d-flex flex-column w-100 p-0 m-0">
      <v-card rounded="lg" class="d-flex flex-column justify-center align-center m-2">
        <v-item-group v-model="model" class="d-flex justify-center">
          <v-item v-for="n in 5" :key="n">
            <template v-slot:default="{ toggle }">
              <v-btn :icon="true" variant="text" class="m-1 h-40" @click="toggle">
                <v-icon :class="model != null && model + 1 >= n ? 'text-warning' : 'text-grey'">
                  {{ model != null && model + 1 >= n ? "mdi-star" : "mdi-star-outline" }}
                </v-icon>
              </v-btn>
            </template>
          </v-item>
        </v-item-group>
      </v-card>

      <form id="form-login" @submit.prevent="addNewReview()" class="d-flex flex-column m-2">
        <v-textarea
          clearable
          class="m-2"
          label="écrire un commentaire"
          variant="solo-filled"
          v-model="text"
          :rules="[(v) => (v || '').length <= 300 || 'Votre commentaire est trop long']"
        >
        </v-textarea>

        <div class="text-center mt-0 m-2">
          <v-btn type="submit" color="#8DA399" rounded="xl" variant="flat" block class="comment-button">
            Partagez
          </v-btn>
        </div>
      </form>
    </v-container>
  </v-main>
</template>

<script>
import { useRoute } from "vue-router";
import axios from "axios";

export default {
  data() {
    return {
      userId: JSON.parse(sessionStorage.getItem("user")).id,
      postId: -1,
      text: "",
      model: null,
    };
  },

  mounted() {
    const route = useRoute();
    this.postId = route.params.id;
  },

  methods: {
    goBack() {
      this.$router.go(-1);
    },

    addNewReview() {
      let score = this.model !== null ? this.model : -2;
      score++;
      const comment = {
        score: score,
        content: this.text,
        idUser: this.userId,
        idPost: this.postId,
      };

      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(apiUrl + "/services/reviewsManager.php", comment, {
          headers: {
            "Content-Type": "application/json",
          },
        })
        .then((result) => {
          if (result.status === 200 && result.data["success"]) {
            window.location.replace(`/post-details/${this.postId}`);
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style scoped>
.comment-button {
  height: 56px;
  text-transform: none;
  font-size: 18pt;
}
</style>
