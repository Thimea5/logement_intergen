<template>
  <v-main>
    <!-- Barre de recherche -->
    <v-toolbar dense class="custom-toolbar">
      <v-text-field
        v-model="searchQuery"
        prepend-icon="mdi-tune"
        append-icon="mdi-magnify"
        hide-details
        single-line
        placeholder="Rechercher un lieu..."
        class="custom-text-field"
        @click:prepend="navigate"
        @click:append="sampleSearch"
        @keydown.enter.prevent="sampleSearch"
      ></v-text-field>
    </v-toolbar>

    <!-- Carte -->
    <l-map
      style="height: 100%"
      :zoom="zoom"
      ref="mapRef"
      :center="this.center"
      :options="{ zoomControl: false }"
      @update:bounds="fetchNearbyPosts"
    >
      <l-tile-layer :url="url" :attribution="attribution"></l-tile-layer>
      <l-control-zoom position="bottomleft"></l-control-zoom>

      <!-- Marqueurs -->
      <l-marker v-for="post in this.filteredPosts" :key="post.id" :lat-lng="[post.latitude, post.longitude]">
        <l-popup>
          <div>
            <strong>{{ post.city }} - {{ post.type_logement }}</strong>
            <p>{{ post.address }}</p>
            <p>Prix : {{ post.price }}€/mois</p>
          </div>
        </l-popup>
      </l-marker>
    </l-map>
  </v-main>
</template>

<script>
import { LMap, LTileLayer, LControlZoom, LMarker, LPopup } from "@vue-leaflet/vue-leaflet";
import { useListPostStore } from "../stores/listPostStore"; // Import du store Pinia

export default {
  components: {
    LMap,
    LTileLayer,
    LControlZoom,
    LMarker,
    LPopup,
  },

  data() {
    return {
      url: "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
      attribution: '&copy; <a target="_blank" href="http://osm.org/copyright">OpenStreetMap</a> contributors',
      zoom: 12,
      center: [45.764043, 4.835659],
      searchQuery: "",
      filteredPosts: [],
    };
  },

  /* asynchrone obligatoire ici */
  async mounted() {
    const postStore = useListPostStore();

    if (!postStore.isLoaded) {
      postStore.loadPosts();
    }

    await this.waitUntil(() => postStore.isLoaded);

    this.fetchNearbyPosts(null);
  },

  methods: {
    sampleSearch() {
      /* Méthode pour la recherche simple en fonction de l'adresse, la ville, le code postal */

      // filtre recherche
      const inputQuery = this.searchQuery.trim().toLowerCase();
      const p = useListPostStore();
      this.filteredPosts = p.listHost.filter((ph) => {
        return (
          ph.address.toLowerCase().includes(inputQuery) ||
          ph.city.toLowerCase().includes(inputQuery) ||
          ph.postalCode.toLowerCase().includes(inputQuery)
        );
      });

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

      this.fetchNearbyPosts(null);
    },

    async geocodeLocation(query) {
      // Utilisation de l'api : https://adresse.data.gouv.fr/api-doc/adresse
      const apiUrl = `https://api-adresse.data.gouv.fr/search/?q=${encodeURIComponent(query)}`;
      try {
        const response = await fetch(apiUrl);
        const results = await response.json();

        if (results.features && results.features.length > 0) {
          const { geometry, properties } = results.features[0];
          return {
            lat: geometry.coordinates[1],
            lng: geometry.coordinates[0],
            label: properties.label,
          };
        }
      } catch (error) {
        console.error("Erreur de géocodage avec adresse.data.gouv.fr :", error);
      }
      return null;
    },

    waitUntil(conditionFn, interval = 100) {
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

    fetchNearbyPosts(pBounds) {
      if (pBounds === null) {
        pBounds = {
          _southWest: {
            lat: this.center[0] - 0.1,
            lng: this.center[1] - 0.1,
          },
          _northEast: {
            lat: this.center[0] + 0.1,
            lng: this.center[1] + 0.1,
          },
        };
      }

      const { _southWest, _northEast } = pBounds;
      const p = useListPostStore();

      this.filteredPosts = p.listHost.filter((ph) => {
        return (
          _southWest.lat < ph.latitude &&
          ph.latitude < _northEast.lat &&
          _southWest.lng < ph.longitude &&
          ph.longitude < _northEast.lng
        );
      });
    },

    navigate() {
      this.$router.push("/advanced-search");
    },
  },
};
</script>

<style scoped>
.custom-toolbar {
  position: absolute;
  top: 10%;
  left: 50%;
  transform: translateX(-50%);
  width: calc(100% - 40px);
  background-color: white;
  border-radius: 8px;
  z-index: 1000;
}

.custom-text-field {
  flex: 1;
  margin: 0 8px;
}
</style>
