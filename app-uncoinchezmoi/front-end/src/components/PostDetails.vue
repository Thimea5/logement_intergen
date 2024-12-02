<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container class="d-flex flex-column justify-content-between">
      <v-row class="justify-center">
        <div class="image-container">
          <v-btn
            icon="mdi-chevron-left"
            variant="plain"
            size="large"
            class="arrow-left"
            color="white"
            @click="prevImage"
          ></v-btn>

          <v-img :src="getImageSrc(currentImageIndex)" class="fixed-image" @click="zoomDialog = true"></v-img>

          <v-btn
            icon="mdi-chevron-right"
            variant="plain"
            size="large"
            class="arrow-right"
            color="white"
            @click="nextImage"
          ></v-btn>
        </div>

        <v-card class="styled-card" v-if="post">
          <v-card-title>{{ post.type_logement }} de TODO</v-card-title>
          <v-card-subtitle>
            <v-icon>mdi-map-marker</v-icon> {{ post.address }} - {{ post.postalCode }} {{ post.city }}
          </v-card-subtitle>
          <v-card-text>
            <p>Taille : {{ post.size }} m²</p>
            <p>Loyer : {{ post.price }} €/Mois</p>
            <p>Services : TODO ICONE</p>
          </v-card-text>
        </v-card>

        <v-card class="styled-card p-3">
          <v-card-title class="text-h5 font-weight-bold">Commentaires & Avis</v-card-title>
          <v-row>
            <v-col v-if="comments.length == 0">
              <v-card class="styled-card m-2" color="red">
                <v-card-title class="text-h6 font-weight-bold">Aucun Commentaire</v-card-title>
              </v-card>
            </v-col>
            <v-col v-else v-for="comment in comments" :key="comment.id" cols="12" sm="6" md="4">
              <v-card class="styled-card m-2" color="red">
                <v-card-title class="text-h6 font-weight-bold">{{
                  comment.author == " " ? "Anonyme" : comment.author
                }}</v-card-title>
                <v-card-subtitle class="text-caption grey--text">{{ formatDate(comment.createdAt) }}</v-card-subtitle>
                <v-card-text class="mt-2">
                  <p>{{ comment.text }}</p>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-card>

        <div class="button-container">
          <v-btn color="primary" @click="contactHost()">Contacter le propriétaire</v-btn>
          <v-btn color="secondary" @click="commentPostById(post.idHost)">Laissez un commentaire</v-btn>
          <v-btn color="error" @click="reportPostById(post.id)">Signalez l'annonce</v-btn>
        </div>
      </v-row>

      <v-dialog v-model="zoomDialog" fullscreen class="dialog-black-background">
        <v-img :src="getImageSrc(currentImageIndex)" class="zoomed-image">
          <v-btn
            icon
            color="white"
            rounded="circle"
            class="position-absolute top-0 right-0 m-1"
            @click="zoomDialog = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-img>
      </v-dialog>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import { useRoute } from "vue-router";
import axios from "axios";

export default {
  data() {
    return {
      post: null,
      imgList: [],
      comments: [],
      currentImageIndex: 0,
      zoomDialog: false,
    };
  },

  mounted() {
    const ps = useListPostStore();
    const route = useRoute();
    const postId = route.params.id;
    console.log(postId);

    this.post = ps.listPost.find((ph) => ph.idPost == postId);
    if (this.post) {
      for (let i = 1; i <= this.post.nbPhoto; i++) {
        this.imgList.push(
          `/src/assets/img/${this.post.img}/host_photo${this.post.img[this.post.img.length - 1]}_${i}.jpg`
        );
      }
    }
    //console.log(this.imgList);

    // Chargement des commentaires du post
    const apiUrl = import.meta.env.VITE_API_URL;
    axios
      .get(apiUrl + "/services/commentsManager.php", {
        params: {
          id: this.post.idPost,
        },
        headers: {
          "Content-Type": "application/json",
        },
      })
      .then((result) => {
        if (result.status === 200 && result.data["success"]) {
          const res = result.data["comments"];
          //console.log("resComments", res);
          for (let i = 0; i < res.length; i++) {
            this.comments.push({
              id: res[i]["id"],
              text: res[i]["content"],
              createdAt: res[i]["creation_date"],
              author: res[i]["nameAuthor"],
            });
          }
        }
      })
      .catch((error) => {
        console.error(error);
      });

    this.comments.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
  },

  methods: {
    formatDate(date) {
      const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      };
      return new Date(date).toLocaleDateString("fr-FR", options).replace(":", "h");
    },

    commentPostById(postId) {
      this.$router.push({ name: "NewComment", params: { id: postId } });
    },

    reportPostById(postId) {
      console.log("TODO report");
    },

    contactHost() {
      console.log("TODO msg");
    },

    getImageSrc(index) {
      return this.imgList[index] || "";
    },

    prevImage() {
      this.currentImageIndex = (this.currentImageIndex - 1 + this.imgList.length) % this.imgList.length;
    },

    nextImage() {
      this.currentImageIndex = (this.currentImageIndex + 1) % this.imgList.length;
    },

    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped>
.image-container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 400px;
  height: 35vh;
}

.fixed-image {
  object-fit: cover;
  width: 100%;
  height: 100%;
  border-radius: 8px;
}

.arrow-left,
.arrow-right {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
}

.arrow-left {
  left: 10px;
}

.arrow-right {
  right: 10px;
}

.dialog-black-background {
  background-color: black !important;
  display: flex;
  justify-content: center;
  align-items: center;
}

.zoomed-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

.button-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 100%;
  gap: 16px;
  margin: 5%;
}
.styled-card {
  margin: 5%;
  background-color: #3b6475;
  border-radius: 8px;
  width: 100%;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
