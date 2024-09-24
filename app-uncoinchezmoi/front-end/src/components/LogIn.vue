<template>
    <div class="vh-100 mt-5">
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