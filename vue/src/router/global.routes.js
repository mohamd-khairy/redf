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
    }
]
