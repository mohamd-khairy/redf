export default [
    {
      path: "/organizations",
      redirect: "organizations/list",
      meta: {
        auth: true,
        permissions: 'read-role'
      },
    },
    {
      path: "/organizations/list",
      name: "organizations-list",
      meta: {
        auth: true,
        permissions: 'read-role'
      },
      component: () =>
        import(
          /* webpackChunkName: "organizations-list" */ "@/pages/organizations/index.vue"
        ),
      meta: {
        title: ""
      }
    },
    {
      path: "/organizations/edit/:id",
      name: "organizations-edit",
      meta: {
        auth: true,
        permissions: 'update-role',
        title: ""
      },
      component: () =>
        import(
          /* webpackChunkName: "organizations-edit" */ "@/pages/organizations/edit.vue"
        ),
    },
    {
      path: "/organizations/create",
      name: "organizations-create",
      meta: {
        auth: true,
        permissions: 'create-role',
        title: ""
      },
      component: () =>
        import(
          /* webpackChunkName: "organizations-create" */ "@/pages/organizations/create.vue"
        ),
    },
    {
      path: "/organizations/:id",
      name: "organizations-view",
      meta: {
        auth: true,
        permissions: 'read-role',
        title: ""
      },
      component: () =>
        import(
          /* webpackChunkName: "organizations-view" */ "@/pages/organizations/show.vue"
        ),
    },
  ];
  