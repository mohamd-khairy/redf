<template>
  <v-list dense>
    <div v-for="(item, index) in menu" :key="index">
      <v-list-item link v-if="item.items.length === 0 && item.text !== 'requests'" :to="item.link ? item.link : undefined"
        active-class="active--text">
        <v-list-item-icon>
          <v-icon>{{ item.icon }}</v-icon>
        </v-list-item-icon>
        <v-list-item-title>{{ $t(item.key) }}</v-list-item-title>
      </v-list-item>
      <v-list-group v-else-if="item.text === 'requests'" no-action exact-active-class="primary--text"
        class="reports-group-cont">
        <template v-slot:activator>
          <v-list-item class="pa-0">
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ $t(item.key) }}</v-list-item-title>
          </v-list-item>
        </template>
        <v-list-item v-for="(template, i) in templates" :key="i" exact :to="`/cases/${template.id}`">
          <v-list-item-icon>
            <v-icon small>mdi-scale-balance</v-icon>
          </v-list-item-icon>
          <v-list-item-title>{{ template.title }}</v-list-item-title>
        </v-list-item>
      </v-list-group>
      <v-list-group v-else-if="item.text === 'reports'" no-action exact-active-class="primary--text"
        class="reports-group-cont">
        <template v-slot:activator>
          <v-list-item class="pa-0">
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ $t(item.key) }}</v-list-item-title>
          </v-list-item>
        </template>
        <v-list-group no-action sub-group exact-active-class="primary--text">
          <template v-slot:activator>
            <v-list-item class="pa-0">
              <v-list-item-title>{{
                $t("general.settings")
              }}</v-list-item-title>
            </v-list-item>
          </template>
          <v-list-item v-for="(subItem, subIndex) in item.items" :key="subIndex" exact
            :to="subItem.link ? subItem.link : undefined">
            <v-list-item-icon>
              <v-icon>mdi-circle-small</v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ $t(subItem.key) }}</v-list-item-title>
          </v-list-item>
        </v-list-group>
        <v-list-item v-for="(pin, i) in pinned" :key="i" exact :to="`/reports/pinned/show/${pin.id}`">
          <v-list-item-icon>
            <v-icon small>mdi-pin</v-icon>
          </v-list-item-icon>
          <v-list-item-title>{{ pin.title }}</v-list-item-title>
        </v-list-item>
      </v-list-group>
      <v-list-group v-else no-action exact-active-class="primary--text">
        <template v-slot:activator>
          <v-list-item class="pa-0">
            <v-list-item-icon>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title>{{ $t(item.key) }}</v-list-item-title>
          </v-list-item>
        </template>
        <v-list-item v-for="(subItem, subIndex) in item.items" :key="subIndex"
          :to="subItem.link ? subItem.link : undefined" exact>
          <v-list-item-icon>
            <v-icon>mdi-circle-small</v-icon>
          </v-list-item-icon>
          <v-list-item-title>{{ $t(subItem.key) }}</v-list-item-title>
        </v-list-item>
      </v-list-group>
    </div>
<!--    <v-list-item link v-for="(template, i) in templates" :key="i" exact :to="`/template/${template.id}`">-->
<!--      <v-list-item-icon>-->
<!--        <v-icon small>mdi-pin</v-icon>-->
<!--      </v-list-item-icon>-->
<!--      <v-list-item-title>{{ template.title }}</v-list-item-title>-->
<!--    </v-list-item>-->
  </v-list>

  <!-- <v-list nav dense>
    <div v-for="(item, index) in menu" :key="index">
      <div v-if="item.key || item.text" class="pa-1 mt-2 overline">{{ item.key ? $t(item.key) : item.text }}</div>
      <nav-menu :menu="item" />
    </div>
  </v-list> -->
  <!-- <v-list dense class="nav-list">
    <div v-for="(item, index) in menu" :key="index" class="nav-item-cont">
      <v-list-item
        v-if="!item.items.length"
        class="nav-item-custom"
        :to="item.link"
        :exact="item.exact"
        :disabled="item.disabled"
        active-class="primary--text"
        link
      >
        <v-tooltip bottom>
          <template v-slot:activator="{ on }">
            <v-icon v-on="on" class="d-flex justify-center align-center">{{
              item.icon
            }}</v-icon>
          </template>
          <span class="d-flex justify-center">{{
            item.key ? $t(item.key) : item.text
          }}</span>
        </v-tooltip>
      </v-list-item>

      <v-menu v-else :offset-x="true" transition="slide-x-transition" content-class="nav-menu" min-width="200">
        <template v-slot:activator="{ on, attrs }">
          <v-list-item
            color="primary"
            v-bind="attrs"
            v-on="on"
            class="nav-item-custom"
            :class=" checkActive(item.items) ? 'v-list-item--active' : ''"
            :ripple="false"
            link


          >
            <v-tooltip bottom>
              <template v-slot:activator="{ on }">
                <v-icon v-on="on" class="d-flex justify-center align-center">{{
                  item.icon
                }}</v-icon>
              </template>
              <span class="d-flex justify-center">{{
                item.key ? $t(item.key) : item.text
              }}</span>
            </v-tooltip>
          </v-list-item>
        </template>
        <v-list class="nav-sub-list">
          <v-list-item
            v-for="(subItem, index) in item.items"
            :key="index"
            :to="subItem.link"

            :disabled="subItem.disabled"
            active-class="primary--text"
            link
          >
            <v-list-item-icon>
              <v-icon :small="true">{{ "mdi-circle-medium" }}</v-icon>
            </v-list-item-icon>
            <v-list-item-title>
              {{
                subItem.key ? $t(subItem.key) : subItem.text
              }}</v-list-item-title
            >
          </v-list-item>
        </v-list>
      </v-menu>
    </div>


  </v-list> -->
</template>

<script>
import { mapActions, mapState } from "vuex";
import NavMenu from "./NavMenu";

export default {
  components: {
    NavMenu
  },
  props: {
    menu: {
      type: Array,
      default: () => []
    },
    pinned: Array,
    templates: Array
  },
  data() {
    return {};
  },
  computed: {},
  methods: {},
  created() { }
};
</script>
<style>
/* .primary-list-active {
  background-color: var(--v-primary-base);
  color: var(--v-background-base);
} */
.v-application--is-rtl .v-list-group--no-action>.v-list-group__items>.v-list-item {
  padding-right: 34px;
}

.v-application .v-list-group--no-action>.v-list-group__items>.v-list-item {
  padding-left: 34px;
}

.reports-group-cont>.v-list-group__items>.v-list-item {
  padding: 0 24px !important;
}

.reports-group-cont>.v-list-group__items .v-list-group--sub-group .v-list-group__items>.v-list-item {
  padding: 0 40px !important;
}
</style>
