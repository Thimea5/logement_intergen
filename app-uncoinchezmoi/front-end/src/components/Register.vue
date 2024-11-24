<template>
	<v-main>
		<v-container class="h-100 p-3">
			<div class="h-100">
				<div class="h-100">
					<v-form class="h-100 mt-auto d-flex flex-column justify-content-between" @submit.prevent="handleRegisterFormStep">
						<div>
							<v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"></v-btn>
							<h1 class="headline text-center">Inscription</h1>
							<p>Saisissez un email et un mot de passe pour vous enregistrer</p>
						</div>
						<div>
							<label class="custom-label mb-3" for="email">Adresse Email</label>
							<v-text-field 
								id="email" 
								rounded="pill" 
								clearable 
								variant="solo-filled" 
								v-model="user.mail"
    							@input="validate()">
							</v-text-field>

							<div class="text-end">
								<v-btn class="rounded-pill" id="sendCode" @click="sendCode()" :disabled="disabled">{{ sendCodeLabel }}</v-btn>
							</div>

							<v-expansion-panels class="my-5" v-model="panel" flat>
								<v-expansion-panel value="otpShow">
									<v-expansion-panel-text>
										<v-otp-input
											v-model="user.code"
											:error="otpError"
											@input="validateOtp()">
										</v-otp-input>
									</v-expansion-panel-text>
								</v-expansion-panel>
							</v-expansion-panels>

							<label class="custom-label mb-3" for="pwd">Mot de passe</label>
							<v-text-field
								id="pwd"
								autocomplete="nope"
    							:type="marker ? 'password' : 'text'"
								rounded="pill"
								clearable
								variant="solo-filled"
								v-model="user.password"
								:append-inner-icon="marker ? 'mdi-eye-off' : 'mdi-eye'"
								@click="marker = !marker">
							</v-text-field>
							
							<label class="custom-label mb-3" for="pwdConf">Confirmez votre mot de passe</label>	
							<v-text-field
								id="pwdConf"
								autocomplete="none"
    							:type="marker ? 'password' : 'text'"
								rounded="pill"
								clearable
								variant="solo-filled"
								v-model="user.passwordConf"
								:append-inner-icon="marker ? 'mdi-eye-off' : 'mdi-eye'"
								@click="marker = !marker">
							</v-text-field>
						</div>
						
						<v-btn class="w-100 rounded-pill mb-5" @click="validateStep1()">Etape suivante</v-btn>

						<template>
							<v-dialog v-model="step2" transition="dialog-bottom-transition" fullscreen>
								<v-card class="p-3 d-flex flex-column justify-content-between">
									<div>
										<v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="step2=false"></v-btn>
										<h1 class="headline text-center">Inscription</h1>
										<p>Êtes-vous propriétaire ou locataire ?</p>
									</div>
									<div>
										<div class="d-flex justify-content-around">
											<div class="w-100 mr-5">
												<label class="custom-label mb-3" for="lName">Nom</label>
												<v-text-field
													id="lName"
													type="text"
													rounded="pill"
													clearable
													variant="solo-filled"
													v-model="user.lastName">
												</v-text-field>
											</div>
											<div class="w-100 ml-5">
												<label class="custom-label mb-3" for="fName">Prénom</label>
												<v-text-field
													id="fName"
													type="text"
													rounded="pill"
													clearable
													variant="solo-filled"
													v-model="user.firstName">
												</v-text-field>
											</div>
										</div>

										<label class="custom-label mb-3" for="bDate">Date de naissance</label>
										<v-date-input
											id="bDate"
											rounded
											clearable
											variant="solo-filled"
											v-model="user.birthDate"
											prepend-icon=""
											append-inner-icon="mdi-calendar-month">
										</v-date-input>
										
										<label class="custom-label mb-3" for="pwdConf">Confirmez votre mot de passe</label>	
										<v-text-field
											id="pwdConf"
											autocomplete="none"
											:type="marker ? 'password' : 'text'"
											rounded="pill"
											clearable
											variant="solo-filled"
											v-model="user.passwordConf"
											:append-inner-icon="marker ? 'mdi-eye-off' : 'mdi-eye'"
											@click="marker = !marker">
										</v-text-field>
									</div>

									<v-btn class="w-100 rounded-pill mb-5" @click="validateStep2()">Etape suivante</v-btn>
								</v-card>
							</v-dialog>
						</template>

						<template>
							<v-dialog v-model="step3" transition="dialog-bottom-transition" fullscreen>
								<v-card class="p-3 d-flex flex-column justify-content-between">
									<div>
										<v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="step3=false"></v-btn>
										<h1 class="headline text-center">Inscription</h1>
										<p>Renseignez les informations de votre profil</p>
									</div>
									
									<div>
										<v-radio-group>
											<v-radio label="Propriétaire" value="host"></v-radio>
											<v-radio label="Locataire" value="guest"></v-radio>
										</v-radio-group>
									</div>

									<v-btn class="w-100 rounded-pill mb-5" @click="">S'inscrire</v-btn>
								</v-card>
							</v-dialog>
						</template>

					</v-form>
				</div>
			</div>
		</v-container>
	</v-main>
