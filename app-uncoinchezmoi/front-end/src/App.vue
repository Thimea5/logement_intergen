<template>
  <v-app class="custom-application">
    <router-view></router-view>

    <v-bottom-navigation v-if="this.userStore.isAuthenticated">
      <v-btn @click="navigate('/')">
        <v-icon>
          <img src="../public/dark-favicon.ico" alt="Icon" style="width: 16px; height: 16px" />
        </v-icon>
        Accueil
      </v-btn>
      <v-btn @click="navigate('/map')">
        <v-icon>mdi-magnify</v-icon>
        Rechercher
      </v-btn>
      <v-btn @click="navigate('/conversations')">
        <v-icon class="fa-regular fa-comments"></v-icon>
        Messages
      </v-btn>
      <v-btn @click="navigate('/user-profile')">
        <v-badge dot color="red" v-if="!isComplete">
          <v-icon>mdi-account-outline</v-icon>
        </v-badge>
        Profil
      </v-btn>
    </v-bottom-navigation>
  </v-app>
</template>

<script>
import "./assets/style.css";
import { useUserStore } from "./stores/userStore";
import { useListPostStore } from "./stores/listPostStore";
import { useConversationStore } from "./stores/ConversationStore";
import { useReservationStore } from "./stores/ReservationStore";

export default {
  name: "App",

  data() {
    return {
      userStore: useUserStore(),
      isHomePage: true,
      isComplete: null,
      isLoggedIn: sessionStorage.getItem("user") != null,
    };
  },

  async mounted() {
    // Chargement un peu long, c'est de l'asynchrone, mais j'ai pas mieux comme solution
    const ps = useListPostStore();
    const cs = useConversationStore();
    const rs = useReservationStore();

    if (this.isLoggedIn) {
      console.log("Connexion OK");
      this.userStore.loadUserFromSession();
      this.isComplete = this.userStore.user.complete;
      console.log("Chargement du store utilisateur OK");

      if (!cs.isLoaded1) cs.load(this.userStore.user.id);
      if (!rs.isLoaded) rs.load(this.userStore.user.id);
      if (!ps.isLoaded) ps.loadPosts();

      await this.waitUntil(() => ps.isLoaded && cs.isLoaded1 && rs.isLoaded);

      console.log("Chargement du store des annonces OK");
      console.log("Chargement du store des conversations OK");
      console.log("Chargement du store des réservations OK");
    }
  },

  methods: {
    navigate(path) {
      this.$router.push(path);
    },

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
  },
};
</script>

<style>
:root {
  --green-color: #669b83;
  --dark-green-color: #4f685d;
  --green-disabled-color: #547e6b;
  --background-color: #f8f9fa;
  --text-color: #343a40;
}
</style>
