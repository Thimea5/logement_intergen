import { createRouter, createWebHistory } from 'vue-router';
import { useUserStore } from '../stores/userStore'; 
import Home from '../components/Home.vue';
import About from '../components/About.vue';
import Login from '../components/LogIn.vue';
import Register from '../components/Register.vue';
import UserProfile from '../components/UserProfile.vue';
import ErrorPage from '../components/Error.vue';
import HomeSearch from '../components/HomeSearch.vue';
import LegalNotices from '../components/LegalNotices.vue';
import ForgotPasswordForm from '../components/ForgotPasswordForm.vue';

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
        meta: { requiresAuth: false }
    },
    {
        path: '/about',
        name: 'About',
        component: About,
        meta: { requiresAuth: false }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
        meta: { requiresAuth: false }
    },
    {
        path: '/register',
        name: 'Register',
        component: Register,
        meta: { requiresAuth: false }
    }, 
    {
        path:'/user-profile',
        name:'UserProfile',
        component: UserProfile,
        meta: { requiresAuth: true }
    },
    {
        path:'/error', 
        name: 'ErrorPage',
        component: ErrorPage,
        meta: { requiresAuth: false }
    }, 
    {
        path: '/home-search',
        name: 'HomeSearch',
        component: HomeSearch,
        meta: { requiresAuth: true }
    },
    {
        path: '/legal-notices',
        name: 'LegalNotices',
        component: LegalNotices,
        meta: { requiresAuth: false }
    },
    {
        path:'/forgot-password',
        name:'ForgotPasswordForm',
        component: ForgotPasswordForm,
        meta: { requiresAuth: false }
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

// Exécuté avant chaque navigation (chgt de route)
router.beforeEach((to, from, next) => {
    const userStore = useUserStore();
   
    if (to.meta.requiresAuth) {
        const token = sessionStorage.getItem('token');
        const expirationTime = sessionStorage.getItem('tokenExpiration');
        const currentTime =  Math.floor(Date.now() / 1000); // Date courante en secondes

        if (token && expirationTime && parseInt(expirationTime) > currentTime) {
            next();
        } else {
            // token invalide ou expiré -> redirection vers le login
            userStore.clearUser();
            next('/login');
        }
    } else {
        next();
    }
});

export default router;
