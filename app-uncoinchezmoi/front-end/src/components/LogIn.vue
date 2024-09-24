<template>
    <div class="container d-flex align_items-center ">
        <div class="form-container m-auto">
            <form class="d-flex flex-column" action="login.php" method="post">
                <h2 class="mb-5">Connexion</h2>
                <input type="text" name="mail" placeholder="Email" class="input-field" required>
                <input type="password" name="password" placeholder="Mot de passe" class="input-field" required>
                
                <button class="btn btn-primary mb-3">Se connecter</button>

                <p>Vous n'avez pas de compte ? <a href="./register">s'inscrire</a></p>
            </form>
        </div>
    </div>
</template>

<script>

import "../assets/connect.css"

</script>
