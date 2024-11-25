<template>
  <div id="map" style="height: 500px; width: 100%"></div>
</template>

<script>
import L from "leaflet";
import axios from "axios";

export default {
  name: "MapComponent",
  data() {
    return {
      map: null, // Instance de la carte
    };
  },
  mounted() {
    this.initializeMap();
    this.fetchLocations();
  },
  methods: {
    initializeMap() {
      // Initialiser la carte
      this.map = L.map("map").setView([48.8566, 2.3522], 13); // Paris comme exemple
      L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        attribution: "© OpenStreetMap contributors",
      }).addTo(this.map);
    },

    async fetchLocations() {
      try {
        const apiUrl = import.meta.env.VITE_API_URL;
        // Remplace cette URL par celle de ton backend
        await axios
          .get(apiUrl + "/services/postsManager.php", {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then((result) => {
            console.log("test");
            console.log(result.status);
            console.log(result.data);
            if (result.status === 200 && result.data["success"]) {
              const locations = result.data["posts"];
              console.log(locations);
              console.log("hey");
            } else {
              console.log(result);
            }
          })
          .catch((error) => console.log(error));

        //console.log(response);
        //const locations = response.data["posts"];
        //console.log(locations);
        //Ajouter chaque position sur la carte
        /*locations.forEach((location) => {
          const { latitude, longitude, name } = location;

          // Ajouter un marqueur à chaque position
          L.marker([latitude, longitude])
            .addTo(this.map)
            .bindPopup(`<b>${name}</b>`) // Popup avec des détails
            .openPopup();
        });*/
      } catch (error) {
        console.error("Erreur lors de la récupération des données:", error);
      }
    },
  },
};
</script>

<style scoped>
#map {
  border: 1px solid #ddd;
  border-radius: 8px;
}
</style>
