<template>
  <v-main>
    <!-- Section sans connexion -->
    <v-container v-if="!isLoggedIn" class="d-flex flex-column justify-center align-center" style="height: 100vh">
      <img src="../assets/logo.png" alt="Logo de l'application" class="home-logo" />

      <div class="d-flex flex-column justify-content-around w-100 mb-4">
        <v-btn
          @click="navigate('/register')"
          class="h-35 p-1 m-2 text-white"
          rounded="pill"
          color="var(--green-color)"
          style="border: 1px solid var(--dark-green-color)"
        >
          S'inscrire
        </v-btn>
        <v-btn
          @click="navigate('/login')"
          class="p-1 m-2 text-white"
          rounded="pill"
          color="var(--green-color)"
          style="border: 1px solid var(--dark-green-color)"
        >
          Se connecter
        </v-btn>
      </div>

      <router-link
        to="/legal-notices"
        rounded="pill"
        class="d-flex align-items-center p-1 m-1 mt-4"
        style="border: 2px solid var(--dark-green-color); border-radius: 10px; text-decoration: none; color: black"
      >
        <v-icon class="mr-2">mdi-scale-balance</v-icon>
        Mentions légales
      </router-link>
    </v-container>

    <!-- Section avec connexion-->
    <v-container v-if="isLoggedIn" class="d-flex flex-column justify-content-center align-items-center w-100">
      <img src="../assets/logo.png" alt="Logo de l'application" class="m-1" width="100" />
      <h1 class="text-align-center">Bienvenue chez toi !</h1>
      <h3 class="mt-1">Regarde nos meilleures annonces :</h3>

      <v-carousel
        :show-arrows="false"
        height="100%"
        class="p-0 m-1"
        v-if="listDisplayByScore.length > 0"
        hide-delimiters
      >
        <v-carousel-item v-for="(elt, i) in this.listDisplayByScore" :key="i">
          <v-card class="p-0 m-0" height="100%" color="var(--background-color)" @click="goToPostDetails(elt.idPost)">
            <div>
              <v-img width="100vw" height="25vh" cover aspect-ratio="1" :src="getImageSrc(elt.idUser)">
                <v-rating
                  class="position-absolute top-0 right-0 m-1"
                  half-increments
                  size="24"
                  color="var(--background-color)"
                  v-model="elt.averageScore"
                  :length="Math.ceil(elt.averageScore)"
                  icon-label="custom icon label text {0} of {1}"
                ></v-rating>
              </v-img>
            </div>

            <v-card-title class="d-flex flex-row align-items-center m-0">
              <p class="m-0">{{ elt.type_logement }} - {{ (elt.size === null ? 0 : elt.size) + "m²" }}</p>
              <p class="m-0 ml-auto">[{{ elt.price }}€/mois]</p>
            </v-card-title>
            <v-card-subtitle class="m-0"> {{ elt.address }} - {{ elt.city }} {{ elt.postalCode }} </v-card-subtitle>
            <v-card-text class="p-3">
              <div class="d-flex flex-row align-items-center">
                <p class="m-0">Taille de la chambre : {{ elt.roomSize }} m²</p>
              </div>

              <div class="d-flex flex-row">
                <p class="p-0 m-0">Services :</p>
                <template v-for="(icon, key) in serviceIcons">
                  <v-icon
                    v-if="this.listService[elt.idPost - 1]?.[key] === 1"
                    :key="`${elt.idPost - 1}-${key}`"
                    class="mx-1"
                  >
                    {{ icon }}
                  </v-icon>
                </template>
              </div>
              <p class="p-0 m-0">
                Fréquence :
                {{ this.listService[elt.idPost - 1]?.time != 0 ? this.listService[elt.idPost - 1]?.time : 0 }}h /
                semaines
              </p>
            </v-card-text>
          </v-card>
        </v-carousel-item>
      </v-carousel>

      <v-btn class="mb-4 m-1 text-white w-100" color="var(--green-color)" @click="navigate('/map')"
        >Découvrir plus d'annonces</v-btn
      >

      <h3 class="mt-2 m-0 p-1">Reprendre la discussion :</h3>

      <v-btn class="m-1 mb-4 w-100 text-white" color="var(--green-color)" @click="navigate('/conversations')"
        >Messagerie</v-btn
      >

      <h3 class="mt-2">Vos informations :</h3>
      <div v-if="user.type === 'host'" class="d-flex flew-row align-items-center m-0 p-0 w-100">
        <v-btn class="text-white m-0 mr-auto mb-4" color="var(--green-color)" @click="navigate('/user-profile')"
          >Mon profil</v-btn
        >
        <v-btn class="text-white m-0 ml-auto mb-4" color="var(--green-color)" @click="navigate('/view-post')">
          Mon logement
        </v-btn>
      </div>

      <v-btn
        v-if="user.type === 'guest'"
        class="text-white m-1 mb-4 w-100"
        color="var(--green-color)"
        @click="navigate('/user-profile')"
      >
        Mon profil
      </v-btn>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import axios from "axios";

