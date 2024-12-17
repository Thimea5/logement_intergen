<template>
  <v-app-bar :elevation="0">
    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
  </v-app-bar>

  <v-container class="d-flex align-center justify-center full-height">
    <div class="login-wrapper">
      <h1 class="headline mt-5 mb-5 title">BON RETOUR CHEZ TOIT</h1>

      <form id="form-login" @submit.prevent="handleLogin" class="d-flex flex-column justify-space-between">
        <div>
          <label for="email" class="custom-label">Email</label>
          <v-text-field
            class="mb-3"
            id="email"
            v-model="email"
            type="mail"
            placeholder="Adresse mail"
            variant="solo-filled"
            :rules="emailRules"
            clearable
            rounded
            required
          ></v-text-field>

          <label for="password" class="custom-label">Mot de passe</label>
          <v-text-field
            id="password"
            v-model="password"
            :type="pwdIsVisible ? 'text' : 'password'"
            placeholder="Entrez votre mot de passe"
            :append-inner-icon="pwdIsVisible ? 'mdi-eye' : 'mdi-eye-off'"
            variant="solo-filled"
            rounded
            required
            @click:append-inner="pwdIsVisible = !pwdIsVisible"
            hide-details
          ></v-text-field>

          <div class="mt-1 mb-5 text-right">
            <router-link to="/forgot-password" class="link">Mot de passe oublié ?</router-link>
          </div>

          <div class="error-message text-center mt-1 mb-5">
            <p v-show="errorMessage && errorMessage.length > 0">{{ errorMessage }}</p>
          </div>
        </div>

        <div class="text-center mt-5">
          <p class="link">Pas de compte ? <router-link to="/register" class="link">S'inscrire</router-link></p>
          <v-btn type="submit" rounded="xl" variant="flat" block class="login-button" :disabled="!isFormValid">
            Connexion
          </v-btn>
        </div>
      </form>
    </div>
  </v-container>
</template>

<script>
import axios from "axios";
import { useUserStore } from "../stores/userStore";
import * as jwt from "jwt-decode";

export default {
  name: "Login",

  data() {
    return {
      email: "",
      emailRules: [
        (value) =>
          !value || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(value) || "L'adresse mail est invalide.",
      ],
      password: "",
      pwdIsVisible: false,
      errorMessage: "",
    };
  },

  computed: {
    isFormValid() {
      return this.emailRules.every((rule) => rule(this.email) === true) && this.password.length !== 0;
    },
  },

  setup() {
    const userStore = useUserStore();
    return { userStore };
  },

  methods: {
    goBack() {
      this.$router.go(-1);
    },
    handleLogin() {
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(
          apiUrl + "/services/login.php",
          {
            email: this.email,
            password: this.password,
          },
          {
            headers: { "Content-Type": "application/json" },
          }
        )
        .then((result) => {
          //console.log(result);
          if (result.status === 200) {
            if (result.data["success"]) {
              const token = result.data["token"];

              sessionStorage.setItem("token", token);

              const decodedToken = jwt.jwtDecode(token);
              const expirationTime = decodedToken.exp; // en secondes
              sessionStorage.setItem("tokenExpiration", expirationTime); // Pas obligatoire, mais pourquoi pas...

              const user = result.data["user-info"];
              const serv = result.data["user-services"];

              this.userStore.setUser(user);
              this.userStore.setServices(serv);

              // Redirection vers la page d'accueil
              this.$router.push("/");
            } else {
              this.errorMessage = result.data["message"];
            }
          }
        })
        .catch((error) => {
          console.error(error);
          this.errorMessage = "Une erreur interne a eu lieu";
        });
    },
  },
};
</script>

<style scoped>
.full-height {
  min-height: 100vh;
}

.login-wrapper {
  width: 100%;
  max-width: 400px;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.title {
  font-weight: bold;
  font-size: 32px;
  text-align: center;
}

.link {
  font-size: 0.875rem;
  color: #6c757d;
  text-decoration: none;
}

.link:hover {
  text-decoration: none;
  color: #0a4889;
}

.custom-label {
  display: block;
  margin-bottom: 4px;
}

.error-message {
  color: red;
  min-height: 25px;
}

.login-button {
  height: 56px;
  background-color: var(--green-color);
  border: 1px solid var(--dark-green-color);
  text-transform: none;
  color: white;
  font-size: 18pt;
}

.login-button:disabled {
  height: 56px;
  background-color: var(--green-disabled-color);
  border: none;
  text-transform: none;
  color: white;
  font-size: 18pt;
}
</style>
