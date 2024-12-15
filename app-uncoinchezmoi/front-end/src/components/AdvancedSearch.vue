<template>
  <v-main>
    <v-container>
      <v-app-bar :elevation="0">
        <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
        <v-app-bar-title>Recherche avancée</v-app-bar-title>
      </v-app-bar>

      <v-text-field
        class="mt-0"
        v-model="searchQuery"
        label="Search"
        append-inner-icon="mdi-magnify"
        variant="outlined"
        hide-details
        single-line
        @click:append-inner="performSearch"
      ></v-text-field>

      <!-- Types de logement en v-carousel -->
      <v-card outlined class="p-1 mt-4">
        <v-card-title>Types de logement</v-card-title>
        <v-card-text>
          <v-carousel hide-delimiters height="100%" class="pt-1 m-0" :show-arrows="false">
            <v-carousel-item v-for="(group, index) in groupedTypes" :key="index">
              <v-row>
                <v-col cols="6" v-for="(type, typeIndex) in group" :key="typeIndex">
                  <v-btn
                    @click="toggleType(type.key)"
                    :color="selectedTypes[type.key] ? 'primary' : 'grey'"
                    class="rounded-pill d-flex flex-column align-center justify-center m-auto"
                    style="width: 100%; height: 50px"
                  >
                    <span>{{ type.label }}</span>
                  </v-btn>
                </v-col>
              </v-row>
            </v-carousel-item>
          </v-carousel>
        </v-card-text>
      </v-card>

      <!-- Types de services en v-carousel -->
      <v-card outlined class="pa-3 mt-1">
        <v-card-title>Types de services</v-card-title>
        <v-card-text>
          <v-carousel hide-delimiters height="100%" class="pt-1 m-0" :show-arrows="false">
            <v-carousel-item v-for="(group, index) in groupedServices" :key="index">
              <v-row>
                <v-col cols="6" v-for="(service, serviceIndex) in group" :key="serviceIndex">
                  <v-btn
                    @click="toggleService(service.key)"
                    :color="selectedServices[service.key] ? 'primary' : 'grey'"
                    class="rounded-pill d-flex flex-column align-center justify-center m-auto"
                    style="width: auto; height: 50px"
                  >
                    <span>{{ service.label }}</span>
                  </v-btn>
                </v-col>
              </v-row>
            </v-carousel-item>
          </v-carousel>
        </v-card-text>
      </v-card>

      <v-card rounded="lg" class="d-flex flex-column justify-center align-center m-2">
        <v-item-group v-model="selectedRating" class="d-flex justify-center">
          <v-item v-for="n in 5" :key="n">
            <template v-slot:default="{ toggle }">
              <v-btn :icon="true" variant="text" class="m-1 h-40" @click="toggle">
                <v-icon :class="selectedRating != null && selectedRating + 1 >= n ? 'text-warning' : 'text-grey'">
                  {{ selectedRating != null && selectedRating + 1 >= n ? "mdi-star" : "mdi-star-outline" }}
                </v-icon>
              </v-btn>
            </template>
          </v-item>
        </v-item-group>
      </v-card>

      <!-- Bouton Rechercher -->
      <v-row>
        <v-col cols="12">
          <v-btn @click="performSearch" color="primary" block class="mt-4"> Rechercher </v-btn>
        </v-col>
      </v-row>

      <!-- Résultats de la recherche -->
      <v-bottom-sheet v-model="sheet" scrollable height="60vh">
        <v-card>
          <v-card-title class="d-flex justify-space-between align-center"> Résultats de la recherche </v-card-title>
          <v-card-subtitle>{{ this.results.length }} annonces trouvées</v-card-subtitle>
          <v-card-text>
            <div v-if="loading">Chargement des annonces...</div>
            <div v-else>
              <v-row dense>
                <v-col v-for="listing in this.results" :key="listing.id" cols="6">
                  <v-card @click="goToPostDetails(listing.id)">
                    <v-card-title class="p-0">
                      <v-img :src="getImageSrc(listing.img)"></v-img>
                    </v-card-title>
                    <v-card-subtitle>
                      {{ listing.type_logement }} -
                      {{ (listing.size === null ? 0 : listing.size) + "m²" }}
                    </v-card-subtitle>
                    <v-card-text>{{ listing.city }} - {{ listing.postalCode }}</v-card-text>
                  </v-card>
                </v-col>
              </v-row>
            </div>
          </v-card-text>
        </v-card>
      </v-bottom-sheet>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import axios from "axios";

