<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
      <v-app-title class="headline mt-5 mb-5">Conversation</v-app-title>
    </v-app-bar>

    <v-container class="d-flex align-center justify-center full-height">
      <v-list>
        <v-list-item v-for="(conversation, index) in conversations">
          <v-list-item-avatar>
            <v-img :src="conversation.image" alt="Profile Picture"></v-img>
          </v-list-item-avatar>

          <v-list-item-content>
            <v-list-item-title class="headline">
              {{ conversation.name }}
            </v-list-item-title>
            <v-list-item-subtitle>
              {{ conversation.lastMessage }}
            </v-list-item-subtitle>
          </v-list-item-content>
        </v-list-item>
      </v-list>
    </v-container>
  </v-main>
</template>

<script>
import { useConversationStore } from "../stores/ConversationStore";

export default {
  data() {
    return {
      userId: JSON.parse(sessionStorage.getItem("user")).id,
      conversations: null,
      messages: [],
    };
  },

  mounted() {
    console.log("mounted inside conv");
    const cs = useConversationStore();
    if (!cs.isLoaded) {
      cs.load(this.userId);
    }
  },

  methods: {
    goBack() {
      this.$router.go(-1);
    },

    showMessages() {
      this.$router.push("/TODO_ICI");
    },
  },
};
</script>
