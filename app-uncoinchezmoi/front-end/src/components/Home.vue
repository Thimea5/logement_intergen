<template>
  <v-main>
    <v-container class="py-4" fluid>
      <v-card class="welcome-card" outlined>
        <v-card-text class="text-center">
          <h1>Un Coin Chez Moi</h1>
          <p>Bienvenue sur l'application</p>
        </v-card-text>
      </v-card>

      <v-card v-if="isLoggedIn" class="welcome-card" outlined>
        <h4>Réparation en cours</h4>
        <br />
        <v-btn @click="logOut()" color="primary">Déconnexion</v-btn> 
      </v-card>
    </v-container>
  </v-main>

  <!-- TODO: Bug overflow en y ? -->  
</template>

<script>
  export default {
    name: 'Home',

    data() {
      return {
        isLoggedIn: sessionStorage.getItem('user') != null
      };
    },

    mounted() {
      //console.log("CHECK UTILISATEUR : " + sessionStorage.getItem('user'));
      //console.log(this.isLoggedIn); 
    },

    methods: {
      logOut() {
        sessionStorage.clear();

        window.location.replace("/");
        //this.$router.push("/");
      }
    }

  }
</script>

<style scoped>
  .welcome-card {
    padding: 20px;
    margin: 0 auto;
    max-width: 400px;
  }

  .feature-card {
    text-align: center;
  }
</style>