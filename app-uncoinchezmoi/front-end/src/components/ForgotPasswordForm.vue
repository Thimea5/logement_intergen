<template>
  <v-container>
    <div class="wrapper">
      <h3 class="headline mt-5 mb-5 title">Mot de passe oublié</h3>
      <v-form ref="form" @submit.prevent="handlePasswordReset">
        <v-text-field
          v-model="mail"
          label="Adresse e-mail"
          type="email"
          required
          outlined
          prepend-icon="mdi-email"
          @input="validate()"
        ></v-text-field>

        <v-btn color="primary" class="mb-4" block @click="handleSendCode" :disabled="disabled">
          {{ sendCodeLabel }}
        </v-btn>

        <v-expansion-panels class="my-5" v-model="panel" flat>
          <v-expansion-panel value="otpShow">
            <v-expansion-panel-text>
              <v-otp-input v-model="userCode" :error="otpError" @input="validateOtp()"> </v-otp-input>
            </v-expansion-panel-text>
          </v-expansion-panel>
        </v-expansion-panels>
      </v-form>

      <v-form v-if="codeSent" @submit.prevent="handlePasswordReset">
        <v-text-field
          v-model="newPassword"
          label="Nouveau mot de passe"
          type="password"
          required
          outlined
          prepend-icon="mdi-lock-reset"
        ></v-text-field>

        <v-text-field
          v-model="confirmPassword"
          label="Confirmer le mot de passe"
          type="password"
          required
          outlined
          prepend-icon="mdi-lock-check"
        ></v-text-field>

        <v-btn color="success" class="mt-4" block @click="handlePasswordReset"> Réinitialiser le mot de passe </v-btn>
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
      sendCodeLabel: "Envoyer le code de vérification",
      interval: {},
      mail: "",
      userCode: "",
      newPassword: "",
      confirmPassword: "",
      codeSent: false,
      generatedCode: Math.floor(100000 + Math.random() * 900000),
      panel: "",
      timer: 60,
      otpError: false,
      disabled: true,
    };
  },

  methods: {
    validate() {
      //console.log("validate");
      if (/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.mail)) {
        this.disabled = false;
      } else {
        this.disabled = true;
      }
    },

    validateOtp() {
      //console.log("validateOtp");
      if (this.userCode.length == 6) {
        if (this.userCode == this.generatedCode) {
          console.log("OTP valide : ", this.userCode, this.generatedCode);
          this.otpError = false;
        } else {
          console.log("OTP invalide : ", this.userCode, this.generatedCode);
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
        alert("Veuillez renseigner une adresse mail");
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
      //console.log("handlePAsswordReset");
      if (this.userCode != this.generatedCode) {
        alert("Code de vérification incorrect.");
        return;
      }

      if (this.newPassword !== this.confirmPassword) {
        alert("Les mots de passe ne correspondent pas.");
        return;
      }

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
