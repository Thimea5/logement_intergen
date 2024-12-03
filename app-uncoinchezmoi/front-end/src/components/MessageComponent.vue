<template>
  <v-main>
    <v-container>
      <v-app-bar :elevation="0">
        <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
        <v-app-bar-title>Messages</v-app-bar-title>
      </v-app-bar>

      <div class="d-flex flex-column justify-center">
        <v-text-area class="m-2" v-model="msgContent"></v-text-area>
        <v-btn @click="sendMessage()">Envoyer</v-btn>
      </div>
    </v-container>
  </v-main>
</template>

<script>
import axios from "axios";
import { useRoute } from "vue-router";
import { useConversationStore } from "../stores/ConversationStore";

export default {
  data() {
    return {
      msgContent: "",
      loading: true,
      messages: [],
      newMessage: "",
      messages: [],
      userTarget: {},
    };
  },
  mounted() {
    console.log("mounted msg");
    const cs = useConversationStore();
    const route = useRoute();
    const convId = route.params.id;
    //console.log(convId);
    //console.log(cs.conversations);
    let lct = cs.conversations.find((c) => c.id == convId);
    console.log(lct);
    console.log(cs.messages);
    //console.log(test);
  },
  methods: {
    goBack() {
      this.$router.go(-1);
    },
    sendMessage() {
      console.log("test");
    },
  },
};
</script>
