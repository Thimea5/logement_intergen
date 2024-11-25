<template>
  <div id="map" style="height: 500px; width: 100%;"></div>
</template>

<script>
import L from 'leaflet';

export default {
  name: 'MapComponent',
  mounted() {
    this.initializeMap();
  },
  methods: {
    initializeMap() {
      // Crée une instance de carte centrée sur une position par défaut (Paris ici)
      const map = L.map('map').setView([48.8566, 2.3522], 13); // Paris

      // Ajoute des tuiles OpenStreetMap
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
      }).addTo(map);
    }
  }
};
</script>

<style scoped>
/* Optionnel : Ajoute un style de base pour le conteneur de la carte */
#map {
  border: 1px solid #ddd;
  border-radius: 8px;
}
</style>
