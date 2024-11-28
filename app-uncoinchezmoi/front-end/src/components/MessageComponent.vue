<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container class="d-flex align-center justify-center full-height">
      <h1 class="headline mt-5 mb-5">Conversation</h1>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      userId: JSON.parse(sessionStorage.getItem("user")).id,
    };
  },

  mounted() {},

  methods: {
    goBack() {
      this.$router.go(-1);
    },
  },
};
</script>