export default {
  name: "AdvancedSearch",
  data() {
    return {
      searchQuery: "",
      loading: false,
      cardList: [],
      listPost: [],
      sheet: false,
      selectedRating: null,
      results: [],

      typesOptions: [
        { key: "Studio", label: "Studio", icon: "mdi-door" },
        { key: "Appartement", label: "Appartement", icon: "mdi-office-building" },
        { key: "Maison", label: "Maison", icon: "mdi-home" },
        { key: "Chambre", label: "Chambre", icon: "mdi-bed" },
        { key: "Villa", label: "Villa", icon: "mdi-home-modern" },
        { key: "Chalet", label: "Chalet", icon: "mdi-home-variant" },
        { key: "Grenier", label: "Grenier", icon: "mdi-roof" },
      ],
      selectedTypes: {
        studio: false,
        appartement: false,
        maison: false,
        chambre: false,
        villa: false,
        chalet: false,
        grenier: false,
      },

      serviceOptions: [
        { key: "isGardening", label: "Jardinage", icon: "mdi-leaf" },
        { key: "isCooking", label: "Cuisines", icon: "mdi-silverware-fork-knife" },
        { key: "isDiy", label: "Bricolage", icon: "mdi-hammer" },
        { key: "isTalking", label: "Discussions", icon: "mdi-chat" },
        { key: "isCleaning", label: "Ménage", icon: "mdi-broom" },
        { key: "isCarSharing", label: "Covoiturage", icon: "mdi-car" },
        { key: "isErrand", label: "Courses", icon: "mdi-shopping" },
        { key: "isPetsSitting", label: "Garde d'animaux", icon: "mdi-paw" },
        { key: "isRepairs", label: "Réparations", icon: "mdi-tools" },
      ],
      selectedServices: {
        isGardening: false,
        isCooking: false,
        isDiy: false,
        isTalking: false,
        isCleaning: false,
        isCarSharing: false,
        isErrand: false,
        isPetsSitting: false,
        isRepairs: false,
      },
    };
  },
  computed: {
    // Split typesOptions into groups of 2
    groupedTypes() {
      const groups = [];
      for (let i = 0; i < this.typesOptions.length; i += 2) {
        groups.push(this.typesOptions.slice(i, i + 2));
      }
      return groups;
    },

    groupedServices() {
      const groups = [];
      for (let i = 0; i < this.serviceOptions.length; i += 2) {
        groups.push(this.serviceOptions.slice(i, i + 2));
      }
      return groups;
    },
  },
  async mounted() {
    const ps = useListPostStore();
    if (!ps.isLoaded) ps.loadPosts();
    await this.waitUntil(() => ps.isLoaded);

    const apiUrl = import.meta.env.VITE_API_URL;
    axios
      .get(apiUrl + "/services/reviewsManager.php", {
        headers: { "Content-Type": "application/json" },
      })
      .then((result) => {
        if (result.status === 200 && result.data["success"]) {
          const reviews = result.data["reviews"];
          this.cardList = ps.listPost;
          this.calculateAverageScores(reviews);
        }
      })
      .catch((error) => {
        console.error(error);
      });
  },
  methods: {
    toggleType(key) {
      this.selectedTypes[key] = !this.selectedTypes[key];
    },

    toggleService(key) {
      this.selectedServices[key] = !this.selectedServices[key];
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

      this.cardList = this.cardList.map((post) => {
        const scoreData = scores[post.idPost];
        return {
          ...post,
          averageScore: scoreData ? scoreData.totalScore / scoreData.count : 0,
        };
      });

      // Unicité pour éviter les doublons, on remercie l'IA
      const uniquePosts = new Map();
      this.cardList.forEach((post) => {
        if (!uniquePosts.has(post.idPost)) {
          uniquePosts.set(post.idPost, post);
        }
      });

      console.log("fin", this.cardList);
    },
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
    performSearch() {
      let score = this.selectedRating !== null && this.selectedRating !== undefined ? this.selectedRating : -2;
      score++;
      console.log("rating", this.selectedRating);
      console.log("score", score);
      this.loading = true;
      const ps = useListPostStore();
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .get(apiUrl + "/services/reviewsManager.php", {
          headers: { "Content-Type": "application/json" },
        })
        .then((result) => {
          if (result.status === 200 && result.data["success"]) {
            const reviews = result.data["reviews"];
            this.cardList = ps.listPost;
            this.calculateAverageScores(reviews);
          }
        })
        .catch((error) => {
          console.error(error);
        });
      console.log("listCard", this.cardList);

      const activeServices = Object.entries(this.selectedServices)
        .filter(([key, value]) => value)
        .map(([key]) => key);

      const activeTypes = Object.entries(this.selectedTypes)
        .filter(([key, value]) => value)
        .map(([key]) => key);

      this.cardList = this.cardList
        .map((listing, index) => {
          if (listing.available) {
            const serviceData = ps.listServices[index] || {};
            return {
              id: listing.idPost,
              address: listing.address,
              city: listing.city,
              postalCode: listing.postalCode,
              services: {
                isCleaning: serviceData.isCleaning || false,
                isCarSharing: serviceData.isCarSharing || false,
                isCooking: serviceData.isCooking || false,
                isDiy: serviceData.isDiy || false,
                isErrand: serviceData.isErrand || false,
                isGardening: serviceData.isGardening || false,
                isPetsSitting: serviceData.isPetsSitting || false,
                isTalking: serviceData.isTalking || false,
              },
              type_logement: listing.type_logement,
              img: listing.img,
              size: listing.size,
              averageScore: listing.averageScore || 0, // Ajout de la note moyenne
            };
          }
        })
        .filter((item) => {
          if (!item) return false;

          // Filtrage par mot-clé
          const matchQuery =
            item.address.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            item.city.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            item.postalCode.toLowerCase().includes(this.searchQuery.toLowerCase());

          // Filtrage par services
          const matchServices = activeServices.every((service) => {
            return !!item.services[service];
          });

          // Filtrage par types de logement
          let matchTypes = true;
          if (activeTypes.length > 0) {
            matchTypes = activeTypes.map((type) => type.toLowerCase()).includes(item.type_logement.toLowerCase());
          }

          // Filtrage par note moyenne (averageScore)
          const matchRating = score == -1 || item.averageScore >= score;

          return matchQuery && matchServices && matchTypes && matchRating;
        });

      this.loading = false;
      this.sheet = true;
      this.results = this.cardList;
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

    goToPostDetails(pPostId) {
      this.$router.push({ name: "PostDetails", params: { id: pPostId } });
    },

    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>
