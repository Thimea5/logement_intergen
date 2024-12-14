<template>
  <v-main>
    <v-app-bar :elevation="0">
      <v-btn icon="mdi-keyboard-backspace" variant="plain" @click="goBack"></v-btn>
    </v-app-bar>

    <v-container class="d-flex flex-column m-0 p-1">
      <!-- DatePicker avec dates désactivées -->
      <DatePicker
        class="w-100"
        v-model.range="range"
        mode="dateTime"
        is24hr
        :min-date="minDate"
        :disabled-dates="disabledDates"
      ></DatePicker>

      <v-card class="mt-5 mb-5 bg-light">
        <v-card-title> Réservation :</v-card-title>
        <v-card-subtitle>
          Du {{ formatDateWithTime(range.start) }} au {{ formatDateWithTime(range.end) }}
        </v-card-subtitle>

        <v-card-text>
          <p>
            Coût de la réservation : <strong>{{ calculateCost() }}€</strong>
          </p>
          <v-btn color="primary" class="d-flex m-auto mt-1" @click="validateReservation()">
            Validez la réservation
          </v-btn>
        </v-card-text>
      </v-card>
    </v-container>
  </v-main>
</template>

<script>
import { DatePicker } from "v-calendar";
import "v-calendar/style.css";
import { ref } from "vue";
import { useRoute } from "vue-router";
import axios from "axios";
import { useListPostStore } from "../stores/listPostStore";
import { useReservationStore } from "../stores/ReservationStore";

export default {
  components: {
    DatePicker,
  },
  name: "Reservation",
  data() {
    return {
      user: JSON.parse(sessionStorage.getItem("user")) || {},
      post: null,
      range: ref({
        start: new Date(),
        end: new Date(),
      }),
      minDate: new Date(),
      reservations: null,
    };
  },

  computed: {
    disabledDates() {
      if (!this.reservations || this.reservations.length === 0) {
        return [];
      }

      return this.reservations.map((reservation) => {
        return {
          start: new Date(reservation.start_date),
          end: new Date(reservation.end_date),
        };
      });
    },
  },

  async mounted() {
    const route = useRoute();

    const ps = useListPostStore();
    if (!ps.isLoaded) ps.loadPosts();
    await this.waitUntil(() => ps.isLoaded);

    const postId = route.params.id;
    this.post = ps.listPost.find((ph) => ph.idPost == postId);

    const rs = useReservationStore();
    if (!rs.isLoaded) rs.load(this.user.id);
    await this.waitUntil(() => rs.isLoaded);

    this.reservations = rs.reservations.filter((r) => {
      return r.id_post == postId;
    });
  },

  methods: {
    formatDateWithTime(date) {
      if (!date) return "";

      const d = new Date(date);
      const day = d.getDate().toString().padStart(2, "0");
      const month = (d.getMonth() + 1).toString().padStart(2, "0");
      const year = d.getFullYear();
      const hours = d.getHours().toString().padStart(2, "0");
      const minutes = d.getMinutes().toString().padStart(2, "0");

      return `${day}/${month}/${year} (${hours}h${minutes})`;
    },

    goBack() {
      this.$router.go(-1);
    },

    calculateCost() {
      if (!this.range.start || !this.range.end || !this.post || !this.post.price) {
        return 0;
      }

      const startDate = new Date(this.range.start);
      const endDate = new Date(this.range.end);

      const timeDiff = endDate - startDate; // Différence en millisecondes
      const totalDays = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

      const dailyPrice = this.post.price / 30; // 1 Mois = 30 jours

      const totalCost = dailyPrice * totalDays;

      return totalCost.toFixed(2);
    },

    waitUntil(conditionFn, interval = 250) {
      return new Promise((resolve) => {
        const checkCondition = () => {
          if (conditionFn()) {
            resolve();
          } else {
            setTimeout(checkCondition, interval);
          }
        };
        checkCondition();
      });
    },

    validateReservation() {
      const newReservation = {
        idPost: this.post.idPost,
        startDate: this.range.start,
        endDate: this.range.end,
        idUser: this.user.id,
        cost: this.calculateCost(),
      };

      console.log(newReservation);

      const apiUrl = import.meta.env.VITE_API_URL;
      axios
        .post(apiUrl + "/services/reservationManager.php", newReservation, {
          headers: {
            "Content-Type": "application/json",
          },
        })
        .then((result) => {
          if (result.status === 200 && result.data["success"]) {
            window.location.replace("/"); // on force le refresh
          }
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
