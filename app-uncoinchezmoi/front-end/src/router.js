import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import About from './components/About.vue';
import Login from './components/LogIn.vue';
import Register from './components/Register.vue';
import UserProfile from './components/UserProfile.vue'
import mentionsLegales from './components/icons/mentionesLegales.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/about',
    name: 'About',
    component: About
  },
  {
    path: '/login',
    name: 'Login',
    component: Login
  },
  {
    path: '/register',
    name: 'Register',
    component: Register
  }, 
  {
    path:'/user-profile',
    name:'UserProfile',
    component: UserProfile
  }
  ,  {
    path:'/mentions-legales',
    name:'mentionsLegales',
    component: mentionsLegales
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
