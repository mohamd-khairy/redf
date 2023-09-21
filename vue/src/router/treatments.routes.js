export default [
  {
    path: "/treatments",
    redirect: "treatments/list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
  },
  {
    path: "/treatments/list",
    name: "treatments-list",
    meta: {
      auth: true,
      title: "menu.transactions",
    },
    component: () => import("@/pages/transactions/MainPage.vue"),
  },
  {
    path: "/treatments/list/:id",
    name: "treatments-show",
    meta: {
      auth: true,
      title: "menu.transactions",
    },
    component: () =>
      import(
        /* webpackChunkName: "treatments-show" */ "@/pages/transactions/ShowTreatment.vue"
      ),
  },
];
