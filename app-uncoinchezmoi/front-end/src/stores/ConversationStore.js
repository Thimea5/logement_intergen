import { defineStore } from "pinia";
import axios from "axios";

/* dans ce store, on stocke seulement les conversations de l'utilisateur ainsi que tout les messages associés du coup */
export const useConversationStore = defineStore("conversation", {
  state: () => ({
    conversations: null,
    convUsersInfo: null,
    messages: null,
    isLoaded1: false,
  }),

  actions: {
    load(pId) {
      //console.log("loading inside store", pId);
      if (!this.isLoaded1) {
        const apiUrl = import.meta.env.VITE_API_URL;
        axios
          .get(apiUrl + "/services/conversationsManager.php", {
            params: {
              id: pId,
            },
            headers: { "Content-Type": "application/json" },
          })
          .then((result) => {
            //console.log(result);
            if (result.status === 200 && result.data["success"]) {
              const conv = result.data["conversations"];
              const msg = result.data["messages"];
              const usr = result.data["users"];
              //console.log(conv);
              //console.log(msg);
              //console.log(usr);
            }

            this.isLoaded1 = true;
          })
          .catch((error) => {
            console.error(error);
          });
      }
    },
  },
});
