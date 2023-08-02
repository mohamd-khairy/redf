export default [
  {
    path: "/reports/builder",
    name: "reports-builder",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "builder"
    },
    component: () =>
      import(/* webpackChunkName: "reports-builder" */ "@/pages/reports/builder/Builder.vue")
  },
  {
    path: "/reports/builder/show-builder",
    name: "reports-show-builder",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    props: true,
    component: () =>
      import(/* webpackChunkName: "reports-show-builder" */ "@/pages/reports/builder/ShowBuilder.vue")
  },
  {
    path: "/reports/drafted",
    name: "reports-drafted",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    component: () =>
      import(/* webpackChunkName: "reports-drafted" */ "@/pages/reports/draft/Drafted.vue"),

  },
  {
    path: "/reports/drafted/show/:id",
    name: "show-drafted",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    component: () =>
      import(/* webpackChunkName: "reports-draft" */ "@/pages/reports/draft/ShowDrafted.vue")
  },
  {
    path: "/reports/drafted/edit/:id",
    name: "reports-drafted-edit",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    component: () =>
      import(
        /* webpackChunkName: "draft-edit" */ "@/pages/reports/draft/EditDraft"
      )
  },
  {
    path: "/reports/pinned",
    name: "reports-pinned",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    component: () =>
      import(/* webpackChunkName: "reports-pinned" */ "@/pages/reports/pinned/Pinned.vue"),
  },
  {
    path: "/reports/pinned/edit/:id",
    name: "reports-pinned-edit",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    component: () =>
      import(
        /* webpackChunkName: "draft-edit" */ "@/pages/reports/pinned/EditPin"
      )
  },
  {
    path: "/reports/pinned/show/:id",
    name: "report-pinned-show",
    meta: {
      auth: true,
      permissions: 'read-setting',
      title: "dashboard"
    },
    component: () =>
      import(/* webpackChunkName: "show-pinned-report" */ "@/pages/reports/pinned/ShowPinned.vue")
  },

]
