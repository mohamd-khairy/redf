<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center p-3 pt-0">
      <div>
        <div class="display-1">{{ pageTitle }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
    </div> -->
    <v-card class="mb-60">
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
          <v-text-field v-model="searchQuery" append-icon="mdi-magnify" class="flex-grow-1 mr-md-2" solo hide-details
                        dense clearable :placeholder="$t('general.search')" @keyup.enter="search(searchQuery)"></v-text-field>

          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn color="primary" class="mx-2" elevation="0" v-bind="attrs" v-on="on" :to="caseUrl"
                     :disabled="currentPageId > 3" v-can="'create-user'">
                <v-icon> mdi-plus </v-icon>
              </v-btn>
            </template>
            <span>{{ plusButtonTitle }}</span>
          </v-tooltip>
          <v-btn color="primary" class="me-1" elevation="0" :to="formTypesUrl" v-can="'create-user'">
            {{ buttonName }}
          </v-btn>
          <v-btn :loading="isLoading" icon @click.prevent="open()" small class="ml-2">
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>

      <v-data-table show-select v-model="selected" :headers="headers" :items="items" :options.sync="options"
                    class="flex-grow-1 dt-custom-row-cursor" :loading="isLoading" :page="page" :pageCount="numberOfPages"
                    :show-select="false" :server-items-length="total" @click:row="handleRow">
        <!-- <template v-slot:item.id="{ item }">
          <div class="font-weight-bold">
            # <copy-label :text="item.id + ''" />
          </div>
        </template> -->

        <template v-slot:item.name="{ item }">
          <div>{{ item.name ?? "---" }}</div>
        </template>
        <template v-slot:item.form_request_number="{ item }">
          <div class="font-weight-bold">
            <!-- <copy-label :text="item.form_request_number + ''" /> -->
            {{ item.form_request_number + "" }}
          </div>
          <!-- <div>{{ item.form_request_number ?? "---" }}</div> -->
        </template>

        <template v-slot:item.user="{ item }">
          <div>
            <span class="font-weight-bold">{{ " المدعي: " }}</span>
            <span>{{ item?.form_request_side?.claimant?.name ?? "---" }}</span>
            <br />
            <span class="font-weight-bold">{{ "المدعي علية: " }}</span>
            <span>{{ item?.form_request_side?.defendant?.name ?? "---" }}</span>
          </div>
        </template>

        <template v-slot:item.assigner="{ item }">
          <div>
            {{ item.form_assigned_requests[0]?.user.name ?? "---" }}
          </div>
        </template>
        <!-- <template v-slot:item.court="{ item }">
          <div>
            {{
              item?.last_form_request_information?.court
                ? $t(`general.${item.last_form_request_information?.court}`)
                : "---"
            }}
          </div>
        </template> -->

        <template v-slot:item.status="{ item }">
          <v-chip v-if="item.last_form_request_information != null" small
                  :color="getStatusColor(item?.status?.toLowerCase())" text-color="white"
                  @click.stop="openShowActionDialog(item)">
            <!-- {{
              item?.status ? $t(`general.${item.status.toLowerCase()}`) : "---"
            }} -->
            {{ item?.status ? item.status : "---" }}
          </v-chip>
          <v-chip v-else small :color="getStatusColor(item?.status?.toLowerCase())" text-color="white">
            <!-- {{
              item?.status ? $t(`general.${item.status.toLowerCase()}`) : "---"
            }} -->
            {{ item?.status ? item.status : "---" }}
          </v-chip><br>
          <v-chip x-small v-if="checkRecieveDate(item) != ''">{{ checkRecieveDate(item) }}</v-chip>
        </template>

        <!-- <template v-slot:item.sub_status="{ item }">
          <v-chip small :color="getStatusColor(item?.status?.toLowerCase())" text-color="white">
            {{ item?.sub_status ? item.sub_status : "---" }}
          </v-chip>
        </template> -->

        <template v-slot:item.case_date="{ item }">
          <div>{{ item.case_date | formatDate("ll") }}</div>
        </template>
        <!-- <template v-slot:item.created_at="{ item }">
          <div>{{ item.created_at | formatDate("ll") }}</div>
        </template> -->
        <template v-slot:item.action="{ item }">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <!-- <v-btn color="primary" v-on="on">
                  {{ $t("cases.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn> -->
                <v-btn v-on="on" class="ma-2" icon color="primary">
                  <v-icon>mdi-dots-vertical</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <v-list-item @click="openActionDialog(item)" v-if="item.form_assigned_requests[0]">
                <v-list-item-title>
                  <v-icon>mdi-plus-circle-outline</v-icon>
                  <span class="action-span">{{ $t("cases.add_action") }}</span>
                </v-list-item-title>
              </v-list-item>
              <v-list-item @click="openCasePreviewDialog(item.id)">
                <v-list-item-title>
                  <v-icon>mdi-timeline-text-outline</v-icon>
                  <span class="action-span">{{
                      $t("cases.view_timeline")
                    }}</span>
                </v-list-item-title>
              </v-list-item>
              <!-- <v-list-item @click="openCaseInfoDialog(item.id)">
                <v-list-item-title>
                  <v-icon>mdi-eye-outline</v-icon>
                  <span class="action-span">{{ $t("cases.view_info") }}</span>
                </v-list-item-title>
              </v-list-item> -->
              <v-list-item v-if="!item.form_assigned_requests[0]" @click="openAssignDialog(item.id)">
                <v-list-item-title>
                  <v-icon>mdi-at</v-icon>
                  <span class="action-span">{{ $t("cases.assign_user") }}</span>
                </v-list-item-title>
              </v-list-item>
              <v-list-item :to="`/cases/${currentPageId}/edit/${item.id}`" v-if="item.form_assigned_requests[0]">
                <v-list-item-title>
                  <v-icon>mdi-pencil-outline</v-icon>
                  <!-- <v-icon>mdi-open-in-new</v-icon> -->
                  <span class="action-span">{{ $t("cases.editCase") }}</span>
                </v-list-item-title>
              </v-list-item>
              <v-list-item @click.prevent="deleteItem(item.id)" v-can="'delete-user'" class="d-flex">
                <v-list-item-title>
                  <v-icon>mdi-close</v-icon>
                  <span class="action-span">{{ $t("cases.deleteCase") }}</span>
                </v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
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
      <CasePreviewDialog :dialogVisible="casePrevDialog" :case-id="formId" v-if="casePrevDialog"
                         @closePrevDialog="casePrevDialog = false" />
      <CaseInfoDialog :dialogVisible="caseInfoDialog" :case-id="formId" v-if="caseInfoDialog"
                      @closeInfoDialog="caseInfoDialog = false" />
      <AddAction :dialogVisible="addActionDialog" :formRequestId="formId"
                 :lastAction="selectedForm.last_form_request_information || null" v-if="addActionDialog && currentPageId == 1"
                 @close-action-dialog="closeActionDialog" />
      <AddDynamicAction :dialogVisible="addDynamicActionDialog" :formRequestId="formId"
                        :lastAction="selectedForm.last_form_request_information || null"
                        v-else-if="addDynamicActionDialog && currentPageId != 1" @close-action-dialog="closeDynamicActionDialog" />
      <assign @userAssigned="userAssigned" v-model="dialog" :id="formId"></assign>
      <show-action :dialogVisible="showActionDialog" :formRequestId="formId"
                   :lastAction="selectedForm.last_form_request_information || null" v-if="showActionDialog"
                   @close-action-dialog="closeShowActionDialog"></show-action>
    </v-card>
  </div>
</template>

<script>
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import CasePreviewDialog from "../../components/cases/CasePreviewDialog.vue";
import CaseInfoDialog from "../../components/cases/CaseInfoDialog.vue";
import Assign from "../../components/cases/Assign";
import AddAction from "../../components/cases/AddAction.vue";
import AddDynamicAction from "@/components/cases/AddDynamicAction";
import ShowAction from "@/components/cases/ShowAction";

export default {
  name:"LegalIndex",
  components: {
    ShowAction,
    CasePreviewDialog,
    CaseInfoDialog,
    Assign,
    CopyLabel,
    emptyDataSvg,
    AddAction,
    AddDynamicAction,
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
        // {
        //   text: this.$t("menu.requests"),
        //   disabled: false,
        //   href: "#",
        // },
      ],

      searchQuery: "",
      selected: [],
      items: [],

      formTypesUrl: "",
      caseUrl: "",
      buttonName: "",
      plusButtonTitle: "",
      formId: 0,
      dialog: false,
      casePrevDialog: false,
      caseInfoDialog: false,
      addActionDialog: false,
      addDynamicActionDialog: false,
      showActionDialog: false,
      selectedForm: null,
    };
  },
  watch: {
    selected(val) { },
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
      if (this.navTemplates.length) {
        this.setCurrentBread();
      }
    },
  },
  computed: {
    ...mapState("cases", ["formRequests"]),
    ...mapState("app", ["navTemplates", "pageTitle"]),
    headers() {
      const headers = [
        { text: this.$t("cases.adviceNumber"), value: "form_request_number", width: 50, align: "center" },
        { text: this.$t("cases.adviceName"), value: "name" },
        {
          text: this.$t("tables.user"),
          value: "user",
          width: 250,
          align: "center"
        },
        { text: this.$t("tables.assigner"), value: "assigner" },
        { text: this.$t("cases.adviceStatus"), value: "status", align: "center" },
        { text: this.$t("cases.adviceDate"), value: "case_date", width: 50 },
        {
          text: this.$t("tables.actions"),
          sortable: false,
          align: "center",
          value: "action",
          width: 50
        },
      ];
      if (+this.currentPageId === 1) {
        // headers.splice(4, 0, { text: this.$t("tables.court"), value: "court" });
      }
      return headers;
    },
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
    ...mapActions("cases", ["getFormRequests", "deleteForm"]),
    ...mapActions("app", ["setBreadCrumb"]),
    search() { },
    handleRow(item) {
      this.openCaseInfoDialog(item.id);
      console.log(item);
    },
    checkRecieveDate(item) {
      let last = item?.last_form_request_action?.formable;

      if (last?.type == "court" && last?.date_of_receipt) {
        return "( تم استلام الحكم )";
      } else if (last?.type == "court" && !last?.date_of_receipt) {
        return "( بإنتظار استلام الحكم )";
      }
      return "";
    },
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
        this.breadcrumbs.push({
          text: this.$t("menu.cases_child"),
        });
      }

      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: currentPage.title,
      });
    },
    userAssigned() {
      this.open();
    },
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";
      let { id } = this.$route.params;
      let data = {
        template_id: id,
        form_type: "legal_advice",
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
    openActionDialog(item) {
      if (this.currentPageId == 1) this.addActionDialog = true;
      else this.addDynamicActionDialog = true;
      this.formId = item.id;
      this.selectedForm = item;
    },
    openCasePreviewDialog(id) {
      this.formId = id;
      this.casePrevDialog = true;
    },
    openCaseInfoDialog(id) {
      this.formId = id;
      this.caseInfoDialog = true;
    },
    openShowActionDialog(item) {
      this.showActionDialog = true;
      this.formId = item.id;
      this.selectedForm = item;
    },
    closeActionDialog() {
      this.addActionDialog = false;
      this.open();
    },
    closeDynamicActionDialog() {
      this.addDynamicActionDialog = false;
      this.open();
    },
    closeShowActionDialog() {
      this.showActionDialog = false;
      this.open();
    },
    getStatusColor(status) {
      const colors = {
        processing: "blue",
        pending: "orange",
        accepted: "green",
        closed: "red",
      };

      return colors[status] || "primary";
    },

    async deleteItem(id) {
      const msg = this.$t("general.delete_confirmation");
      const { isConfirmed } = await ask(msg, "error");

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
.action-span {
  margin-right: 5px;
}

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
