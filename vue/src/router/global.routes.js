export default [
    {
        path: '/departments',
        name: 'departments',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Departments/Departments.vue'),
        meta: {
            auth: true,
            title: "menu.departments",
            // layout: 'landing'
        },
    },
    {
        path: "/departments/create",
        name: 'departmentsCreate',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Departments/CreateDepartment.vue'),
        meta: {
            auth: true,
            title: "departments.createDepartment",
            // layout: 'landing'
        },
    },
    {
        path: "/departments/edit/:id",
        name: 'departmentsUpdate',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Departments/UpdateDepartment.vue'),
        meta: {
            auth: true,
            title: "departments.updateDepartment",
            // layout: 'landing'
        },
    },

    {
        path: "/templates/list",
        name: 'TemplatesList',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/List/Main.vue'),
        meta: {
            auth: true,
            title: "menu.templates",
            // layout: 'landing'
        },
    },
    {
        path: "/templates/list",
        name: 'TemplatesList',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/List/Main.vue'),
        meta: {
            auth: true,
            title: "menu.templates",
            // layout: 'landing'
        },
    },
    {
        path: "/templates/create",
        name: 'TemplatesCreate',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/List/CreateTemplate.vue'),
        meta: {
            auth: true,
            title: "templates.createTemplate",
            // layout: 'landing'
        },
    },
    {
        path: "/templates/edit/:id",
        name: 'TemplatesUpdate',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/Types/UpdateTemplate.vue'),
        meta: {
            auth: true,
            title: "templates.updateTemplate",
            // layout: 'landing'
        },
    },

    {
        path: "/templates/types",
        name: 'Templates',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/Types/Main.vue'),
        meta: {
            auth: true,
            title: "menu.templates",
            // layout: 'landing'
        },
    },
    {
        path: "/templates/types/create",
        name: 'templatesCreate',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/Types/CreateTemplate.vue'),
        meta: {
            auth: true,
            title: "templates.createTemplate",
            // layout: 'landing'
        },
    },
    {
        path: "/templates/types/edit/:id",
        name: 'templatesUpdate',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Templates/Types/UpdateTemplate.vue'),
        meta: {
            auth: true,
            title: "templates.updateTemplate",
            // layout: 'landing'
        },
    },

]
