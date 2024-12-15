<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
      <v-app-bar-title>Messagerie</v-app-bar-title>
    </v-app-bar>

    <v-container>
      <p style="display: inline-block; border-bottom: 2px solid black; padding-bottom: 4px">Messages</p>

      <v-list v-for="(elt, index) in conversations" :key="index" class="m-auto">
        <v-list-item
          class="m-1"
          @click="showConversation(getUserTarget(elt.id_user1, elt.id_user2)?.id)"
          style="border-bottom: 1px solid lightgray"
        >
          <div style="display: flex; flex-direction: row; align-items: center">
            <div class="ms-1 me-2">
              <v-img
                aspect-ratio="1"
                rounded="pill"
                :src="getImageSrc(getUserTarget(elt.id_user1, elt.id_user2)?.id)"
                width="50"
                cover
              ></v-img>
            </div>
            <div class="mt-2 mb-1 m-0">
              <p class="m-0 mb-1">
                <strong
                  >{{ getUserTarget(elt.id_user1, elt.id_user2)?.firstname }}
                  {{ getUserTarget(elt.id_user1, elt.id_user2)?.lastname }}</strong
                >
                <br />
                {{ this.displayLastMessage(index) }}
              </p>
            </div>
          </div>
        </v-list-item>
      </v-list>
    </v-container>
  </v-main>
</template>

<script>
import axios from "axios";
import { useConversationStore } from "../stores/ConversationStore";

export default {
  data() {
    return {
      loading: true,
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      conversations: [],
      usersTarget: [],
      messages: [],
    };
  },
  async mounted() {
    const cs = useConversationStore();

    cs.isLoaded1 = false;
    cs.load(this.user.id);
    await this.waitUntil(() => cs.isLoaded1);

    this.conversations = cs.conversations;
    this.usersTarget = cs.convUsersInfo;
    this.messages = cs.messages;
  },

  methods: {
    displayLastMessage(index) {
      let lastMsg =
        this.messages[index].length == 0
          ? "Aucun message"
          : this.messages[index][this.messages[index].length - 1].content;

      return lastMsg.length > 30 ? lastMsg.substring(0, 30) + "..." : lastMsg;
    },

    waitUntil(conditionFn, interval = 10) {
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

    goBack() {
      this.$router.go(-1);
    },

    showConversation(pId) {
      // en paramètre, c'est l'id du destinataire.
      this.$router.push({ name: "MessageComponent", params: { id: pId } });
    },

    getUserTarget(idUser1, idUser2) {
      return this.usersTarget.find((user) => user.id === idUser2 || user.id === idUser1);
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
