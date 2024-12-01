import { defineStore } from "pinia";

// Double persistence : en mémoire de l'application avec Pinia et SessionStorage
export const useUserStore = defineStore("user", {
  state: () => ({
    user: null,
    service: null,
    preference: null,
  }),

  actions: {
    setPreference(preferenceData) {
      this.preference = preferenceData;
      sessionStorage.setItem("preference", JSON.stringify(preferenceData));
    },

    setUser(userData) {
      this.user = userData;
      sessionStorage.setItem("user", JSON.stringify(userData));
    },

    setServices(serviceData) {
      this.service = serviceData;
      sessionStorage.setItem("service", JSON.stringify(serviceData));
    },

    loadUserFromSession() {
      if (sessionStorage.length != 0) {
        this.user = JSON.parse(sessionStorage.getItem("user"));
        this.service = JSON.parse(sessionStorage.getItem("service"));
        //this.preference = JSON.parse(sessionStorage.getItem("preference"));
      } else {
        this.user = null;
        this.service = null;
        this.preference = null;
      }
    },

    clearUser() {
      this.user = null;
      this.service = null;
      this.preference = null;
      sessionStorage.clear();
    },
  },

  getters: {
    isAuthenticated: (state) => !!state.user,
  },
});
