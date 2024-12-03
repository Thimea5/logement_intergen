<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" width="25%" @click="goBack()"></v-btn>
      <v-app-title
        >{{ this.target != null ? this.target.firstname : "Anonyme" }}
        {{ this.target != null ? this.target.lastname : "" }}</v-app-title
      >
    </v-app-bar>

    <v-container class="d-flex flex-column" style="height: 100vh">
      <div class="messages-container">
        <div
          v-for="(msg, index) in messages"
          :key="index"
          class="message"
          :class="{ 'message-left': msg.id_user != target.id, 'message-right': msg.id_user == target.id }"
        >
          <v-card
            class="lightblue m-2 p-2"
            text-color="black"
            :class="{ 'chip-left': msg.id_user != target.id, 'chip-right': msg.id_user == target.id }"
            style="border-radius: 0; display: inline-block; max-width: 80%"
          >
            <v-card-title style="white-space: normal; word-wrap: break-word; overflow: visible" height="100%">
              {{ msg.content }}
            </v-card-title>

            <div style="border-top: 1px solid #ccc; margin: 2% 5%"></div>

            <v-card-subtitle class="text-caption grey--text">
              Envoyé le {{ formatDate(msg.creation_date) }}
            </v-card-subtitle>
          </v-card>
        </div>
      </div>

      <div class="message-input-container" style="padding-bottom: 20%">
        <v-textarea
          v-model="msgContent"
          label="Écrivez un message"
          outlined
          rows="3"
          class="message-textarea"
        ></v-textarea>

        <v-btn @click="sendMessage()" color="primary" class="mt-2" rounded block>Envoyer</v-btn>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import { useRoute } from "vue-router";
import axios from "axios";
import { useConversationStore } from "../stores/ConversationStore";

export default {
  data() {
    return {
      id: -1,
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      target: null,
      msgContent: "",
      messages: [],
      messagesContainerHeight: 70, // Hauteur ajustable pour la zone des messages
    };
  },
  async mounted() {
    const cs = useConversationStore();
    const route = useRoute();

    if (!cs.isLoaded1) cs.load(this.user.id);

    await this.waitUntil(() => cs.isLoaded1);

    if (cs.isLoaded1) {
      this.id = route.params.id;
      // On récupère la conv
      let ci = cs.conversations.find((c) => {
        return c.id == this.id;
      });

      // On récupère la cible de la conv (en face)
      this.target = cs.convUsersInfo.find((usr) => {
        return usr.id == ci.id_user1 || usr.id == ci.id_user2;
      });

      this.loadMessages();
      //console.log(this.target.id);
      this.scrollToBottom();
    }
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
        year: "numeric",
        month: "long",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
      };
      return new Date(date).toLocaleDateString("fr-FR", options).replace(":", "h");
    },

    goBack() {
      this.$router.go(-1);
    },

    loadMessages() {
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .get(apiUrl + "/services/messageManager.php", {
          params: { id: this.id },
          header: { "Content-Type": "application/json" },
        })
        .then((result) => {
          console.log(result.data);
          this.messages = result.data;
        })
        .catch((error) => {
          console.log(error);
        });

      console.log(this.messages);
    },

    sendMessage() {
      // Ajout d'un nouveau message dans la liste
      console.log(this.user.id);
      console.log(this.target.id);
      if (this.msgContent.trim() !== "") {
        this.messages.push({ content: this.msgContent });
        const apiUrl = import.meta.env.VITE_API_URL;
        axios
          .post(
            apiUrl + "/services/messageManager.php",
            {
              content: this.msgContent,
              conv_id: this.id,
              user_id: this.target.id,
            },
            { header: { "Content-Type": "application/json" } }
          )
          .then((result) => {
            console.log(result);
            this.msgContent = "";
            this.loadMessages();
            this.scrollToBottom();
          })
          .catch((error) => console.log(error));
      }
    },

    // Fonction pour faire défiler l'écran jusqu'au bas des messages
    scrollToBottom() {
      this.$nextTick(() => {
        const container = this.$el.querySelector(".messages-container");
        container.scrollTop = container.scrollHeight;
      });
    },
  },
};
</script>

<style scoped>
.messages-container {
  overflow-y: auto;
  padding: 10px;
  margin-bottom: 60%;
  margin-top: 0%;
}

.message {
  margin-bottom: 10px;
}

.message-input-container {
  margin-top: 20px;
  padding: 10px;
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  top: 1;
  background-color: white;
  width: 100%;
  z-index: 1;
}

.message-textarea {
  width: 100%;
  max-width: 400px;
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
