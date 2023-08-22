<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('cases.casesList') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
      <v-btn color="primary" to="/cases/create" v-can="'create-user'">
        {{ $t('cases.createUser') }}
      </v-btn>
    </div> -->
    <v-card>
      <!-- cases list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn color="primary" v-show="selected.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <!--              <v-list-item >-->
              <!--                <v-list-item-title>{{ $t('general.verify') }}</v-list-item-title>-->
              <!--              </v-list-item>-->
              <!--              <v-list-item >-->
              <!--                <v-list-item-title>{{ $t('general.disabled') }}</v-list-item-title>-->
              <!--              </v-list-item>-->
              <!--              <v-divider></v-divider>-->
              <v-list-item @click="deleteAllCases()">
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
            @keyup.enter="search(searchQuery)"
          ></v-text-field>

          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                class="mx-2"
                elevation="0"
                v-bind="attrs"
                v-on="on"
                :to="caseUrl"
                v-can="'create-user'"
              >
                <v-icon> mdi-plus </v-icon>
              </v-btn>
            </template>
            <span>{{ plusButtonTitle }}</span>
          </v-tooltip>
          <v-btn
            color="primary"
            class="me-1"
            elevation="0"
            :to="formTypesUrl"
            v-can="'create-user'"
          >
            {{ buttonName }}
          </v-btn>
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
        v-model="selected"
        :headers="headers"
        :items="items"
        :options.sync="options"
        class="flex-grow-1"
        :loading="isLoading"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="total"
      >
        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold">
            # <copy-label :text="item.id + ''" />
          </div>
        </template>

        <template v-slot:item.name="{ item }">
          <div>{{ item.name ?? "---" }}</div>
        </template>
        <template v-slot:item.form_request_number="{ item }">
          <div>{{ item.form_request_number ?? "---" }}</div>
        </template>

        <!--        <template v-slot:item.email="{ item }">-->
        <!--          <div class="d-flex align-center py-1">-->
        <!--            <v-avatar size="32" class="elevation-1 grey lighten-3 ml-2">-->
        <!--              <v-img :src="item.avatar" />-->
        <!--            </v-avatar>-->
        <!--            <div class="ml-1 caption font-weight-bold">-->
        <!--              <copy-label :text="item.email" />-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </template>-->

        <template v-slot:item.user="{ item }">
          <div>{{ item.user.name ?? "---" }}</div>
        </template>

        <template v-slot:item.assigner="{ item }">
          <div>
            {{ item.form_assigned_requests[0]?.assigner.name ?? "---" }}
          </div>
        </template>

        <!-- <template v-slot:item.role="{ item }">
          <v-chip
            label
            small
            v-for="(item, index) in item.roles"
            :key="index"
            class="font-weight-bold"
            :color="item.display_name === 'Admin' ? 'primary' : undefined"
          >
            {{ item.display_name }}
          </v-chip>
        </template> -->

        <template v-slot:item.created_at="{ item }">
          <div>{{ item.created_at | formatDate("lll") }}</div>
        </template>

        <template v-slot:item.action="{ item }">
          <div class="actions">
            <v-btn color="primary" icon @click="openActionDialog(item.id)">
              <v-icon>mdi-pencil</v-icon>
            </v-btn>
            <v-btn color="primary" icon @click="openCasePreviewDialog(item.id)">
              <v-icon>mdi-eye</v-icon>
            </v-btn>

            <v-btn
              title="Assign"
              @click="openAssignDialog(item.id)"
              elevation="0"
              color="primary"
              icon
            >
              <v-icon>mdi-at</v-icon>
            </v-btn>
            <v-btn
              color="primary"
              icon
              :to="`/cases/${currentPageId}/edit/${item.id}`"
              v-can="'update-user'"
            >
              <v-icon>mdi-open-in-new</v-icon>
            </v-btn>
            <v-btn
              color="error"
              icon
              @click.prevent="deleteItem(item.id)"
              v-can="'delete-user'"
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
      <CasePreviewDialog
        :dialogVisible="casePrevDialog"
        :case-id="formId"
        v-if="casePrevDialog"
        @closePrevDialog="casePrevDialog = false"
      />
      <AddAction
        :dialogVisible="addActionDialog"
        :formRequestId="formId"
        v-if="addActionDialog"
        @closeActionDialog="addActionDialog = false"
      />
      <assign v-model="dialog" :id="formId"></assign>
    </v-card>
  </div>
