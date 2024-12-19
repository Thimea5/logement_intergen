<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
    </v-app-bar>
    <v-container class="h-100 p-3">
      <div class="h-100">
        <div class="h-100">
          <v-form class="h-100 mt-auto d-flex flex-column justify-content-between">
            <div>
              <h1 class="headline mb-5 title">Inscription</h1>
              <p>Saisissez un email et un mot de passe pour vous enregistrer</p>
            </div>
            <div>
              <label class="custom-label mb-3" for="email">Adresse Email</label>
              <v-text-field id="email" placeholder="Adresse Email" rounded="pill" clearable variant="solo-filled"
                v-model="user.mail" :rules="[rules.email, rules.emailExists, rules.required]" @input="validate()">
              </v-text-field>

              <div class="text-end">
                <v-btn class="rounded-pill text-light" color="var(--dark-green-color)" id="sendCode"
                  @click="sendCode()">{{
                  sendCodeLabel
                  }}</v-btn>
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
              <v-text-field id="pwd" placeholder="Mot de passe" autocomplete="nope"
                :type="marker[0] ? 'password' : 'text'" rounded="pill" clearable variant="solo-filled"
                v-model="user.password" :append-inner-icon="marker[0] ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="toggleMarker(0)" :rules="[rules.required]">
              </v-text-field>

              <label class="custom-label mb-3" for="pwdConf">Confirmez votre mot de passe</label>
              <v-text-field id="pwdConf" placeholder="Confirmez votre mot de passe" autocomplete="none"
                :type="marker[1] ? 'password' : 'text'" rounded="pill" clearable variant="solo-filled"
                v-model="user.passwordConf" :append-inner-icon="marker[1] ? 'mdi-eye-off' : 'mdi-eye'"
                @click:append-inner="toggleMarker(1)" :rules="[rules.required, rules.passwordsMatch]">
              </v-text-field>
            </div>

            <v-btn class="w-100 rounded-pill mb-5 text-light" color="var(--dark-green-color)"
              @click="validateStep1()">Etape
              suivante</v-btn>

            <template>
              <v-dialog v-model="step2" transition="dialog-bottom-transition" fullscreen>
                <v-card class="p-3 d-flex flex-column justify-content-between">
                  <div>
                    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="step2 = false"></v-btn>
                    <h1 class="headline text-center">Inscription</h1>
                    <p>Renseignez les informations de votre profil</p>
                  </div>
                  <div class="d-flex flex-column justify-content-around">
                    <div class="d-flex justify-content-around">
                      <div class="w-100 mr-5">
                        <label class="custom-label mb-3" for="lName">Nom</label>
                        <v-text-field id="lName" placeholder="Nom" type="text" rounded="pill" clearable
                          variant="solo-filled" v-model="user.lastName" :rules="[rules.required]">
                        </v-text-field>
                      </div>
                      <div class="w-100 ml-5">
                        <label class="custom-label mb-3" for="fName">Prénom</label>
                        <v-text-field id="fName" placeholder="Prénom" type="text" rounded="pill" clearable
                          variant="solo-filled" v-model="user.firstName" :rules="[rules.required]">
                        </v-text-field>
                      </div>
                    </div>

                    <label class="custom-label mb-3" for="bDate">Date de naissance</label>
                    <v-date-input id="bDate" rounded clearable variant="solo-filled" v-model="user.birthDate"
                      prepend-icon="" append-inner-icon="mdi-calendar-month" :rules="[rules.required]">
                    </v-date-input>

                    <label class="custom-label mb-3" for="tel">N° Téléphone</label>
                    <v-text-field id="tel" placeholder="Numéro de téléphone" rounded="pill" clearable
                      variant="solo-filled" v-model="user.telephone" :rules="[rules.required]">
                    </v-text-field>

                    <div class="d-flex w-100">
                      <div class="d-flex flex-column w-50 mr-2">
                        <label class="custom-label mb-3" for="gender">Genre</label>
                        <v-select v-model="user.gender" rounded="pill" variant="solo-filled" :items="genders"
                          item-title="text" item-value="value" :rules="[rules.required]">
                        </v-select>
                      </div>
                      <div class="d-flex flex-column w-50 ml-2">
                        <label class="custom-label mb-3" for="marital">Statut marital</label>
                        <v-select id="marital" v-model="user.maritalStatus" rounded="pill" variant="solo-filled"
                          :items="status" item-title="text" item-value="value" :rules="[rules.required]">
                        </v-select>
                      </div>
                    </div>
                  </div>

                  <v-btn class="w-100 rounded-pill mb-5 text-light" color="var(--dark-green-color)"
                    @click="validateStep2()">Etape suivante
                  </v-btn>
                </v-card>
              </v-dialog>
            </template>

            <template>
              <v-dialog v-model="step3" transition="dialog-bottom-transition" fullscreen>
                <v-card class="p-3 d-flex flex-column justify-content-between">
                  <div>
                    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="step3 = false"></v-btn>
                    <h1 class="headline text-center">Inscription</h1>
                    <p>Souhaitez-vous proposer un logement ou cherchez-vous un logement ?</p>
                  </div>

                  <v-btn-toggle class="h-100 d-flex flex-column justify-content-center align-items-center"
                    v-model="user.type" mandatory color="#E6CDB5">
                    <v-btn class="w-100 h-25 m-3 rounded-xl" value="guest"> Locataire </v-btn>
                    <v-btn class="w-100 h-25 m-3 rounded-xl" value="host"> Propriétaire </v-btn>
                  </v-btn-toggle>

                  <v-btn class="w-100 rounded-pill mb-5" color="#4F685D" @click="validateUser()">Suivant</v-btn>
                </v-card>
              </v-dialog>
            </template>

            <template v-if="user.type == 'host'">
              <v-dialog v-model="step3_1" transition="dialog-bottom-transition" fullscreen>
                <v-card class="p-3 d-flex flex-column justify-content-between">
                  <div>
                    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
                      @click="step3_1 = false"></v-btn>
                    <h1 class="headline text-center">Votre logement</h1>
                    <p>Bonjour {{ user.firstName }}, décrivez votre logement</p>
                  </div>

                  <div class="h-100 d-flex flex-column justify-content-around">
                    <div>
                      <div class="d-flex justify-content-between">
                        <div class="w-50 me-5">
                          <label class="custom-label mb-3" for="city">Ville</label>
                          <v-text-field id="city" placeholder="Ville" rounded="pill" clearable variant="solo-filled"
                            v-model="post.city" :rules="[rules.required]">
                          </v-text-field>
                        </div>

                        <div class="w-50 me-5">
                          <label class="custom-label mb-3" for="postal_code">Code postal</label>
                          <v-text-field id="postal_code" placeholder="Code postal" rounded="pill" clearable
                            variant="solo-filled" v-model="post.postal_code" :rules="[rules.required]">
                          </v-text-field>
                        </div>
                      </div>

                      <label class="custom-label mb-3" for="address">Adresse</label>
                      <v-text-field id="address" placeholder="Numéro de rue" rounded="pill" clearable
                        variant="solo-filled" v-model="searchQuery" :rules="[rules.required]" ref="autoCompleteInput">
                      </v-text-field>

                      <v-list v-if="suggestions.length">
                        <v-list-item v-for="(suggestion, index) in suggestions" :key="index"
                          @click="selectSuggestion(suggestion)">
                          <v-list-item-title>{{ suggestion.description }}</v-list-item-title>
                        </v-list-item>
                      </v-list>

                    </div>

                    <div>
                      <label class="custom-label mb-3" for="property">Type de propriété</label>
                      <v-btn-toggle id="property" class="d-flex flex-wrap justify-content-between h-auto"
                        v-model="post.type_logement" mandatory color="#E6CDB5">
                        <v-btn class="rounded-xl m-1 p-3 border" value="Maison"> Maison </v-btn>
                        <v-btn class="rounded-xl m-1 p-3 border" value="Appartement"> Appartement </v-btn>
                        <v-btn class="rounded-xl m-1 p-3 border" value="Villa"> Villa </v-btn>
                        <v-btn class="rounded-xl m-1 p-3 border" value="Chalet"> Chalet </v-btn>
                      </v-btn-toggle>
                    </div>

                    <div>
                      <label class="custom-label mb-3" for="size">Surface</label>
                      <v-text-field id="size" placeholder="Surface" rounded="pill" clearable variant="solo-filled"
                        v-model="post.size" :rules="[rules.required]" suffix="m²">
                      </v-text-field>
                    </div>
                  </div>

                  <v-btn class="w-100 rounded-pill mb-5" color="#4F685D" @click="validateStep3_1()">Suivant</v-btn>
                </v-card>
              </v-dialog>
            </template>

            <template v-if="user.type == 'guest'">
              <v-dialog v-model="step3_1" transition="dialog-bottom-transition" fullscreen>
                <v-card class="p-3 d-flex flex-column justify-content-between">
                  <div>
                    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
                      @click="step3_1 = false"></v-btn>
                    <h1 class="headline text-center">Votre profil</h1>
                    <p>Bonjour {{ user.firstName }}, veuillez finaliser votre inscription</p>
                  </div>

                  <div class="h-100 d-flex flex-column justify-content-around">
                    <div>

                      <label class="custom-label mb-3" for="services">Services proposés</label>
                      <v-btn-toggle id="services" class="d-flex flex-wrap justify-content-between h-auto"
                        v-model="guest.services" multiple color="#E6CDB5">
                        <v-btn class="mx-1 my-3 p-3 rounded-pill border" v-for="service in services" :value="service"
                          :text="service"></v-btn>
                      </v-btn-toggle>
                    </div>

                    <div>
                      <label class="custom-label mb-3" for="max_price">Prix maximum souhaité</label>
                      <v-text-field id="max_price" placeholder="Prix max" rounded="pill" clearable variant="solo-filled"
                        v-model="guest.max_price" :rules="[rules.required]" suffix="€">
                      </v-text-field>
                    </div>
                  </div>

                  <v-btn class="w-100 rounded-pill mb-5" color="#4F685D" @click="registerGuest()">S'inscrire</v-btn>
                </v-card>
              </v-dialog>
            </template>

            <template v-if="user.type == 'host'">
              <v-dialog v-model="step3_2" transition="dialog-bottom-transition" fullscreen>
                <v-card class="p-3 d-flex flex-column justify-content-between">
                  <div>
                    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
                      @click="step3_2 = false"></v-btn>
                    <h1 class="headline text-center">Votre logement</h1>
                    <p>Bonjour {{ user.firstName }}, décrivez votre logement</p>
                  </div>

                  <div class="h-100 d-flex flex-column justify-content-around">
                    <div>
                      <label class="custom-label mb-3" for="services">Services demandés</label>
                      <v-btn-toggle id="services" class="d-flex flex-wrap justify-content-between h-auto"
                        v-model="post.services" multiple color="#E6CDB5">
                        <v-btn class="mx-1 my-2 p-2 rounded-pill border" v-for="service in services" :value="service"
                          :text="service"></v-btn>
                      </v-btn-toggle>
                    </div>

                    <div>
                      <label class="custom-label mb-3" for="description">Description du bien</label>
                      <v-textarea id="description" placeholder="Description" v-model="post.description" no-resize
                        rounded variant="solo-filled">
                      </v-textarea>
                    </div>

                    <div>
                      <label class="custom-label mb-3" for="price">Prix du loyer</label>
                      <v-number-input id="price" placeholder="Prix" rounded="pill" clearable variant="solo-filled"
                        v-model="post.price" :rules="[rules.required]" suffix="€" controlVariant="split" :step="10"
                        :min="0">
                      </v-number-input>
                    </div>
                  </div>

                  <v-btn class="w-100 rounded-pill mb-5" color="#4F685D" @click="validateStep3_2()">Suivant</v-btn>
                </v-card>
              </v-dialog>
            </template>

            <template v-if="user.type == 'host'">
              <v-dialog v-model="step3_3" transition="dialog-bottom-transition" fullscreen>
                <v-card class="p-3 d-flex flex-column justify-content-between">
                  <div>
                    <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
                      @click="step3_3 = false"></v-btn>
                    <h1 class="headline text-center">Votre annonce</h1>
                    <p>Ajoutez des photos à votre annonce</p>
                  </div>

                  <div class="h-50 my-5 d-flex flex-wrap justify-content-around align-items-around">
                    <v-file-input v-for="i in 4" class="d-none" :id="'img'+i" accept="image/*"
                      v-model="post.imgs[i-1]"></v-file-input>

                    <v-btn v-for="i in 4" class="m-4 text-muted rounded-xl" :id="'fInput'+i" width="35%" height="35%"
                      @click="triggerInputFile(i)">
                      <v-icon v-if="post.imgs[i-1] == null" class="position-absolute start-50 translate-middle top-50"
                        size="50" icon="mdi-plus"></v-icon>
                      <v-icon v-else class="position-absolute start-50 translate-middle top-50" size="50"
                        icon="mdi-image-check-outline"></v-icon>
                    </v-btn>
                  </div>

                  <v-btn class="w-100 rounded-pill mb-5" color="#4F685D" @click="registerHost()">S'inscrire</v-btn>
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
import axios from "axios";
import { VDateInput } from "vuetify/labs/VDateInput";
import { VNumberInput } from "vuetify/labs/VNumberInput";

