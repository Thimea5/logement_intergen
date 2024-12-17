<template>
  <v-main class="d-flex flex-column justify-content-between w-100">
    <v-app-bar :elevation="0" class="w-100 d-flex align-items-center">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()" class="mr-auto"></v-btn>
      <v-btn @click="navigate('/map')" class="bg-dark">carte</v-btn>
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
    ></v-text-field>

    <v-container class="d-flex flex-column align-center">
      <div>
        <!-- Partie v-if -->
        <v-card v-if="listDisplay.length === 0" class="bg-blue-grey-lighten-4">
          <v-card-title>Aucune annonce trouvée pour le moment</v-card-title>
          <v-card-text>
            <p>Essayez de modifier vos critères de recherche.</p>
          </v-card-text>
        </v-card>

        <!-- Partie v-else -->
        <div v-else>
          <v-row>
            <v-col v-for="elt in listDisplay" :key="elt.idPost" cols="12" sm="6" md="4">
              <v-card @click="goToPostDetails(elt.idPost)" class="bg-blue-grey-lighten-4">
                <v-img :src="getImageSrc(elt.idUser)"> </v-img>
                <v-card-title>
                  {{ elt.type_logement }} ({{ (elt.size === null ? 0 : elt.size) + "m²" }}) [{{ elt.price }}€/mois]
                </v-card-title>
                <v-card-subtitle>{{ elt.address }} - {{ elt.city }} {{ elt.postalCode }}</v-card-subtitle>
                <v-card-text class="p-2 mt-0">
                  <p class="p-0 m-0">Taille de la chambre : {{ elt.roomSize }} m²</p>
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
            </v-col>
          </v-row>
        </div>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
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
    };
  },

  mounted() {
    const ps = useListPostStore();
    this.listDisplay = ps.listPost;
    this.listService = ps.listServices;
  },

  methods: {
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
