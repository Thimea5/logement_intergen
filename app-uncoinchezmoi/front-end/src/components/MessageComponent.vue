<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" width="15%" class="ms-1 me-1" @click="goBack()"></v-btn>
      <div>
        <v-img
          aspect-ratio="1"
          rounded="pill"
          :src="getImageSrc(this.target != null ? this.target.id : -1)"
          width="50"
          cover
        ></v-img>
      </div>

      <v-toolbar-title class="ms-5">
        {{ this.target != null ? this.target.firstname : "Anonyme" }}
        {{ this.target != null ? this.target.lastname : "" }} | {{ this.age != 0 ? this.age : "??" }} ans
      </v-toolbar-title>
    </v-app-bar>

    <v-container class="d-flex flex-column p-1">
      <div class="m-auto mt-2 mb-2">
        <v-btn
          :disabled="this.user.type === 'guest' ? false : true"
          :color="this.user.type === 'guest' ? 'green' : 'white'"
          variant="outlined"
          @click="showPostDetails = true"
        >
          Voir le logement
        </v-btn>
      </div>

      <!-- Zone des messages -->
      <div
        class="messages-container flex-grow-1"
        ref="messagesContainer"
        style="overflow-y: auto; height: calc(93vh - 250px)"
      >
        <template v-for="(messages, date) in groupMessagesByDate()" :key="date">
          <div class="date-separator">
            <v-divider class="my-4" text>{{ date }}</v-divider>
          </div>

          <div class="message-group">
            <div
              v-for="(msg, index) in messages"
              :key="index"
              :class="{ 'message-left': msg.id_user != target.id, 'message-right': msg.id_user == target.id }"
              @click="openPopup(msg)"
            >
              <v-card
                class="m-1"
                text-color="black"
                :class="{ 'chip-left': msg.id_user != target.id, 'chip-right': msg.id_user == target.id }"
              >
                <v-card-title style="white-space: normal; word-wrap: break-word; font-size: 10pt">
                  {{ msg.content }}
                </v-card-title>
                <v-card-subtitle class="text-caption grey-text text-end me-1 m-0 p-0">
                  {{ formatDate(msg.creation_date, 1) }}
                </v-card-subtitle>
              </v-card>
            </div>
          </div>
        </template>
      </div>

      <div class="message-input-container">
        <v-textarea
          v-model="msgContent"
          variant="solo-filled"
          label="Écrivez votre message"
          rows="3"
          clearable
          class="message-textarea"
          append-inner-icon="mdi-send"
          @click:append-inner="sendMessage()"
        ></v-textarea>
      </div>
    </v-container>

    <v-dialog v-model="showPostDetails">
      <v-card class="p-0 m-0">
        <v-card-title class="d-flex flex-row align-items-center m-1 p-1">
          <h3 class="m-2 mr-auto">Informations</h3>
          <v-icon class="m-2" @click="showPostDetails = false">mdi-close</v-icon>
        </v-card-title>

        <v-card-text class="mt-0 m-1 p-2">
          <h4>
            {{ post.type_logement }} de {{ this.target.genre == "F" ? "Mme " : "M. " }} {{ this.target.lastname }}
            {{ this.target.firstname }}
          </h4>
          <div class="d-flex flex-row mt-1 mb-1 p-0 align-items-center">
            <v-icon size="x-large">mdi-map-marker</v-icon>
            <div class="d-flex flex-column m-0 p-0">
              <p class="m-0 p-0">{{ post.address }}</p>
              <p class="m-0 p-0">{{ post.postalCode }} {{ post.city }}</p>
            </div>
          </div>

          <p class="pb-1 m-0">
            Description :
            {{ post.description.length > 150 ? post.description.substring(0, 150) + "..." : post.description }}
          </p>

          <p>Taille de la chambre : {{ post.roomSize }} m²</p>

          <div class="d-flex flex-row">
            <p class="p-0 m-0">Services :</p>
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

          <p class="m-0 p-0">
            Fréquence :
            {{ this.listService[post.idPost - 1]?.time != 0 ? this.listService[post.idPost - 1]?.time : 0 }}h / semaines
          </p>
        </v-card-text>

        <!-- Bouton Réserver -->
        <v-card-actions>
          <v-btn color="var(--green-color)" style="border: 1px solid var(--dark-green-color)" block @click="reserver">
            Réserver
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="showPopup" max-width="600px">
      <v-card>
        <v-card-title class="d-flex justify-space-between align-center">
          <h1>
            De {{ selectedMessage.id_user == target.id ? "Moi" : this.target.firstname + " " + this.target.lastname }}
          </h1>
          <v-icon @click="showPopup = false">mdi-close</v-icon>
        </v-card-title>

        <v-card-subtitle class="text-caption text-start me-3">
          {{ selectedMessage ? formatDate(selectedMessage.creation_date, 2) : "" }}
        </v-card-subtitle>

        <v-card-text style="font-size: 14pt; line-height: 1.5; text-align: center">
          {{ selectedMessage ? selectedMessage.content : "" }}
        </v-card-text>

        <v-card-actions v-if="selectedMessage.id_user == target.id">
          <v-btn
            color="var(--green-color)"
            style="border: 1px solid var(--dark-green-color)"
            block
            @click="deleteMessage"
          >
            Supprimer mon message
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-main>
</template>

