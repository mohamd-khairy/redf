<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t("types.detectionTypesList") }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn
        color="primary"
        to="/settings/detection-types/create"
        v-can="'create-type'"
      >
        {{ $t("types.createDetectionType") }}
      </v-btn>
    </div> -->
    <v-card>
      <!-- Types list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selectedTypes.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <v-list-item @click="deleteAllTypes()">
                <v-list-item-title>{{
                  $t("general.delete")
                }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
        <v-col cols="6" class="d-flex text-right align-center">
          <v-text-field
            v-model="searchQuery"
            append-icon="mdi-magnify"
            class="flex-grow-1 mx-2"
            solo
            hide-details
            dense
            clearable
            :placeholder="$t('general.search')"
            @keyup.enter="searchType(searchQuery)"
          ></v-text-field>

          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                class="mx-2"
                elevation="0"
                v-bind="attrs"
                v-on="on"
                to="/settings/detection-types/create"
                v-can="'create-type'"
              >
                <v-icon> mdi-plus </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("types.createDetectionType") }}</span>
          </v-tooltip>
          <v-btn
            :loading="isLoading"
            icon
            @click.prevent="open()"
            small
            class="ml-2"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-data-table
        show-select
        v-model="selectedTypes"
        :headers="headers"
        :items="typeItems"
        :options.sync="options"
        class="flex-grow-1"
        :loading="isLoading"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="totalTypes"
      >
        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold">
            # <copy-label :text="item.id + ''" />
          </div>
        </template>

        <!--        <template v-slot:item.created_at="{ item }">-->
        <!--          <div>{{ item.created_at | formatDate("lll") }}</div>-->
        <!--        </template>-->

        <template v-slot:item.action="{ item }">
          <div class="actions">
            <v-btn
              icon
              color="primary"
              :to="`/settings/detection-types/edit/${item.id}`"
              v-can="'update-type'"
            >
              <v-icon>mdi-open-in-new</v-icon>
            </v-btn>
            <v-btn
              icon
              color="error"
              @click.prevent="deleteItem(item.id)"
              v-can="'delete-type'"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </div>
        </template>

        <template v-slot:no-data>
          <div class="text-center my-2 primary--text" color="primary">
            <emptyDataSvg></emptyDataSvg>
            <div class="dt-no_data">
              {{ $t("general.no_data_available") }}
            </div>
          </div>
        </template>
      </v-data-table>
    </v-card>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import CopyLabel from "../../../components/common/CopyLabel";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
  name: "DetectionTypesPage",
  components: {
    CopyLabel,
    emptyDataSvg,
  },
  data() {
    return {
      page: 1,
      totalTypes: 0,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.detection_types"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("types.detectionTypesList"),
        },
      ],

      searchQuery: "",
      selectedTypes: [],
      typeItems: [],
      headers: [
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("tables.name"), value: "name" },
        { text: this.$t("types.nameOnScreen"), value: "display_name" },
        // { text: this.$t('tables.created'), value: 'created_at' },
        { text: "", sortable: false, align: "right", value: "action" },
      ],
    };
  },
  watch: {
    selectedTypes(val) {},
    options: {
      handler() {
        this.open();
      },
    },
    deep: true,
    searchQuery() {
      this.open();
    },
  },
  computed: {
    ...mapState("types", ["types"]),
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("types.detectionTypesList"),
    });
  },
  methods: {
    ...mapActions("types", [
      "getDetectionTypes",
      "deleteDetectionType",
      "deleteAll",
    ]),
    ...mapActions("app", ["setBreadCrumb"]),
    searchType() {},
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";
      let data = {
        search: this.searchQuery ?? "",
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? "",
      };
      this.getDetectionTypes(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.typeItems = this.types.data;
            this.totalTypes = this.types.total;
            this.numberOfPages = this.types.last_page;
          } else {
            this.typeItems = this.types;
            this.totalTypes = this.types.length;
            this.numberOfPages = 1;
          }
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    async deleteItem(id) {
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");

      if (isConfirmed) {
        this.isLoading = true;
        this.deleteDetectionType(id)
          .then((response) => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },

    async deleteAllTypes() {
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");
      if (isConfirmed) {
        if (this.selectedTypes.length) {
          this.selectedTypes.forEach((item) => {
            ids.push(item.id);
          });
        }
        data = {
          ids: ids,
          action: "delete",
          value: 1,
        };
        this.isLoading = true;
        this.deleteAll(data)
          .then((response) => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
  transition: all 0.3s ease;
}
.slide-fade-leave-active {
  transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}
.slide-fade-enter,
.slide-fade-leave-to {
  transform: translateX(10px);
  opacity: 0;
}
</style>
