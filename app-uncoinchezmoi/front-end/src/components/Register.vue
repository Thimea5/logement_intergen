<template>
	<v-main>
		<v-container class="h-100 p-3">
			<div class="h-100">
				<div class="h-100">
					<v-form class="h-100 mt-auto d-flex flex-column justify-content-between">
						<div>
							<v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"></v-btn>
							<h1 class="headline text-center">Inscription</h1>
							<p>Saisissez un email et un mot de passe pour vous enregistrer</p>
						</div>
						<div>
							<label class="custom-label mb-3" for="email">Adresse Email</label>
							<v-text-field id="email" rounded="pill" clearable variant="solo-filled" v-model="user.mail"
								:rules="[rules.email, rules.emailExists, rules.required]" @input="validate()">
							</v-text-field>

							<div class="text-end">
								<v-btn class="rounded-pill" color="#8DA399" id="sendCode" @click="sendCode()"
									:disabled="disabled">{{
									sendCodeLabel }}</v-btn>
							</div>

							<v-expansion-panels class="my-5" v-model="panel" flat>
								<v-expansion-panel value="otpShow">
									<v-expansion-panel-text>
										<v-otp-input v-model="user.code" :error="otpError" @input="validateOtp()"
											:rules="[rules.codeMatch]">
										</v-otp-input>
									</v-expansion-panel-text>
								</v-expansion-panel>
							</v-expansion-panels>

							<label class="custom-label mb-3" for="pwd">Mot de passe</label>
							<v-text-field id="pwd" autocomplete="nope" :type="marker[0] ? 'password' : 'text'"
								rounded="pill" clearable variant="solo-filled" v-model="user.password"
								:append-inner-icon="marker[0] ? 'mdi-eye-off' : 'mdi-eye'"
								@click:append-inner="toggleMarker(0)" :rules="[rules.required]">
							</v-text-field>

							<label class="custom-label mb-3" for="pwdConf">Confirmez votre mot de passe</label>
							<v-text-field id="pwdConf" autocomplete="none" :type="marker[1] ? 'password' : 'text'"
								rounded="pill" clearable variant="solo-filled" v-model="user.passwordConf"
								:append-inner-icon="marker[1] ? 'mdi-eye-off' : 'mdi-eye'"
								@click:append-inner="toggleMarker(1)" :rules="[rules.required, rules.passwordsMatch]">
							</v-text-field>
						</div>

						<v-btn class="w-100 rounded-pill mb-5" color="#8DA399" @click="validateStep1()">Etape
							suivante</v-btn>

						<template>
							<v-dialog v-model="step2" transition="dialog-bottom-transition" fullscreen>
								<v-card class="p-3 d-flex flex-column justify-content-between">
									<div>
										<v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
											@click="step2 = false"></v-btn>
										<h1 class="headline text-center">Inscription</h1>
										<p>Renseignez les informations de votre profil</p>
									</div>
									<div class="d-flex flex-column justify-content-around">
										<div class="d-flex justify-content-around">
											<div class="w-100 mr-5">
												<label class="custom-label mb-3" for="lName">Nom</label>
												<v-text-field id="lName" type="text" rounded="pill" clearable
													variant="solo-filled" v-model="user.lastName" :rules="[rules.required]">
												</v-text-field>
											</div>
											<div class="w-100 ml-5">
												<label class="custom-label mb-3" for="fName">Prénom</label>
												<v-text-field id="fName" type="text" rounded="pill" clearable
													variant="solo-filled" v-model="user.firstName" :rules="[rules.required]">
												</v-text-field>
											</div>
										</div>

										<label class="custom-label mb-3" for="bDate">Date de naissance</label>
										<v-date-input id="bDate" rounded clearable variant="solo-filled"
											v-model="user.birthDate" prepend-icon=""
											append-inner-icon="mdi-calendar-month" :rules="[rules.required]">
										</v-date-input>

										<label class="custom-label mb-3" for="tel">N° Téléphone</label>
										<v-text-field id="tel" rounded="pill" clearable variant="solo-filled"
											v-model="user.telephone" :rules="[rules.required]">
										</v-text-field>

										<div class="d-flex w-100">
											<div class="d-flex flex-column w-50 mr-2">
												<label class="custom-label mb-3" for="gender">Genre</label>
												<v-select v-model="user.gender" rounded="pill" variant="solo-filled"
													:items="genders" item-title="text" item-value="value" :rules="[rules.required]">
												</v-select>
											</div>
											<div class="d-flex flex-column w-50 ml-2">
												<label class="custom-label mb-3" for="marital">Statut marital</label>
												<v-select id="marital" v-model="user.maritalStatus" rounded="pill"
													variant="solo-filled" :items="status" item-title="text"
													item-value="value" :rules="[rules.required]">
												</v-select>
											</div>
										</div>
									</div>


									<v-btn class="w-100 rounded-pill mb-5" color="#8DA399"
										@click="validateStep2()">Etape
										suivante
									</v-btn>
								</v-card>
							</v-dialog>
						</template>

						<template>
							<v-dialog v-model="step3" transition="dialog-bottom-transition" fullscreen>
								<v-card class="p-3 d-flex flex-column justify-content-between">
									<div>
										<v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
											@click="step3 = false"></v-btn>
										<h1 class="headline text-center">Inscription</h1>
										<p>Souhaitez-vous proposer un logement ou cherchez-vous un logement ?</p>
									</div>

									<v-btn-toggle
										class="h-100 d-flex flex-column justify-content-center align-items-center"
										v-model="user.type" mandatory color="#E6CDB5">
										<v-btn class="w-100 h-25 m-3 rounded-xl" value="guest" selected> Locataire
										</v-btn>
										<v-btn class="w-100 h-25 m-3 rounded-xl" value="host"> Propriétaire </v-btn>
									</v-btn-toggle>

									<v-btn class="w-100 rounded-pill mb-5" color="#4F685D"
										@click="registerUser()">S'inscrire</v-btn>
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
			users: [],

			genders: [
				{
					text: "Homme",
					value: "H"
				},
				{
					text: "Femme",
					value: "F"
				},
				{
					text: "Autre",
					value: "N/A"
				},
			],

			status: [
				{
					text: "Célibataire",
					value: "single"
				},
				{
					text: "Marié(e)",
					value: "married"
				},
			],

			rules: {
				required: value => !!value || 'Ce champ est requis',
				email: value => (!value || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(value)) || "L'adresse mail est invalide.",
				emailExists: value => !this.users.includes(value) || 'Cette adresse est déjà utilisée',
				passwordsMatch: value => value === this.user.password || 'Les mots de passe ne correspondent pas',
				codeMatch: value => value === code || 'Code de vérification incorrect'
			},

			step2: false,
			step3: false,

			interval: {},
			timer: 60,
			disabled: true,
			sendCodeLabel: "Envoyer Code",
			otpError: false,

			panel: "",

			marker: [true, true],

			user: {
				mail: '',
				code: '',
				password: '',
				passwordConf: '',
				firstName: '',
				lastName: '',
				birthDate: null,
				telephone: '',
				gender: '',
				maritalStatus: '',
				type: 'guest'
			},

		}
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

		toggleMarker(i) {
			this.marker[i] = !this.marker[i]
		},
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
			// if (!(this.user.mail && this.user.password && this.user.passwordConf)) {
			// 	alert('Veuillez remplir tout les champs  !')
			// 	return
			// }

			// if (this.user.password != this.user.passwordConf) {
			// 	alert('Les mots de passe ne correspondent pas.')
			// 	return
			// }

			// if (this.user.code != code) {
			// 	alert('Code de verification incorrect')
			// 	return
			// }

			this.step2 = true
		},

		validateStep2() {
			// if (!(this.user.lastName && this.user.firstName && this.user.birthDate && this.user.telephone)) {
			// 	alert('Veuillez remplir tous les champs !');
			// 	return;
			// }

			// const phoneRegex = /^[0-9]{10}$/;
			// if (!phoneRegex.test(this.user.telephone)) {
			// 	alert('Veuillez entrer un numéro de téléphone valide (10 chiffres).');
			// 	return;
			// }

			// const birthDate = new Date(this.user.birthDate);
			// const today = new Date();
			// if (birthDate >= today) {
			// 	alert('La date de naissance ne peut pas être dans le futur.');
			// 	return;
			// }

			this.step3 = true;
		},

		sendCode() {
			if (this.users.includes(this.user.mail)) {
				return
			}
			this.panel = 'otpShow'

			this.startChrono()

			if (this.user.mail === "") {
				alert("Veuillez renseigner une adresse mail");
				return;
			}

			console.log("Envoyer le code de vérification par email: " + code);

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
			const apiUrl = import.meta.env.VITE_API_URL;
			axios.post(apiUrl + '/services/register.php', {
				mail: this.user.mail,
				password: this.user.password,
				firstName: this.user.firstName,
				lastName: this.user.lastName,
				birthDate: this.user.birthDate,
				telephone: this.user.telephone,
				gender: this.user.gender,
				maritalStatus: this.user.maritalStatus,
				type: this.user.type
			}, {
				headers: {
					'Content-Type': 'application/json'
				}
			}).then(result => {
				if (result.status == 200 && result.data["success"]) {
					console.log("L'utilisateur est bien créé en base");
					this.$router.push("/login");
				}
			}).catch(error => {
				console.error(error);
			});

		}
	}
}

function _(id) {
	return document.querySelector("#" + id)
}


</script>
