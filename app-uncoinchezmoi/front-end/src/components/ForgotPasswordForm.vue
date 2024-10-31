<template>
    <!--Première version brouillon en attendant la maj du code de Loïc et en attendant un template figma ?-->
    <v-main>
    <v-container>
        <v-row>
            <v-col cols="12" sm="8" md="6" lg="4">
                <v-card outlined>
                    <v-card-title class="text-center">
                        Mot de passe oublié
                    </v-card-title>

                    <v-card-text>
                        <v-form ref="form" @submit.prevent="handleSendCode">
                            <v-text-field
                                v-model="mail"
                                label="Adresse e-mail"
                                type="email"
                                required
                                outlined
                                prepend-icon="mdi-email"
                            ></v-text-field>

                            <v-btn
                                color="primary"
                                class="mb-4"
                                block
                                @click="handleSendCode"
                            >
                                Envoyer le code de vérification
                            </v-btn>
                        </v-form>

                        <v-form v-if="codeSent" @submit.prevent="handlePasswordReset">
                            <v-text-field
                                v-model="userCode"
                                label="Code de vérification"
                                type="text"
                                required
                                outlined
                                prepend-icon="mdi-shield-key"
                            ></v-text-field>

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

                            <v-btn
                                color="success"
                                class="mt-4"
                                block
                                @click="handlePasswordReset"
                            >
                                Réinitialiser le mot de passe
                            </v-btn>
                        </v-form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
    </v-main>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ForgotPasswordForm',
    data() {
        return {
            mail: '',
            userCode: '',
            newPassword: '',
            confirmPassword: '',
            codeSent: false,
            generatedCode: Math.floor(100000 + Math.random() * 900000),
        };
    },
    methods: {
        handleSendCode() {
            // recyclage à voir ?
            if (!this.mail) {
                alert("Veuillez renseigner une adresse mail");
                return;
            }

            console.log("Envoyer le code de vérification par email:", this.generatedCode);

            emailjs.init({
                publicKey: 'gH39qa5yQWVo_b1pQ',
            });

            const templateParams = {
                to_email: this.mail,
                to_name: this.mail,
                message: this.generatedCode,
            };

            try {
                emailjs.send('service_bkoff5o', 'template_u5sv70c', templateParams);
                console.log("Email envoyé !");
                this.codeSent = true;
            } catch (error) {
                console.error("Erreur d'envoi de l'email:", error);
                alert("Erreur lors de l'envoi de l'email. Veuillez réessayer.");
            }
        },

        handlePasswordReset() {
            if (this.userCode != this.generatedCode) {
                alert("Code de vérification incorrect.");
                return;
            }

            if (this.newPassword !== this.confirmPassword) {
                alert("Les mots de passe ne correspondent pas.");
                return;
            }
            
            const apiUrl = import.meta.env.VITE_API_URL;
            axios.post(apiUrl+'/services/forgotPassword.php', {
                mail: this.mail,
                password: this.newPassword
            }, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then(result => {
                if (result.status === 200) {
                    if (result.data['success']) {
                        this.$router.push('/login');
                    }
                } 
            }).catch(error => console.error(error));
        },
    },
};
</script>

<style scoped>

</style>
