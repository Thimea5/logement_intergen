<template>
    <div class="vh-100 mt-5">
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

<style>

.form-container {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 20px 20px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  width: 90%;
}

form {
  padding: 20px;
  box-sizing: border-box;
}

.input-field {
  width: 100%;
  padding: 12px;
  box-sizing: border-box;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-bottom: 20px;
  background-color: #fff;
  color: #333;
  outline: none;
  transition: border-color 0.3s, background-color 0.3s;
}

.input-field:hover, .input-field:focus {
  border-color: #3498db;
}

@media screen and (max-width: 600px) {
  .login-container {
    width: 90%;
  }
}

</style>