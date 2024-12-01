import { defineStore } from "pinia";
import axios from "axios";

export const useListPostStore = defineStore("listPost", {
  state: () => ({
    listPost: [],
    listServices: [],
    isLoaded: false,
  }),

  actions: {
    loadPosts() {
      if (!this.isLoaded) {
        const apiUrl = import.meta.env.VITE_API_URL;
        axios
          .get(apiUrl + "/services/postsManager.php", {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then((result) => {
            if (result.status === 200 && result.data["success"]) {
              const resPosts = result.data["posts"];
              const resServices = result.data["services"];
              console.log("resPosts", resPosts);
              console.log("resServices", resServices);
              for (let i = 0; i < resPosts.length; i++) {
                this.listPost.push({
                  idPost: resPosts[i]["id"],
                  address: resPosts[i]["address"],
                  city: resPosts[i]["city"],
                  postalCode: resPosts[i]["postal_code"],
                  latitude: resPosts[i]["lat"],
                  longitude: resPosts[i]["lng"],
                  type_logement: resPosts[i]["type_logement"],
                  price: resPosts[i]["price"],
                  size: resPosts[i]["size"],
                  img: resPosts[i]["cheminPhoto"],
                  nbPhoto: resPosts[i]["nb_photo"],
                  idUser: resPosts[i]["id_user"],
                  available: resPosts[i]["available"],
                });

                this.listServices.push({
                  id: resServices[i][0]["id"],
                  idUser: resServices[i][0]["id_user"],
                  isCleaning: resServices[i][0]["cleaning"],
                  isCarSharing: resServices[i][0]["carSharing"],
                  isCooking: resServices[i][0]["cooking"],
                  isDiy: resServices[i][0]["diy"],
                  isErrand: resServices[i][0]["errand"],
                  isGardening: resServices[i][0]["gardening"],
                  isPetsSitting: resServices[i][0]["petSitting"],
                  isTalking: resServices[i][0]["chatting"],
                });
              }

              this.isLoaded = true;
            }
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
});
