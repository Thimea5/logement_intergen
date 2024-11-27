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
          <v-card-title>Commentaires & Avis</v-card-title>
          <v-cols v-for="comment in comments" :key="comment.id" cols="12" sm="6" md="4">
            <v-card class="styled-card m-2">
              <v-card-title>Quentin</v-card-title>
              <v-card-text>BLALBLALA - {{ comment.id }}</v-card-text>
            </v-card>
          </v-cols>
        </v-card>

        <div class="button-container">
          <v-btn color="primary" @click="contactHost()">Contacter le propriétaire</v-btn>
          <v-btn color="secondary" @click="commentPostById(post.id)">Laissez un commentaire</v-btn>
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

export default {
  data() {
    return {
      post: null,
      imgList: [],
      comments: [{ id: 0 }, { id: 5 }],
      currentImageIndex: 0,
      zoomDialog: false,
    };
  },

  mounted() {
    const ps = useListPostStore();
    const route = useRoute();
    const postId = route.params.id;

    this.post = ps.listHost.find((ph) => ph.idHost == postId);
    if (this.post) {
      for (let i = 1; i <= this.post.nbPhoto; i++) {
        this.imgList.push(
          `/src/assets/img/${this.post.img}/host_photo${this.post.img[this.post.img.length - 1]}_${i}.jpg`
        );
      }
    }

    console.log(this.post);
  },

  methods: {
    commentPostById(postId) {
      console.log("TODO comment");
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
