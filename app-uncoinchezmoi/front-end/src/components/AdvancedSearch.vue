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

      <!-- Types de logement en v-carousel -->
      <v-card outlined class="p-2 mt-3 mb-0" color="var(--background-color)">
        <v-card-title class="m-0 p-0">Choix du logement :</v-card-title>
        <v-card-text>
          <v-carousel hide-delimiters height="100%" class="pt-1 m-0" :show-arrows="false">
            <v-carousel-item v-for="(group, index) in groupedTypes" :key="index">
              <v-row>
                <v-col cols="6" v-for="(type, typeIndex) in group" :key="typeIndex">
                  <v-btn
                    @click="toggleType(type.key)"
                    :color="selectedTypes[type.key] ? 'var(--green-color)' : 'grey'"
                    class="text-white rounded-pill d-flex flex-column align-center justify-center m-auto"
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
      <v-card outlined class="p-2 mt-3 mb-0" color="var(--background-color)">
        <v-card-title class="m-0 p-0">Choix des services :</v-card-title>
        <v-card-text>
          <v-carousel hide-delimiters height="100%" class="pt-1 m-0" :show-arrows="false">
            <v-carousel-item v-for="(group, index) in groupedServices" :key="index">
              <v-row>
                <v-col cols="6" v-for="(service, serviceIndex) in group" :key="serviceIndex">
                  <v-btn
                    @click="toggleService(service.key)"
                    :color="selectedServices[service.key] ? 'var(--green-color)' : 'grey'"
                    class="text-white rounded-pill d-flex flex-column align-center justify-center m-auto"
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

      <!-- étoiles -->
      <v-card rounded="lg" class="p-2 mt-3 mb-0" color="var(--background-color)">
        <v-card-title class="m-0 p-0">Notation : </v-card-title>
        <v-item-group v-model="selectedRating" class="d-flex justify-center">
          <v-item v-for="n in 5" :key="n">
            <template v-slot:default="{ toggle }">
              <v-btn :icon="true" variant="text" class="m-1 h-40" @click="toggle">
                <v-icon :class="selectedRating != null && selectedRating + 1 >= n ? 'text-white' : 'text-white'">
                  {{ selectedRating != null && selectedRating + 1 >= n ? "mdi-star" : "mdi-star-outline" }}
                </v-icon>
              </v-btn>
            </template>
          </v-item>
        </v-item-group>
      </v-card>

      <!-- sliders -->
      <v-card rounded="lg" class="p-3 pb-0 mt-3 mb-0" color="var(--background-color)">
        <v-slider
          class="mt-5"
          max="1600"
          min="45"
          v-model="cost"
          label="Loyer (€/mois)"
          thumb-label="always"
        ></v-slider>
        <v-slider class="mt-2" max="25" min="5" v-model="roomSize" label="Taille (m²)" thumb-label="always"></v-slider>
      </v-card>

      <!-- Bouton Rechercher -->
      <div class="d-flex flex-row align-items-center mt-4 mb-4">
        <v-btn @click="cleanSearch" color="var(--green-color)" class="text-white mr-auto"> Effacer </v-btn>
        <v-btn @click="performSearch" color="var(--green-color)" class="text-white"> Rechercher </v-btn>
      </div>

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
                      <v-img :src="getImageSrc(listing.idUser)"></v-img>
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
      cost: null,
      roomSize: null,
      results: [],
      autocompleteService: null,
      suggestions: [],

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
    // formation de groupe de 2
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

    cleanSearch() {
      this.cost = null;
      this.roomSize = null;
      this.selectedRating = null;
      this.searchQuery = "";

      this.selectedServices = {
        isGardening: false,
        isCooking: false,
        isDiy: false,
        isTalking: false,
        isCleaning: false,
        isCarSharing: false,
        isErrand: false,
        isPetsSitting: false,
        isRepairs: false,
      };

      this.selectedTypes = {
        studio: false,
        appartement: false,
        maison: false,
        chambre: false,
        villa: false,
        chalet: false,
        grenier: false,
      };
    },

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
      const decoupage = this.searchQuery.split(", ");

      let score = this.selectedRating !== null && this.selectedRating !== undefined ? this.selectedRating : -2;
      score++;

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
              averageScore: listing.averageScore || 0,
              roomSize: listing.roomSize,
              price: listing.price,
              idUser: listing.idUser,
            };
          }
        })
        .filter((item) => {
          if (!item) return false;
          // Filtrage par adresse
          // On a saisie que la ville...
          let matchQuery;
          if (decoupage.length == 2) {
            matchQuery =
              item.city.toLowerCase().includes(decoupage[0].substring(5).toLowerCase()) ||
              item.postalCode.toLowerCase().includes(decoupage[0].substring(0, 5).toLowerCase());
          } else {
            // on a la saisie complète
            matchQuery =
              item.address.toLowerCase().includes(decoupage[0].toLowerCase()) ||
              item.city.toLowerCase().includes(decoupage[1].substring(0, 5).toLowerCase()) ||
              item.postalCode.toLowerCase().includes(decoupage[1].substring(5).toLowerCase());
          }

          // Filtrage par services
          const matchServices = activeServices.every((service) => {
            return !!item.services[service];
          });

          // Filtrage par types de logement
          let matchTypes = true;
          if (activeTypes.length > 0) {
            matchTypes = activeTypes.map((type) => type.toLowerCase()).includes(item.type_logement.toLowerCase());
          }

          // Filtrage par note, taille, loyer
          const matchRating = score == -1 || item.averageScore >= score;
          const matchRoomSize = this.roomSize == null || item.roomSize >= this.roomSize;
          const matchCost = this.cost == null || item.price <= this.cost;

          return matchQuery && matchServices && matchTypes && matchRating && matchRoomSize && matchCost;
        });

      this.loading = false;
      this.sheet = true;
      this.results = this.cardList;
    },

    getImageSrc(pId) {
      const url = new URL(`/src/assets/img/host${pId}/post/1.jpg`, import.meta.url).href;

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