export default {
  name: "Home",

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      listDisplay: [],
      listService: [],
      listDisplayByScore: [],
      isLoggedIn: sessionStorage.getItem("user") != null,
      serviceIcons: {
        isCleaning: "mdi-broom",
        isCarSharing: "mdi-car",
        isCooking: "mdi-silverware-fork-knife",
        isDiy: "mdi-hammer",
        isErrand: "mdi-cart",
        isGardening: "mdi-sprout",
        isPetsSitting: "mdi-paw",
        isTalking: "mdi-chat",
      },
    };
  },

  async mounted() {
    // Réinitialisation des listes à chaque montage
    this.listDisplay = [];
    this.listService = [];
    this.listDisplayByScore = [];

    const ps = useListPostStore();
    if (!ps.isLoaded) ps.loadPosts();
    await this.waitUntil(() => ps.isLoaded);

    this.listDisplay = ps.listPost;
    this.listService = ps.listServices;

    const apiUrl = import.meta.env.VITE_API_URL;
    axios
      .get(apiUrl + "/services/reviewsManager.php", {
        headers: { "Content-Type": "application/json" },
      })
      .then((result) => {
        if (result.status === 200 && result.data["success"]) {
          const reviews = result.data["reviews"];
          this.calculateAverageScores(reviews);
          this.sortByScore("desc"); // Tri décroissant par défaut
        }
      })
      .catch((error) => {
        console.error(error);
      });
  },

  methods: {
    waitUntil(conditionFn, interval = 200) {
      return new Promise((resolve) => {
        const checkCondition = () => {
          if (conditionFn()) {
            resolve();
          } else {
            setTimeout(checkCondition, interval);
          }
        };
        checkCondition();
      });
    },

    sortByScore(order) {
      this.listDisplayByScore = [...this.listDisplay].sort((a, b) => {
        if (order === "asc") return a.averageScore - b.averageScore;
        if (order === "desc") return b.averageScore - a.averageScore;
      });
    },

    calculateAverageScores(reviews) {
      const scores = {};

      reviews.forEach((review) => {
        if (!scores[review.id_post]) {
          scores[review.id_post] = { totalScore: 0, count: 0 };
        }
        scores[review.id_post].totalScore += review.score;
        scores[review.id_post].count++;
      });

      this.listDisplay = this.listDisplay.map((post) => {
        const scoreData = scores[post.idPost];
        return {
          ...post,
          averageScore: scoreData ? scoreData.totalScore / scoreData.count : 0,
        };
      });

      // Unicité pour éviter les doublons, on remercie l'IA
      const uniquePosts = new Map();
      this.listDisplay.forEach((post) => {
        if (!uniquePosts.has(post.idPost)) {
          uniquePosts.set(post.idPost, post);
        }
      });

      this.listDisplayByScore = Array.from(uniquePosts.values());
    },

    goToPostDetails(pPost) {
      this.$router.push({ name: "PostDetails", params: { id: pPost } });
    },

    getImageSrc(pId) {
      const url = new URL(`/src/assets/img/host${pId}/post/1.jpg`, import.meta.url).href;

      if (!url.includes("undefined")) {
        return url;
      } else {
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },

    navigate(path) {
      this.$router.push(path);
    },
  },
};
</script>

<style scoped>
.home-logo {
  width: 60%;
  height: auto;
  margin-top: 0;
  margin-bottom: auto;
  border: none;
}
</style>
