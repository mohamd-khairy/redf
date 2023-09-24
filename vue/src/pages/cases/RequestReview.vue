<template>
  <div class="d-flex flex-column flex-grow-1">
    <!--    {{ items}}-->
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
      <v-autocomplete :disabled="forms.length === 0" class="mx-1 autocomplete_select" :label="$t('cases.caseTemplates')"
        :items="forms" :item-text="item => item.name" :item-value="item => item.id" hide-details v-model="selectedForms"
        return-object multiple solo clearable prepend-inner-icon="mdi-magnify" chips @click:clear="fetchApplications()">
      </v-autocomplete>
    </div>

    <v-tabs hide-slider show-arrows active-class="active-form" class="mb-4 mt-1" centered grow background-color="#fafafa">
      <v-tab v-for="(tab, index) in selectedForms" :key="tab.id" :value="tab.id" @click="fetchApplications(tab.id)">
        {{ tab.name }}
      </v-tab>
    </v-tabs>

    <v-card>
      <v-tabs v-model="activeTab" center-active active-class="active" hide-slider>
        <v-tab class="view_tabs">
          <v-icon>mdi-view-dashboard-outline</v-icon>
          <span>{{ $t("cases.view_board") }}</span>
        </v-tab>
        <v-tab class="view_tabs">
          <v-icon>mdi-menu</v-icon>
          <span>{{ $t("cases.view_menu") }}</span>
        </v-tab>
      </v-tabs>

      <v-card-text>
        <v-tabs-items v-model="activeTab">
          <v-tab-item>
            <div class="min-h-screen d-flex overflow-x-scroll py-4 px-4 kanban-scroll-container" ref="scrollContainer">
              <div v-for="(column, i) in items" :key="column.id" class="bg-gray-100 px-3 py-3 column-width stage-cont"
                :class="i > 0 ? 'mr-4' : ''">
                <p class="stage-title">
                  {{ column.name }}
                </p>
                <!-- Draggable component comes from vuedraggable. It provides drag & drop functionality -->
                <div class="scroll-item">
                  <draggable :disabled="true" :list="column.applications" :animation="200" ghost-class="ghost-card"
                    group="tasks" :scroll-sensitivity="500" :force-fallback="true" @start="onDragStart" @end="onDragEnd">
                    <!-- Each element from here will be draggable and animated. Note :key is very important here to be unique both for draggable and animations to be smooth & consistent. -->
                    <task-card v-for="task in column.applications" :key="task.id" :task="task" class="mt-3" ref="listItem"
                      @openAssign="openAssignDialog(task.form_request_id, task)"
                      @deleteItem="deleteItem(task.form_request_id)"></task-card>
                    <!-- </transition-group> -->
                  </draggable>
                </div>
              </div>
            </div>
          </v-tab-item>
          <v-tab-item>
            <div class="min-h-screen d-flex py-4 px-4">
              <v-data-table :footer-props="footerProps" v-model="selected" :headers="headers" :items="items"
                :options.sync="options" :show-select="false" class="flex-grow-1 dt-custom-row-cursor" :loading="isLoading"
                :page="page" :pageCount="numberOfPages" :server-items-length="total" @click:row="handleClick">
                <!-- <template v-slot:item.id="{ item }">
                <div class="font-weight-bold">
                  # <copy-label :text="item.id + ''" />
                </div>
              </template> -->

                <template v-slot:item.name="{ item }">
                  <div>{{ item.form_request?.name ?? "---" }}</div>
                </template>
                <template v-slot:item.form_request_number="{ item }">
                  <div class="font-weight-bold">
                    {{ item.form_request_number }}
                  </div>
                  <!-- <div>{{ item.form_request_number ?? "---" }}</div> -->
                </template>

                <!--              <template v-slot:item.caseName="{ item }">-->
                <!--                <div>{{ item?.case?.item?.name ?? "-&#45;&#45;" }}</div>-->
                <!--              </template>-->

                <template v-slot:item.users="{ item }">
                  <div>
                    <v-avatar v-for="user in item?.form_request?.form_assigned_requests" size="30" class="avatar-item">
                      <img :src="'/images/avatars/avatar1.svg'" alt="Avatar 1" />
                    </v-avatar>
                  </div>
                </template>

                <template v-slot:item.status="{ item }">
                  <v-chip small :color="getStatusColor(item?.display_status?.display_status?.toLowerCase())"
                    text-color="white">
                    {{ item?.form_request?.display_status ? item.form_request?.display_status : "---" }}
                    <!-- {{ item.status ? $t(`general.${item.status} `) : "" }} -->
                  </v-chip>
                </template>

                <template v-slot:item.created_at="{ item }">
                  <div>{{ item.created_at | formatDate("lll") }}</div>
                </template>

                <template v-slot:item.action="{ item }">
                  <div class="actions">
                    <!-- assign user button -->
                    <v-tooltip top v-if="!item.form_request?.form_assigned_requests?.length">
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn color="primary" icon elevation="0" v-bind="attrs" v-on="on"
                          @click.prevent.stop="openAssignDialog(item.form_request_id, item)">
                          <v-icon>mdi-at</v-icon>
                        </v-btn>
                      </template>
                      <span>{{ $t("cases.assign_user") }}</span>
                    </v-tooltip>

                    <!-- delete case button -->
                    <v-tooltip top>
                      <template v-slot:activator="{ on, attrs }">
                        <v-btn color="error" icon elevation="0" v-bind="attrs" v-on="on"
                          @click.prevent.stop="deleteItem(item.id)" v-can="'delete-user'">
                          <v-icon>mdi-close</v-icon>
                        </v-btn>
                      </template>
                      <span>{{ $t("cases.deleteRequest") }}</span>
                    </v-tooltip>

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

      <multi-assign @userAssigned="userAssigned" @updateUserIds="updateUserIds" :userIds="assignedUsers" v-model="dialog"
        :id="formId" />
    </v-card>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import MultiAssign from "../../components/cases/MultiAssign";
