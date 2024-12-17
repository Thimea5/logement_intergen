<template>
  <v-main>
    <v-text-field
      v-model="searchQuery"
      prepend-inner-icon="mdi-tune"
      append-inner-icon="mdi-magnify"
      hide-details
      single-line
      placeholder="Rechercher un lieu..."
      class="custom-toolbar"
      @click:prepend-inner="navigate('/advanced-search')"
      @click:append-inner="sampleSearch"
      @keydown.enter.prevent="sampleSearch"
    ></v-text-field>
    <v-list v-if="suggestions.length" class="custom-suggestions-list">
      <v-list-item
        v-for="(suggestion, index) in suggestions"
        :key="index"
        @click="selectSuggestion(suggestion)"
        class="custom-suggestion-item"
      >
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

    <!-- Carte -->
    <l-map :zoom="zoom" :center="center" style="height: 100%" ref="mapRef" @update:bounds="refreshMapWithNearPosts">
      <v-btn @click="navigate('/list-post')" class="custom-switch-button">
        liste
        <v-icon class="ml-2">mdi-format-list-bulleted</v-icon>
      </v-btn>

      <!-- Tuiles -->
      <l-tile-layer :url="tileLayerUrl" :attribution="attribution" ref="tileLayer" />

      <!-- Marqueurs -->
      <l-marker
        v-for="post in filteredPosts"
        :key="post.id"
        :lat-lng="[post.latitude, post.longitude]"
        @click="onMarkerClick(post)"
      >
      </l-marker>
    </l-map>

    <v-bottom-sheet class="mb-14 shadow-none" z-index="1000" v-model="popup">
      <v-card class="position-relative">
        <v-btn class="position-absolute right-0" variant="solo" icon="mdi-close" @click="popup = false"></v-btn>
        <template v-if="selectedPost">
          <v-card-text @click="goToPostDetails(selectedPost)">
            <div class="h-100 d-flex justify-content-between align-items-center">
              <div class="w-50 h-100">
                <v-img :src="getImageSrc(selectedPost.idUser)" cover height="200">
                  <template v-slot:placeholder>
                    <v-row align="center" class="fill-height ma-0" justify="center">
                      <v-progress-circular color="#4f685d" indeterminate> </v-progress-circular>
                    </v-row>
                  </template>
                </v-img>
              </div>
              <div class="d-flex flex-column justify-content-between ms-5 mb-3">
                <div class="d-flex flex-column justify-content-between">
                  <h6 class="pb-0 mb-0 fw-bold">{{ selectedPost.address }}</h6>
                  <h6>{{ selectedPost.city }}, {{ selectedPost.postalCode }}</h6>
                </div>
                <div class="d-flex flex-column justify-content-between mt-3">
                  <h6>{{ this.selectedPost.type_logement }}, {{ this.selectedPost.size }} m²</h6>
                  <h6 class="fw-bold">{{ this.selectedPost.price }} €</h6>
                </div>
              </div>
            </div>
          </v-card-text>
        </template>
        <template v-else>
          <v-card-title>Aucun post sélectionné</v-card-title>
        </template>
      </v-card>
    </v-bottom-sheet>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import { LMap, LTileLayer, LControlLayers, LMarker, LPopup } from "@vue-leaflet/vue-leaflet";
import L from "leaflet";
import { icon } from "leaflet";

export default {
  name: "Carte",
  components: {
    LMap,
    LTileLayer,
    LControlLayers,
    LMarker,
    LPopup,
  },

  data() {
    return {
      popup: false,
      searchQuery: "",
      filteredPosts: {},
      previousBounds: null,
      zoom: 12,
      center: [45.764043, 4.835659], // Position de départ à Lyon
      tileLayerUrl: "https://api.maptiler.com/maps/basic/256/{z}/{x}/{y}.png?key=aRVKZ0fk9aEUzL9b5GZs",
      satelliteUrl: "https://api.maptiler.com/maps/hybrid/256/{z}/{x}/{y}.jpg?key=aRVKZ0fk9aEUzL9b5GZs",
      openStreetMapUrl: "https://api.maptiler.com/maps/openstreetmap/256/{z}/{x}/{y}.jpg?key=aRVKZ0fk9aEUzL9b5GZs",
      streetUrl: "https://api.maptiler.com/maps/streets-v2/256/{z}/{x}/{y}.png?key=aRVKZ0fk9aEUzL9b5GZs",
      attribution: '&copy; <a href="https://www.maptiler.com/copyright/">MapTiler</a> contributors',

      selectedPost: null,
      autocompleteService: null,
      suggestions: [],
    };
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

  /* On est obligé de passé en Asynchrone ici, sinon, les données ne chargent pas (carte + annonces) */
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

    this.$nextTick(() => {
      setTimeout(() => {
        const map = this.$refs.mapRef.leafletObject;
        if (map) {
          // Positionnement du zoom-control en bas à gauche
          map.zoomControl.setPosition("bottomleft");

          // création et application des calques (tuiles)
          this.tileLayer = L.tileLayer(this.tileLayerUrl, {
            attribution: this.attribution,
          });

          const layers = {
            "Carte de base": this.tileLayer,
          };

          // L.control.layers(layers).addTo(map);

          this.tileLayer.addTo(map);
          this.refreshMapWithNearPosts(map.getBounds());
        }
      }, 250); // Délai de 250ms pour s'assurer que la carte est prête
    });
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

    navigate(pPath) {
      this.$router.push(pPath);
    },

    onMarkerClick(post) {
      const map = this.$refs.mapRef.leafletObject;
      map.flyTo([post.latitude, post.longitude], 12);

      this.selectedPost = post;
      this.popup = true;

      // console.log(this.selectedPost)
    },

    sampleSearch() {
      /* Méthode pour la recherche simple en fonction de l'adresse, la ville, le code postal */

      // filtre recherche
      const query = this.searchQuery.trim().toLowerCase();
      if (query.length == 0) return;

      const ps = useListPostStore();
      this.filteredPosts = ps.listPost.filter((ph) => {
        return (
          ph.address.toLowerCase().includes(query) ||
          ph.city.toLowerCase().includes(query) ||
          ph.postalCode.toLowerCase().includes(query)
        );
      });

      if (this.filteredPosts.length > 0) {
        // calcul et déplacement du centre de la carte
        let moyenneLatitude = 0;
        let moyenneLongitude = 0;
        for (let i = 0; i < this.filteredPosts.length; i++) {
          moyenneLatitude += this.filteredPosts[i].latitude;
          moyenneLongitude += this.filteredPosts[i].longitude;
        }

        this.$refs.mapRef.leafletObject.setView(
          [moyenneLatitude / this.filteredPosts.length, moyenneLongitude / this.filteredPosts.length],
          12
        );
      }
    },

    goToPostDetails(listing) {
      this.$router.push({ name: "PostDetails", params: { id: listing.idPost } });
    },

    getImageSrc(pId) {
      const url = new URL(`/src/assets/img/host${pId}/post/1.jpg`, import.meta.url).href;

      if (!url.includes("undefined")) {
        return url;
      } else {
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },

    refreshMapWithNearPosts(pBounds) {
      // on les chargent toutes d'un coup, c'est pas fou, mais ça passe
      const ps = useListPostStore();
      this.filteredPosts = ps.listPost;
    },
  },
};
</script>
