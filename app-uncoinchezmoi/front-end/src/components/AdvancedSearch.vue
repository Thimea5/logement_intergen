<template>
  <v-main>
    <v-container>
      <v-app-bar :elevation="0">
        <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
        <v-app-bar-title>Recherche avancée</v-app-bar-title>
      </v-app-bar>

      <v-text-field
        v-model="searchQuery"
        label="Search"
        prepend-inner-icon="mdi-magnify"
        variant="outlined"
        hide-details
        single-line
      ></v-text-field>

      <!-- Types de logement -->
      <v-card outlined class="pa-4 mt-4">
        <v-row>
          <v-col cols="12">
            <h3 class="mb-4">Types de logement</h3>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col v-for="(type, index) in typesOptions" :key="index" cols="6" class="p-0">
            <v-checkbox
              v-model="selectedTypes[type.key]"
              :label="type.label"
              color="primary"
              hide-details
              class="py-0"
            ></v-checkbox>
          </v-col>
        </v-row>
      </v-card>

      <!-- Types de services -->
      <v-card outlined class="pa-3 mt-1">
        <v-row>
          <v-col cols="12">
            <h3 class="mb-4">Types de services</h3>
          </v-col>
        </v-row>
        <v-row dense>
          <v-col v-for="(service, index) in serviceOptions" :key="index" cols="6" class="p-0">
            <v-checkbox
              v-model="selectedServices[service.key]"
              :label="service.label"
              color="primary"
              hide-details
              class="py-0"
            ></v-checkbox>
          </v-col>
        </v-row>
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
          <v-card-subtitle>{{ cardList.length }} annonces trouvées</v-card-subtitle>
          <v-card-text>
            <div v-if="loading">Chargement des annonces...</div>
            <div v-else>
              <v-row dense>
                <v-col v-for="listing in cardList" :key="listing.id" cols="6">
                  <v-card @click="goToPostDetails(listing.id)">
                    <v-card-title class="p-0">
                      <v-img :src="getImageSrc(listing.img)"></v-img>
                    </v-card-title>
                    <v-card-subtitle>
                      {{ listing.type_logement }} -
                      {{ (listing.size === null ? 0 : listing.size) + "m²" }}</v-card-subtitle
                    >
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

export default {
  name: "AdvancedSearch",

  data() {
    return {
      searchQuery: "",
      loading: false,
      cardList: [],
      sheet: false,

      typesOptions: [
        { key: "studio", label: "Studio", icon: "mdi-door" },
        { key: "appartement", label: "Appartement", icon: "mdi-office-building" },
        { key: "maison", label: "Maison", icon: "mdi-home" },
        { key: "chambre", label: "Chambre", icon: "mdi-bed" },
        { key: "villa", label: "Villa", icon: "mdi-home-modern" },
        { key: "chalet", label: "Chalet", icon: "mdi-home-variant" },
        { key: "grenier", label: "Grenier", icon: "mdi-roof" },
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

  methods: {
    goToPostDetails(pPostId) {
      this.$router.push({ name: "PostDetails", params: { id: pPostId } });
    },

    goBack() {
      this.$router.go(-1);
    },

    performSearch() {
      this.loading = true;
      const ps = useListPostStore();

      const activeServices = Object.entries(this.selectedServices)
        .filter(([key, value]) => value)
        .map(([key]) => key);

      const activeTypes = Object.entries(this.selectedTypes)
        .filter(([key, value]) => value)
        .map(([key]) => key);

      this.cardList = ps.listPost
        .map((listing, index) => {
          if (listing.available) {
            return {
              id: listing.id,
              address: ps.listHost[index]?.address,
              city: ps.listHost[index]?.city,
              postalCode: ps.listHost[index]?.postalCode,
              services: ps.listServices[index],
              type_logement: ps.listHost[index]?.type_logement,
              img: ps.listHost[index]?.img,
              size: ps.listHost[index]?.size,
            };
          }
        })
        .filter((item) => {
          const matchQuery =
            item.address.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            item.city.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
            item.postalCode.toLowerCase().includes(this.searchQuery.toLowerCase());

          const matchServices = activeServices.every((service) => {
            return !!item.services[service];
          });

          let matchTypes = true;
          if (activeTypes.length > 0) {
            matchTypes = activeTypes.includes(item.type_logement);
          }

          return matchQuery && matchServices && matchTypes;
        });

      this.loading = false;
      this.sheet = true;
    },

    getImageSrc(pImgPath) {
      try {
        return new URL(`/src/assets/img/${pImgPath}/host_photo${pImgPath[pImgPath.length - 1]}_1.jpg`, import.meta.url)
          .href;
      } catch (error) {
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },
  },
};
</script>
