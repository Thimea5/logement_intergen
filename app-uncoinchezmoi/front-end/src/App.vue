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
      <v-btn @click="navigate('/messages')">
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
    console.log("async mounted");
    console.log(this.userStore.isAuthenticated);
    const ps = useListPostStore();

    if (!ps.isLoaded) ps.loadPosts();

    // C'est un peu long au chargement, mais j'ai pas trouvé de solution pour l'instant
    console.log(ps.isLoaded);
    await this.waitUntil(() => ps.isLoaded);
    console.log("après");

    this.listDisplay = ps.listPost;

    console.log(this.isLoggedIn);
    if (this.isLoggedIn) {
      console.log(this.isLoggedIn);
      this.userStore.loadUserFromSession();
      this.isComplete = this.userStore.user.complete;
    }

    console.log(this.userStore.isAuthenticated);
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
