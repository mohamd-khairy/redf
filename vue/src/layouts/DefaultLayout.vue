<template>
  <div
    v-shortkey="['ctrl', '/']"
    class="d-flex flex-grow-1"
    @shortkey="onKeyup"
  >
    <!-- Navigation -->
    <v-navigation-drawer
      v-model="drawer"
      app
      floating
      class="elevation-0 navigation-cont custom-nav-cont"
      :right="$vuetify.rtl"
      :light="menuTheme === 'light'"
      :dark="menuTheme === 'dark'"
    >
      <!-- Navigation menu info -->
      <template v-slot:prepend>
        <div class="px-2 pt-2" style="height: 80px">
          <div
            class="title font-weight-bold text-center text-uppercase"
            style="height: 100%"
          >
            <!--            {{ product.name }}-->
            <img
              :class="!websiteLogo ? 'd-none' : ''"
              :src="websiteLogo"
              :alt="websiteName"
              style="
                width: 100%;
                max-width: 100%;
                height: auto;
                max-height: 100%;
                object-fit: cover;
              "
            />
            <!-- <img src="../assets/images/logo.png" alt=""
              style="width: 100%;max-width: 100%; height: auto;max-height: 100%; object-fit: cover;"> -->
          </div>
          <!--          <div class="overline grey&#45;&#45;text">{{ product.version }}</div>-->
        </div>
      </template>

      <!-- Navigation menu -->
      <main-menu
        :menu="navigationPermissions"
        :pinned="pinnedReports"
        :templates="navTemplates"
      />

      <!-- Navigation menu footer -->
      <template v-slot:append>
        <!-- Footer navigation links -->
        <div class="pa-1 text-center">
          <v-btn
            v-for="(item, index) in navigation.footer"
            :key="index"
            :href="item.href"
            :target="item.target"
            small
            text
          >
            {{ item.key ? $t(item.key) : item.text }}
          </v-btn>
        </div>
      </template>
    </v-navigation-drawer>

    <!-- Toolbar -->
    <v-app-bar
      app
      flat
      outlined
      prominent
      shrink-on-scroll
      :color="isToolbarDetached ? 'surface' : undefined"
      :light="toolbarTheme === 'light'"
      :dark="toolbarTheme === 'dark'"
      :height="breadcrumbs.length > 1 ? 40 : 60"
    >
      <v-card
        class="flex-grow-1 d-flex fill-height"
        :class="[isToolbarDetached ? 'pa-1 mt-3 mx-1' : 'pa-0 ma-0']"
        :flat="!isToolbarDetached"
      >
        <div class="d-flex flex-grow-1 align-center">
          <div class="d-flex flex-grow-1 align-start fill-height">
            <v-app-bar-nav-icon
              @click.stop="drawer = !drawer"
            ></v-app-bar-nav-icon>
            <v-app-bar-title>{{ pageTitle }}</v-app-bar-title>

            <!-- <v-spacer class="d-none d-lg-block"></v-spacer>

            <v-spacer class="d-block d-sm-none"></v-spacer> -->
            <v-spacer></v-spacer>

            <toolbar-apps />

            <div :class="[$vuetify.rtl ? 'ml-1' : 'mr-1']">
              <toolbar-notifications />
            </div>

            <toolbar-user />
          </div>
        </div>
      </v-card>
      <template v-slot:extension v-if="breadcrumbs.length > 1">
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </template>
    </v-app-bar>

    <v-main>
      <v-container class="fill-height" :fluid="!isContentBoxed">
        <v-layout>
          <slot></slot>
        </v-layout>
      </v-container>

      <v-footer app inset>
        <v-spacer></v-spacer>
        <div class="overline">
          Built with <v-icon small color="pink">mdi-heart</v-icon>
          <a
            class="text-decoration-none"
            href="https://wakeb.tech"
            target="_blank"
          >
            Wakeb</a
          >
        </div>
      </v-footer>
    </v-main>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

// navigation menu configurations
import config from "../configs";

import MainMenu from "../components/navigation/MainMenu";
import ToolbarUser from "../components/toolbar/ToolbarUser";
import ToolbarApps from "../components/toolbar/ToolbarApps";
import ToolbarLanguage from "../components/toolbar/ToolbarLanguage";
import ToolbarNotifications from "../components/toolbar/ToolbarNotifications";

