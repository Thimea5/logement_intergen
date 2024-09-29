<template>
    <div class="container d-flex align-items-center">
        <div class="form-container m-auto">
            <form @submit.prevent="handleLogin" class="d-flex flex-column">
                <h2 class="mb-5">Connexion</h2>
                <input type="email" v-model="email" placeholder="Email" class="input-field" required>
                <input type="password" v-model="password" placeholder="Mot de passe" class="input-field" required>
                <button class="btn btn-primary mb-3" type="submit">Se connecter</button>
                <p>Vous n'avez pas de compte ? <a href="./register">S'inscrire</a></p>
            </form>
        </div>
    </div>
</template>

<script>
    import "../assets/connect.css";
    import axios from 'axios';

    export default {
        name: 'Login',

        data() {
            return {
                email: '',
                password: ''
            }
        },

        methods: {
            async handleLogin() {
                try {
                    const response = await axios.post('/api/services/login.php', {
                        email: this.email,
                        password: this.password
                    }, {
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });
                
                    // traitement de la réponse
                    if (response.data.success) {
                        const user = response.data['user-info'];
                        
                        // Envoi des infos de l'utilisateur dans le SessionStorage
                        sessionStorage.setItem('user', JSON.stringify({
                            id: user.id, 
                            firstname: user.firstname,
                            lastname: user.lastname,
                            email: user.email,
                            birthdate: user.birthdate,
                            type: user.type
                        }));

                        // redirection avec Vue router
                        this.$router.push('/user-profile');    
                    } else {
                        // TODO avec UX : Une fois la charte graphique ok, mettre un joli message en rouge
                        console.log("ERREUR");
                        alert(response.data.message);
                    }
                } catch (error) {
                    console.error('Erreur lors de la connexion', error);
                    alert('Une erreur est survenue, merci de réessayer.');
                }
            }
        }
    }
</script>
