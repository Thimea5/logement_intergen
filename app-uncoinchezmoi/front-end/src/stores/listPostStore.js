import { defineStore } from "pinia";
import axios from "axios";

export const useListPostStore = defineStore("listPost", {
  state: () => ({
    listPost: [],
    listHost: [],
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
              const res = result.data["posts"];
              const resServices = result.data["services"];
              for (let i = 0; i < res.length; i++) {
                this.listPost.push({
                  id: res[i]["id"],
                  available: res[i]["available"],
                });

                this.listHost.push({
                  idHost: res[i]["id_host"],
                  address: res[i]["address"],
                  city: res[i]["city"],
                  postalCode: res[i]["postal_code"],
                  latitude: res[i]["lat"],
                  longitude: res[i]["lng"],
                  type_logement: res[i]["type_logement"],
                  price: res[i]["price"],
                  size: res[i]["size"],
                  img: res[i]["cheminPhoto"],
                  nbPhoto: res[i]["nbPhoto"],
                });

                this.listServices.push({
                  idHost: resServices[i][0]["serv_idHost"],
                  isCleaning: resServices[i][0]["serv_cleaning"],
                  isCarSharing: resServices[i][0]["serv_carsharing"],
                  isCooking: resServices[i][0]["serv_cooking"],
                  isDiy: resServices[i][0]["serv_diy"],
                  isErrand: resServices[i][0]["serv_errand"],
                  isGardening: resServices[i][0]["serv_gardening"],
                  isPetsSitting: resServices[i][0]["serv_petsSitting"],
                  isRepairs: resServices[i][0]["serv_repairs"],
                  isTalking: resServices[i][0]["serv_talking"],
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
