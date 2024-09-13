<template>
  <v-app>
    <!-- Barre de navigation Vuetify -->
    <v-app-bar app>
      <v-toolbar-title>Vue App</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-btn text>
        <router-link to="/">Home</router-link>
      </v-btn>
      <v-btn text>
        <router-link to="/about">About</router-link>
      </v-btn>
      <v-btn text>
        <router-link to="/users">Users</router-link>
      </v-btn>
    </v-app-bar>

    <v-main>
      <router-view></router-view>
    </v-main>
  </v-app>
</template>

<script>
export default {
  name: 'App'
}
</script>

<style>

 
</style>
