<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container class="d-flex align-center justify-center full-height">
      <div class="comment-wrapper">
        <h1 class="headline mt-5 mb-5 title">Partagez votre avis avec les autres</h1>

        <form id="form-login" @submit.prevent="addNewComment()" class="d-flex flex-column justify-space-between">
          <v-textarea clearable label="écrire un commentaire" variant="solo-filled" v-model="text"> </v-textarea>

          <div class="text-center mt-5">
            <v-btn type="submit" color="#8DA399" rounded="xl" variant="flat" block class="comment-button">
              Postez
            </v-btn>
          </div>
        </form>
      </div>
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

    addNewComment() {
      const comment = {
        content: this.text,
        idUser: this.userId,
        idPost: this.postId,
      };

      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(apiUrl + "/services/commentsManager.php", comment, {
          headers: {
            "Content-Type": "application/json",
          },
        })
        .then((result) => {
          console.log(result);
          window.location.replace(`/post-details/${this.postId}`);
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>

<style scoped>
.comment-wrapper {
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.comment-button {
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
