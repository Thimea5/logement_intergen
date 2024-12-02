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

      <div class="d-flex justify-space-between align-center" style="width: 100%">
        <v-btn
          v-if="this.user.type == 'guest'"
          @click="navigate('/advanced-search')"
          color="primary"
          style="flex: 1; margin-right: 5px"
        >
          Rechercher
        </v-btn>
        <v-btn v-else @click="navigate('/view-post')" color="primary" style="flex: 1; margin-right: 5px">
          Mon logement
        </v-btn>
        <v-btn @click="navigate('/TODO_ICI')" color="secondary" style="flex: 1; margin-left: 5px">
          Favoris
          <v-icon>mdi-heart</v-icon>
        </v-btn>
      </div>

      <h4 style="margin-top: 5%">Recommandées pour vous</h4>

      <!-- Affichage des cartes de logement (le contenu sera ajouté plus tard) -->
      <div>
        <!-- Partie v-if -->
        <v-card v-if="listDisplay.length === 0" class="bg-blue-grey-lighten-4">
          <v-card-title>Aucune annonce trouvée pour le moment</v-card-title>
          <v-card-text>
            <p>Essayez de modifier vos critères de recherche.</p>
          </v-card-text>
        </v-card>

        <!-- Partie v-else -->
        <div v-else>
          <v-row>
            <v-col v-for="elt in listDisplay" :key="elt.id" cols="12" sm="6" md="4">
              <v-card @click="goToPostDetails(elt.idPost)" class="bg-blue-grey-lighten-4">
                <v-img :src="getImageSrc(elt.img)">
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
                <v-card-title>
                  {{ elt.type_logement }} - {{ (elt.size === null ? 0 : elt.size) + "m²" }} [{{ elt.price }}€/mois]
                </v-card-title>
                <v-card-subtitle>{{ elt.address }} - {{ elt.city }} {{ elt.postalCode }}</v-card-subtitle>
                <v-card-text class="d-flex flex-row">
                  <p>Services :</p>
                  <template v-for="(icon, key) in serviceIcons">
                    <v-icon v-if="listService[elt.idPost]?.[key] === 1" :key="`${elt.idPost}-${key}`" class="mx-1">
                      {{ icon }}
                    </v-icon>
                  </template>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";

export default {
  name: "Home",

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      listDisplay: [],
      listService: [],
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

  mounted() {
    console.log("mounted");
    if (this.isLoggedIn) {
      setTimeout(() => {
        const ps = useListPostStore();
        this.listDisplay = ps.listPost;
        this.listService = ps.listServices;
      }, 250);
    }
  },

  methods: {
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
