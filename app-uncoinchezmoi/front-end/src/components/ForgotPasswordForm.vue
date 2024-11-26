<template>
  <v-container>
    <div class="wrapper">
      <h3 class="headline mt-5 mb-5 title">Mot de passe oublié</h3>
      <v-form ref="form" @submit.prevent="handlePasswordReset">
        <v-text-field v-model="mail" label="Adresse e-mail" type="email" :rules="[rules.required]" rounded="pill"
          variant="solo-filled" append-inner-icon="mdi-email" @input="validate()"></v-text-field>

        <v-btn class="w-100 rounded-pill mb-5" color="#8DA399" @click="handleSendCode" :disabled="disabled">
          {{ sendCodeLabel }}
        </v-btn>

        <v-expansion-panels class="my-5" v-model="panel" flat>
          <v-expansion-panel value="otpShow">
            <v-expansion-panel-text>
              <v-otp-input v-model="userCode" :error="otpError" @input="validateOtp()" :rules="[rules.codeMatch]">
              </v-otp-input>
            </v-expansion-panel-text>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-form>

      <v-form v-if="codeSent" @submit.prevent="handlePasswordReset">
        <v-text-field v-model="newPassword" label="Nouveau mot de passe" type="password" rounded="pill"
          variant="solo-filled" append-inner-icon="mdi-lock-reset" :rules="[rules.required]"></v-text-field>

        <v-text-field v-model="confirmPassword" label="Confirmer le mot de passe" type="password" rounded="pill"
          variant="solo-filled" append-inner-icon="mdi-lock-check"
          :rules="[rules.required, rules.passwordsMatch]"></v-text-field>

        <v-btn class="w-100 rounded-pill mb-5" color="#8DA399" @click="handlePasswordReset"> Réinitialiser le mot de
          passe </v-btn>
      </v-form>
    </div>
  </v-container>
</template>

<script>
import axios from "axios";

export default {
  name: "ForgotPasswordForm",

  data() {
    return {
      users: [],
      generatedCode: Math.floor(100000 + Math.random() * 900000),

      rules: {
        required: value => !!value || 'Ce champ est requis',
        email: value => (!value || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(value)) || "L'adresse mail est invalide.",
        emailExists: value => !this.users.includes(value) || 'Cette adresse est déjà utilisée',
        passwordsMatch: value => value === this.newPassword || 'Les mots de passe ne correspondent pas',
        codeMatch: value => value === generatedCode || 'Code de vérification incorrect'
      },

      sendCodeLabel: "Envoyer le code de vérification",
      interval: {},
      mail: "",
      userCode: "",
      newPassword: "",
      confirmPassword: "",
      codeSent: false,
      panel: "",
      timer: 60,
      otpError: false,
      disabled: true,
    };
  },

  mounted() {
		this.getAllUsers()
	},

  methods: {
    getAllUsers() {
      const apiUrl = import.meta.env.VITE_API_URL;
      axios.get(apiUrl + '/services/userList.php')
        .then(response => {
          this.users = response.data;
        })
        .catch(error => {
          console.error('Erreur lors de la récupération des utilisateurs:', error);
        });
    },

    validate() {
      if (/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.mail)) {
        this.disabled = false;
      } else {
        this.disabled = true;
      }
    },

    validateOtp() {
      
      if (this.userCode.length == 6) {
        if (this.userCode == this.generatedCode) {
          // console.log("OTP valide : ", this.userCode, this.generatedCode);
          this.otpError = false;
        } else {
          // console.log("OTP invalide : ", this.userCode, this.generatedCode);
          this.otpError = true;
        }
      }
    },

    startChrono() {
      //console.log("startChrono");
      this.disabled = true;
      let wait = this.timer;
      this.interval = setInterval(() => {
        if (wait == 0) {
          clearInterval(this.interval);
          setTimeout(() => {
            this.sendCodeLabel = "Renvoyer Code";
            this.disabled = false;
          }, 100);
        } else {
          this.sendCodeLabel = wait;
          wait--;
        }
      }, 1000);
    },

    handleSendCode() {
      if (!this.mail) {
        return;
      }

      this.panel = "otpShow";

      this.startChrono();

      //console.log("Envoyer le code de vérification par email:", this.generatedCode);

      emailjs.init({
        publicKey: "gH39qa5yQWVo_b1pQ",
      });

      const templateParams = {
        to_email: this.mail,
        to_name: this.mail,
        message: this.generatedCode,
      };

      try {
        emailjs.send("service_bkoff5o", "template_u5sv70c", templateParams);
        console.log("Email envoyé !");
        this.codeSent = true;
      } catch (error) {
        console.error("Erreur d'envoi de l'email:", error);
        alert("Erreur lors de l'envoi de l'email. Veuillez réessayer.");
      }
    },

    handlePasswordReset() {

      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(
          apiUrl + "/services/forgotPassword.php",
          {
            mail: this.mail,
            password: this.newPassword,
          },
          {
            headers: {
              "Content-Type": "application/json",
            },
          }
        )
        .then((result) => {
          if (result.status === 200) {
            if (result.data["success"]) {
              this.$router.push("/login");
            }
          }
        })
        .catch((error) => console.error(error));
    },
  },
};
</script>

<style scoped></style>
