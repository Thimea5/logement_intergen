import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/Home.vue';
import About from '../components/About.vue';
import Login from '../components/LogIn.vue';
import Register from '../components/Register.vue';
import UserProfile from '../components/UserProfile.vue';
import ErrorPage from '../components/Error.vue';
import HomeSearch from '../components/HomeSearch.vue';
import LegalNotices from '../components/LegalNotices.vue';

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
  },
  {
    path:'/error', 
    name: 'ErrorPage',
    component: ErrorPage
  }, 
  {
    path: '/home-search',
    name: 'HomeSearch',
    component: HomeSearch
  },
  {
    path: '/legal-notices',
    name: 'LegalNotices',
    component: LegalNotices
  },
  {
    /* Note : le '*' n'est plus reconnu sur vue-router 4... 
    Définition d'une route générale pour rediriger toutes les routes non définies vers /error */
    path: '/:pathMatch(.*)*', 
    redirect: '/error'
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

/* TODO : Ajouter un routeur.beforeEach pour gérer avec user.isAuthenticated sur différente route ? */

export default router;
