export default [{
  path: '/cases/:id',
  name: 'cases-list',
  meta: {
    auth: true
  },
  component: () => import(/* webpackChunkName: "cases-list" */ '@/pages/cases/index')
},
{
  path: '/cases/form-types/:id',
  name: 'form-types',
  meta: {
    auth: true
  },
  exact: true,
  component: () => import(/* webpackChunkName: "form-types" */ '@/pages/cases/FormTypes')
},
{
  path: '/cases/create/:id',
  name: 'cases-create',
  meta: {
    auth: true,
    // permissions: 'read-event'
  },
  component: () => import(/* webpackChunkName: "pipes-list" */ '@/pages/cases/CreateCase')
},
{
  path: '/flights/show',
  name: 'flights-show',
  meta: {
    auth: true
  },
  component: () => import(/* webpackChunkName: "flights-show" */ '@/pages/flights/ShowPage.vue')
}
]
