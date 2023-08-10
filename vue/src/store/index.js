import Vue from "vue";
import Vuex from "vuex";

// Global vuex
import AppModule from "./app";
import Users from "./users";
import Departments from "./departments";
import Auth from "./auth";
import Roles from "./roles";
import Settings from "./settings";
import Locations from "./locations";
import Types from "./types";
import Events from "./events";
import Reports from "./reports";
import drones from "./drones";
import Flights from "./flights";
import Organizations from "./organizations";
import Templates from "./templates";
import Cases from "./cases";
import Documents from "./documents";
import Tasks from "./tasks";

// Example Apps
import BoardModule from "../apps/board/store";
import EmailModule from "../apps/email/store";
import TodoModule from "../apps/todo/store";

Vue.use(Vuex);
Vue.directive("can", function (el, binding, vnode) {
  if (localStorage.getItem("user_permissions").indexOf(binding.value) !== -1) {
    return (vnode.elm.style.display = "inline-flex");
  } else {
    return (vnode.elm.style.display = "none");
    // return vnode.elm.hidden = true;
  }
});

/**
 * Main Vuex Store
 */
const store = new Vuex.Store({
  modules: {
    app: AppModule,
    "board-app": BoardModule,
    "email-app": EmailModule,
    "todo-app": TodoModule,
    users: Users,
    departments: Departments,
    roles: Roles,
    settings: Settings,
    locations: Locations,
    types: Types,
    events: Events,
    reports: Reports,
    drones: drones,
    flights: Flights,
    organizations: Organizations,
    templates: Templates,
    cases: Cases,
    auth: Auth,
    documents: Documents,
    tasks: Tasks,
  },
});

export default store;
