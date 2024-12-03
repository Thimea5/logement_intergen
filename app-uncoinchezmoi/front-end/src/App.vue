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
    const ps = useListPostStore();
    const cs = useConversationStore();

    if (!ps.isLoaded) ps.loadPosts();

    // C'est un peu long au chargement, mais j'ai pas trouvé de solution pour l'instant
    await this.waitUntil(() => ps.isLoaded);
    console.log("chargement des listes ok");
    this.listDisplay = ps.listPost;

    if (this.isLoggedIn) {
      console.log("connexion faite");
      this.userStore.loadUserFromSession();
      this.isComplete = this.userStore.user.complete;

      if (!cs.isLoaded1) cs.load(this.userStore.user.id);

      await this.waitUntil(() => cs.isLoaded1);
      console.log("chargeemnt des conv/msg ok");
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
