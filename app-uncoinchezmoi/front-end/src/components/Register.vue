<template>
    <div class="container d-flex align_items-center">
        <div class="form-container m-auto">
            <form class="d-flex flex-column" action="register.php" method="post">
                <h2 class="mb-5">Inscription</h2>
                <input type="email" name="mail" placeholder="Email" class="input-field" required>

                <button class="btn btn-primary w-50 mb-5 ml-auto">Envoyer code</button>

                <div class="my-5">
                  <input type="text" name="firstname" placeholder="Prénom" class="input-field" required>
                  <input type="text" name="lastname" placeholder="Nom" class="input-field" required>
                  <input type="date" name="birth_date" class="w-50 input-field">
                </div>

                <input type="password" name="password" placeholder="Mot de passe" class="input-field" required>
                <input type="password" name="password_conf" placeholder="Confirmation" class="input-field" required>

                <button class="btn btn-primary mb-3">S'inscrire</button>

                <p>Déjà inscrit ? <a href="./login">se connecter</a></p>
            </form>
        </div>
    </div>
</template>

<script>

import "../assets/connect.css"

</script>
