<template>
  <div class="d-flex flex-column flex-grow-1">
   
     <v-card>
      <!-- users list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selectedOrganizations.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
             
              <v-list-item @click="deleteAllOrganization()">
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
            class="flex-grow-1 mr-md-2"
            solo
            hide-details
            dense
            clearable
            :placeholder="$t('general.search')"
            @keyup.enter="searchOrganization(searchQuery)"
          ></v-text-field>

          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                class="mx-2 "
                elevation="0"
                v-bind="attrs"
                v-on="on"
                to="/organizations/create"
                
              >
                <v-icon>
                  mdi-plus
                </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("organizations.createOrg") }}</span>
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
        v-model="selectedOrganizations"
        :headers="headers"
        :items="organizationItems"
        :options.sync="options"
        class="flex-grow-1"
        :loading="isLoading"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="totalOrganizations"
      >
        <template v-slot:item.id="{ item }">
          {{item.id}}
        </template>
        <template v-slot:item.name="{ item }">
          {{item.name}}
        </template>
         <template v-slot:item.description="{ item }">
          {{item.description}}
        </template>
         <template v-slot:item.created_at="{ item }">
          <div>{{ item.created_at | formatDate("lll") }}</div>
        </template>
       

        <template v-slot:item.action="{ item }">
          <div class="actions">
            <v-btn
              color="primary"
              icon
              :to="`/organizations/edit/${item.id}`"
              
            >
              <v-icon>mdi-open-in-new</v-icon>
            </v-btn>
            <v-btn
              color="error"
              icon
              @click.prevent="deleteItem(item.id)"
              
            >
              <v-icon>mdi-delete</v-icon>
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
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
  components: {
   emptyDataSvg
  },
  data() {
    return {
      page: 1,
      totalOrganizations: 0,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("organizations.organizationsManagement"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("organizations.organizationsList")
        }
      ],

      searchQuery: "",
      selectedOrganizations: [],
      organizationItems: [],
      headers: [
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("tables.name"), value: "name" },
        { text: this.$t("tables.description"), value: "description" },
        { text: this.$t("tables.created"), value: "created_at" },
        { text: "", sortable: false, align: "right", value: "action" }
      ]
    };
  },
  watch: {
    options: {
      handler() {
        this.open();
      }
    },
    searchQuery() {
      this.open();
    }
  },
  computed: {
    ...mapState("organizations", ['organizations'])
   
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("organizations.organizationsList")
    });
    
  },

  methods: {
   
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("organizations", ["getOrganizations", "deleteOrganization", "deleteAll"]),
     open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";

      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? ""
      };
      this.getOrganizations(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.organizationItems = this.organizations.data;
            this.totalOrganizations = this.organizations.total;
            this.numberOfPages = this.organizations.last_page;
          } else {
            this.organizationItems = this.organizations;
            this.totalOrganizations = this.organizations.length;
            this.numberOfPages = 1;
          }
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    searchUser() {},
    async deleteItem(id){
      const { isConfirmed } = await ask(this.$t("organizations.confirmDeleteOrg"), "warning");

      if (isConfirmed) {
        this.isLoading = true;
        this.deleteOrganization(id)
          .then(response => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },
    async deleteAllOrganization(){
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask(this.$t("organizations.confirmDeleteSelectedOrg"), "warning");
      if (isConfirmed) {
        if (this.selectedOrganizations.length) {
          this.selectedOrganizations.forEach(item => {
            ids.push(item.id);
          });
        }
        data = {
          ids: ids,
          action: "delete",
          value: 1
        };
        this.isLoading = true;
        this.deleteAll(data)
          .then(response => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },
    searchOrganization(){}
   
  }
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
