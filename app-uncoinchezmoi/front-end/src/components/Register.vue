<template>
  <v-main>
    <v-container>
      <h1>Un Coin Chez Moi</h1>
      <v-card>
        <v-card-title class="headline">Inscription</v-card-title>
        <v-card-text>
          <v-form @submit.prevent="handleRegisterFormStep">
            <template v-if="step === 1">
              <v-text-field 
                v-model="mail" label="Email" required type="email" bg-color="#E6CDB5">
							</v-text-field>

              <v-text-field 
                v-model="password" label="Mot de passe" required type="password" bg-color="#E6CDB5">
							</v-text-field>

              <v-text-field 
                v-model="passwordConf" label="Confirmation" required type="password" bg-color="#E6CDB5">
							</v-text-field>

              <v-btn class="mb-3 float-right" color="#E6CDB5" type="submit">
                Suivant
              </v-btn>
            </template>

            <template v-else-if="step === 2">
              <v-text-field 
                v-model="code" label="Code de validation" required type="text" bg-color="#E6CDB5">
							</v-text-field>

              <v-btn class="mb-3 float-right" color="#E6CDB5" type="submit">
								Valider
              </v-btn>
            </template>
          </v-form>
        </v-card-text>
      </v-card>
    </v-container>
  </v-main>
</template>

<script>
	import axios from 'axios';

	let code = Math.floor(1000 + Math.random() * 9000);

	export default {
		name: 'Register',

		data() {
			return {
				step: 1, // Variable de gestion de l'étape dans le formulaire
				mail: '',
				code: '',
				password: '',
				passwordConf: '',
				userType: 'locataire' // par défaut, utilisateur de type locataire
			}
		},

		methods: {
			handleRegisterFormStep() {
				// TODO Guillaume : découper le formulaire
				if (this.step === 1) {
					if (this.mail && this.password && this.passwordConf) {
						if (this.password !== this.passwordConf) {
							alert('Les mots de passe ne correspondent pas.'); 
							return;
						} else {
							this.sendCode();
							this.step = 2; 
						}
					} else {
						alert('Veuillez remplir tout les champs  !');
						return;
					}
				} else if (this.step === 2) {
					if (Number(this.code) !== code) {
						alert('Mauvaise saisie du code de validation.');
						return;
					}
					// console.log("ici");
					this.registerUser(); // Inscription en base
					// TODO : ajouter les autres formulaires Proprio et Locataire et ajouté la redirection vers eux
				}
			},

			sendCode() {
				console.log("sendcode");
				if (this.mail === "") {
					alert("Veuillez renseigner une adresse mail");
					// simpleAlert("Attention !", "veuillez renseigner une adresse mail"); A utiliser si alertPal configuré
					return;
				}

				console.log("Envoyer le code de vérification par email: " + code);

				//return;
				emailjs.init({
					publicKey: 'gH39qa5yQWVo_b1pQ',
				});

				var templateParams = {
					to_email: this.mail,
					to_name: this.mail,
					message: code
				};

				emailjs.send('service_bkoff5o', 'template_gx7o92j', templateParams).then(
					(response) => {
						console.log('SUCCESS !', response.status, response.text);
					}, (error) => {
						console.log('FAILED...', error);
					}
				);
			},

			registerUser() {
				console.log("registerser");
				const apiUrl = import.meta.env.VITE_API_URL;
				console.log(apiUrl);
				axios.post(apiUrl+'/services/register.php', {
					mail: this.mail,
					password: this.password,
					code: this.code,
					submit: true
				}, {
					headers: {
						'Content-Type': 'application/json'
					}
				}).then(result => {
					if (result.status == 200 && result.data["success"]) {
						console.log("l'utilisateur est bien crée en base")
						this.$router.push("/login"); 
					}
				}).catch(error => console.error(error));
			}
		}
	}

	function simpleAlert(title, description) {
    Alertpal.alert({
        title: title,
        description: description,
        cancel: "Ok"
    });
	}	
</script>
