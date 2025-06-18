import { createRouter, createWebHistory } from "vue-router";
import store from "../store";


const routes = [
  
  {
    path: '/:catchAll(.*)',
    redirect: { name: 'app.dashboard' }
  },

  {
       path: '/app',
       name: 'app',
       component: () => import('../components/AppLayout.vue'),
       meta: {
           requiresAuth: true
       },
       redirect: { name: 'app.dashboard' },
       children: [
        {
          path: 'dashboard',
          name: 'app.dashboard',
          component: () => import('../view/Dashboard.vue'),
        },
        {
          path: 'products',
          name: 'app.products',
          component: () => import('../view/Products/Products.vue'),
        },
        {
          path: 'products/create',
          name: 'app.products.create',
          component: () => import('../view/Products/ProductForm.vue'),
        },
        {
          path: 'products/:id',
          name: 'app.products.edit',
          component: () => import('../view/Products/ProductForm.vue'),
          props: {
            id: (value) => /^\d+$/.test(value)
          }
        },

        {
          path: 'orders',
          name: 'app.orders',
        component: () => import('../view/Orders/orders.vue'),
        },
        {
        path: 'orders/:id',
        name: 'app.orders.view',
         component: () => import('../view/Orders/OrderView.vue'),
           props: {
            id: (value) => /^\d+$/.test(value)
          }
        },
        {
          path: 'users',
          name: 'app.users',
         component: () => import('../view/Users/users.vue'),
        },
        {
          path: 'users/create',
          name: 'app.users.create',
         component: () => import('../view/Users/UserForm.vue'),
        },
        
        {
          path: 'users/:id',
          name: 'app.users.edit',
          component: () => import('../view/Users/UserForm.vue'),
          props: {
            id: (value) => /^\d+$/.test(value)
          }
        },
        
      ]

  },
  {
    path: '/login',
    name: 'login', // Puedes proporcionar un nombre para la ruta si lo deseas
    component: () => import('../view/Login.vue'),
    meta: {
      requiresGuest: true
    }

  },

  {
    path: '/request-password',
    name: 'requestPassword',
    component: () => import('../view/RequestPassword.vue'),
    meta: {
      requiresGuest: true
    }

  },
  {
    path: '/reset-password/:token',
    name: 'resettPassword',
    component: () => import('../view/ResetPassword.vue'),
    meta: {
      requiresGuest: true
    },
    props: (route) => ({
      token: route.params.token,
      email: route.query.email
    })
  },
  {
    path: '/:pathMatch(.*)',
    name: 'notFound',
    component: () => import('../view/NotFound.vue'),
  }


];

const router = createRouter({
  history: createWebHistory(),
  routes
});


router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth && !store.state.user.token) {
    next({ name: 'login' })
  } else if (to.meta.requiresGuest && store.state.user.token) {
    next({ name: 'app.dashboard' })
  } else if (to.name === from.name) {
     //Evita redirecciones innecesarias
    next(false);
  } else {
    next();
  }
});


export default router;
