export default [
    {
        path: '/departments',
        name: 'departments',
        component: () => import(/* webpackChunkName: "landing-home" */ '@/pages/dashboard/Departments.vue'),
        meta: {
            title: "departments"
            // layout: 'landing'
        }
    },
]
