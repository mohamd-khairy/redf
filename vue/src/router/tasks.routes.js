export default [
  {
    path: "/tasks",
    redirect: "tasks/list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
  },
  {
    path: "/tasks/list",
    name: "tasks-list",
    meta: {
      auth: true,
      permissions: "read-role",
    },
    component: () =>
      import(/* webpackChunkName: "tasks-list" */ "@/pages/tasks/index.vue"),
    meta: {
      title: "المهام",
    },
  },
  {
    path: "/tasks/edit/:id",
    name: "tasks-edit",
    meta: {
      auth: true,
      permissions: "update-role",
      title: "المهام",
    },
    component: () =>
      import(/* webpackChunkName: "tasks-edit" */ "@/pages/tasks/edit.vue"),
  },
  {
    path: "/tasks/create",
    name: "tasks-create",
    meta: {
      auth: true,
      permissions: "create-role",
      title: "المهام",
    },
    component: () =>
      import(/* webpackChunkName: "tasks-create" */ "@/pages/tasks/create.vue"),
  },
  {
    path: "/tasks/:id",
    name: "tasks-view",
    meta: {
      auth: true,
      permissions: "read-role",
      title: "المهام",
    },
    component: () =>
      import(/* webpackChunkName: "tasks-view" */ "@/pages/tasks/show.vue"),
  },
];
