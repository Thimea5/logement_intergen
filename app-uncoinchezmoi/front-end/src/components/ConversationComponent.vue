<template>
  <v-main>
    <v-container>
      <v-app-bar :elevation="0">
        <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
        <v-app-bar-title>Mes conversations</v-app-bar-title>
      </v-app-bar>

      <!-- Parcours des conversations et affichage de l'utilisateur cible correspondant -->
      <v-list v-for="(elt, index) in conversations" :key="index" class="m-auto">
        <v-list-item class="bg-blue-grey-lighten-4" @click="showConversation(elt.id)">
          <p><strong>Conversation ID:</strong> {{ elt.id }}</p>
          <p><strong>Date de création:</strong> {{ elt.creation_date }}</p>
          <p>
            <strong>Utilisateur cible:</strong> {{ getUserTarget(elt.id_user1, elt.id_user2)?.firstname }}
            {{ getUserTarget(elt.id_user1, elt.id_user2)?.lastname }}
          </p>
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
  mounted() {
    const cs = useConversationStore();
    this.conversations = cs.conversations;
    this.usersTarget = cs.convUsersInfo;
    this.messages = cs.messages;
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    showConversation(pId) {
      this.$router.push({ name: "MessageComponent", params: { id: pId } });
    },

    getUserTarget(idUser1, idUser2) {
      return this.usersTarget.find((user) => user.id === idUser2 || user.id === idUser1);
    },
  },
};
</script>
