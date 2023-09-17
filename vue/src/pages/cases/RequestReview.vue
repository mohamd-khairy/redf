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

          <!--          <v-tooltip top>-->
          <!--            <template v-slot:activator="{ on, attrs }">-->
          <!--              <v-btn-->
          <!--                color="primary"-->
          <!--                class="mx-2"-->
          <!--                elevation="0"-->
          <!--                v-bind="attrs"-->
          <!--                v-on="on"-->
          <!--                :to="caseUrl"-->
          <!--                :disabled="currentPageId > 3"-->
          <!--                v-can="'create-user'"-->
          <!--              >-->
          <!--                <v-icon> mdi-plus </v-icon>-->
          <!--              </v-btn>-->
          <!--            </template>-->
          <!--            <span>{{ plusButtonTitle }}</span>-->
          <!--          </v-tooltip>-->
          <!--          <v-btn-->
          <!--            color="primary"-->
          <!--            class="me-1"-->
          <!--            elevation="0"-->
          <!--            :to="formTypesUrl"-->
          <!--            v-can="'create-user'"-->
          <!--          >-->
          <!--            {{ buttonName }}-->
          <!--          </v-btn>-->
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
      <div
        class="min-h-screen d-flex overflow-x-scroll py-4 px-4 kanban-scroll-container"
        ref="scrollContainer"
      >
        <div
          v-for="(column, i) in columns"
          :key="column.title"
          class="bg-gray-100 px-3 py-3 column-width stage-cont"
          :class="i > 0 ? 'mr-4' : ''"
        >
          <p class="stage-title">
            {{ column.title }}
          </p>
          <!-- Draggable component comes from vuedraggable. It provides drag & drop functionality -->
          <draggable
            :list="column.tasks"
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
              v-for="task in column.tasks"
              :key="task.id"
              :task="task"
              class="mt-3 cursor-move scroll-item"
              ref="listItem"
            ></task-card>
            <!-- </transition-group> -->
          </draggable>
        </div>
      </div>
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
      <assign @userAssigned="userAssigned" v-model="dialog" :id="formId" />
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
import Assign from "../../components/cases/Assign";
import AddAction from "../../components/cases/AddAction.vue";
import AddDynamicAction from "@/components/cases/AddDynamicAction";
import TaskCard from "./TaskCard.vue";

export default {
  name: "RequestReview",
  components: {
    CasePreviewDialog,
    CaseInfoDialog,
    Assign,
    CopyLabel,
    emptyDataSvg,
    AddAction,
    AddDynamicAction,
    draggable,
    TaskCard,
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

      columns: [
        {
          title: "Backlog 1",
          tasks: [
            {
              id: 1,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
              users: [
                {
                  name: "Moaz Gamal",
                  img: "/images/avatars/avatar1.svg",
                },
                {
                  name: "Mostafa Gamal",
                  img: "/images/avatars/avatar1.svg",
                },
                {
                  name: "Mohamed Khairy",
                  img: "/images/avatars/avatar1.svg",
                },
                {
                  name: "Mohamed Khairy",
                  img: "/images/avatars/avatar1.svg",
                },
              ],
            },
            {
              id: 2,
              title: "Provide documentation on integrations",
              date: "Sep 12",
              users: [
                {
                  name: "Mostafa Gamal",
                  img: "/images/avatars/avatar1.svg",
                },

                {
                  name: "Mohamed Khairy",
                  img: "/images/avatars/avatar1.svg",
                },
              ],
            },
            {
              id: 3,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
              users: [
                {
                  name: "Mostafa Gamal",
                  img: "/images/avatars/avatar1.svg",
                },
                {
                  name: "Moaz Gamal",
                  img: "/images/avatars/avatar1.svg",
                },

                {
                  name: "Mohamed Khairy",
                  img: "/images/avatars/avatar1.svg",
                },
              ],
            },
            {
              id: 4,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
              users: [
                {
                  name: "Mostafa Gamal",
                  img: "/images/avatars/avatar1.svg",
                },
                {
                  name: "Moaz Gamal",
                  img: "/images/avatars/avatar1.svg",
                },

                {
                  name: "Mohamed Khairy",
                  img: "/images/avatars/avatar1.svg",
                },
              ],
            },
            {
              id: 5,
              title: "Test checkout flow",
              date: "Sep 15",
              type: "QA",
              users: [
                {
                  name: "Mostafa Gamal",
                  img: "/images/avatars/avatar1.svg",
                },
                {
                  name: "Moaz Gamal",
                  img: "/images/avatars/avatar1.svg",
                },

                {
                  name: "Mohamed Khairy",
                  img: "/images/avatars/avatar1.svg",
                },
              ],
            },
          ],
        },
        {
          title: "Backlog 2",
          tasks: [
            {
              id: 1,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 2,
              title: "Provide documentation on integrations",
              date: "Sep 12",
            },
            {
              id: 3,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
            },
            {
              id: 4,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 5,
              title: "Test checkout flow",
              date: "Sep 15",
              type: "QA",
            },
          ],
        },
        {
          title: "Backlog 3",
          tasks: [
            {
              id: 1,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 2,
              title: "Provide documentation on integrations",
              date: "Sep 12",
            },
            {
              id: 3,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
            },
            {
              id: 4,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 5,
              title: "Test checkout flow",
              date: "Sep 15",
              type: "QA",
            },
          ],
        },
        {
          title: "In Progress",
          tasks: [
            {
              id: 6,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
            },
            {
              id: 7,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 8,
              title: "Provide documentation on integrations",
              date: "Sep 12",
              type: "Backend",
            },
          ],
        },
        {
          title: "Review",
          tasks: [
            {
              id: 9,
              title: "Provide documentation on integrations",
              date: "Sep 12",
            },
            {
              id: 10,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
            },
            {
              id: 11,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 12,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
            },
            {
              id: 13,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
          ],
        },
        {
          title: "Done",
          tasks: [
            {
              id: 14,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
            {
              id: 15,
              title: "Design shopping cart dropdown",
              date: "Sep 9",
              type: "Design",
            },
            {
              id: 16,
              title: "Add discount code to checkout page",
              date: "Sep 14",
              type: "Feature Request",
            },
          ],
        },
      ],
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
  },
  computed: {
    ...mapState("cases", ["formRequests"]),
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
    ...mapActions("cases", ["getFormRequests", "deleteForm", "deleteAll"]),
    ...mapActions("app", ["setBreadCrumb"]),
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
</style>
