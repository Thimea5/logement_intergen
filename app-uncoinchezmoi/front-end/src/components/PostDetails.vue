<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container>
      <div class="image-carousel">
        <v-row class="align-center justify-center">
          <v-btn icon="mdi-chevron-left" variant="plain" size="large" @click="prevImage"></v-btn>
          <v-img
            :src="getImageSrc(currentImageIndex)"
            width="65vw"
            height="250px"
            class="rounded"
            @click="openZoomDialog"
          >
          </v-img>
          <v-btn icon="mdi-chevron-right" variant="plain" size="large" @click="nextImage"></v-btn>
        </v-row>
      </div>

      <v-dialog v-model="zoomDialog" style="background-color: 'black'">
        <v-img :src="getImageSrc(currentImageIndex)" width="100%"></v-img>
      </v-dialog>

      <div>
        <v-card v-if="post">
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

        <v-card class="p-3">
          <v-card-title>Commentaires & Avis</v-card-title>

          <v-cols v-for="comment in comments" :key="comment.id" cols="12" sm="6" md="4">
            <v-card class="m-2">
              <v-card-title>Quentin </v-card-title>
              <v-card-text>BLALBLALA - {{ comment.id }}</v-card-text>
            </v-card>
          </v-cols>
        </v-card>

        <v-btn @click="contactHost()">Contacter le propriétaire</v-btn>
        <v-btn @click="commentPostById(post.id)">Laissez un commentaire</v-btn>
        <v-btn @click="reportPostById(post.id)">Signalez l'annonce</v-btn>
      </div>
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

    openZoomDialog() {
      this.zoomDialog = true;
    },

    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>

<style scoped>
.image-carousel {
  display: flex;
  align-items: center;
  justify-content: center;
}
.rounded {
  border-radius: 8px;
}
</style>
