<template>
  <v-main>
    <!-- Barre de recherche -->
    <v-btn class="btn position-absolute top-0 left-0 m-3 mapBtn bg-light" icon="mdi-tune" rounded="pill"
      @click="navigate('/advanced-search')">
    </v-btn>
    <v-btn class="btn position-absolute top-0 right-0 m-3 mapBtn bg-light" icon="mdi-format-list-bulleted"
      rounded="pill" @click="navigate('/advance-search')">
    </v-btn>

    <!-- Carte -->
    <l-map :zoom="zoom" :center="center" style="height: 100%" ref="mapRef" @update:bounds="refreshMapWithNearPosts">
      <!-- Tuiles -->
      <l-tile-layer :url="tileLayerUrl" :attribution="attribution" ref="tileLayer" />

      <!-- Marqueurs -->
      <l-marker v-for="post in filteredPosts" :key="post.id" :lat-lng="[post.latitude, post.longitude]"
        @click="onMarkerClick(post)">

      </l-marker>
    </l-map>
    <v-bottom-sheet class="mb-14 shadow-none" z-index="1000" v-model="popup">
      <v-card class="position-relative">
        <v-btn class="position-absolute right-0" variant="solo" icon="mdi-close" @click="popup = false"></v-btn>
        <template v-if="selectedPost">
          <v-card-text @click="goToPostDetails(selectedPost)">
            <div class="h-100 d-flex justify-content-between align-items-center">
              <div class="w-50 h-100">
                <v-img :src="getImageSrc('host_photo'+selectedPost.idPost)" cover height="200">
                  <template v-slot:placeholder>
                    <v-row align="center" class="fill-height ma-0" justify="center">
                      <v-progress-circular color="#4f685d" indeterminate>
                      </v-progress-circular>
                    </v-row>
                  </template>
                </v-img>

              </div>
              <div class="d-flex flex-column justify-content-between ms-5 mb-3">
                <div class="d-flex flex-column justify-content-between">
                  <h6 class="pb-0 mb-0 fw-bold">{{ selectedPost.address }}</h6>
                  <h7>{{ selectedPost.city }}, {{ selectedPost.postalCode }}</h7>
                </div>
                <div class="d-flex flex-column justify-content-between mt-3">
                  <h7>{{ this.selectedPost.type_logement }}, {{ this.selectedPost.size }} m²</h7>
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

      selectedPost: null
    };
  },

  /* On est obligé de passé en Asynchrone ici, sinon, les données ne chargent pas (carte + annonces) */
  mounted() {
    this.$nextTick(() => {
      setTimeout(() => {
        const map = this.$refs.mapRef.leafletObject;
        if (map) {
          // Positionnement du zoom-control en bas à gauche
          map.zoomControl.setPosition("bottomleft");

          // création et application des calques (tuiles)
          this.tileLayer = L.tileLayer(this.tileLayerUrl, {
            attribution: this.attribution,
          })

          const layers = {
            "Carte de base": this.tileLayer,
          }

          // L.control.layers(layers).addTo(map);

          this.tileLayer.addTo(map);
          this.refreshMapWithNearPosts(map.getBounds());
        }
      }, 250); // Délai de 250ms pour s'assurer que la carte est prête
    });
  },

  methods: {
    navigate(pPath) {
      this.$router.push(pPath);
    },

    onMarkerClick(post) {
      const map = this.$refs.mapRef.leafletObject;
      map.flyTo([post.latitude, post.longitude], 12)

      this.selectedPost = post
      this.popup = true

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

    getImageSrc(pImgPath) {
      const url = new URL(
        `/src/assets/img/${pImgPath}/host_photo${pImgPath[pImgPath.length - 1]}_1.jpg`,
        import.meta.url
      ).href;
      //console.log(url);
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
