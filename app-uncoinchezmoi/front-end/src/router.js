import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue';
import About from './components/About.vue';
import UserList from './components/UserList.vue'; // Le composant pour la liste des utilisateurs

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
    path: '/users',
    name: 'Users',
    component: UserList
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
