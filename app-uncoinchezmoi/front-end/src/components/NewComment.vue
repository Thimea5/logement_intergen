<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
      <v-app-title class="ms-5"> Partagez votre avis avec les autres </v-app-title>
    </v-app-bar>

    <v-container class="d-flex flex-column align-center justify-center">
      <v-card class="px-1 m-0 w-100" rounded="lg" title="Notation" variant="flat">
        <v-item-group v-model="model" class="d-flex justify-sm-space-between px-6 pt-2 pb-6">
          <v-item v-for="n in 5" :key="n">
            <template v-slot:default="{ toggle }">
              <v-btn
                :active="model != null && model + 1 >= n"
                :icon="`mdi-numeric-${n}`"
                height="40"
                variant="text"
                width="40"
                border
                class="m-1"
                @click="toggle"
              ></v-btn>
            </template>
          </v-item>
        </v-item-group>
      </v-card>

      <form id="form-login" @submit.prevent="addNewReview()" class="d-flex flex-column">
        <v-textarea clearable class="m-0 w-100" label="écrire un commentaire" variant="solo-filled" v-model="text">
        </v-textarea>

        <div class="text-center mt-5">
          <v-btn type="submit" color="#8DA399" rounded="xl" variant="flat" block class="comment-button"> Postez </v-btn>
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
      if (this.model != undefined || this.model != null) {
        this.model++;
      } else {
        this.model = -1;
      }

      const comment = {
        score: this.model,
        content: this.text,
        idUser: this.userId,
        idPost: this.postId,
      };

      console.log(comment);

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
