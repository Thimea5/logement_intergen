<template>
  <v-main class="d-flex flex-column justify-content-between align-items-center">
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
      <v-toolbar-title>Bonjour, {{ user.firstname }} {{ user.lastname }}</v-toolbar-title>
    </v-app-bar>

    <v-container class="h-100 d-flex flex-column justify-content-around">
      <div class="w-100">
        <v-card class="my-2" width="100%">
          <v-card-title>Informations</v-card-title>
          <v-card-text>
            <ul class="list-unstyled ms-5">
              <li><v-icon class="me-5 my-1">mdi-check-decagram</v-icon>Identité vérifié</li>
              <li><v-icon class="me-5 my-1">mdi-phone-outline</v-icon>{{ user.tel }}</li>
              <li><v-icon class="me-5 my-1">mdi-email-outline</v-icon>{{ user.mail }}</li>
              <li><v-icon class="me-5 my-1">mdi-cake-variant-outline</v-icon>{{ user.birthdate }}</li>
            </ul>
          </v-card-text>
        </v-card>

        <!-- <v-card class="my-2" width="100%">
        <v-card-title class="headline">A propos de vous</v-card-title>
        <v-card-text
          >TODO ici ?
          <p>{{ user }}</p>
        </v-card-text>
      </v-card> -->

        <v-card class="my-2" width="100%">
          <v-card-title class="headline">Mes réservations</v-card-title>
          <v-card-text @click="goToPostDetails(selectedPost)">
            <v-card
              class="mb-5"
              v-for="res in this.reservations"
              @click="goToPostDetails(res.post[0].idPost)"
              color="var(--background-color)"
            >
              <v-card-title class="mt-3">
                <v-img :src="getImageSrc(res.id_post)" cover height="50px" rounded="lg"></v-img
              ></v-card-title>
              <v-card-subtitle class="text-end"> {{ res.post[0].address }} </v-card-subtitle>
              <v-card-text>
                <div class="d-flex justify-content-between align-items-center">
                  <h4 class="w-100">{{ res.cost }}€ / mois</h4>
                  <div class="d-flex flex-column align-items-center">
                    <v-chip>
                      {{ new Date(res.start_date).toLocaleDateString("fr-CA").split("-").reverse().join(" / ") }}
                    </v-chip>
                    <v-icon class="text-center my-1">mdi-arrow-down-thin</v-icon>
                    <v-chip>
                      {{ new Date(res.end_date).toLocaleDateString("fr-CA").split("-").reverse().join(" / ") }}
                    </v-chip>
                  </div>
                </div>
              </v-card-text>
            </v-card>
          </v-card-text>
        </v-card>
      </div>

      <div class="mx-10 text-center">
        <v-btn class="rounded-pill mb-5" color="#4F685D" @click="modifStep1 = true" prepend-icon="mdi-pencil">
          Modifier mon profil
        </v-btn>

        <v-btn
          v-if="user.type == 'host'"
          class="rounded-pill mb-5"
          color="#4F685D"
          @click="navigate('/view-post')"
          prepend-icon="mdi-home"
        >
          Mon logement
        </v-btn>

        <v-btn class="rounded-pill mb-2 bg-danger text-light" @click="logOut()" prepend-icon="mdi-power"
          >Déconnexion</v-btn
        >
      </div>
    </v-container>

    <template>
      <v-dialog v-model="modifStep1" transition="dialog-bottom-transition" fullscreen>
        <v-card class="px-3">
          <div class="d-flex flex-row my-2">
            <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="modifStep1 = false"></v-btn>
            <v-card-title class="my-2"> Modifier </v-card-title>
          </div>
          <v-card-text>
            <v-text-field clearable label="Adresse mail" v-model="user.mail"></v-text-field>
            <v-text-field clearable label="Prénom" v-model="user.firstname"></v-text-field>
            <v-text-field clearable label="Nom" v-model="user.lastname"></v-text-field>
            <v-date-input label="Date de naissance" v-model="dateModel"></v-date-input>
            <v-text-field clearable label="Téléphone" v-model="user.tel"></v-text-field>
            <v-btn class="w-100 rounded-pill mb-2" color="#4F685D" @click="editUserProfile()">Sauvegarder</v-btn>
          </v-card-text>
        </v-card>
      </v-dialog>
    </template>
  </v-main>
</template>

<script>
import { VDateInput } from "vuetify/labs/VDateInput";
import axios from "axios";
import { useReservationStore } from "../stores/ReservationStore";
import { useListPostStore } from "../stores/listPostStore";

export default {
  name: "UserProfile",

  components: {
    VDateInput,
  },

  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      modifStep1: false,
      isHost: false,
      dateModel: null,
      reservations: null,
      listPost: [],
    };
  },
  async mounted() {
    this.isHost = this.user.type == "host";
    this.dateModel = new Date(this.user.birthdate);

    const rs = useReservationStore();
    if (!rs.isLoaded) rs.load(this.user.id);
    await this.waitUntil(() => rs.isLoaded);

    this.reservations = rs.reservationsUsers;

    const ps = useListPostStore();

    for (let r of this.reservations) {
      r.post = ps.listPost.filter((p) => p.idPost == r.id_post);
    }

    // console.log(this.reservations)
  },

  methods: {
    waitUntil(conditionFn, interval = 250) {
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

    logOut() {
      sessionStorage.clear();

      window.location.replace("/");
    },
    goBack() {
      this.$router.go(-1);
    },
    editUserProfile() {
      this.user.birthdate =
        this.dateModel.getFullYear() + "-" + (this.dateModel.getMonth() + 1) + "-" + this.dateModel.getDate();

      const userModif = {
        mail: this.user.mail,
        firstname: this.user.firstname,
        lastname: this.user.lastname,
        birthdate: this.user.birthdate,
        tel: this.user.tel,
      };
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(
          apiUrl + "/services/editUser.php",
          { usr: userModif, id: this.user.id },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
        .then((result) => {
          console.log(result);
          this.modifStep1 = false;
        })
        .catch((error) => {
          console.error(error);
        });
    },

    navigate(path) {
      this.$router.push(path);
    },
    getImageSrc(pId) {
      const url = new URL(`/src/assets/img/host${pId}/post/1.jpg`, import.meta.url).href;

      if (!url.includes("undefined")) {
        return url;
      } else {
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },
    goToPostDetails(listing) {
      this.$router.push({ name: "PostDetails", params: { id: listing.idPost } });
    },
  },
};
</script>

<style>
.full-height {
  min-height: 100vh;
}
</style>
