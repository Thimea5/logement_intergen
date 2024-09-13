<template>
  <v-container>
    <h1>User List</h1>
    <v-list>
      <v-list-item v-for="user in users" :key="user.id">
        <v-list-item-content>
          {{ user.id }} - {{ user.firstname }} {{ user.lastname }} {{ user.username }} {{ user.email }}
          <br />
        </v-list-item-content>
      </v-list-item>
    </v-list>
  </v-container>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      users: []
    };
  },
  mounted() {
    this.getUsers();
  },
  methods: {
    getUsers() {
      axios.get('/api/users-test.php')
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error("There was an error fetching the users:", error);
        });
    }
  }
}
</script>

<style scoped>
/* Styles spécifiques si nécessaire */
</style>
