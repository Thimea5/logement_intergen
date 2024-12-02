<template>
    <v-main>
        <v-app-bar :elevation="0">
            <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
            <h1>Mon logement</h1>
        </v-app-bar>

        <v-container>
            <p>Vous pouvez verifier et/ou modifier les informations de votre logement</p>
            <div class="h-100">
                <div class="h-100">
                    <v-form class="h-100 mt-auto d-flex flex-column justify-content-between">
                        <v-card class="p-3 d-flex flex-column justify-content-between mb-5" :title="this.post.address"
                            :subtitle="this.post.city + ', ' + this.post.postalCode">
                            <v-divider class="m-1"></v-divider>
                            <v-card-text>
                                <div class="d-flex justify-content-between align-items-center">
                                    <h1>{{ this.post.price }} €</h1>
                                    <h5>{{ this.post.type_logement }}, {{ this.post.size }} m²</h5>
                                </div>
                                <br />
                                <div>
                                    <h5>Description:</h5>
                                    <p class="ms-5">{{ this.post.description }}</p>
                                    <p>{{ this.serviceList }}</p>
                                </div>
                                <v-divider></v-divider>
                                <h5>Services: </h5>
                                <div class="d-flex justify-content-around">
                                    <template v-for="(icon, key) in serviceIcons">
                                        <v-icon v-if="servicesList?.[key] == 1" class="mx-1">
                                            {{ icon }}
                                        </v-icon>
                                    </template>
                                </div>
                            </v-card-text>

                            <v-card-actions class="d-flex justify-content-between">
                                <v-btn color="#4F685D" variant="outlined" rounded="pill" @click="openModif = true"
                                    append-icon="mdi-pen">Modifier</v-btn>
                                <v-btn color="danger" variant="outlined" rounded="pill" @click="deactivatePost()"
                                    append-icon="mdi-delete">Désactiver</v-btn>
                            </v-card-actions>
                        </v-card>

                        <template>
                            <v-dialog v-model="openModif" transition="dialog-bottom-transition" fullscreen>
                                <v-card class="p-3 d-flex flex-column justify-content-between">
                                    <div>
                                        <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large"
                                            @click="this.$router.go(0)"></v-btn>
                                        <h1 class="headline text-center">Votre logement</h1>
                                        <p>Bonjour {{ user.firstName }}, décrivez votre logement</p>
                                    </div>

                                    <div class="h-100 d-flex flex-column justify-content-around">
                                        <div>
                                            <div class="d-flex justify-content-between">
                                                <div class="w-50 me-5">
                                                    <label class="custom-label mb-3" for="city">Ville</label>
                                                    <v-text-field id="city" placeholder="Ville" rounded="pill" clearable
                                                        variant="solo-filled" v-model="post.city">
                                                    </v-text-field>
                                                </div>

                                                <div class="w-50 me-5">
                                                    <label class="custom-label mb-3" for="postal_code">Code
                                                        postal</label>
                                                    <v-text-field id="postal_code" placeholder="Code postal"
                                                        rounded="pill" clearable variant="solo-filled"
                                                        v-model="post.postalCode">
                                                    </v-text-field>
                                                </div>
                                            </div>

                                            <label class="custom-label mb-3" for="address">Adresse</label>
                                            <v-text-field id="address" placeholder="Numéro de rue" rounded="pill"
                                                clearable variant="solo-filled" v-model="post.address">
                                            </v-text-field>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <div class="w-50 me-2">
                                                <label class="custom-label mb-3" for="price">Prix</label>
                                                <v-text-field id="price" placeholder="Surface" rounded="pill" clearable
                                                    variant="solo-filled" v-model="post.price" suffix="€">
                                                </v-text-field>
                                            </div>

                                            <div class="w-50 ms-2">
                                                <label class="custom-label mb-3" for="size">Surface</label>
                                                <v-text-field id="size" placeholder="Surface" rounded="pill" clearable
                                                    variant="solo-filled" v-model="post.size" suffix="m²">
                                                </v-text-field>
                                            </div>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <div class="w-50 me-2">
                                                <label class="custom-label mb-3" for="property">Type de
                                                    propriété</label>
                                                <v-select id="property" rounded="pill" variant="solo-filled"
                                                    :items="logTypes"
                                                    class="d-flex flex-wrap justify-content-between h-auto"
                                                    v-model="post.type_logement" mandatory>
                                                </v-select>
                                            </div>

                                            <div class="w-50 ms-2">
                                                <label class="custom-label mb-3" for="property">Services
                                                    demandés</label>
                                                <v-select v-model="selectedServices" :items="services" rounded
                                                    item-value="service" item-text="service" multiple chips variant="solo-filled"
                                                    :selected-items="getSelectedServices">
                                                </v-select>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="custom-label mb-3" for="description">Description du
                                                bien</label>
                                            <v-textarea class="overflow-scroll" id="description"
                                                placeholder="Description" rows="2" v-model="post.description" no-resize
                                                rounded variant="solo-filled">
                                            </v-textarea>
                                        </div>
                                    </div>

                                    <v-btn class="w-100 rounded-pill mb-5" color="#4F685D"
                                        @click="updatePost()">Sauvegarder</v-btn>
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
import { useListPostStore } from "../stores/listPostStore";

