<template>
  <v-main>
    <v-container>
      <v-card>
        <v-card-title class="headline">Recherche [TEST]</v-card-title>
        <v-card-text>
          <p>Recherche par ville, quartier pour l'instant</p>
          <v-text-field 
            v-model="search" label="Search" prepend-inner-icon="mdi-magnify" variant="outlined" hide-details single-line>
          </v-text-field>
        </v-card-text>
      </v-card>
    </v-container>
  </v-main>
</template>

<script>
  export default {
    name: 'HomeSearch',

    data() {
      return {
        search: '' 
      };
    }
  }
</script>
