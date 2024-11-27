<!--TODO ANNONCE RECHERCHE : 
 faire du style sur l'affichage de la pop-up, changer le lien si 
 dans getimageSrc, ajouter l'image d'erreur, demandé aux ux
-->
<template>
  <v-main>
    <!-- Barre de recherche -->
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

    <!-- Carte -->
    <l-map :zoom="zoom" :center="center" style="height: 100%" ref="mapRef" @update:bounds="refreshMapWithNearPosts">
      <!-- Tuiles -->
      <l-tile-layer :url="tileLayerUrl" :attribution="attribution" ref="tileLayer" />
      <l-tile-layer :url="satelliteUrl" :attribution="attribution" ref="satelliteLayer" />
      <l-tile-layer :url="openStreetMapUrl" :attribution="attribution" ref="openStreetMapLayer" />
      <l-tile-layer :url="streetUrl" :attribution="attribution" ref="streetLayer" />

      <!-- Marqueurs -->
      <l-marker v-for="post in filteredPosts" :key="post.id" :lat-lng="[post.latitude, post.longitude]">
        <l-popup>
          <strong>{{ post.type_logement }} - {{ (post.size === null ? 0 : post.size) + "m²" }}</strong>
          <p>{{ post.address }} - {{ post.postalCode }} - {{ post.city }}</p>
          <img
            :src="getImageSrc(post.img, post.idHost)"
            alt="Aucune image disponible"
            style="width: 80%; height: 50%"
            @click="goToPostDetails(post)"
          />
          <p>Prix : {{ post.price }}€/mois</p>
        </l-popup>
      </l-marker>
    </l-map>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import { LMap, LTileLayer, LControlLayers, LMarker, LPopup } from "@vue-leaflet/vue-leaflet";
import L from "leaflet";

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
      searchQuery: "",
      filteredPosts: [],
      previousBounds: null,
      zoom: 12,
      center: [45.764043, 4.835659], // Position de départ à Lyon
      tileLayerUrl: "https://api.maptiler.com/maps/basic/256/{z}/{x}/{y}.png?key=aRVKZ0fk9aEUzL9b5GZs",
      satelliteUrl: "https://api.maptiler.com/maps/hybrid/256/{z}/{x}/{y}.jpg?key=aRVKZ0fk9aEUzL9b5GZs",
      openStreetMapUrl: "https://api.maptiler.com/maps/openstreetmap/256/{z}/{x}/{y}.jpg?key=aRVKZ0fk9aEUzL9b5GZs",
      streetUrl: "https://api.maptiler.com/maps/streets-v2/256/{z}/{x}/{y}.png?key=aRVKZ0fk9aEUzL9b5GZs",
      attribution: '&copy; <a href="https://www.maptiler.com/copyright/">MapTiler</a> contributors',
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
          });
          this.satelliteLayer = L.tileLayer(this.satelliteUrl, {
            attribution: this.attribution,
          });
          this.openStreetMapLayer = L.tileLayer(this.openStreetMapUrl, {
            attribution: this.attribution,
          });

          this.streetLayer = L.tileLayer(this.streetUrl, {
            attribution: this.attribution,
          });

          const layers = {
            "Carte de base": this.tileLayer,
            "Vue satellite": this.satelliteLayer,
            "OpenStreetMap vue": this.openStreetMapLayer,
            "Street Vue": this.streetLayer,
          };

          L.control.layers(layers).addTo(map);

          this.tileLayer.addTo(map);
          this.refreshMapWithNearPosts(map.getBounds());
        }
      }, 100); // Délai de 100ms pour s'assurer que la carte est prête
    });
  },

  methods: {
    navigate(pPath) {
      this.$router.push(pPath);
    },

    sampleSearch() {
      console.log("sampleSearch");
      /* Méthode pour la recherche simple en fonction de l'adresse, la ville, le code postal */

      // filtre recherche
      const query = this.searchQuery.trim().toLowerCase();
      if (query.length == 0) return;

      const ps = useListPostStore();
      this.filteredPosts = ps.listHost.filter((ph) => {
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
      this.$router.push({ name: "PostDetails", params: { id: listing.idHost } });
    },

    getImageSrc(pImgPath, pIndex) {
      try {
        return new URL(`/src/assets/img/${pImgPath}/host_photo${pIndex}_1.jpg`, import.meta.url).href;
      } catch (error) {
        console.error("Erreur lors du chargement de l'image :", error);
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },

    refreshMapWithNearPosts(pBounds) {
      // on les chargent toutes d'un coup, c'est pas fou, mais ça passe
      const ps = useListPostStore();
      this.filteredPosts = ps.listHost;
    },
  },
};
</script>

<style scoped>
.custom-toolbar {
  position: absolute;
  top: 15%;
  left: 50%;
  transform: translateX(-50%);
  width: calc(100% - 100px);
  background-color: white;
  border-radius: 8px;
  z-index: 1000;
}
</style>
