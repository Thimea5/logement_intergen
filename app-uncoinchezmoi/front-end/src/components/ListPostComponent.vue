<template>
  <v-main>
    <v-container class="d-flex flex-column w-100 p-0 m-0">
      <v-app-bar :elevation="0" class="w-100 d-flex align-items-center">
        <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()" class="mr-auto"></v-btn>
        <v-btn @click="navigate('/map')" class="p-1 m-3" color="var(--green-color)">
          carte
          <v-icon class="ml-2">mdi-map</v-icon>
        </v-btn>
      </v-app-bar>

      <v-text-field
        v-model="searchQuery"
        prepend-inner-icon="mdi-tune"
        append-inner-icon="mdi-magnify"
        hide-details
        single-line
        placeholder="Rechercher un lieu..."
        class="w-100 ps-4 pe-4"
        @click:prepend-inner="navigate('/advanced-search')"
        @click:append-inner="sampleSearch"
        @keydown.enter.prevent="sampleSearch"
        ref="autoCompleteInput"
      ></v-text-field>
      <v-list v-if="suggestions.length">
        <v-list-item v-for="(suggestion, index) in suggestions" :key="index" @click="selectSuggestion(suggestion)">
          <v-list-item-title>{{ suggestion.description }}</v-list-item-title>
          <v-list-item-subtitle>
            <span v-if="suggestion.city">Ville : {{ suggestion.city }}</span
            ><br />
            <span v-if="suggestion.postalCode">Code postal : {{ suggestion.postalCode }}</span
            ><br />
            <span v-if="suggestion.country">Pays : {{ suggestion.country }}</span>
          </v-list-item-subtitle>
        </v-list-item>
      </v-list>

      <div class="p-1 m-0">
        <!-- Partie v-if -->
        <v-card v-if="listDisplay.length === 0" color="var(--background-color)">
          <v-card-title>Aucune annonce trouvée pour le moment</v-card-title>
          <v-card-text>
            <p>Essayez de modifier vos critères de recherche.</p>
          </v-card-text>
        </v-card>

        <!-- Partie v-else -->
        <div v-else v-for="elt in listDisplay" :key="elt.idPost">
          <v-card
            class="p-0 m-2 mt-3"
            height="100%"
            color="var(--background-color)"
            @click="goToPostDetails(elt.idPost)"
          >
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
        </div>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import axios from "axios";
export default {
  name: "ListPost",

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      listDisplay: [],
      listService: [],
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
      searchQuery: "",
      autocompleteService: null,
      suggestions: [],
    };
  },

  async mounted() {
    this.loadGoogleMapsAPI()
      .then(() => {
        if (window.google && window.google.maps) {
          this.autocompleteService = new google.maps.places.AutocompleteService();
          console.log("AutocompleteService initialized");
        }
      })
      .catch((error) => {
        console.error("Failed to load Google Maps API", error);
      });

    const ps = useListPostStore();
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
        }
      })
      .catch((error) => {
        console.error(error);
      });
  },
  watch: {
    searchQuery(newQuery) {
      if (newQuery.length > 2) {
        this.fetchSuggestions(newQuery);
      } else {
        this.suggestions = [];
      }
    },
  },
  methods: {
    loadGoogleMapsAPI() {
      return new Promise((resolve, reject) => {
        if (window.google && window.google.maps) {
          resolve(); // L'API est déjà chargée
          return;
        }

        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyAzP1jU_5lO_65-P_Hk_kZcFEQyVu5MqIg&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = () => resolve();
        script.onerror = (error) => reject(error);
        document.head.appendChild(script);
      });
    },
    fetchSuggestions(query) {
      if (!this.autocompleteService) return;

      const options = {
        input: query,
        componentRestrictions: { country: "fr" }, // Limiter à la France
      };

      // Requête à l'API Google Places pour récupérer les prédictions
      this.autocompleteService.getPlacePredictions(options, (predictions, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && predictions) {
          // Récupérer les détails des prédictions via place_id
          const placeDetailsPromises = predictions.map((prediction) => {
            return new Promise((resolve) => {
              const placeService = new google.maps.places.PlacesService(document.createElement("div"));
              placeService.getDetails({ placeId: prediction.place_id }, (details, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                  resolve({
                    description: prediction.description,
                    city: this.extractComponent(details.address_components, "locality"),
                    postalCode: this.extractComponent(details.address_components, "postal_code"),
                    country: this.extractComponent(details.address_components, "country"),
                    fullDetails: details,
                  });
                } else {
                  resolve(null);
                }
              });
            });
          });

          // Attendre que toutes les promesses soient résolues pour mettre à jour les suggestions
          Promise.all(placeDetailsPromises).then((detailedSuggestions) => {
            this.suggestions = detailedSuggestions.filter(Boolean); // Retirer les éléments nuls
          });
        } else {
          this.suggestions = [];
        }
      });
    },

    selectSuggestion(suggestion) {
      this.searchQuery = suggestion.fullDetails["formatted_address"];
      console.log("Selected Address Details:", suggestion.fullDetails["formatted_address"]);
      this.suggestions = [];
    },

    extractComponent(components, type) {
      const component = components.find((comp) => comp.types.includes(type));
      return component ? component.long_name : null;
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

      this.listDisplay = Array.from(uniquePosts.values());
    },
    goBack() {
      this.$router.go(-1);
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

    sampleSearch() {
      console.log("recherche simple");
    },
  },
};
</script>
