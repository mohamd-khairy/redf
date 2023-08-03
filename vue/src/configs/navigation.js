export default {
  // main navigation - side menu
  menu: [
    {
      icon: "mdi-view-dashboard-outline",
      key: "menu.dashboard",
      text: "dashboard",
      link: "/dashboard/analytics",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-list-box",
      text: "departments",
      key: "menu.departments",
      link: "/departments",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-office-building",
      text: "orgnaizations",
      key: "menu.organizations",
      link: "/organizations",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-animation",
      text: "projects",
      key: "menu.projects",
      link: "/projects",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-gavel",
      text: "cases",
      key: "menu.cases",
      link: "/cases",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-scale-balance",
      text: "legal_advice",
      key: "menu.legal_advice",
      link: "/legal_advice",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-file-document",
      text: "tasks",
      key: "menu.tasks",
      link: "/tasks",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-file-document-outline",
      text: "documents",
      key: "menu.documents",
      link: "/documents",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-hand-coin-outline",
      text: "beneficiaries",
      key: "menu.beneficiaries",
      link: "/beneficiaries",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-history",
      text: "log",
      key: "menu.log",
      link: "/log",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-file-document-edit-outline",
      text: "review_and_audit",
      key: "menu.review_and_audit",
      link: "/review_and_audit",
      permission: "read-role",
      items: []
    },
    {
      icon: "mdi-form-textbox",
      text: "models",
      key: "menu.pipesModel",
      link: "/models",
      permission: "read-role",
      items: []
    },

    {
      icon: "mdi-chart-line",
      text: "reports",
      key: "menu.reports",
      // link: "/reports",
      items: [
        {
          icon: "mdi-account-multiple-outline",
          text: "Builder",
          key: "menu.builder",
          permission: "read-role",
          link: "/reports/builder"
        },
        {
          icon: "mdi-account-multiple-outline",
          text: "Drafted",
          key: "menu.drafted",
          permission: "read-role",
          link: "/reports/drafted"
        },
        {
          icon: "mdi-account-multiple-outline",
          text: "Pinned",
          key: "menu.pinned",
          permission: "read-role",
          link: "/reports/pinned"
        }
      ]
    },
    // {
    //   icon: "mdi-airplane",
    //   text: "flights",
    //   key: "menu.flights",

    //   permission: "read-flight",
    //   items: [
    //     {
    //       icon: "mdi-account-multiple-outline",
    //       text: "flights table",
    //       key: "menu.flights_table",
    //       permission: "read-flight",
    //       link: "/flights",
    //     },
    //     {
    //       icon: "mdi-account-multiple-outline",
    //       text: "flights table",
    //       key: "menu.live_stream",
    //       permission: "read-flight",
    //       link: "/flights/locations",
    //     },
    //   ]
    // },
    {
      icon: "mdi-account-multiple-outline",
      text: "Users Management",
      key: "menu.usersManagement",
      items: [
        {
          icon: "mdi-account-multiple-outline",
          text: "Users",
          key: "menu.users",
          permission: "read-user",
          link: "/users/list"
        },
        {
          icon: "mdi-account-multiple-outline",
          text: "Roles",
          key: "menu.roles",
          permission: "read-role",
          link: "/roles/list"
        }
      ]
    },
    {
      icon: "mdi-cog-outline",
      key: "menu.settings",
      text: "Settings",
      items: [
        {
          key: "menu.general",
          text: "General",
          link: "/settings/general",
          permission: "read-setting"
        },
        {
          key: "menu.mailTemplate",
          text: "Mail Template",
          link: "/settings/mail-template",
          permission: "read-setting"
        },
        {
          key: "menu.mailServer",
          text: "Mail Server",
          link: "/settings/mail-server",
          permission: "read-setting"
        },
        // { key: 'menu.sms', link: '/settings/sms' },
        {
          key: "menu.stations",
          text: "Station",
          link: "/settings/stations",
          permission: "read-location"
        },
        {
          key: "menu.detection_types",
          text: "Detection Type",
          link: "/settings/detection-types",
          permission: "read-type"
        },
        {
          key: "menu.drones",
          text: "Drones",
          link: "/settings/drones",
          permission: "read-drone"
        },
        {
          key: "menu.logs",
          text: "Logs",
          link: "/settings/logs",
          permission: "read-drone"
        }
      ]
    }
  ]
};
