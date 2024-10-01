<template>
    <div class="container d-flex align-items-center">
        <div class="form-container m-auto">
            <h1 class="text-center">Bienvenu {{ user.firstname }}</h1>
            <p class="text-center">Bienvenue sur la page de user Profile !</p>

            <p>Informations sur l'utilisateur connecté :</p>
            <ul>
                <li v-for="(value, key) in user" :key="key">
                    {{ key }} : {{ value }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import "../assets/connect.css";

    export default {
        name: 'UserProfile',

        data() {
            return {
                user: JSON.parse(sessionStorage.getItem('user')) || {}
            };
        },

        methods: {
            
        }
    }
</script>
