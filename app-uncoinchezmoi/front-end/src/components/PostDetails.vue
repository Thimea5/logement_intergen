<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container class="d-flex flex-column justify-content-between">
      <v-row class="justify-center">
        <v-carousel hide-delimiters class="w-100 h-100" v-if="imgList.length > 0">
          <v-carousel-item v-for="(imgPath, i) in this.imgList" :key="i">
            <div class="w-100 h-100 m-0 p-0">
              <v-img :src="getImageSrc(i)" class="fixed-image" @click="zoomDialog = true"></v-img>
            </div>
          </v-carousel-item>
        </v-carousel>

        <v-card class="p-1 m-2 w-100" v-if="post">
          <v-card-title>
            {{ post.type_logement }} de {{ this.usersData.genre == "F" ? "Mme" : "M." }} {{ this.usersData.lastname }}
            {{ this.usersData.firstname }}
          </v-card-title>
          <v-card-subtitle>
            <v-icon>mdi-map-marker</v-icon> {{ post.address }} - {{ post.postalCode }} {{ post.city }}
          </v-card-subtitle>
          <v-card-text class="p-1">
            <p>
              Taille {{ post.size }} m² - <strong>[{{ post.price }} €/Mois]</strong>
            </p>
            <div class="d-flex flex-row">
              <p>Services :</p>
              <template v-for="(icon, key) in serviceIcons">
                <v-icon
                  v-if="this.listService[post.idPost - 1]?.[key] === 1"
                  :key="`${post.idPost - 1}-${key}`"
                  class="mx-1"
                >
                  {{ icon }}
                </v-icon>
              </template>
            </div>
            <p>
              Fréquence :
              {{ this.listService[post.idPost - 1]?.time != 0 ? this.listService[post.idPost - 1]?.time : 0 }}h /
              semaines
            </p>
            <v-btn v-if="this.user.type == 'guest'" color="primary" class="d-flex m-auto mt-1" @click="contactHost()">
              Contacter le propriétaire
            </v-btn>
          </v-card-text>
        </v-card>

        <v-card class="p-1 m-2 w-100 bg-blue-lighten-2">
          <v-card-title class="d-flex flex-row m-0">
            <div class="mr-auto">Commentaires & Avis</div>
            <div>{{ this.moyenne ? this.moyenne : "??" }}<v-icon>mdi-star</v-icon></div>
          </v-card-title>
          <!--<v-card-subtitle class="m-0">{{ this.comments.length }} avis</v-card-subtitle>-->
          <v-row>
            <v-col v-if="comments.length == 0">
              <v-card class="m-auto">
                <v-card-title class="text-h6 font-weight-bold">Aucun Commentaire</v-card-title>
              </v-card>
            </v-col>
            <v-col v-else>
              <v-carousel hide-delimiters :show-arrows="false" cycle class="h-100 w-80">
                <v-carousel-item v-for="comment in comments" :key="comment.id">
                  <v-card class="m-1">
                    <v-card-title height="100%">
                      De {{ comment.author == " " ? "Anonyme" : comment.author }}
                    </v-card-title>

                    <v-card-text>
                      {{ comment.text }}
                    </v-card-text>
                    <v-card-subtitle class="d-flex align-items-center p-2 mt-0">
                      <div class="mr-auto text-caption grey--text">Posté le {{ formatDate(comment.createdAt) }}</div>
                      <v-icon
                        v-if="comment.author === `${user.firstname} ${user.lastname}`"
                        size="small"
                        @click="deleteReview(comment.id)"
                      >
                        mdi-delete
                      </v-icon>
                    </v-card-subtitle>
                  </v-card>
                </v-carousel-item>
              </v-carousel>
            </v-col>
          </v-row>

          <!-- On limite à un seul commentaire par annonce pour chaque utilisateur -->
          <v-btn
            v-if="this.user.type == 'guest' && this.canAddReview"
            color="secondary"
            class="d-flex m-auto mt-1"
            @click="commentPostById(post.idHost)"
          >
            Partagez votre Avis
          </v-btn>
        </v-card>
        <v-btn v-if="this.user.type == 'guest'" color="primary" class="d-flex m-auto mt-1 mb-2" @click="reserver()"
          >Réserver l'offre</v-btn
        >
      </v-row>

      <v-dialog v-model="zoomDialog" fullscreen class="dialog-black-background">
        <v-img :src="getImageSrc(currentImageIndex)" class="zoomed-image">
          <v-btn
            icon
            color="white"
            rounded="circle"
            class="position-absolute top-0 right-0 m-1"
            @click="zoomDialog = false"
          >
            <v-icon>mdi-close</v-icon>
          </v-btn>
        </v-img>
      </v-dialog>
    </v-container>
  </v-main>
</template>

<script>
import { useListPostStore } from "../stores/listPostStore";
import { useRoute } from "vue-router";
import axios from "axios";
import { useConversationStore } from "../stores/ConversationStore";

