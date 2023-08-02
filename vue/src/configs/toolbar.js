export default {
  // apps quickmenu
  apps: [
    {
      icon: 'mdi-view-dashboard-outline',
      text: 'Email',
      key: 'menu.dashboard',
      link: '/dashboard/analytics'
    },
    // {
    //   icon: 'mdi-form-textbox',
    //   title: 'Modesl',
    //   key: 'menu.pipesModel',
    //   link: '/pipes'
    // }, {
    //   icon: 'mdi-message-outline',
    //   title: 'Stations',
    //   key: 'menu.stations',
    //   link: '/settings/stations'
    // }, {
    //   icon: 'mdi-view-column-outline',
    //   title: 'Board',
    //   key: 'menu.drones',
    //   link: '/settings/drones'
    // }
  ],

  // user dropdown menu
  user: [
    { icon: 'mdi-account-box-outline', key: 'menu.profile', text: 'Profile', link: '/users/edit' }
    // { icon: 'mdi-email-outline', key: 'menu.email', text: 'Email', link: '/apps/email' },
    // { icon: 'mdi-format-list-checkbox', key: 'menu.todo', text: 'Todo', link: '/apps/todo' },s
    // { icon: 'mdi-email-outline', key: 'menu.chat', text: 'Chat', link: '/apps/chat' },
    // { icon: 'mdi-email-outline', key: 'menu.board', text: 'Board', link: '/apps/ board' }
  ]
}
