<template>
  <v-main>
    <v-container>
      <v-card>
        <v-card-title class="headline">Recherche [TEST]</v-card-title>
        <v-card-text>
          <p>Recherche par ville, quartier pour l'instant</p>
          <v-text-field 
            v-model="search" 
            @input="filterPost" 
            label="Search" 
            prepend-inner-icon="mdi-magnify" 
            variant="outlined" 
            hide-details 
            single-line>
          </v-text-field>
        </v-card-text>

        <v-card-text>
          <div v-if="loading">Chargement des annonces...</div>
          <div v-else-if="cardList.length === 0">Aucune annonce trouvée</div>
          <div v-else>
            <v-row>
              <v-col v-for="listing in cardList" :key="listing.id" cols="12" sm="6" md="4">
                <v-card>
                  <v-card-title>{{ listing.address }}</v-card-title>
                  <v-card-subtitle>{{ listing.city }} - {{ listing.postalCode }}</v-card-subtitle>
                  <v-card-text>
                    <p>Disponibilité: {{ listing.available }}</p>
                  </v-card-text>
                </v-card>
              </v-col>
            </v-row>
          </div>
        </v-card-text>
      </v-card>
    </v-container>
  </v-main>
</template>

<script>
import axios from 'axios';

export default {
  name: 'HomeSearch',

  data() {
    return {
      listHost: [],
      listPost: [],
      search: '', 
      loading: false,
      cardList: []
    };
  },

  setup() {
  },

  async mounted() {
    await this.loadPosts();

    let lh = this.listHost;
    let lp = this.listPost;

    this.cardList = []; // Réinitialiser cardList

    for (let i = 0; i < lh.length; i++) {
      this.cardList.push({
        id: lp[i]?.id, // Utilisation de l'opérateur de coalescence nulle pour éviter les erreurs
        address: lh[i]?.address,
        city: lh[i]?.city, 
        postalCode: lh[i]?.postalCode,
        available: lp[i]?.available
      });
    }
  },

  methods: {
    async loadPosts() {
      const apiUrl = import.meta.env.VITE_API_URL;
			console.log(apiUrl);
      await axios.get(apiUrl+'/services/postsManager.php', {
        headers: {
          'Content-Type': 'application/json'
        }
      }).then(result => {
        if (result.status === 200 && result.data["success"]) {
          const res = result.data["posts"];
          
          this.listPost = [];
          this.listHost = [];
          for (let i = 0; i < res.length; i++) {
            this.listPost.push({
              id: res[i]["id"],
              available: res[i]["available"]
            });

            this.listHost.push({
              idHost: res[i]["id_host"],
              address: res[i]["address"],
              city: res[i]["city"],
              postalCode: res[i]["postal_code"]
            });
          }
        }
        
      }).catch(error => {
        console.log(error);
      });
    },

    filterPost() {
      this.loading = true;

      this.cardList = this.listPost.map((listing, index) => {
        return {
          id: listing.id,
          address: this.listHost[index]?.address,
          city: this.listHost[index]?.city,
          postalCode: this.listHost[index]?.postalCode,
          available: listing.available
        };
      }).filter((item) => {
        return (
          item.address.toLowerCase().includes(this.search.toLowerCase()) ||
          item.city.toLowerCase().includes(this.search.toLowerCase()) ||
          item.postalCode.toLowerCase().includes(this.search.toLowerCase())
        );
      });

      this.loading = false; 
    }
  }
};
</script>
