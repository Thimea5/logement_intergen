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
      //console.log("this.loadPosts");
      if (!this.isLoaded) {
        const apiUrl = import.meta.env.VITE_API_URL;
        axios
          .get(apiUrl + "/services/postsManager.php", {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then((result) => {
            console.log(result);
            if (result.status === 200 && result.data["success"]) {
              const resPosts = result.data["posts"];
              const resServices = result.data["services"];
              //console.log(resPosts);
              //console.log(resServices);
              for (let i = 0; i < resPosts.length; i++) {
                //console.log(resPosts[i]["id"]);
                this.listPost.push({
                  idPost: resPosts[i]["post_id"],
                  address: resPosts[i]["address"],
                  city: resPosts[i]["city"],
                  postalCode: resPosts[i]["postal_code"],
                  latitude: resPosts[i]["lat"],
                  longitude: resPosts[i]["lng"],
                  type_logement: resPosts[i]["type_logement"],
                  description: resPosts[i]["description"],
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
                  isCarSharing: resServices[i][0]["carsharing"],
                  isCooking: resServices[i][0]["cooking"],
                  isDiy: resServices[i][0]["diy"],
                  isErrand: resServices[i][0]["errand"],
                  isGardening: resServices[i][0]["gardening"],
                  isPetsSitting: resServices[i][0]["petSitting"],
                  isTalking: resServices[i][0]["chatting"],
                });
              }
            }

            console.log("resPosts", this.listPost);
            console.log("resServices", this.listServices);
            this.isLoaded = true;
            //console.log("fini");
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
  },
});
