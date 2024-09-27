<template>
    <div class="container d-flex align-items-center">
        <div class="form-container m-auto">
            <form @submit.prevent="handleRegister" class="d-flex flex-column">
                <h2 class="mb-5">Inscription</h2>
                <input type="email" v-model="mail" placeholder="Email" class="input-field" required>

                <!--<button class="btn btn-primary w-50 mb-5 ml-auto" type="button" @click="sendCode">Envoyer code</button>-->

                <div class="my-5">
                    <input type="text" v-model="firstname" placeholder="Prénom" class="input-field" required>
                    <input type="text" v-model="lastname" placeholder="Nom" class="input-field" required>
                    <input type="date" v-model="birthdate" class="w-50 input-field">
                </div>

                <input type="password" v-model="password" placeholder="Mot de passe" class="input-field" required>
                <input type="password" v-model="passwordConf" placeholder="Confirmation" class="input-field" required>

                <button class="btn btn-primary mb-3">S'inscrire</button>

                <p>Déjà inscrit ? <a href="./login">Se connecter</a></p>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import "../assets/connect.css";

export default {
    name: 'Register',

    data() {
        return {
            mail: '',
            firstname: '',
            lastname: '',
            birthdate: '',
            password: '',
            passwordConf: ''
        }
    },

    methods: {
        async handleRegister() {
            try {
                // Vérification confirmation mot de passe
                if (this.password !== this.passwordConf) {
                    alert('Les mots de passe ne correspondent pas.');
                    return;
                }
                
                const response = await axios.post('/api/services/register.php', {
                    mail: this.mail,
                    firstname: this.firstname,
                    lastname: this.lastname,
                    birthdate: this.birthdate,
                    password: this.password
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });
                
                //console.log(response.data);
                if (response.data.success) {
                    alert('Inscription réussie !');
                    // TODO - Redirection
                } else {
                    alert(response.data.message || 'Échec de l\'inscription.');
                }
            } catch (error) {
                console.error('Erreur lors de l\'inscription:', error);
                alert('Une erreur est survenue, merci de réessayer.');
            }
        },

        async sendCode() {
            // TODO: Voir pour la sécurité lors de l'inscription, redirection vers une page avec un numéro, envoie du mail
            console.log("Envoyer le code de vérification par email");
        }
    }
}
</script>
