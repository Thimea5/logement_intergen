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
                                    <h1> {{ this.post.price }} € </h1>
                                    <h5> {{ this.post.type_logement }}, {{ this.post.size }} m² </h5>
                                </div>
                                <br>
                                <div>
                                    <h5>Description: </h5>
                                    <p class="ms-5"> {{ this.post.description }} </p>
                                </div>
                                <v-divider></v-divider>
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
                    </v-form>
                </div>
            </div>
        </v-container>
    </v-main>
</template>

<script>

import { useListPostStore } from "../stores/listPostStore";

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
        }
    },
    mounted() {
        const ps = useListPostStore()

        setTimeout(() => {
            this.post = ps.listPost.filter(post => post.idUser == this.user.id)[0]

            this.servicesList = ps.listServices.filter(list => list.idUser == this.user.id)[0]
        }, 250);
    },
    methods: {
        goBack() {
            this.$router.go(-1)
        },

        deactivatePost() {
            console.log(this.servicesList)
        },

        updatePost() {

        }
    }
}

</script>