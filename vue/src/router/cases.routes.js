export default [{
  path: '/cases/:id',
  name: 'cases-list',
  meta: {
    auth: true
  },
  component: () => import(/* webpackChunkName: "cases-list" */ '@/pages/cases/index')
},
// {
//   path: '/cases/form-types',
//   name: 'form-types',
//   meta: {
//     auth: true
//   },
//   exact: true,
//   component: () => import(/* webpackChunkName: "form-types" */ '@/pages/cases/FormTypes')
// },
{
  path: '/cases/:id',
  name: 'cases-live',
  meta: {
    auth: true,
    permissions: 'read-event'
  },
  component: () => import(/* webpackChunkName: "pipes-list" */ '@/pages/flights/FlightLive.vue')
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
