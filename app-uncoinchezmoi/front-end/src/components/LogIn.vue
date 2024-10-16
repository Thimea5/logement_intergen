<template>
  <v-main>
    <v-container>
      <v-card>
        <v-card-title class="headline">Bienvenue chez vous</v-card-title>
        <v-card-text>
          <v-form @submit.prevent="handleLogin">
            <div class="input-container">
              <label for="email" class="custom-label">Mail</label>
              <v-text-field 
								id="email" v-model="email" placeholder="Entrez votre email" required type="email" outlined bg-color="#E6CDB5">
                <!--<template v-slot:append>
                  <v-icon>mdi-email</v-icon>
                </template>-->
              </v-text-field>
            </div>

            <div class="input-container">
              <label for="password" class="custom-label">Mot de passe</label>
              <v-text-field 
								id="password" v-model="password" placeholder="Entrez votre mot de passe" required type="password" outlined bg-color="#E6CDB5">
                <!--<template v-slot:append>
                  <v-icon>mdi-lock</v-icon>
                </template>-->
              </v-text-field>
            </div>

            <v-btn type="submit" color="#E6CDB5" class="mb-3 float-right">Connexion</v-btn>
            <!--<p> Vous n'avez pas de compte ? <a href="./register">S'inscrire</a></p>-->
          </v-form>
        </v-card-text>
      </v-card>
    </v-container>
  </v-main>
</template>

<script>
	import axios from 'axios';
	import { useUserStore } from '../stores/userStore';
	import { useListPostStore } from '../stores/listPostStore';

	export default {
		name: 'Login',

		data() {
			return {
				email: '',
				password: ''
			}
		},

		setup() {
			const userStore = useUserStore();
			const listPost = useListPostStore();
			return { userStore, listPost };
		},

		methods: {
			handleLogin() {
				axios.post('/api/services/login.php', {
					email: this.email,
					password: this.password
				}, {
					headers: {
						'Content-Type': 'application/json'
					}
				}).then(result => {
					if (result.status == 200 && result.data["success"]) {
						console.log("Connexion faite !");
						const user = result.data['user-info'];
						this.userStore.setUser({
							id: user.id,
							firstname: user.firstname,
							lastname: user.lastname,
							email: user.email,
							birthdate: user.birthdate,
							type: user.type
						});

						this.loadPostsData();
					}
				}).catch(error => console.error(error));

				
			},

			loadPostsData() {
				this.listPost.loadPosts();
				this.$router.push('/');
			}
		}
	}
</script>

<style>
	.input-container {
		margin-bottom: 16px;
	}

	.custom-label {
		display: block;
		margin-bottom: 4px;
		font-weight: bold;
	}
</style>