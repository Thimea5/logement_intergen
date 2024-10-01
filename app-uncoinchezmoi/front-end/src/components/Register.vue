<template>
    <div class="container d-flex align-items-center">
        <div class="form-container m-auto">
            <form @submit.prevent="handleRegister" class="d-flex flex-column">
                <h2 class="mb-5">Inscription</h2>
                <input type="email" v-model="mail" placeholder="Email" class="input-field" required>

                <button class="btn btn-primary w-50 ml-auto text-light" type="button" @click="sendCode">Envoyer code</button>

                <input class="input-field mt-2 w-25 ml-auto" v-model="code" type="text" name="code" id="code">

                <div class="my-3">
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
            code: '',
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

                let user = null;

                await axios.post('/api/services/register.php', {
                    mail: this.mail,
                    firstname: this.firstname,
                    lastname: this.lastname,
                    birthdate: this.birthdate,
                    password: this.password,
                    submit: true
                }, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then(result => {
                    console.log(result)
                    // Blindage à revoir 
                    if (result.status == 200) {
                        if (result.data["success"]) {
                            user = result.data["user-info"];

                            // Envoi des infos de l'utilisateur dans le SessionStorage
                            sessionStorage.setItem('user', JSON.stringify({
                                id: user.id,
                                firstname: user.firstname,
                                lastname: user.lastname,
                                email: user.email,
                                birthdate: user.birthdate,
                                type: user.type
                            }));

                            window.location.replace("./user-profile");
                        }
                    }
                }).catch(error => console.error(error))
            } catch (error) {
                console.error('Erreur lors de l\'inscription:', error);
                alert('Une erreur est survenue, merci de réessayer.');
            }
        },

        async sendCode() {
            return;
            if (this.mail == "") {
                alert("veuillez renseigner une adresse mail");
                return;
            }
            var code = Math.floor(1000 + Math.random() * 9000);
            // TODO: Voir pour la sécurité lors de l'inscription, redirection vers une page avec un numéro, envoie du mail
            console.log("Envoyer le code de vérification par email: "+code);

            const response = await axios.post("/api/services/register", {
                mail: this.mail,
                code: this.code,
                submit: false
            }, {
                headers: {
                    'Content-Type': 'application/json'
                }
            });

            console.log(response)

            if (response.data.success) {
                console.log('Inscription réussie !');
            } else {
                console.log(response.data.message || 'Échec de l\'envoie du code.');
            }
        }
    }
}
</script>
