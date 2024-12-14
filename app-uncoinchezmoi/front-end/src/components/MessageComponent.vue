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

      <v-app-title class="ms-5">
        {{ this.target != null ? this.target.firstname : "Anonyme" }}
        {{ this.target != null ? this.target.lastname : "" }} | {{ this.age != 0 ? this.age : "??" }} ans
      </v-app-title>
    </v-app-bar>

    <v-container class="d-flex flex-column p-1">
      <div class="m-auto mt-2 mb-2">
        <v-btn color="green" variant="outlined" @click="TODO"> Demander le logement </v-btn>
      </div>
      <!--BUG ICI, ne s'adapte pas à la taille d'écran-->
      <div style="position: static; height: 55vh; overflow: auto">
        <template v-for="(messages, date) in groupMessagesByDate()" :key="date">
          <!-- Séparateur de date -->
          <div class="date-separator">
            <v-divider class="my-4" text>
              {{ date }}
            </v-divider>
          </div>

          <!-- Conteneur des messages pour une date -->
          <div class="message-group">
            <div
              v-for="(msg, index) in messages"
              :key="index"
              :class="{ 'message-left': msg.id_user != target.id, 'message-right': msg.id_user == target.id }"
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
                  {{ formatDate(msg.creation_date) }}
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
  </v-main>
</template>

<script>
import { useRoute } from "vue-router";
import axios from "axios";
import { useConversationStore } from "../stores/ConversationStore";
import "../assets/style.css";

export default {
  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      idDest: -1,
      convId: -1,
      age: 0,
      target: null,
      msgContent: "",
      messages: [],
      timeline: [],
    };
  },

  async mounted() {
    // Chargement des messages
    const cs = useConversationStore();
    const route = useRoute();

    if (!cs.isLoaded1) cs.load(this.user.id);
    await this.waitUntil(() => cs.isLoaded1);

    this.idDest = route.params.id;

    let ci = cs.conversations.find((c) => {
      return (
        (c.id_user1 == this.idDest && c.id_user2 == this.user.id) ||
        (c.id_user2 == this.idDest && c.id_user1 == this.user.id)
      );
    });

    this.convId = ci.id;

    this.target = cs.convUsersInfo.find((usr) => {
      return usr.id == ci.id_user1 || usr.id == ci.id_user2;
    });

    this.age = this.calculateAge(this.target.birthdate);

    this.loadMessages();
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

    formatDate(date) {
      const options = {
        hour: "2-digit",
        minute: "2-digit",
      };
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
          console.log(result.data);
          this.messages = result.data;
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
            console.log(result);
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
  margin-bottom: 10%;
  padding: 10px;
  position: fixed;
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
