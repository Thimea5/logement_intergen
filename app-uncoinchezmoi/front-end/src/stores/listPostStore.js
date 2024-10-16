import { defineStore } from 'pinia';
import axios from 'axios';

// TODO : Question de la double persistence ? utilité de l'ajout dans le sessionStorage
export const useListPostStore = defineStore('listPost', {
  state: () => ({
    listPost: JSON.parse(sessionStorage.getItem('listPost')) || [], 
    listHost: JSON.parse(sessionStorage.getItem('listHost')) || [], 
    isLoaded: false,
  }),

  actions: {
    loadPosts() {
      if (!this.isLoaded) {
        axios.get('/api/services/postsManager.php', {
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

            sessionStorage.setItem('listPost', JSON.stringify(this.listPost));
            sessionStorage.setItem('listHost', JSON.stringify(this.listHost));

            this.isLoaded = true;
          }
        }).catch(error => {
          console.log(error);
        });
      }
    }
  }
});
