<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-card>
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selectedDocuments.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <v-list-item @click="deleteAllDocuments()">
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
            @keyup.enter="searchDocument(searchQuery)"
          ></v-text-field>

          <!-- <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                class="mx-2"
                elevation="0"
                v-bind="attrs"
                v-on="on"
                to="/documents/create"
              >
                <v-icon> mdi-plus </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("documents.createDoc") }}</span>
          </v-tooltip> -->
          <v-btn
            :loading="isLoading"
            icon
            @click.prevent="open()"
            small
            class="ms-2"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-data-table
        show-select
        v-model="selectedDocuments"
        :headers="headers"
        :items="documentItems"
        :options.sync="options"
        class="flex-grow-1"
        :loading="isLoading"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="totalDocuments"
      >
        <template v-slot:item.id="{ item }">
          {{ item.id }}
        </template>
        <template v-slot:item.name="{ item }">
          {{ item.name }}
        </template>
        <template v-slot:item.status="{ item }">
          {{ item.status }}
        </template>
        <template v-slot:item.type="{ item }">
          {{ item.type }}
        </template>
        <template v-slot:item.priority="{ item }">
          {{ item.priority }}
        </template>
        <template v-slot:item.file="{ item }">
          <a :href="item.path" target="_blank">
            <v-icon> mdi-file </v-icon>
          </a>
        </template>
        <template v-slot:item.start_date="{ item }">
          <div>{{ item.start_date | formatDate("lll") }}</div>
        </template>
        <template v-slot:item.end_date="{ item }">
          <div>{{ item.end_date | formatDate("lll") }}</div>
        </template>

        <template v-slot:item.action="{ item }">
          <div class="actions">
            <v-btn color="primary" icon :to="`/documents/edit/${item.id}`">
              <v-icon>mdi-open-in-new</v-icon>
            </v-btn>
            <v-btn color="error" icon @click.prevent="deleteItem(item.id)">
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
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
  components: {
    emptyDataSvg,
  },
  data() {
    return {
      page: 1,
      totalDocuments: 0,
      currentPageId: null,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.review_Doc"),
        },
      ],

      searchQuery: "",
      selectedDocuments: [],
      documentItems: [],
      headers: [
        { text: this.$t("tables.caseNumber"), value: "id" },
        { text: this.$t("cases.caseName"), value: "name" },
        { text: this.$t("cases.status"), value: "status" },
        { text: this.$t("tables.assigned_to"), value: "priority" },
        { text: this.$t("tables.file"), value: "file" },
        { text: this.$t("tables.added_date"), value: "start_date" },
        {
          text: this.$t("tables.actions"),
          sortable: false,
          align: "right",
          value: "action",
        },
      ],
    };
  },
  watch: {
    options: {
      handler() {
        this.open();
      },
    },
    searchQuery() {
      this.open();
    },
    navTemplates() {
      this.setCurrentBread();
    },
    currentPageId() {
      this.setCurrentBread();
    },
  },
  computed: {
    ...mapState("documents", ["documents"]),
    ...mapState("app", ["navTemplates"]),
  },
  created() {
    let { id } = this.$route.params;
    this.currentPageId = id;
    // this.setBreadCrumb({
    //   breadcrumbs: this.breadcrumbs,
    //   pageTitle: this.$t("documents.documentsList"),
    // });
  },

  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("documents", ["getDocuments", "deleteDocument", "deleteAll"]),
    setCurrentBread() {
      const currentPage = this.navTemplates.find((nav) => {
        return nav.id === +this.currentPageId;
      });

      if (currentPage) {
        this.breadcrumbs.unshift({
          text: currentPage.title,
          disabled: false,
          href: "#",
        });
      }
      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: currentPage.title,
      });
    },
    open() {
      // this.isLoading = true;
      const { id } = this.$route.params;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";

      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? "",
      };
      // this.getDocuments(data)
      //   .then(() => {
      //     this.isLoading = false;
      //     if (itemsPerPage != -1) {
      //       this.documentItems = this.documents.data;
      //       this.totalDocuments = this.documents.total;
      //       this.numberOfPages = this.documents.last_page;
      //     } else {
      //       this.documentItems = this.documents;
      //       this.totalDocuments = this.documents.length;
      //       this.numberOfPages = 1;
      //     }
      //   })
      //   .catch(() => {
      //     this.isLoading = false;
      //   });
    },
    async deleteItem(id) {
      const { isConfirmed } = await ask(
        this.$t("documents.confirmDeleteDoc"),
        "warning"
      );

      if (isConfirmed) {
        this.isLoading = true;
        this.deleteDocument(id)
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
    async deleteAllDocuments() {
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask(
        this.$t("documents.confirmDeleteSelectedOrg"),
        "warning"
      );
      if (isConfirmed) {
        if (this.selectedDocuments.length) {
          this.selectedDocuments.forEach((item) => {
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
    searchDocument() {},
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
