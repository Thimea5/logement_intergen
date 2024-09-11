
<template>
  <div id="app">
    <input type="text" v-model="message" placeholder="Enter a message">
    <button @click="sendMessage">Send Message</button>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      message: ''
    };
  },
  methods: {
    async sendMessage() {
      try {
        const response = await axios.post('http://localhost:5173/backend/api.php', {
          message: this.message
        });

        console.log(this.message);
        console.log(response.data);
      } catch (error) {
        console.error(error);
      }
    }
  }
};
</script>