</template>

<script>
import axios from 'axios';
import { VDateInput } from 'vuetify/labs/VDateInput'

const code = Math.floor(100000 + Math.random() * 900000);

export default {
	name: 'Register',
	components: {
		VDateInput,
	},
	data() {
		return {
			interval: {},

			step2:false,
			step3:false,

			timer: 60,
			disabled: true,
			sendCodeLabel: "Envoyer Code",
			otpError: false,

			panel: "",

			marker: true,
			
			step: 1, // Variable de gestion de l'étape dans le formulaire
			user: {
				mail: '',
				code: '',
				password: '',
				passwordConf: '',
				firstName: '',
				lastName: '',
				birthDate: '',
				type: ''
			},
			userType: 'locataire' // par défaut, utilisateur de type locataire
		}
	},

	methods: {
		validateOtp() {
			if (this.user.code.length == 6) {
				if (this.user.code == code) {
					console.log("OTP valide : ", this.user.code, code)
					this.otpError = false
				} else {
					console.log("OTP invalide : ", this.user.code, code)
					this.otpError = true
				}
			}
		},

		startChrono() {
			this.disabled = true
			let wait = this.timer
			this.interval = setInterval(() => {
				if (wait == 0) {
					clearInterval(this.interval)
					setTimeout(() => {
						this.sendCodeLabel = "Renvoyer Code"
						this.disabled = false
					}, 100);
				} else {
					this.sendCodeLabel = wait
					wait--
				}
			}, 1000)
		},
		
		validate() {
			if (/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.user.mail)) {
				this.disabled = false
			} else {
				this.disabled = true;  // Le bouton reste désactivé si l'email n'est pas valide
			}
		},

		validateStep1() {
			if (!(this.user.mail && this.user.password && this.user.passwordConf)) {
				alert('Veuillez remplir tout les champs  !')
				return
			}

			if (this.user.password != this.user.passwordConf) {
				alert('Les mots de passe ne correspondent pas.')
				return
			}

			if (this.user.code != code) {
				alert('Code de verification incorrect')
				return
			}

			this.step2 = true
		},

		validateStep2() {
			if (this.user.mail && this.user.password && this.user.passwordConf) {
				if (this.user.password !== this.user.passwordConf) {
					alert('Les mots de passe ne correspondent pas.');
					return;
				} else {
					this.step2 = true
				}
			} else {
				alert('Veuillez remplir tout les champs  !');
				return;
			}
		},

		handleRegisterFormStep() {
			if (this.step === 1) {
				if (this.mail && this.password && this.passwordConf) {
					if (this.password !== this.passwordConf) {
						alert('Les mots de passe ne correspondent pas.');
						return;
					} else {
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
			this.panel = 'otpShow'

			this.startChrono()
			
			if (this.user.mail === "") {
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
				to_email: this.user.mail,
				to_name: this.user.mail,
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
			axios.post('/api/services/register.php', {
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

function _(id) {
	return document.querySelector("#"+id)
}

function simpleAlert(title, description) {
	Alertpal.alert({
		title: title,
		description: description,
		cancel: "Ok"
	});
}	
</script>
