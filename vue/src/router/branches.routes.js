export default [
  {
    path: "/branches",
    redirect: "branches/list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
  },
  {
    path: "/branches/list",
    name: "branches-list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
    component: () =>
      import(
        /* webpackChunkName: "branches-list" */ "@/pages/branches/index.vue"
      ),
    meta: {
      title: "الفروع",
    },
  },
  {
    path: "/branches/edit/:id",
    name: "branches-edit",
    meta: {
      auth: true,
      permissions: "update-role",
      title: "الفروع",
    },
    component: () =>
      import(
        /* webpackChunkName: "branches-edit" */ "@/pages/branches/edit.vue"
      ),
  },
  {
    path: "/branches/create",
    name: "branches-create",
    meta: {
      auth: true,
      permissions: "create-role",
      title: "الفروع",
    },
    component: () =>
      import(
        /* webpackChunkName: "branches-create" */ "@/pages/branches/create.vue"
      ),
  },
  {
    path: "/branches/:id",
    name: "branches-view",
    meta: {
      auth: true,
      permissions: "read-role",
      title: "الفروع",
    },
    component: () =>
      import(
        /* webpackChunkName: "branches-view" */ "@/pages/branches/show.vue"
      ),
  },
];