</template>

<script>
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import CasePreviewDialog from "../../components/cases/CasePreviewDialog.vue";
import Assign from "../../components/cases/Assign";
import AddAction from "../../components/cases/AddAction.vue";
export default {
  components: {
    CasePreviewDialog,
    Assign,
    CopyLabel,
    emptyDataSvg,
    AddAction,
  },
  data() {
    return {
      currentPageId: null,
      page: 1,
      total: 0,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.requests"),
          disabled: false,
          href: "#",
        },
      ],

      searchQuery: "",
      selected: [],
      items: [],
      headers: [
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("tables.caseNumber"), value: "form_request_number" },
        { text: this.$t("tables.name"), value: "name" },
        { text: this.$t("tables.user"), value: "user" },
        { text: this.$t("tables.assigner"), value: "assigner" },
        { text: this.$t("tables.status"), value: "status" },
        { text: this.$t("tables.created"), value: "created_at" },
        { text: "", sortable: false, align: "right", value: "action" },
      ],
      formTypesUrl: "",
      caseUrl: "",
      buttonName: "",
      plusButtonTitle: "",
      formId: 0,
      dialog: false,
      casePrevDialog: false,
      addActionDialog: false,
    };
  },
  watch: {
    selected(val) {},
    options: {
      handler() {
        this.open();
      },
    },
    deep: true,
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
    ...mapState("cases", ["formRequests"]),
    ...mapState("app", ["navTemplates"]),
  },
  created() {
    let { id } = this.$route.params;
    this.currentPageId = id;
    this.formTypesUrl = `/cases/${id}/form-types`;
    this.caseUrl = `/cases/${id}/create/${id}`;
  },
  mounted() {
    // this.open()
  },
  methods: {
    ...mapActions("cases", ["getFormRequests", "deleteForm", "deleteAll"]),
    ...mapActions("app", ["setBreadCrumb"]),
    search() {},
    setCurrentBread() {
      const currentPage = this.navTemplates.find((nav) => {
        return nav.id === +this.currentPageId;
      });
      this.plusButtonTitle = `انشاء ${currentPage?.title || ""}`;
      this.buttonName = `نماذج ${currentPage?.title || ""}`;
      if (currentPage) {
        this.breadcrumbs.push({
          text: currentPage.title,
          disabled: false,
          href: "#",
        });
      }
      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: this.$t("cases.casesList"),
      });
    },
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";
      let { id } = this.$route.params;
      let data = {
        template_id: id,
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? "",
      };
      this.getFormRequests(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.items = this.formRequests.data;
            this.total = this.formRequests.total;
            this.numberOfPages = this.formRequests.last_page;
          } else {
            this.items = this.formRequests;
            this.total = this.formRequests.length;
            this.numberOfPages = 1;
          }
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    openAssignDialog(id) {
      this.dialog = true;
      this.formId = id;
    },
    openActionDialog(id) {
      this.addActionDialog = true;
      this.formId = id;
    },
    openCasePreviewDialog(id) {
      this.formId = id;
      this.casePrevDialog = true;
    },
    async deleteItem(id) {
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");

      if (isConfirmed) {
        this.isLoading = true;
        this.deleteForm(id)
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

    async deleteAllCases() {
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");
      if (isConfirmed) {
        if (this.selected.length) {
          this.selected.forEach((item) => {
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
