import Vue from "vue";
import Router from "vue-router";

// Routes
import AppsRoutes from "./apps.routes";
import UIRoutes from "./ui.routes";
import PagesRoutes from "./pages.routes";
import UsersRoutes from "./users.routes";
import RolesRoutes from "./roles.routes";
import EcommerceRoutes from "./ecommerce.routes";
import LandingRoutes from "./landing.routes";
import SettingsRoutes from "./settings.routes";
import PipesRoutes from "./pipes.routes";
import FlightsRoutes from "./flights.routes";
import ReportsRoutes from "./reports.routes";
import OrganizationsRoutes from "./organizations.routes";
import store from "@/store";
import i18n from "../plugins/vue-i18n";

Vue.use(Router);

export const routes = [
  {
    path: "/",
    redirect: "/dashboard/analytics"
  },
  {
    path: "/dashboard/analytics",
    name: "dashboard-analytics",
    component: () =>
      import(
        /* webpackChunkName: "dashboard" */ "@/pages/dashboard/DashboardPage.vue"
      ),
    meta: {
      title: "dashboard"
    }
  },
  ...AppsRoutes,
  ...UIRoutes,
  ...PagesRoutes,
  ...UsersRoutes,
  ...RolesRoutes,
  ...EcommerceRoutes,
  ...LandingRoutes,
  ...SettingsRoutes,
  ...PipesRoutes,
  ...FlightsRoutes,
  ...ReportsRoutes,
  ...OrganizationsRoutes,

  {
    path: "/blank",
    name: "blank",
    component: () =>
      import(/* webpackChunkName: "blank" */ "@/pages/BlankPage.vue"),
    meta: {
      title: "blank"
    }
  },
  {
    path: "*",
    name: "error",
    component: () =>
      import(/* webpackChunkName: "error" */ "@/pages/error/NotFoundPage.vue"),
    meta: {
      layout: "error",
      title: "errorNotFound"
    }
  }
];

const router = new Router({
  mode: "history",
  base: process.env.BASE_URL || "/",
  // base:'/drones/',
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) return savedPosition;

    return { x: 0, y: 0 };
  },
  routes
});

/**
 * Before each route update
 */
router.beforeEach((to, from, next) => {
  const token = store.state.auth.token;

  if (!token && to.meta.auth) {
    return next({ name: "auth-signin" });
  }

  if (to.meta.permissions && to.meta.permissions.length > 0) {
    let isAllowed = localStorage.getItem('user_permissions').includes(to.meta.permissions)
    if (!isAllowed) return next('auth-signin')
  }
  document.title = i18n.t(`menu.${to.meta.title}`)
  return next();
});

/**
 * After each route update
 */
router.afterEach((to, from) => { });

export default router;