export default {
  components: {
    MainMenu,
    ToolbarUser,
    ToolbarApps,
    ToolbarLanguage,
    ToolbarNotifications,
  },
  data() {
    return {
      drawer: null,
      showSearch: false,
      // pinnedReports: [],
      templateData: [],
      navigation: config.navigation,
      logo: "/images/logo.svg",
    };
  },
  created() {
    // this.getPinned();
    this.getSavedPinned();

    this.getNavTemplate();
  },
  updated() {
    // this.setFavicon(this.websitefavIcon);
  },
  computed: {
    ...mapState("app", [
      "product",
      "isContentBoxed",
      "menuTheme",
      "toolbarTheme",
      "isToolbarDetached",
      "breadcrumbs",
      "pageTitle",
      "navTemplates",
    ]),
    ...mapState("app", {
      websiteLogo: (state) => {
        if (!state.websiteLoginIcon) {
          return "/images/logo.png";
        }
        return state.websiteLoginIcon;
      },
      websitefavIcon: (state) => {
        const result = state.generalSettings.filter(
          (setting) => setting.key === "website_favorite_place_icon"
        );

        if (result.length > 0) {
          return result[0]["full_url"];
        }
        return "/images/logo.svg";
      },
      websiteName: (state) => {
        if (!state.websiteName) {
          return " ";
        }
        return state.websiteName;
      },
      // settingLoading: state => state.generalSettingLoading
    }),
    ...mapState("reports", ["pinnedReports"]),
    navigationPermissions() {
      let menus = [];
      let items = [];
      this.navigation.menu.forEach((nav) => {
        let isAllowed = localStorage
          .getItem("user_permissions")
          ?.includes(nav.permission);
        if (nav.items.length > 0) {
          items = nav.items.filter((item) => {
            let allowed = localStorage
              .getItem("user_permissions")
              ?.includes(item.permission);
            if (allowed) return item;
          });
          nav.items = items;
        }
        if (isAllowed || nav.items.length > 0) menus.push(nav);
      });
      return menus;
    },
  },
  methods: {
    ...mapActions("reports", ["getSavedPinned"]),
    ...mapActions("app", ["getNavTemplate"]),
    async getPinned() {
      try {
        const response = await this.$axios.get(`report/pinned`);
        const { pinneds } = response?.data.data;
        const pinnedData = pinneds?.data.map((pin) => ({
          title: pin.title,
          id: pin.id,
        }));
        this.pinnedReports = pinnedData;
      } catch (error) {
        console.error(error);
      }
    },
    // async getTemplateTypes() {
    //   try {
    //     const response = await this.$axios.get(`get-template-type`);
    //     const templates = response?.data.data;
    //     this.templateData = templates.map((template) => ({
    //       title: template.name,
    //       id: template.id,
    //     }));
    //   } catch (error) {
    //     console.error(error);
    //   }
    // },

    onKeyup(e) {
      this.$refs.search.focus();
    },
  },
};
</script>

<style>
.buy-button {
  box-shadow: 1px 1px 18px #ee44aa;
}

.v-navigation-drawer.custom-nav-cont {
  /* background: color-mix(in srgb, #009390, #9cacac 40%) !important; */
  background: #014c4f !important;
}

.v-navigation-drawer__prepend .title img {
  /* filter: brightness(0) invert(1); */
  /* background: #fff; */
}

.navigation-cont.custom-nav-cont .v-navigation-drawer__content {
  background: #014c4f !important;
}

.v-menu__content
  .theme--light.v-list-item:not(.v-list-item--active):not(
    .v-list-item--disabled
  ) {
  color: #000 !important;
}

.custom-nav-cont
  .theme--light.v-list-item:not(.v-list-item--active):not(
    .v-list-item--disabled
  ) {
  color: #fff !important;
}

.navigation-cont.custom-nav-cont .v-list--dense .v-list-item .v-icon::before {
  color: #fff !important;
}

.navigation-cont.custom-nav-cont .v-list--dense .v-list-item.active--text {
  background: #fff;
  color: #014c4f !important;
}

/* .v-list-group--active .v-list-item--active {} */

.navigation-cont.custom-nav-cont .v-list-group--active .v-list-item--active {
  background: #fff;
}

.navigation-cont.custom-nav-cont
  .v-list-group--active
  .v-list-item--active
  .v-icon::before {
  color: #014c4f !important;
}

.navigation-cont.custom-nav-cont
  .v-list-group--active
  .v-list-item--active
  .v-list-item {
  background: transparent !important;
  color: #014c4f !important;
}

.navigation-cont.custom-nav-cont
  .v-list--dense
  .v-list-item.active--text
  .v-icon::before {
  color: #014c4f !important;
}

.v-list-group--sub-group .v-list-group__header {
  margin-top: 5px;
}

.navigation-cont.custom-nav-cont
  .v-list-group.v-list-group--active
  .v-list-group__items
  .v-list-item--active:not(.v-list-group__header) {
  border-color: transparent !important;
  margin-top: 5px;
  color: #014c4f !important;
}
</style>
