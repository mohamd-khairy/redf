export default [
  {
    path: "/specializations",
    redirect: "specializations/list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
  },
  {
    path: "/specializations/list",
    name: "specializations-list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
    component: () =>
      import(
        /* webpackChunkName: "specializations-list" */ "@/pages/specializations/index.vue"
      ),
    meta: {
      title: "",
    },
  },
  {
    path: "/specializations/edit/:id",
    name: "specializations-edit",
    meta: {
      auth: true,
      permissions: "update-role",
      title: "",
    },
    component: () =>
      import(
        /* webpackChunkName: "specializations-edit" */ "@/pages/specializations/edit.vue"
      ),
  },
  {
    path: "/specializations/create",
    name: "specializations-create",
    meta: {
      auth: true,
      permissions: "create-role",
      title: "",
    },
    component: () =>
      import(
        /* webpackChunkName: "specializations-create" */ "@/pages/specializations/create.vue"
      ),
  },
  {
    path: "/specializations/:id",
    name: "specializations-view",
    meta: {
      auth: true,
      permissions: "read-role",
      title: "",
    },
    component: () =>
      import(
        /* webpackChunkName: "specializations-view" */ "@/pages/specializations/show.vue"
      ),
  },
];