export default {
  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      post: null,
      imgList: [],
      comments: [],
      currentImageIndex: 0,
      zoomDialog: false,
      usersData: [],
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
      listService: [],
      moyenne: 0,
      canAddReview: false,
    };
  },

  async mounted() {
    const route = useRoute();
    const ps = useListPostStore();

    if (!ps.isLoaded) ps.loadPosts();
    await this.waitUntil(() => ps.isLoaded);

    const postId = route.params.id; // récupération du paramètre

    // Chargement des images
    this.post = ps.listPost.find((ph) => ph.idPost == postId);
    if (this.post) {
      for (let i = 1; i <= this.post.nbPhoto; i++) {
        this.imgList.push(
          `/src/assets/img/${this.post.img}/host_photo${this.post.img[this.post.img.length - 1]}_${i}.jpg`
        );
      }
    }

    const apiUrl = import.meta.env.VITE_API_URL;
    axios
      .get(apiUrl + "/services/reviewsManager.php", {
        params: {
          id: this.post.idPost,
        },
        headers: {
          "Content-Type": "application/json",
        },
      })
      .then((result) => {
        // Chargement des commentaires et calcul de la moyenne
        if (result.status === 200 && result.data["success"]) {
          const res = result.data["reviews"];
          for (let i = 0; i < res.length; i++) {
            this.comments.push({
              id: res[i]["id"],
              score: res[i]["score"],
              text: res[i]["comment"],
              createdAt: res[i]["creation_date"],
              author: res[i]["nameAuthor"],
            });
          }
          this.comments.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
        }

        if (
          this.comments.find((c) => {
            console.log(c.author);
            console.log(this.user.firstname + " " + this.user.lastname);
            return c.author == this.user.firstname + " " + this.user.lastname;
          })
        ) {
          this.canAddReview = false;
        } else {
          this.canAddReview = true;
        }
        this.calculateAverage();

        // Chargement des informations de l'hôte
        axios
          .get(apiUrl + "/services/userManager.php")
          .then((response) => {
            this.usersData = response.data[0][postId];
          })
          .catch((error) => {
            console.error("Erreur lors de la récupération des utilisateurs:", error);
          });

        this.listService = ps.listServices;
      })
      .catch((error) => {
        console.error(error);
      });
  },

  methods: {
    deleteReview(pId) {
      console.log(pId);
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .delete(`${apiUrl}/services/reviewsManager.php`, {
          data: {
            id: pId,
          },

          headers: {
            "Content-Type": "application/json",
          },
        })
        .then((result) => {
          console.log(result);
          if (result.status === 200 && result.data["success"]) {
            this.$router.go(0);
          }
        })
        .catch((error) => {
          console.log(error);
        });
      this.calculateAverage();
    },

    calculateAverage() {
      this.moyenne = 0;

      let validScoresCount = 0;
      for (let i = 0; i < this.comments.length; i++) {
        if (this.comments[i].score != -1) {
          this.moyenne += this.comments[i].score;
          validScoresCount++;
        }
      }

      if (validScoresCount > 0) {
        this.moyenne /= validScoresCount;
      } else {
        this.moyenne = 0;
      }

      this.moyenne = parseFloat(this.moyenne.toFixed(2));
    },

    reserver() {
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(
          apiUrl + "/services/reservationManager.php",
          { idPost: this.post.idPost, idUser: this.user.id },
          {
            header: {
              "Content-Type": "application/json",
            },
          }
        )
        .then((result) => {
          console.log(result);
          this.$router.push("/reservation");
        })
        .catch((error) => {
          console.log(error);
        });
    },

    getImageSrc(index) {
      // TODO BUG ICI, si aucune image, ça renvoie quand même une url...
      this.currentImageIndex = index;
      const url = new URL(`${this.imgList[index]}`, import.meta.url).href;
      if (!url.includes("undefined")) {
        return url;
      } else {
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },

    formatDate(date) {
      const options = {
        year: "numeric",
        month: "numeric",
        day: "numeric",
      };
      return new Date(date).toLocaleDateString("fr-FR", options).replace(":", "h");
    },

    commentPostById(postId) {
      this.$router.push({ name: "NewComment", params: { id: postId } });
    },

    reportPostById(postId) {
      this.$router.push({ name: "NewReport", params: { id: postId } });
    },

    contactHost() {
      const cs = useConversationStore();

      if (cs.isLoaded1) {
        let ci = cs.conversations.find((c) => {
          return c.id == this.id;
        });

        if (ci == undefined) {
          const apiUrl = import.meta.env.VITE_API_URL;
          axios
            .post(
              apiUrl + "/services/conversationsManager.php",
              { idDest: this.usersData.id, idUser: this.user.id },
              {
                header: {
                  "Content-Type": "application/json",
                },
              }
            )
            .then((result) => {
              console.log(result);
              this.$router.push({ name: "MessageComponent", params: { destUserId: this.usersData.id } });
            })
            .catch((error) => {
              console.log(error);
            });
        }
      }
    },

    goBack() {
      this.$router.go(-1);
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

<style scoped>
.fixed-image {
  object-fit: cover;
  border-radius: 8px;
}

.dialog-black-background {
  background-color: black !important;
  display: flex;
  justify-content: center;
  align-items: center;
}

.zoomed-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}
</style>