<script>
import { useRoute } from "vue-router";
import axios from "axios";
import { useConversationStore } from "../stores/ConversationStore";
import { useListPostStore } from "../stores/listPostStore";
import "../assets/style.css";

export default {
  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      idDest: -1,
      convId: -1,
      post: null,
      age: 0,
      target: null,
      msgContent: "",
      messages: [],
      timeline: [],
      showPostDetails: false,
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
      selectedMessage: null,
      showPopup: false,
    };
  },

  async mounted() {
    // Chargement des messages
    const cs = useConversationStore();
    const ps = useListPostStore();
    const route = useRoute();

    if (!cs.isLoaded1) cs.load(this.user.id);
    if (!ps.isLoaded) ps.loadPosts();
    await this.waitUntil(() => ps.isLoaded && cs.isLoaded1);

    this.idDest = route.params.id;

    this.post = ps.listPost.find((p) => {
      return p.idUser == this.idDest;
    });

    this.listService = ps.listServices;

    let ci = cs.conversations.find((c) => {
      return (
        (c.id_user1 == this.idDest && c.id_user2 == this.user.id) ||
        (c.id_user2 == this.idDest && c.id_user1 == this.user.id)
      );
    });

    this.convId = ci.id;
    console.log(ci);

    this.target = cs.convUsersInfo.find((usr) => {
      return usr.id == ci.id_user1 || usr.id == ci.id_user2;
    });

    this.age = this.calculateAge(this.target.birthdate);

    this.loadMessages();
    console.log(this.messages);
  },

  methods: {
    openPopup(msg) {
      this.selectedMessage = msg;
      this.showPopup = true;
    },

    scrollToBottom() {
      const container = this.$refs.messagesContainer;
      if (container) {
        container.scrollTop = container.scrollHeight;
      }
    },
    reserver() {
      this.$router.push({ name: "Reservation", params: { id: this.post.idPost } });
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

    formatDate(date, mode) {
      let options;
      if (mode === 1) {
        options = {
          hour: "2-digit",
          minute: "2-digit",
        };
      } else {
        options = {
          year: "numeric",
          month: "long",
          day: "numeric",
          hour: "2-digit",
          minute: "2-digit",
        };
      }

      return new Date(date).toLocaleTimeString("fr-FR", options).replace(":", "h");
    },

    shouldDisplayDateSeparator(index) {
      if (index === 0) return true;

      const currentDate = new Date(this.messages[index].creation_date).toDateString();
      const previousDate = new Date(this.messages[index - 1].creation_date).toDateString();
      return currentDate !== previousDate;
    },

    calculateAge(dateString) {
      const today = new Date();
      const birthDate = new Date(dateString);
      let age = today.getFullYear() - birthDate.getFullYear();
      const monthDiff = today.getMonth() - birthDate.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
        age--;
      }
      return age;
    },

    goBack() {
      this.$router.go(-1);
    },

    loadMessages() {
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .get(apiUrl + "/services/messageManager.php", {
          params: { id: this.convId },
          header: { "Content-Type": "application/json" },
        })
        .then((result) => {
          this.messages = result.data;
          console.log(this.messages);
        })
        .catch((error) => {
          console.log(error);
        });
    },

    groupMessagesByDate() {
      const grouped = {};
      this.messages.forEach((msg) => {
        const dateKey = new Date(msg.creation_date).toLocaleDateString("fr-FR");
        if (!grouped[dateKey]) {
          grouped[dateKey] = [];
        }
        grouped[dateKey].push(msg);
      });
      return grouped;
    },

    deleteMessage() {
      console.log(this.selectedMessage);
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .delete(`${apiUrl}/services/messageManager.php`, {
          data: {
            id: this.selectedMessage.id,
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
    },

    sendMessage() {
      if (this.msgContent.trim() !== "") {
        this.messages.push({ content: this.msgContent });
        const apiUrl = import.meta.env.VITE_API_URL;
        axios
          .post(
            apiUrl + "/services/messageManager.php",
            {
              content: this.msgContent,
              conv_id: this.convId,
              user_id: this.target.id,
            },
            { header: { "Content-Type": "application/json" } }
          )
          .then((result) => {
            this.msgContent = "";
            this.loadMessages();
          })
          .catch((error) => console.log(error));
      }
    },

    getImageSrc(pId) {
      const url = new URL(`/src/assets/img/user${pId}/${pId}.jpg`, import.meta.url).href;
      if (!url.includes("undefined")) {
        return url;
      } else {
        return new URL(`/src/assets/img/error.jpg`, import.meta.url).href;
      }
    },
  },
};
</script>

<style scoped>
.message-input-container {
  margin-top: 1%;
  margin-bottom: 0px;
  padding: 10px;
  padding-bottom: 0px;
  position: sticky;
  bottom: 0;
  left: 0;
  right: 0;
  top: 1;
  background-color: white;
  z-index: 1;
}

.message-textarea {
  width: 100%;
  max-width: 400px;
}

.date-separator {
  text-align: center;
  margin: 1% 0;
  color: grey;
  font-size: 0.9em;
}

.message-group {
  display: flex;
  flex-direction: column;
  width: 100%;
}

.message-left {
  text-align: left;
}

.message-right {
  text-align: right;
}

.chip-left {
  background-color: #f5f5f5;
  float: left;
  clear: both;
}

.chip-right {
  background-color: #e6cdb5;
  float: right;
  clear: both;
}
</style>
