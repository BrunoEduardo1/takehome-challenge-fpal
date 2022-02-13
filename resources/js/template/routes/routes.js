import DashboardLayout from '../layout/DashboardLayout.vue';
// GeneralViews
import NotFound from '../pages/NotFound';

// Admin pages
import Overview from '../pages/Overview.vue';
import UserProfile from '../pages/UserProfile.vue';
import TableList from '../pages/TableList.vue';
import Typography from '../pages/Typography.vue';
import Icons from '../pages/Icons.vue';
import Notifications from '../pages/Notifications.vue';
import Upgrade from '../pages/Upgrade.vue';

import Users from '../pages/Users';

// user authentication pages
import Login from '../pages/Login';
import Register from '../pages/Register';
import RecoverPassword from '../pages/RecoverPassword';
import ResetPassword from '../pages/ResetPassword';

import AuthMiddleware from '../directives/auth.middleware';

const routes = [
  {
    path: '/admin',
    component: DashboardLayout
  },
  {
    path: '/',
    name: 'Login',
    component: Login,
    beforeEnter: AuthMiddleware.run
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    beforeEnter: AuthMiddleware.run
  },
  {
    path: '/forgotPassword',
    name: 'RecoverPassword',
    component: RecoverPassword
    // beforeEnter: AuthMiddleware.run
  },
  {
    path: '/resetPassword/:token/:email',
    name: 'ResetPassword',
    component: ResetPassword
    // beforeEnter: AuthMiddleware.run
  },
  {
    path: '/admin',
    component: DashboardLayout,
    redirect: '/admin/overview',
    beforeEnter: AuthMiddleware.run,
    children: [
      {
        path: 'overview',
        name: 'Overview',
        component: Overview
      },
      {
        path: 'user',
        name: 'User',
        component: UserProfile
      },
      {
        path: 'users',
        name: 'Usuários',
        component: Users
      },
      {
        path: 'table-list',
        name: 'Table List',
        component: TableList
      },
      {
        path: 'typography',
        name: 'Typography',
        component: Typography
      },
      {
        path: 'icons',
        name: 'Icons',
        component: Icons
      },
      {
        path: 'notifications',
        name: 'Notifications',
        component: Notifications
      },
      {
        path: 'upgrade',
        name: 'Upgrade to PRO',
        component: Upgrade
      }
    ]
  },
  { path: '*', component: NotFound }
];

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes;
