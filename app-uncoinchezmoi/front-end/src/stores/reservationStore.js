import { defineStore } from "pinia";
import axios from "axios";

/* dans ce store, on stocke seulement les conversations de l'utilisateur ainsi que tout les messages associés du coup */
export const useReservationStore = defineStore("reservation", {
  state: () => ({
    reservations: null,
    reservationsUsers: null,
    isLoaded: false,
  }),

  actions: {
    load(pId) {
      if (!this.isLoaded) {
        const apiUrl = import.meta.env.VITE_API_URL;
        axios
          .get(apiUrl + "/services/reservationManager.php", {
            params: {
              id: pId,
            },
            headers: { "Content-Type": "application/json" },
          })
          .then((result) => {
            if (result.status === 200 && result.data["success"]) {
              this.reservations = result.data["reservations"];
              this.reservationsUsers = result.data["reservationsUsers"];
              this.isLoaded = true;
            }
            console.log(this.reservations);
            console.log(this.reservationsUsers);
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
  },
});
