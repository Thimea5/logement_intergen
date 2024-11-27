<template>
  <v-main>
    <!-- Partie non authentifiée -->
    <v-container v-if="!isLoggedIn" class="d-flex flex-column justify-center align-center" style="height: 100vh">
      <!-- Logo centré en haut -->
      <img src="../assets/logo.png" alt="Logo Application" class="logo" />

      <!-- Conteneur des boutons de connexion et d'inscription -->
      <div class="d-flex justify-space-between align-center" style="width: 100%; margin-top: 20px">
        <!-- Bouton S'inscrire à gauche -->
        <v-btn @click="navigate('/register')" color="primary" class="mx-2" style="flex: 1"> S'inscrire </v-btn>

        <!-- Bouton Se connecter à droite -->
        <v-btn @click="navigate('/login')" color="secondary" class="mx-2" style="flex: 1"> Se connecter </v-btn>
      </div>

      <!-- Lien vers les mentions légales centré -->
      <router-link to="/legal-notices" class="legal-link" style="margin-top: 20px; text-align: center"
        >Mentions légales</router-link
      >
    </v-container>

    <!-- Partie authentifiée -->
    <v-container v-if="isLoggedIn" class="d-flex flex-column justify-center">
      <!-- Logo plus petit et centré en haut -->
      <img src="../assets/logo.png" alt="Logo Application" class="logo-small" />

      <!-- Conteneur des boutons Mon logement et Favoris -->
      <div class="d-flex justify-space-between align-center" style="width: 100%; margin-top: 20px">
        <!-- Bouton Mon logement à gauche -->
        <v-btn @click="navigate('/my-property')" color="primary" class="mx-2" style="flex: 1"> Mon logement </v-btn>

        <!-- Bouton Favoris à droite -->
        <v-btn @click="navigate('/favorites')" color="secondary" class="mx-2" style="flex: 1">
          Favoris
          <v-icon>mdi-heart</v-icon>
        </v-btn>
      </div>

      <!-- Texte Recommandées pour vous à gauche -->
      <p class="recommended-text" style="margin-top: 20px">Recommandées pour vous</p>

      <!-- Affichage des cartes de logement (le contenu sera ajouté plus tard) -->
      <!-- Affichage des cartes de logement (le contenu sera ajouté plus tard) -->
      <div v-if="listDisplay.length === 0">Aucune annonce trouvée pour le moment</div>
      <div v-else>
        <v-row>
          <v-col v-for="listing in listDisplay" :key="listing.id" cols="12" sm="6" md="4">
            <v-card @click="goToPostDetails(listing)">
              <v-img :src="getImageSrc(listing.img)">
                <v-btn icon color="red" rounded="circle" class="favorite-btn">
                  <v-icon>mdi-heart</v-icon>
                </v-btn>
              </v-img>
              <v-card-title>{{ listing.address }}</v-card-title>
              <v-card-subtitle>{{ listing.city }} - {{ listing.postalCode }}</v-card-subtitle>
              <v-card-text>
                <p>Disponibilité: {{ listing.available }}</p>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";

export default {
  name: "Home",

  data() {
    return {
      listDisplay: [],
      isLoggedIn: sessionStorage.getItem("user") != null,
    };
  },

  async mounted() {
    if (this.isLoggedIn) {
      const ps = useListPostStore();

      if (!ps.isLoaded) {
        ps.loadPosts();
      }

      // C'est un peu long au chargement, mais j'ai pas trouvé de solution pour l'instant
      await this.waitUntil(() => ps.isLoaded);

      this.listDisplay = ps.listHost.reverse();
    }
  },

  methods: {
    goToPostDetails(listing) {
      this.$router.push({ name: "PostDetails", params: { id: listing.idHost } });
    },

    getImageSrc(pImgPath) {
      try {
        return new URL(`/src/assets/img/${pImgPath}/host_photo${pImgPath[pImgPath.length - 1]}_1.jpg`, import.meta.url)
          .href;
      } catch (error) {
        console.error("Erreur lors du chargement de l'image :", error);
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },
    waitUntil(conditionFn, interval = 100) {
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
    navigate(path) {
      this.$router.push(path);
    },
  },
};
</script>

<style scoped>
.favorite-btn {
  position: absolute;
  top: 10px;
  right: 10px;
  background-color: white; /* Optionnel : Couleur blanche pour le fond */
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2); /* Optionnel : Petit ombrage */
}

/* Style pour le logo dans la partie non authentifiée */
.logo {
  width: 60%;
  height: auto;
  margin-top: 0;
  margin-bottom: 75%;
}

/* Style pour le logo dans la partie authentifiée (plus petit) */
.logo-small {
  width: 20%;
  height: auto;
  margin-top: 0;
  margin-bottom: 5%;
  margin-left: auto;
  margin-right: auto;
}

/* Personnalisation des boutons */
.v-btn {
  width: 48%; /* Occupe 48% de la largeur disponible, ce qui permet d'ajouter un espace entre les deux */
  font-weight: bold;
  padding: 10px 20px;
}

.v-btn.primary {
  background-color: #1976d2; /* Bleu clair pour "Mon logement" */
  color: white;
}

.v-btn.secondary {
  background-color: #ff5722; /* Orange pour "Favoris" */
  color: white;
}

/* Texte Recommandées pour vous */
.recommended-text {
  font-size: 18px;
  font-weight: bold;
  color: #333;
}

/* Style pour le lien Mentions légales */
.legal-link {
  color: #6200ee; /* Couleur violette */
  font-size: 14px;
  text-decoration: none;
}

.legal-link:hover {
  text-decoration: underline;
}

/* Centrer tout dans le conteneur */
.d-flex {
  display: flex;
}

.justify-center {
  justify-content: center;
}

.align-center {
  align-items: center;
}

.justify-space-between {
  justify-content: space-between;
}
</style>
