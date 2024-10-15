import { defineStore } from 'pinia';

// Double persistence : en mémoire de l'application avec Pinia et SessionStorage
export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
  }),

  actions: {
    setUser(userData) {
      this.user = userData;
      sessionStorage.setItem('user', JSON.stringify(userData)); 
    },

    loadUserFromSession() {
      const storedUser = sessionStorage.getItem('user');
      if (storedUser) {
        this.user = JSON.parse(storedUser);
      } else {
        this.user = null;
      }
    },

    clearUser() {
      this.user = null;
      sessionStorage.removeItem('user');
    },
  },

  getters: {
    isAuthenticated: (state) => !!state.user,
  },
});