import { OpenStreetMapProvider } from "leaflet-geosearch";
const provider = new OpenStreetMapProvider();

const code = Math.floor(100000 + Math.random() * 900000);

export default {
  name: "Register",
  components: {
    VDateInput,
    VNumberInput,
  },
  data() {
    return {
      users: [],

      genders: [
        {
          text: "Homme",
          value: "H",
        },
        {
          text: "Femme",
          value: "F",
        },
        {
          text: "Autre",
          value: "N/A",
        },
      ],

      status: [
        {
          text: "Célibataire",
          value: "single",
        },
        {
          text: "Marié(e)",
          value: "married",
        },
      ],

      rules: {
        required: (value) => !!value || "Ce champ est requis",
        email: (value) =>
          !value || /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(value) || "L'adresse mail est invalide.",
        emailExists: (value) => !this.users.includes(value) || "Cette adresse est déjà utilisée",
        passwordsMatch: (value) => value === this.user.password || "Les mots de passe ne correspondent pas",
        codeMatch: (value) => value === code || "Code de vérification incorrect",
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
        mail: "",
        code: "",
        password: "",
        passwordConf: "",
        firstName: "",
        lastName: "",
        birthDate: null,
        telephone: "",
        gender: "",
        maritalStatus: "",
        type: "guest",
      },

      step3_1: false,
      step3_2: false,
      step3_3: false,

      suggestions: [],
      autocompleteService: "",
      searchQuery: "",

      post: {
        city: "",
        postal_code: "",
        address: "",
        lat: 45.7,
        lng: 4.8,
        type_logement: "Maison",
        description: "",
        size: 0,
        price: 0,
        services: [],
        imgs: []
      },

      fullAddress: "",

      guest: {
        services: [],
      },

      services: [
        "Jardinage",
        "Courses",
        "Ménage",
        "Discussion",
        "Cuisine",
        "Bricolage",
        "Covoiturage",
        "Garde d'animaux",
      ],
    };
  },

  async mounted() {
    this.loadGoogleMapsAPI()
      .then(() => {
        if (window.google && window.google.maps) {
          this.autocompleteService = new google.maps.places.AutocompleteService();
          console.log("AutocompleteService initialized");
        }
      })
      .catch((error) => {
        console.error("Failed to load Google Maps API", error);
      });

    this.getAllUsers()
  },
  watch: {
    searchQuery(newQuery) {
      if (newQuery.length > 2) {
        console.log(newQuery)
        this.fetchSuggestions(newQuery);
      } else {
        this.suggestions = [];
      }
    },
  },

  methods: {
    loadGoogleMapsAPI() {
      return new Promise((resolve, reject) => {
        if (window.google && window.google.maps) {
          resolve(); // L'API est déjà chargée
          return;
        }

        const script = document.createElement("script");
        script.src = `https://maps.googleapis.com/maps/api/js?key=AIzaSyAzP1jU_5lO_65-P_Hk_kZcFEQyVu5MqIg&libraries=places`;
        script.async = true;
        script.defer = true;
        script.onload = () => resolve();
        script.onerror = (error) => reject(error);
        document.head.appendChild(script);
      });
    },
    fetchSuggestions(query) {
      if (!this.autocompleteService) return;

      const options = {
        input: query,
        componentRestrictions: { country: "fr" }, // Limiter à la France
      };

      // Requête à l'API Google Places pour récupérer les prédictions
      this.autocompleteService.getPlacePredictions(options, (predictions, status) => {
        if (status === google.maps.places.PlacesServiceStatus.OK && predictions) {
          // Récupérer les détails des prédictions via place_id
          const placeDetailsPromises = predictions.map((prediction) => {
            return new Promise((resolve) => {
              const placeService = new google.maps.places.PlacesService(document.createElement("div"));
              placeService.getDetails({ placeId: prediction.place_id }, (details, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                  resolve({
                    description: prediction.description,
                    city: this.extractComponent(details.address_components, "locality"),
                    postalCode: this.extractComponent(details.address_components, "postal_code"),
                    country: this.extractComponent(details.address_components, "country"),
                    fullDetails: details,
                  });
                } else {
                  resolve(null);
                }
              });
            });
          });

          // Attendre que toutes les promesses soient résolues pour mettre à jour les suggestions
          Promise.all(placeDetailsPromises).then((detailedSuggestions) => {
            this.suggestions = detailedSuggestions.filter(Boolean); // Retirer les éléments nuls
          });
        } else {
          this.suggestions = [];
        }
      });
    },

    selectSuggestion(suggestion) {
      let pc = extractPostalCode(suggestion.fullDetails["formatted_address"])
      console.log("Selected Address Details:", pc)

      this.searchQuery = suggestion.description
      this.post.address = suggestion.description
      this.post.city = suggestion.city
      this.post.postal_code = pc

      this.suggestions = [];
    },

    extractComponent(components, type) {
      const component = components.find((comp) => comp.types.includes(type));
      return component ? component.long_name : null;
    },


    goBack() {
      this.$router.go(-1);
    },
    getAllUsers() {
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .get(apiUrl + "/services/userManager.php")
        .then((response) => {
          //console.log(response.data);
          for (let i = 0; i < response.data.length; i++) {
            this.users.push(response.data[i]["mail"]);
          }
          //console.log(this.users);
        })
        .catch((error) => {
          console.error("Erreur lors de la récupération des utilisateurs:", error);
        });
    },

    toggleMarker(i) {
      this.marker[i] = !this.marker[i];
    },
    validateOtp() {
      if (this.user.code.length == 6) {
        if (this.user.code == code) {
          //console.log("OTP valide : ", this.user.code, code);
          this.otpError = false;
        } else {
          //console.log("OTP invalide : ", this.user.code, code);
          this.otpError = true;
        }
      }
    },

    startChrono() {
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

    validate() {
      if (/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/.test(this.user.mail)) {
        this.disabled = false;
      } else {
        this.disabled = true; // Le bouton reste désactivé si l'email n'est pas valide
      }
    },

    validateStep1() {
      // if (!(this.user.mail && this.user.password && this.user.passwordConf)) {
      //   //console.log(1);
      //   return;
      // }

      // if (this.user.password != this.user.passwordConf) {
      //   //console.log(2);
      //   return;
      // }

      // if (this.user.code != code) {
      //   //console.log(3);
      //   return;
      // }
      //console.log("ici ?");

      this.step2 = true;
    },

    validateStep2() {
      // if (!(this.user.lastName && this.user.firstName && this.user.birthDate && this.user.telephone)) {
      //   return;
      // }

      // const phoneRegex = /^[0-9]{10}$/;
      // if (!phoneRegex.test(this.user.telephone)) {
      //   return;
      // }

      // const birthDate = new Date(this.user.birthDate);
      // const today = new Date();
      // if (birthDate >= today) {
      //   return;
      // }
      // // console.log("hey !");
      this.step3 = true;
    },

    sendCode() {
      if (this.users.includes(this.user.mail)) {
        return;
      }
      this.panel = "otpShow";

      this.startChrono();

      if (this.user.mail === "") {
        return;
      }

      //console.log("Envoyer le code de vérification par email: " + code);

      emailjs.init({
        publicKey: "gH39qa5yQWVo_b1pQ",
      });

      var templateParams = {
        to_email: this.user.mail,
        to_name: this.user.mail,
        message: code,
      };

      emailjs.send("service_bkoff5o", "template_gx7o92j", templateParams).then(
        (response) => {
          //console.log("SUCCESS !", response.status, response.text);
        },
        (error) => {
          //console.log("FAILED...", error);
        }
      );
    },

    validateUser() {
      this.step3_1 = true;
    },

    async validateStep3_1() {

      if (this.post.address !== "" && this.post.city !== "" && this.post.postal_code !== "") {
        this.fullAddress = this.post.address + " " + this.post.city + " " + this.post.postal_code;
        console.log(this.fullAddress);

        provider.search({ query: this.fullAddress }).then((result) => {
          if (result.length > 0) {
            const { x, y } = result[0];

            this.post.lat = y;
            this.post.lng = x;
          }
        });
      }

      this.step3_2 = true;
    },

    validateStep3_2() {
      this.step3_3 = true;
    },

    triggerInputFile(index) {
      const fileInput = document.getElementById('img'+index)

      if (fileInput) {
        fileInput.click()
      }
    },

    registerGuest() {
      const aServ = [];
      for (let i = 0; i < this.services.length; i++) {
        aServ.push(this.guest.services.includes(this.services[i]));
      }

      // Créer un objet FormData
      const formData = new FormData();

      // Ajouter les données utilisateur à FormData
      formData.append('mail', this.user.mail);
      formData.append('password', this.user.password);
      formData.append('firstName', this.user.firstName);
      formData.append('lastName', this.user.lastName);
      formData.append('birthDate', this.user.birthDate);
      formData.append('telephone', this.user.telephone);
      formData.append('gender', this.user.gender);
      formData.append('maritalStatus', this.user.maritalStatus);
      formData.append('type', this.user.type);

      // Ajouter les services à FormData
      formData.append('services', JSON.stringify(aServ));

      // Si tu veux envoyer des fichiers (comme des photos), tu peux les ajouter ici
      if (this.guest.imgs && this.guest.imgs.length > 0) {
        for (let i = 0; i < this.guest.imgs.length; i++) {
          const file = this.guest.imgs[i];
          formData.append('photos[]', file);
        }
      }

      console.log(formData);

      // Appeler la fonction pour enregistrer l'utilisateur avec FormData
      this.registerUser(formData);
    },

    registerHost() {
      const aServ = [];
      for (let i = 0; i < this.services.length; i++) {
        aServ.push(this.post.services.includes(this.services[i]));
      }

      const data = {
        mail: this.user.mail,
        password: this.user.password,
        firstName: this.user.firstName,
        lastName: this.user.lastName,
        birthDate: this.user.birthDate,
        telephone: this.user.telephone,
        gender: this.user.gender,
        maritalStatus: this.user.maritalStatus,
        type: this.user.type,

        city: this.post.city,
        postal_code: this.post.postal_code,
        address: this.post.address,
        lat: this.post.lat,
        lng: this.post.lng,
        type_logement: this.post.type_logement || "Maison",
        size: this.post.size,
        description: this.post.description,
        price: this.post.price,

        services: aServ,
      };

      // Créer un objet FormData
      const formData = new FormData();

      // Ajouter toutes les données à FormData
      for (let key in data) {
        formData.append(key, data[key]);
      }

      // Ajouter les fichiers (photos) à FormData avec des noms basés sur leur index
      if (this.post.imgs && this.post.imgs.length > 0) {
        for (let i = 0; i < this.post.imgs.length; i++) {
          const file = this.post.imgs[i];

          // Ajouter le fichier à FormData avec son nouveau nom
          formData.append('photos[]', file);
        }
      }

      console.log(formData)
      
      this.registerUser(formData);
    },

    registerUser(pData) {
      console.log(pData)
      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(apiUrl + "/services/register.php", pData)
        .then((result) => {
          if (result.status === 200 && result.data["success"]) {
            this.$router.push("/login");
          } else {
            console.log("Erreur lors de la création de l'utilisateur", result);
          }
        })
        .catch((error) => {
          console.error("Erreur : ", error);
        });
    },

    getImg() {
      try {
        return new URL(`/src/assets/img/host_photo1/host_photo1_1.jpg`, import.meta.url).href;
      } catch (error) {
        return new URL(`/src/assets/img/add_button.png`, import.meta.url).href;
      }
    },
  },
};

function _(id) {
  return document.querySelector("#" + id);
}

function extractPostalCode(address) {
  // Séparer l'adresse en fonction des virgules
  const parts = address.split(',');

  // Récupérer le deuxième élément (code postal + ville)
  const secondPart = parts[1].trim();

  // Extraire les 5 premiers caractères pour obtenir le code postal
  const postalCode = secondPart.substring(0, 5);

  return postalCode;
}
</script>

<style scoped>
@font-face {
  font-family: "GillSansMT";
  src: url("../assets/fonts/gill-sans-mt-regular.ttf") format("truetype");
  font-style: normal;
}

* {
  font-family: "GillSansMT", sans-serif;
}

.title {
  font-weight: bold;
  font-size: 32px;
  text-align: center;
}

.v-list {
  max-height: 200px;
  overflow-y: auto;
  background: white;
  border: 1px solid #ddd;
}

</style>