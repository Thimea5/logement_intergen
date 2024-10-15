<template>
    <v-container>
    <div class="mentions-legales">
      <h1>Mentions légales</h1>
      <p>
        Bienvenue sur notre application d'Un Coin Chez Moi. Voici quelques informations importantes concernant l'utilisation de notre application.
      </p>
      <h2>Informations légales</h2>
      <p>
        1. Propriétaire de l'application : <strong>[Nom de l'entreprise]</strong><br>
        2. Adresse : <strong>[Adresse complète]</strong><br>
        3. Email : <strong>[Email de contact]</strong><br>
        4. Numéro de téléphone : <strong>[Numéro de contact]</strong><br>
        5. Numéro d'enregistrement au registre des entreprises : <strong>[Numéro]</strong>
      </p>
      <h2>Conditions d'utilisation</h2>
      <p>
        L'accès à l'application et son utilisation sont régis par les termes et conditions suivantes...
      </p>
    </div>
    </v-container>
  </template>
  
  <script>
  export default {
    name: 'legalNotices'
  };
  </script>
  
  <style scoped>
  .mentions-legales {
    margin: 20px;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 8px;
  }
  
  h1 {
    font-size: 24px;
    color: #333;
  }
  
  h2 {
    font-size: 20px;
    color: #666;
  }
  
  p {
    font-size: 16px;
    line-height: 1.5;
    color: #444;
  }
  </style>