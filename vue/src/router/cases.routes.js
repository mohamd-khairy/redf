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
    path: "/cases/:id/review-doc",
    name: "cases-review_doc",
    meta: {
      title: "menu.review_Doc",
      auth: true,
    },
    component: () =>
      import(/* webpackChunkName: "cases-list" */ "@/pages/cases/reviewDoc"),
  },
  {
    path: "/cases/:id/request-review",
    name: "cases-request_review",
    meta: {
      title: "menu.request_review",
      auth: true,
    },
    component: () =>
    import(/* webpackChunkName: "cases-list" */ "@/pages/cases/RequestReview"),
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
    path: "/cases/:formType/create/:id",
    name: "cases-create",
    meta: {
      title: "menu.requests",
      auth: true,
      // permissions: 'read-event'
    },
    component: () =>
      import(
        /* webpackChunkName: "pipes-list" */ "@/pages/cases/CreateDynamic"
      ),
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
      import(/* webpackChunkName: "pipes-list" */ "@/pages/cases/EditDynamic"),
  },
  {
    path: "/cases/:formType/request-review/edit/:id",
    name: "request-review-edit",
    meta: {
      title: "menu.editCase",
      auth: true,
      // permissions: 'read-event'
    },
    component: () =>
      import(/* webpackChunkName: "pipes-list" */ "@/pages/cases/Edit"),
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
