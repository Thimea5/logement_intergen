<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" size="x-large" @click="goBack()"></v-btn>
    </v-app-bar>
    <v-container class="d-flex flex-column justify-center full-height">
      <h1 class="headline mb-5 title">Mentions légales</h1>
      <h2 class="sub_title">Editions</h2>
      <p>
        Vous êtes actuellement connecté sur <strong>application.uncoinchezmoi.live</strong>, l'application officiel de
        la société UnCoinChezMoi
      </p>
      <p>
        La présente application est la propriété de la société UnCoinChezMoi. Siège social situé : Université Lumière
        Lyon 2 - Campus Porte des Alpes (PDA) - 5 avenue Pierre Mendès France - 69500 Bron
      </p>
      <p>
        Conception et maintenance : Aucune plainte, déclaration ou demande de conseil transmise par message internet ne
        sera prise en compte. Veuillez formuler vos demandes exclusivement par voie postale.
      </p>
      <h2 class="sub_title">Accessibilité de l'application</h2>
      <p>
        L'utilisateur du présent site reconnaît disposer de la compétence et des moyens nécessaires pour utiliser et
        accéder à ce site et avoir vérifié que la configuration informatique utilisée ne contient aucun virus et qu'elle
        est en parfait état de fonctionnement.
      </p>
      <p>
        L'utilisateur reconnaît avoir été informé que le présent site est accessible 24 heures sur 24 et 7 jours sur 7,
        à l'exception des cas de force majeure, difficultés informatiques, difficultés liées à la structure des réseaux
        de communications ou difficultés techniques.
      </p>
      <h2 class="sub_title">La protection des données personnelles sur l'application</h2>
      <p>
        <strong>Messagerie :</strong> Les messages envoyés sur le réseau internet peuvent être interceptés. Ne divulguez
        pas d'informations personnelles inutiles ou sensibles. Si vous souhaitez nous communiquer de telles
        informations, utilisez impérativement la voie postale.
      </p>
      <p>
        <strong>Utilisation des formulaires :</strong> L'utilisateur est notamment informé, conformément à l'article 27
        de la loi Informatique, fichiers et libertés du 6 janvier 1978, que les informations qu'il communique en
        répondant s'il le souhaite aux formulaires présents sur le site permettent de répondre à sa demande, et sont
        destinées à UnCoinChezMoi, responsable du traitement, à des fins de gestion administrative et commerciale ainsi
        que, sauf opposition de sa part, aux autres sociétés du groupe ou aux sociétés partenaires, qui pourront ainsi
        le contacter ou lui adresser leurs offres.
      </p>
      <p>
        <strong>Informations nominatives diffusées sur le site :</strong> Les personnes concernées ont été informées de
        leurs droits. Le droit d'accès est immédiat sur le site. Le droit de rectification s'exerce auprès de
        UnCoinChezMoi.
      </p>

      <h2 class="sub_title">Droit d'auteur - Copyright</h2>
      <p>
        L'utilisateur du présent site est tenu de respecter les dispositions de la loi relative à l'Informatique, aux
        fichiers et aux libertés, dont la violation est passible de sanctions pénales.
      </p>
      <p>
        Il doit notamment s'abstenir, s'agissant des informations nominatives auxquelles il accède, de toute collecte,
        de toute utilisation détournée, et d'une manière générale, de tout acte susceptible de porter atteinte à la vie
        privée ou à la réputation des personnes.
      </p>
      <p>
        La structure générale, ainsi que les logiciels, textes, images animées ou non, son savoir faire, documents
        téléchargeables, les représentations iconographiques et photographiques, et tous autres éléments composants le
        site sont la propriété exclusive de UnCoinChezMoi.
      </p>
      <p>
        Toute représentation totale ou partielle de ce site par quelque procédé que se soit, sans l'autorisation
        expresse de UnCoinChezMoi est interdite et constituerait une contrefaçon sanctionnée par les articles L.335-2 et
        suivants du Code de la propriété intellectuelle.
      </p>
      <p>
        Il en est de même des bases de données figurant sur le site web, qui sont protégées par les dispositions de la
        loi du 1er juillet 1998 portant transposition dans le Code de la propriété intellectuelle de la directive
        européenne du 11 mars 1996 relative à la protection juridique des bases de données.
      </p>
      <p>
        Toute reproduction totale ou partielle de ces marques ou de ces logos, effectuée à partir des éléments du site
        sans l'autorisation expresse de UnCoinChezMoi est donc prohibée, au sens de l'article L.713-2 du Code de la
        propriété intellectuelle.
      </p>
      <p>
        Tout utilisateur ou visiteur du site web ne peut mettre en place un hyperlien en direction de ce site sans
        l'autorisation expresse et préalable de UnCoinChezMoi.
      </p>
      <p>
        UnCoinChezMoi met tout en oeuvre pour offrir aux utilisateurs des informations et/ou des outils disponibles et
        vérifiés, mais ne saurait être tenue pour responsable des erreurs, d'une absence de disponibilité des
        informations et/ou de la présence de virus sur son site.
      </p>

      <h2 class="sub_title">Fichiers PDF</h2>
      <p>
        Pour lire un fichier PDF, vous avez besoin du logiciel Acrobat Reader . Pour téléchargez Acrobat Reader,
        utilisez le site internet de Adobe : <strong>http://www.adobe.com</strong>. Ce site vous proposera, à cette
        occasion, de remplir un questionnaire nominatif. Si vous ne souhaitez pas livrer vos noms et adresse, passez
        directement à l'étape n° 2 pour télécharger directement et gratuitement le logiciel.
      </p>
    </v-container>
  </v-main>
</template>

<script>
export default {
  name: "legalNotices",
  methods: {
    goBack() {
      this.$router.go(-1);
    },
  },
};
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
  text-align: center;
}

.sub_title {
  text-align: left;
  font-weight: bold;
}
</style>
