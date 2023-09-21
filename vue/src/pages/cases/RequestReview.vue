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
    <div class="d-flex align-center align-self-center">
      <v-spacer></v-spacer>
      <v-autocomplete
        :disabled="forms.length === 0"
        class="mx-1 autocomplete_select"
        :label="$t('cases.caseTemplates')"
        :items="forms"
        :item-text="item => item.name"
        :item-value="item => item.id"
        hide-details
        v-model="selectedForms"
        return-object
        multiple
        solo
        clearable
        prepend-inner-icon="mdi-magnify"
        chips
        @click:clear="fetchApplications()"
      >
      </v-autocomplete>
    </div>

      <v-tabs
        hide-slider
        show-arrows
        v-model="form_id"
        active-class="active-form"
        class="mb-4 mt-1"
        centered
        grow
        background-color="#fafafa"
      >
        <v-tab
          v-for="(tab,index) in selectedForms"
          :key="index"
          :value="tab.id"
          @click="fetchApplications(tab.id)"
        >
          {{ tab.name }}
        </v-tab>
      </v-tabs>

    <v-card>
      <v-tabs v-model="activeTab" center-active active-class="active" hide-slider>
        <v-tab class="view_tabs">
          <v-icon>mdi-view-dashboard-outline</v-icon>
          <span>{{$t("cases.view_board")}}</span>
        </v-tab>
        <v-tab class="view_tabs">
          <v-icon>mdi-menu</v-icon>
          <span>{{$t("cases.view_menu")}}</span>
        </v-tab>
      </v-tabs>

      <v-card-text>
        <v-tabs-items v-model="activeTab">
          <v-tab-item>
            <div
              class="min-h-screen d-flex overflow-x-scroll py-4 px-4 kanban-scroll-container"
              ref="scrollContainer"
            >
              <div
                v-for="(column, i) in columns"
                :key="column.id"
                class="bg-gray-100 px-3 py-3 column-width stage-cont"
                :class="i > 0 ? 'mr-4' : ''"
              >
                <p class="stage-title">
                  {{ column.name }}
                </p>
                <!-- Draggable component comes from vuedraggable. It provides drag & drop functionality -->
                <div class="scroll-item">
                <draggable
                  :disabled="true"
                  :list="column.applications"
                  :animation="200"
                  ghost-class="ghost-card"
                  group="tasks"
                  :scroll-sensitivity="500"
                  :force-fallback="true"
                  @start="onDragStart"
                  @end="onDragEnd"
                >
                  <!-- Each element from here will be draggable and animated. Note :key is very important here to be unique both for draggable and animations to be smooth & consistent. -->
                  <task-card
                    v-for="task in column.applications"
                    :key="task.id"
                    :task="task"
                    class="mt-3"
                    ref="listItem"
                    @openAssign="openAssignDialog(task.id)"
                    @deleteItem="deleteItem(task.form_request?.id)"
                  ></task-card>
                  <!-- </transition-group> -->
                </draggable>
                </div>
              </div>
            </div>
          </v-tab-item>
          <v-tab-item>
            <div class="min-h-screen d-flex py-4 px-4">
              <v-data-table show-select v-model="selected" :headers="headers" :items="items" :options.sync="options" :show-select="false"
                          class="flex-grow-1 dt-custom-row-cursor" :loading="isLoading" :page="page" :pageCount="numberOfPages" :server-items-length="total"
                          @click:row="handleClick">
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
                  {{ item.form_request_number }}
                </div>
                <!-- <div>{{ item.form_request_number ?? "---" }}</div> -->
              </template>

              <template v-slot:item.caseName="{ item }">
                <div>{{ item?.case?.item?.name ?? "---" }}</div>
              </template>

              <template v-slot:item.assigner="{ item }">
                <div>
                  {{ item.form_assigned_requests[0]?.user.name ?? "---" }}
                </div>
              </template>

              <template v-slot:item.status="{ item }">
                <v-chip small :color="getStatusColor(item?.status?.toLowerCase())" text-color="white">
                  {{ item?.status ? item.display_status : "---" }}
                  <!-- {{ item.status ? $t(`general.${item.status} `) : "" }} -->
                </v-chip>
              </template>

              <template v-slot:item.created_at="{ item }">
                <div>{{ item.created_at | formatDate("lll") }}</div>
              </template>

              <template v-slot:item.action="{ item }">
                <div class="actions">
                  <!-- add action button -->
                  <!--            <v-tooltip top>-->
                  <!--              <template v-slot:activator="{ on, attrs }">-->
                  <!--                <v-btn-->
                  <!--                  color="primary"-->
                  <!--                  icon-->
                  <!--                  elevation="0"-->
                  <!--                  v-bind="attrs"-->
                  <!--                  v-on="on"-->
                  <!--                  @click="openActionDialog(item)"-->
                  <!--                >-->
                  <!--                  <v-icon>mdi-plus-circle-outline</v-icon>-->
                  <!--                </v-btn>-->
                  <!--              </template>-->
                  <!--              <span>{{ $t("cases.add_action") }}</span>-->
                  <!--            </v-tooltip>-->

                  <!-- view case timeline button -->
                  <!-- <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn color="primary" icon elevation="0" v-bind="attrs" v-on="on"
                        @click.prevent.stop="openCasePreviewDialog(item.id)">
                        <v-icon>mdi-timeline-text-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>{{ $t("cases.request_view_timeline") }}</span>
                  </v-tooltip> -->
                  <!-- view case info button -->
                  <!-- <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn color="primary" icon elevation="0" v-bind="attrs" v-on="on" @click="openCaseInfoDialog(item.id)">
                        <v-icon>mdi-eye-outline</v-icon>
                      </v-btn>
                    </template>
                    <span>{{ $t("cases.view_info") }}</span>
                  </v-tooltip> -->

                  <!-- assign user button -->
                  <v-tooltip top v-if="!item.form_assigned_requests[0]">
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn color="primary" icon elevation="0" v-bind="attrs" v-on="on" @click.prevent.stop="openAssignDialog(item.id)">
                        <v-icon>mdi-at</v-icon>
                      </v-btn>
                    </template>
                    <span>{{ $t("cases.assign_user") }}</span>
                  </v-tooltip>

                  <!-- edit case button -->
                  <!--            <v-tooltip top>-->
                  <!--              <template v-slot:activator="{ on, attrs }">-->
                  <!--                <v-btn color="primary" icon elevation="0" v-bind="attrs" v-on="on"-->
                  <!--                  :to="`/cases/${currentPageId}/request-review/edit/${item.id}`" v-can="'update-user'">-->
                  <!--                  <v-icon>mdi-pencil-outline</v-icon>-->
                  <!--                </v-btn>-->
                  <!--              </template>-->
                  <!--              <span>{{ $t("cases.editCase") }}</span>-->
                  <!--            </v-tooltip>-->

                  <!-- delete case button -->
                  <v-tooltip top>
                    <template v-slot:activator="{ on, attrs }">
                      <v-btn color="error" icon elevation="0" v-bind="attrs" v-on="on" @click.prevent.stop="deleteItem(item.id)"
                             v-can="'delete-user'">
                        <v-icon>mdi-close</v-icon>
                      </v-btn>
                    </template>
                    <span>{{ $t("cases.deleteRequest") }}</span>
                  </v-tooltip>

                  <!-- <v-btn
                    color="error"
                    icon
                    @click.prevent="deleteItem(item.id)"
                    v-can="'delete-user'"
                  >
                    <v-icon>mdi-close</v-icon>
                  </v-btn> -->
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
            </div>
          </v-tab-item>
        </v-tabs-items>
      </v-card-text>



      <CasePreviewDialog
        :dialogVisible="casePrevDialog"
        :case-id="formId"
        v-if="casePrevDialog"
        @closePrevDialog="casePrevDialog = false"
      />
      <CaseInfoDialog
        :dialogVisible="caseInfoDialog"
        :case-id="formId"
        v-if="caseInfoDialog"
        @closeInfoDialog="caseInfoDialog = false"
      />
      <AddAction
        :dialogVisible="addActionDialog"
        :formRequestId="formId"
        :lastAction="selectedForm.last_form_request_information || null"
        v-if="addActionDialog && currentPageId == 1"
        @close-action-dialog="closeActionDialog"
      />
      <AddDynamicAction
        :dialogVisible="addDynamicActionDialog"
        :formRequestId="formId"
        :lastAction="selectedForm.last_form_request_information || null"
        v-else-if="addDynamicActionDialog && currentPageId != 1"
        @close-action-dialog="closeDynamicActionDialog"
      />
      <multi-assign @userAssigned="userAssigned" v-model="dialog" :id="formId" />
    </v-card>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import CasePreviewDialog from "../../components/cases/CasePreviewDialog.vue";
