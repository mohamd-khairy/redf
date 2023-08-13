export default [
  {
    path: "/documents",
    redirect: "documents/list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
  },
  {
    path: "/documents/list",
    name: "documents-list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
    component: () =>
      import(
        /* webpackChunkName: "documents-list" */ "@/pages/documents/index.vue"
      ),
    meta: {
      title: "",
    },
  },
  {
    path: "/documents/edit/:id",
    name: "documents-edit",
    meta: {
      auth: true,
      permissions: "update-role",
      title: "",
    },
    component: () =>
      import(
        /* webpackChunkName: "documents-edit" */ "@/pages/documents/edit.vue"
      ),
  },
  {
    path: "/documents/create",
    name: "documents-create",
    meta: {
      auth: true,
      permissions: "create-role",
      title: "",
    },
    component: () =>
      import(
        /* webpackChunkName: "documents-create" */ "@/pages/documents/create.vue"
      ),
  },
  {
    path: "/documents/:id",
    name: "documents-view",
    meta: {
      auth: true,
      permissions: "read-role",
      title: "",
    },
    component: () =>
      import(
        /* webpackChunkName: "documents-view" */ "@/pages/documents/show.vue"
      ),
  },
];
