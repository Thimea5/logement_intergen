<template>
  <v-app>
    <!-- Barre de navigation Vuetify -->
    <v-app-bar app class="d-flex bg-dark justify-content-between align-items-center">
      <v-toolbar-title class="pl-5 w-25">Vue App</v-toolbar-title>
      <v-toolbar-items class="d-flex justify-content-around w-25">
        <v-btn text>
          <router-link to="/">Home</router-link>
        </v-btn>
        <v-btn text>
          <router-link to="/about">About</router-link>
        </v-btn>
        <v-btn text>
          <router-link to="/users">Users</router-link>
        </v-btn>
      </v-toolbar-items>
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
