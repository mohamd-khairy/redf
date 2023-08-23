export default [
  {
    path: "/cases/:id",
    name: "cases-list",
    meta: {
      title: "menu.requests",
      auth: true,
    },
    component: () =>
      import(/* webpackChunkName: "cases-list" */ "@/pages/cases/index"),
  },
  {
    path: "/cases/:id/form-types",
    name: "form-types",
    meta: {
      title: "menu.requests",
      auth: true,
    },
    exact: true,
    component: () =>
      import(/* webpackChunkName: "form-types" */ "@/pages/cases/FormTypes"),
  },
  {
    path: "/cases/1/create/:id",
    name: "cases-create",
    meta: {
      title: "menu.requests",
      auth: true,
      // permissions: 'read-event'
    },
    component: () =>
      import(/* webpackChunkName: "pipes-list" */ "@/pages/cases/CreateCase"),
  },
  {
    path: "/cases/2/create/:id",
    name: "cases-create",
    meta: {
      title: "menu.requests",
      auth: true,
      // permissions: 'read-event'
    },
    component: () =>
      import(/* webpackChunkName: "pipes-list" */ "@/pages/cases/CreateLegalAdvice"),
  },
  {
    path: "/cases/3/create/:id",
    name: "cases-create",
    meta: {
      title: "menu.requests",
      auth: true,
      // permissions: 'read-event'
    },
    component: () =>
      import(/* webpackChunkName: "pipes-list" */ "@/pages/cases/CreateReviewAndAudit"),
  },
  {
    path: "/cases/:formType/edit/:id",
    name: "cases-create",
    meta: {
      title: "menu.editCase",
      auth: true,
      // permissions: 'read-event'
    },
    component: () =>
      import(/* webpackChunkName: "pipes-list" */ "@/pages/cases/EditCase"),
  },
  {
    path: "/flights/show",
    name: "flights-show",
    meta: {
      auth: true,
    },
    component: () =>
      import(
        /* webpackChunkName: "flights-show" */ "@/pages/flights/ShowPage.vue"
      ),
  },
];