import TaskCard from "./TaskCard.vue";

export default {
  name: "RequestReview",
  components: {
    CopyLabel,
    emptyDataSvg,
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
      addActionDialog: false,
      addDynamicActionDialog: false,
      selectedForm: null,
      activeTab: null,
      selectedForms: [{
        id: 4,
        name: 'مذكرة الدفاع'
      },],
      activeFormId: '',
      assignedUsers: [],
      footerProps: { 'items-per-page-options': [5, 10, 15, 30] },
    };
  },
  watch: {
    selected(val) { },
    options: {
      handler() {
        this.fetchApplications(this.activeFormId)
      },
    },
    deep: true,
    searchQuery() {
      // this.fetchApplications(this.activeFormId)
    },
    navTemplates() {
      this.setCurrentBread();
    },
    currentPageId() {
      if (this.navTemplates.length) {
        this.setCurrentBread();
      }
    },
    selectedForms() {
      if (this.selectedForms.length === 1) {
        this.activeFormId = 4
        this.fetchApplications(this.activeFormId)
      }
    },
    'activeTab'() {
      this.fetchApplications(this.activeFormId)
    }
  },
  computed: {
    ...mapState("cases", ["formRequests", 'forms', 'columns']),
    ...mapState("app", ["navTemplates"]),
    headers() {
      const headers = [
        // { text: this.$t("tables.requestNumber"), value: "form_request_number" },
        { text: this.$t("tables.requestName"), value: "name" },
        { text: this.$t("tables.details"), value: "details" },
        { text: this.$t("tables.requestStatus"), value: "status" },
        { text: this.$t("tables.users"), value: "users" },
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
    if (this.selectedForms.length === 1) {
      this.activeFormId = 4
      // this.fetchApplications(this.activeFormId)
    }
    this.currentPageId = id;
    this.formTypesUrl = `/cases/${id}/form-types`;
    this.caseUrl = `/cases/${id}/create/${id}`;
  },
  mounted() {
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
    ...mapActions("cases", ["getFormRequests", "deleteForm", "getApplications", 'getForms']),
    ...mapActions("app", ["setBreadCrumb"]),
    fetchForms() {
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
    search() { },
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
    updateUserIds(selected) {
      this.assignedUsers = selected
    },
    userAssigned() {
      this.fetchApplications(this.activeFormId)
    },
    fetchApplications(id) {
      this.activeFormId = id
      this.isLoading = true;
      if (this.activeTab !== 1) {
        let data = {
          id: id,
          pageSize: -1,
        };
        this.getApplications(data)
          .then(() => {
            this.isLoading = false;
            this.items = this.columns?.sort((a, b) => a.order - b.order)?.map((column) => {
              return {
                id: column.id,
                name: column.name,
                key: column.key,
                applications: column.applications
              }
            })
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
      else {
        let { page, itemsPerPage } = this.options;
        const direction = this.options?.sortDesc?.length ? "asc" : "desc";
        let data = {
          id: this.activeFormId,
          search: this.searchQuery,
          pageSize: itemsPerPage,
          pageNumber: page,
          sortDirection: direction,
          sortColumn: this.options?.sortBy?.length ?? "",
        };
        this.getApplications(data)
          .then((response) => {
            this.isLoading = false;
            if (itemsPerPage != -1) {
              this.items = response.data.data.data;
              this.total = response.data.data.total;
              this.numberOfPages = response.data.data.last_page;
            }
            else {
              this.items = response.data.data;
              this.total = response.data.data.length;
              this.numberOfPages = 1;
            }
          })
          .catch(() => {
            this.isLoading = false;
          });
      }

    },
    openAssignDialog(id, item) {
      this.assignedUsers = []
      if (item.form_request?.form_assigned_requests?.length > 0) {
        for (let i = 0; i < item.form_request?.form_assigned_requests?.length; i++) {
          this.assignedUsers.push(item.form_request.form_assigned_requests[i].user_id)
        }
      }
      this.dialog = true;
      this.formId = id;
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
            this.fetchApplications(this.activeFormId)
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

.kanban-scroll-container {}

.scroll-item {
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

.view_tabs {}

.view_tabs span {
  margin-right: 8px;
}

.active {
  color: #fff !important;
  background-color: #014C4F !important;
  border-radius: 5px
}

.autocomplete_select {
  width: 400px;
}

.active-form {
  background-color: #e0e9e9 !important;
  border-radius: 7px
}

/*.v-autocomplete .v-autocomplete__content[style*="display: none"] {*/
/*  display: none !important;*/
/*}*/
.v-autocomplete .v-chip {
  display: none;
}
</style>
