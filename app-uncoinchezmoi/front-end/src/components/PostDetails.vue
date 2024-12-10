<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container class="d-flex flex-column justify-content-between">
      <v-row class="justify-center">
        <div class="image-container">
          <v-btn
            icon="mdi-chevron-left"
            variant="plain"
            size="large"
            class="arrow-left"
            color="white"
            @click="prevImage"
          ></v-btn>

          <v-img :src="getImageSrc(currentImageIndex)" class="fixed-image" @click="zoomDialog = true"></v-img>

          <v-btn
            icon="mdi-chevron-right"
            variant="plain"
            size="large"
            class="arrow-right"
            color="white"
            @click="nextImage"
          ></v-btn>
        </div>

        <v-card class="styled-card bg-blue-grey-lighten-4" v-if="post">
          <v-card-title>
            {{ post.type_logement }} de {{ this.usersData.genre == "F" ? "Mme" : "M." }} {{ this.usersData.lastname }}
            {{ this.usersData.firstname }}
          </v-card-title>
          <v-card-subtitle>
            <v-icon>mdi-map-marker</v-icon> {{ post.address }} - {{ post.postalCode }} {{ post.city }}
          </v-card-subtitle>
          <v-card-text>
            <p>Taille {{ post.size }} m² - [{{ post.price }} €/Mois]</p>
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
          </v-card-text>
        </v-card>

        <v-card class="styled-card bg-blue-grey-lighten-4 p-2">
          <v-card-title>Commentaires & Avis ({{ comments.length }})</v-card-title>
          <v-row>
            <v-col v-if="comments.length == 0">
              <v-card class="styled-card m-auto">
                <v-card-title class="text-h6 font-weight-bold">Aucun Commentaire</v-card-title>
              </v-card>
            </v-col>
            <v-col v-else v-for="comment in comments" :key="comment.id" cols="12" sm="6" md="4">
              <v-card class="styled-card m-auto">
                <v-card-title style="white-space: normal; word-wrap: break-word; overflow: visible" height="100%">
                  {{ comment.text }}
                </v-card-title>

                <div style="border-top: 1px solid #ccc; margin: 2% 5%"></div>

                <v-card-subtitle class="text-caption grey--text">
                  De {{ comment.author == " " ? "Anonyme" : comment.author }} -
                  {{ formatDate(comment.createdAt) }}
                </v-card-subtitle>
              </v-card>
            </v-col>
          </v-row>
        </v-card>

        <div class="button-container">
          <v-btn v-if="this.user.type == 'guest'" color="primary" @click="contactHost()"
            >Contacter le propriétaire</v-btn
          >
          <v-btn v-if="this.user.type == 'guest'" color="primary" @click="reserver()">Réserver l'offre</v-btn>
          <v-btn color="secondary" @click="commentPostById(post.idHost)">Laissez un commentaire</v-btn>
          <v-btn color="error" @click="reportPostById(post.id)">Signalez l'annonce</v-btn>
        </div>
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
    };
  },

  async mounted() {
    const route = useRoute();
    const ps = useListPostStore();

    if (!ps.isLoaded) ps.loadPosts();
    await this.waitUntil(() => ps.isLoaded);

    const postId = route.params.id;

    this.post = ps.listPost.find((ph) => ph.idPost == postId);
    if (this.post) {
      for (let i = 1; i <= this.post.nbPhoto; i++) {
        this.imgList.push(
          `/src/assets/img/${this.post.img}/host_photo${this.post.img[this.post.img.length - 1]}_${i}.jpg`
        );
      }
    }

    // Chargement des commentaires du post
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
        if (result.status === 200 && result.data["success"]) {
          const res = result.data["reviews"];
          console.log("resComments", res);
          for (let i = 0; i < res.length; i++) {
            this.comments.push({
              id: res[i]["id"],
              score: res[i]["score"],
              text: res[i]["comment"],
              createdAt: res[i]["creation_date"],
              author: res[i]["nameAuthor"],
            });
          }

          //console.log(this.comments);

          this.comments.sort((a, b) => new Date(b.createdAt) - new Date(a.createdAt));
        }

        //console.log("chargeemnt de l'users");

        axios
          .get(apiUrl + "/services/userManager.php")
          .then((response) => {
            this.usersData = response.data[0][postId];
          })
          .catch((error) => {
            console.error("Erreur lors de la récupération des utilisateurs:", error);
          });

        //console.log(this.usersData);

        this.listService = ps.listServices;
        console.log(ps.listServices);
        console.log(this.listService);
      })
      .catch((error) => {
        console.error(error);
      });
  },

  methods: {
    reserver() {
      console.log(this.post.idPost);
      console.log(this.user.id);

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
        })
        .catch((error) => {
          console.log(error);
        });
    },

    formatDate(date) {
      const options = {
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
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
        //console.log(cs.conversations);
        //console.log(this.usersData);
        //console.log(this.user);
        let ci = cs.conversations.find((c) => {
          return c.id == this.id;
        });

        if (ci == undefined) {
          //console.log("nouvelle conv");
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

    getImageSrc(index) {
      return this.imgList[index] || "";
    },

    prevImage() {
      this.currentImageIndex = (this.currentImageIndex - 1 + this.imgList.length) % this.imgList.length;
    },

    nextImage() {
      this.currentImageIndex = (this.currentImageIndex + 1) % this.imgList.length;
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
.image-container {
  position: relative;
  display: flex;
  justify-content: center;
  align-items: center;
  width: 400px;
  height: 35vh;
}

.fixed-image {
  object-fit: cover;
  width: 100%;
  height: 100%;
  border-radius: 8px;
}

.arrow-left,
.arrow-right {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  z-index: 10;
}

.arrow-left {
  left: 10px;
}

.arrow-right {
  right: 10px;
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

.button-container {
  display: flex;
  flex-direction: column;
  justify-content: center;
  width: 100%;
  gap: 16px;
  margin: 5%;
}
.styled-card {
  margin: 5%;
  border-radius: 8px;
  width: 100%;
  box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
}
</style>
