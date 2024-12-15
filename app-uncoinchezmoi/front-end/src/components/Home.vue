<template>
  <v-main>
    <!-- Section sans connexion -->
    <v-container v-if="!isLoggedIn" class="d-flex flex-column justify-center align-center" style="height: 100vh">
      <img src="../assets/logo.png" alt="Logo de l'application" class="home-logo" />

      <div class="d-flex align-center" style="width: 100%">
        <v-btn @click="navigate('/register')" color="#8DA399" style="flex: 1; margin-right: 5px"> S'inscrire </v-btn>
        <v-btn @click="navigate('/login')" color="#8DA399" style="flex: 1; margin-left: 5px"> Se connecter </v-btn>
      </div>

      <router-link to="/legal-notices" class="home-link"> Mentions légales </router-link>
    </v-container>

    <!-- Section avec connexion-->
    <v-container v-if="isLoggedIn" class="d-flex flex-column justify-center">
      <img src="../assets/logo.png" alt="Logo de l'application" class="home-small-logo" />
      <h1 class="m-auto mb-5">Bienvenue chez toi !</h1>
      <h3 class="mt-1">Regarde nos meilleures annonces :</h3>

      <v-carousel
        :show-arrows="false"
        width="100"
        height="50vh"
        class="p-0 m-0"
        cycle
        v-if="listDisplayByScore.length > 0"
        hide-delimiters
      >
        <v-carousel-item v-for="(elt, i) in this.listDisplayByScore" :key="i">
          <v-card class="w-100 p-0 m-0 bg-blue-grey-lighten-4" @click="goToPostDetails(elt.idPost)">
            <div>
              <v-img width="100vw" height="30vh" cover aspect-ratio="1" :src="getImageSrc(elt.img)">
                <v-btn
                  icon
                  color="white"
                  rounded="circle"
                  class="position-absolute top-0 right-0 m-1"
                  @click.stop="addFavouritesPost(elt.idHost)"
                >
                  <v-icon>mdi-heart</v-icon>
                </v-btn>
              </v-img>
            </div>

            <v-card-title>
              {{ elt.type_logement }} - {{ (elt.size === null ? 0 : elt.size) + "m²" }} [{{ elt.price }}€/mois]
            </v-card-title>
            <v-card-subtitle> {{ elt.address }} - {{ elt.city }} {{ elt.postalCode }} </v-card-subtitle>
            <v-card-text>
              <p>Note moyenne : {{ elt.averageScore.toFixed(1) }}</p>
            </v-card-text>
          </v-card>
        </v-carousel-item>
      </v-carousel>

      <v-btn class="mb-4 m-auto" @click="navigate('/map')">Voir plus d'annonces</v-btn>

      <h3 class="mt-1">Lancez la discussion :</h3>

      <v-btn class="mt-4 mb-4 m-auto" @click="navigate('/conversations')">Messagerie</v-btn>

      <h3 class="mt-1">Vos informations :</h3>
      <v-btn class="mt-4 mb-4 m-auto" @click="navigate('/user-profile')">Mon profil</v-btn>
      <v-btn class="mt-4 mb-4 m-auto" v-if="user.type === 'host'" @click="navigate('/view-post')"> Mon logement </v-btn>
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

    getImageSrc(pImgPath) {
      const url = new URL(
        `/src/assets/img/${pImgPath}/host_photo${pImgPath[pImgPath.length - 1]}_1.jpg`,
        import.meta.url
      ).href;

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
  width: 50%;
  height: auto;
  margin-top: 0;
  margin-bottom: 100%;
}

.home-link {
  margin-top: 20px;
  text-align: center;
  text-decoration: none;
  color: black;
}

.home-small-logo {
  width: 20%;
  height: auto;
  margin-top: 0;
  margin-bottom: 5%;
  margin-left: auto;
  margin-right: auto;
}
</style>
