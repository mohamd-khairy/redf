export default [
  {
    path: "/roles",
    redirect: "roles-list",
    meta: {
      auth: true,
      permissions: 'read-role'
    },
  },
  {
    path: "/roles/list",
    name: "roles-list",
    meta: {
      auth: true,
      permissions: 'read-role'
    },
    component: () =>
      import(
        /* webpackChunkName: "roles-list" */ "@/pages/roles/RolesPage.vue"
      ),
    meta: {
      title: ""
    }
  },
  {
    path: "/roles/edit/:id",
    name: "roles-edit",
    meta: {
      auth: true,
      permissions: 'update-role',
      title: ""
    },
    component: () =>
      import(
        /* webpackChunkName: "roles-edit" */ "@/pages/roles/EditRolePage.vue"
      ),
  },
  {
    path: "/roles/create",
    name: "roles-create",
    meta: {
      auth: true,
      permissions: 'create-role',
      title: ""
    },
    component: () =>
      import(
        /* webpackChunkName: "roles-create" */ "@/pages/roles/CreateRolePage.vue"
      ),
  },
  {
    path: "/roles/:id",
    name: "roles-view",
    meta: {
      auth: true,
      permissions: 'read-role',
      title: ""
    },
    component: () =>
      import(
        /* webpackChunkName: "roles-view" */ "@/pages/roles/ViewRolePage.vue"
      ),
  },
];
