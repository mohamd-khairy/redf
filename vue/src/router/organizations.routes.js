export default [
    {
      path: "/organizations",
      redirect: "organizations/list",
      meta: {
        auth: true,
        permissions: 'read-role',
        title: 'menu.organizations',
      },
    },
    {
      path: "/organizations/list",
      name: "organizations-list",
      meta: {
        title: 'menu.organizations',
        auth: true,
        permissions: 'read-role',
      },
      component: () =>
        import(
          /* webpackChunkName: "organizations-list" */ "@/pages/organizations/index.vue"
        ),
    },
    {
      path: "/organizations/edit/:id",
      name: "organizations-edit",
      meta: {
        title: 'menu.organizations',
        auth: true,
        permissions: 'update-role',
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
        title: 'menu.organizations',
        auth: true,
        permissions: 'create-role',
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
        title: 'menu.organizations',
        auth: true,
        permissions: 'read-role',
      },
      component: () =>
        import(
          /* webpackChunkName: "organizations-view" */ "@/pages/organizations/show.vue"
        ),
    },
  ];