import CaseInfoDialog from "../../components/cases/CaseInfoDialog.vue";
import MultiAssign from "../../components/cases/MultiAssign";
import AddAction from "../../components/cases/AddAction.vue";
import AddDynamicAction from "@/components/cases/AddDynamicAction";
import TaskCard from "./TaskCard.vue";

export default {
  name: "RequestReview",
  components: {
    CasePreviewDialog,
    CaseInfoDialog,
    CopyLabel,
    emptyDataSvg,
    AddAction,
    AddDynamicAction,
    draggable,
    TaskCard,
    MultiAssign
  },
  data() {
    return {
      currentPageId: null,
      isDragging: false,
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
      selectedForm: null,

      columns: [],
      form_id:4,
      activeTab: null,
      selectedForms: [{
        id: 4,
        name: 'مذكرة الدفاع'
      },],
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
      if (this.navTemplates.length) {
        this.setCurrentBread();
      }
    },
    // form_id() {
    //   this.fetchApplications(this.form_id);
    // },
  },
  computed: {
    ...mapState("cases", ["formRequests",'forms']),
    ...mapState("app", ["navTemplates"]),
    headers() {
      const headers = [
        { text: this.$t("tables.requestNumber"), value: "form_request_number" },
        { text: this.$t("tables.requestName"), value: "name" },
        { text: this.$t("tables.caseName"), value: "caseName" },
        { text: this.$t("tables.assigner"), value: "assigner" },
        { text: this.$t("tables.requestStatus"), value: "status" },
        { text: this.$t("tables.created"), value: "created_at" },
        {
          text: this.$t("tables.actions"),
          sortable: false,
          align: "right",
          value: "action",
        },
      ];
      return headers;
    },
  },
  created() {
    let { id } = this.$route.params;
    this.fetchForms()
    this.fetchApplications(this.form_id)
    this.currentPageId = id;
    this.formTypesUrl = `/cases/${id}/form-types`;
    this.caseUrl = `/cases/${id}/create/${id}`;
  },
  mounted() {
    // this.open()
    // this.initScrollBehavior()
    const scrollContainer = this.$refs.scrollContainer;

    if (scrollContainer) {
      scrollContainer.addEventListener("mousemove", (e) => {
        if (this.isDragging) {
          const scrollThreshold = 50; // Adjust this value as needed
          const { clientX } = e;
          const containerWidth = scrollContainer.clientWidth;

          if (clientX < scrollThreshold) {
            this.scrollHorizontally("left");
          } else if (clientX > containerWidth - scrollThreshold) {
            this.scrollHorizontally("right");
          }
        }
      });
    }
  },
  methods: {
    ...mapActions("cases", ["getFormRequests", "deleteForm", "getApplications",'getForms']),
    ...mapActions("app", ["setBreadCrumb"]),
    fetchForms()
    {
      let { id } = this.$route.params;
      this.loading = true;
      this.getForms(id)
        .then(() => {
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    initScrollBehavior() {
      this.$refs.listItem.forEach((item) => {
        item.addEventListener('dragover', (e) => {
          e.preventDefault();
          const mouseY = e.clientY;
          const itemRect = item.getBoundingClientRect();

          if (mouseY < itemRect.top + 50) {
            // Scroll up
            item.scrollTop -= 10;
          } else if (mouseY > itemRect.bottom - 50) {
            // Scroll down
            item.scrollTop += 10;
          }
        });
      });
    },
    fetchApplications(id){
      this.isLoading = true;
      this.getApplications(id)
        .then((response) => {
          this.isLoading = false;
          this.columns= response?.data?.data?.sort((a, b) => a.order - b.order)?.map((column)=>{
            return {
              id:column.id,
              name:column.name,
              key:column.key,
              applications:column.applications.map((item)=>{
                return {
                  id:item.id,
                  form_request:item.form_request,
                  title:item.form_request?.form?.name,
                  date:item.form_request?.form?.updated_at
                }
              }),
            }
          })


        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    search() {},
    onDragStart() {
      this.isDragging = true;
    },
    onDragEnd() {
      this.isDragging = false;
    },
    scrollHorizontally(direction) {
      const scrollContainer = this.$refs.scrollContainer;
      const scrollAmount = 10; // Adjust this value as needed
      const containerWidth = scrollContainer.clientWidth;
      const scrollLeft = scrollContainer.scrollLeft;
      const maxScrollLeft = scrollContainer.scrollWidth - containerWidth;

      if (scrollContainer) {
        console.log("aaaaaaaaa", direction === "left" && scrollLeft > 0);
        console.log(scrollLeft);
        if (direction === "left") {
          scrollContainer.scrollLeft -= scrollAmount;
        } else if (direction === "right") {
          scrollContainer.scrollLeft += scrollAmount;
        }
      }
    },
    handleClick(event) {
      this.$router.push(
        `/cases/${this.currentPageId}/request-review/edit/${event.id}`
      );
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
          text: this.$t("menu.request_review"),
        });
      }
      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: "الطلبات",
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
        form_type: "related_case",
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
    closeActionDialog() {
      this.addActionDialog = false;
      this.open();
    },
    closeDynamicActionDialog() {
      this.addDynamicActionDialog = false;
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
            makeToast("success", response.data.data);
            this.open();
            this.fetchApplications(this.form_id)
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
<style scoped>
.column-width {
  min-width: 328px;
  width: 328px;
  min-height: 200px;
}
/* Unfortunately @apply cannot be setup in codesandbox,
but you'd use "@apply border opacity-50 border-blue-500 bg-gray-200" here */
.ghost-card {
  opacity: 0.5;
  background: #f7fafc;
  border: 1px solid #4299e1;
}
.overflow-x-scroll {
  overflow-x: auto;
}
.kanban-scroll-container {
}
.scroll-item{
  overflow: auto;
  height: 600px;
}
</style>
<style>
.stage-cont {
  border: 1px solid #eaecf0;
  border-radius: 8px;
  background: #f9f9fb;
}
.stage-title {
  font-size: 14px;
  font-weight: 700;
  line-height: 21px;
  letter-spacing: -0.02em;
  text-align: right;
}
.stage {
  border: 1px solid #fff;
  border-radius: 4px;
  padding: 20px;
}
.stage:hover {
  border: 1px dashed #ccc;
}
.case-title {
  font-size: 16px;
  font-weight: 700;
  line-height: 24px;
  letter-spacing: -0.02em;
  text-align: right;
}
.view_tabs {

}
.view_tabs span{
  margin-right: 8px;
}
.active {
  color:#fff !important;
  background-color: #014C4F !important;
  border-radius : 5px
}
.autocomplete_select{
  width: 400px;
}
.active-form{
  background-color: #e0e9e9 !important;
  border-radius : 7px
}
/*.v-autocomplete .v-autocomplete__content[style*="display: none"] {*/
/*  display: none !important;*/
/*}*/
.v-autocomplete .v-chip {
  display: none;
}
</style>