import { OpenStreetMapProvider } from "leaflet-geosearch";
const provider = new OpenStreetMapProvider();

export default {
    data() {
        return {
            user: JSON.parse(sessionStorage.getItem("user")),
            post: {},
            openModif: false,

            servicesList: {},
            serviceIcons: {
                isCleaning: "mdi-broom",
                isCarSharing: "mdi-car",
                isCooking: "mdi-silverware-fork-knife",
                isDiy: "mdi-hammer",
                isErrand: "mdi-cart",
                isGardening: "mdi-sprout",
                isPetsSitting: "mdi-paw",
                isTalking: "mdi-chat",
            },

            logTypes: ['Maison', 'Appartement', 'Villa', 'Chalet'],
            services: ["Jardinage", "Courses", "Ménage", "Discussion", "Cuisine", "Bricolage", "Covoiturage", "Garde d'animaux"],

            selectedServices: [] // Liste des services sélectionnés
        };
    },
    mounted() {
        const ps = useListPostStore();

        setTimeout(() => {
            this.post = ps.listPost.filter((post) => post.idUser == this.user.id)[0];

            // Récupérer la liste des services activés pour l'utilisateur
            this.servicesList = ps.listServices.filter((list) => list.idUser == this.user.id)[0];
        }, 250);
    },

    computed: {
        // Propriété calculée pour obtenir les services activés
        getSelectedServices() {
            return this.services.filter(service => {
                // Correspondance entre les services et les clés dans servicesList
                const key = this.getKeyFromService(service);
                return this.servicesList[key] === 1;
            });
        }
    },

    methods: {
        goBack() {
            this.$router.go(-1);
        },

        deactivatePost() {
            // Logique pour désactiver un post (si nécessaire)
        },

        updatePost() {
            const fullAddress = this.post.address + " " + this.post.city + " " + this.post.postalCode;
            // console.log(fullAddress);
            
            provider.search({ query: fullAddress }).then((result) => {
                if (result.length > 0) {
                    const { x, y } = result[0];
                    
                    this.post.latitude = y;
                    this.post.longitude = x;
                }
            });
            
            // console.log(this.post.latitude, this.post.longitude)

            const postData = {
                idPost: this.post.idPost,
                city: this.post.city,
                postalCode: this.post.postalCode,
                address: this.post.address,
                lat: this.post.latitude,
                lng: this.post.longitude,
                price: this.post.price,
                size: this.post.size,
                type_logement: this.post.type_logement,
                description: this.post.description,
                // services: this.selectedServices  // Inclure les services sélectionnés
            };

            // console.log(postData)

            const apiUrl = import.meta.env.VITE_API_URL;
            
            axios.post(apiUrl + "/services/updatePost.php", postData)
                .then(response => {
                    if (response.data.success) {
                        
                        this.$router.go(0);
                    } else {
                        alert('Une erreur est survenue lors de la mise à jour.');
                    }
                })
                .catch(error => {
                    console.error(error);
                    alert('Erreur de communication avec le serveur.');
                });
        },

        // Méthode pour obtenir la clé correspondante dans servicesList à partir du nom du service
        getKeyFromService(service) {
            const mapping = {
                "Ménage": "isCleaning",
                "Covoiturage": "isCarSharing",
                "Cuisine": "isCooking",
                "Bricolage": "isDiy",
                "Courses": "isErrand",
                "Jardinage": "isGardening",
                "Garde d'animaux": "isPetsSitting",
                "Discussion": "isTalking"
            };
            return mapping[service] || null;
        }
    },

    watch: {
        // Mettre à jour la liste des services sélectionnés si `servicesList` change
        servicesList: {
            handler() {
                this.selectedServices = this.getSelectedServices;
            },
            deep: true
        }
    },

    created() {
        // Initialiser les services sélectionnés lors de la création du composant
        this.selectedServices = this.getSelectedServices;  // Utiliser la méthode getSelectedServices pour initialiser selectedServices
    }
};

</script>
