<template>
  <v-main>
    <v-container>
      <v-row>
        <v-col>
          <v-date-picker
            v-model="selectedDate"
            color="primary"
            header-color="indigo"
            multiple
            show-caps
            @input="onDateChange()"
          ></v-date-picker>
        </v-col>
      </v-row>

      <v-row class="mt-4">
        <v-col>
          <v-alert v-if="selectedDate && selectedDate.length > 0" type="info">
            Période sélectionnée : {{ formattedDateRange }}
          </v-alert>
        </v-col>
      </v-row>
    </v-container>
  </v-main>
</template>

<script>
export default {
  data() {
    return {
      selectedDate: [],
      startDate: null,
      endDate: null,
    };
  },

  computed: {
    formattedDateRange() {
      if (this.selectedDate.length === 2) {
        const [startDate, endDate] = this.selectedDate;
        return `${this.formatDate(startDate)} - ${this.formatDate(endDate)}`;
      }
      return "Aucune période sélectionnée";
    },
  },

  methods: {
    // Fonction de formatage des dates
    formatDate(date) {
      console.log("formatDate");
      const options = { year: "numeric", month: "numeric", day: "numeric" };
      return new Date(date).toLocaleDateString("fr-FR", options);
    },

    // Fonction qui se déclenche lors de la sélection d'une date
    onDateChange() {
      console.log("onDateChange");
      console.log(this.selectedDate, this.startDate, this.endDate);
      if (this.selectedDate.length === 2) {
        const [start, end] = this.selectedDate;
        this.selectDatesInRange(start, end);
      }
    },

    // Logique pour sélectionner automatiquement toutes les dates entre start et end
    selectDatesInRange(startDate, endDate) {
      console.log("select");
      const start = new Date(startDate);
      const end = new Date(endDate);
      let selectedDates = [];

      // Si la deuxième date est avant la première, inversez-les
      if (start > end) {
        [start, end] = [end, start];
      }

      // Créer un tableau de toutes les dates entre startDate et endDate
      while (start <= end) {
        selectedDates.push(this.formatDateToString(start));
        start.setDate(start.getDate() + 1);
      }

      // Mettez à jour la sélection
      this.selectedDate = selectedDates;
    },

    // Formatage de la date en chaîne de caractères (YYYY-MM-DD)
    formatDateToString(date) {
      console.log("formatStr");
      return date.toISOString().split("T")[0];
    },
  },
};
</script>

<style scoped>
/* Styles optionnels pour améliorer l'interface */
</style>
