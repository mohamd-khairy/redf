export default [
  {
    path: "/users",
    redirect: "users-list",
    meta: {
      auth: true,
      permissions: "read-user",
      title: "menu.dashboard",
    },
  },
  {
    path: "/users/list",
    name: "users-list",
    meta: {
      auth: true,
      permissions: "read-user",
      title: "menu.dashboard",
    },
    component: () =>
      import(
        /* webpackChunkName: "users-list" */ "@/pages/users/UsersPage.vue"
      ),
  },
  {
    path: "/users/edit",
    name: "users-edit",
    meta: {
      auth: true,
      permissions: "update-user",
      title: "menu.dashboard",
    },
    component: () =>
      import(
        /* webpackChunkName: "users-edit" */ "@/pages/users/EditUserPage.vue"
      ),
  },
  {
    path: "/users/edit/:id",
    name: "users-list-edit",
    meta: {
      auth: true,
      permissions: "update-user",
      title: "menu.dashboard",
    },
    component: () =>
      import(
        /* webpackChunkName: "users-edit" */ "@/pages/users/EditUserListPage.vue"
      ),
  },
  {
    path: "/users/create",
    name: "users-create",
    meta: {
      auth: true,
      permissions: "create-user",
      title: "menu.dashboard",
    },
    component: () =>
      import(
        /* webpackChunkName: "users-create" */ "@/pages/users/CreateUserPage.vue"
      ),
  },
  {
    path: "/beneficiaries",
    name: "Beneficiaries",
    meta: {
      auth: true,
      title: "menu.beneficiaries",
    },
    component: () => import("@/pages/dashboard/Users/Main.vue"),
  },
  {
    path: "/activities",
    name: "Activities",
    meta: {
      auth: true,
      title: "menu.activities",
    },
    component: () => import("@/pages/dashboard/Activities/Main.vue"),
  },
];
