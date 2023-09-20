<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-card>
      <v-row dense class="pa-2 align-center">
        <v-col cols="6"> </v-col>
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
            @keyup.enter="searchTask(searchQuery)"
          ></v-text-field>

          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-btn
                color="primary"
                class="mx-2"
                elevation="0"
                v-bind="attrs"
                v-on="on"
                to="/tasks/create"
              >
                <v-icon> mdi-plus </v-icon>
              </v-btn>
            </template>
            <span>{{ $t("tasks.createTask") }}</span>
          </v-tooltip>
          <v-btn
            :loading="isLoading"
            icon
            @click.prevent="getData()"
            small
            class="ml-2"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <!-- cases list -->
      {{ columns }}
      <div
        class="min-h-screen d-flex overflow-x-scroll py-4 px-4 kanban-scroll-container"
        ref="scrollContainer"
      >
        <!-- <div
          v-for="(column, i) in columnss"
          :key="column.id"
          class="bg-gray-100 px-3 py-3 column-width stage-cont"
          :class="i > 0 ? 'mr-4' : ''"
        >
          <p class="stage-title">
            {{ column.name }}
          </p>

          <draggable
            :list="column.applications"
            :animation="200"
            ghost-class="ghost-card"
            group="tasks"
            :scroll-sensitivity="500"
            :force-fallback="true"
            @start="onDragStart"
            @end="onDragEnd"
          >
            <task-card
              v-for="task in column.applications"
              :key="task.id"
              :task="task"
              class="mt-3 cursor-move scroll-item"
              ref="listItem"
            ></task-card>
          </draggable>
        </div> -->
      </div>
    </v-card>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import axios from "axios";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import TaskCard from "./TaskCard.vue";

export default {
  name: "tasks",
  components: {
    CopyLabel,
    emptyDataSvg,
    draggable,
    TaskCard,
  },
  data() {
    return {
      currentPageId: null,
      isDragging: false,

      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("tasks.tasksManagement"),
          disabled: false,
          href: "#",
        },

        {
          text: this.$t("tasks.tasksList"),
          to: "/tasks/list",
          exact: true,
        },
      ],

      searchQuery: "",
      columns: [],

      // columnss: [
      //   {
      //     name: "Backlog 1",
      //     applications: [
      //       {
      //         id: 1,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //         users: [
      //           {
      //             name: "Moaz Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //           {
      //             name: "Mostafa Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //           {
      //             name: "Mohamed Khairy",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //           {
      //             name: "Mohamed Khairy",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //         ],
      //       },
      //       {
      //         id: 2,
      //         title: "Provide documentation on integrations",
      //         date: "Sep 12",
      //         users: [
      //           {
      //             name: "Mostafa Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },

      //           {
      //             name: "Mohamed Khairy",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //         ],
      //       },
      //       {
      //         id: 3,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //         users: [
      //           {
      //             name: "Mostafa Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //           {
      //             name: "Moaz Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },

      //           {
      //             name: "Mohamed Khairy",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //         ],
      //       },
      //       {
      //         id: 4,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //         users: [
      //           {
      //             name: "Mostafa Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //           {
      //             name: "Moaz Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },

      //           {
      //             name: "Mohamed Khairy",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //         ],
      //       },
      //       {
      //         id: 5,
      //         title: "Test checkout flow",
      //         date: "Sep 15",
      //         type: "QA",
      //         users: [
      //           {
      //             name: "Mostafa Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //           {
      //             name: "Moaz Gamal",
      //             img: "/images/avatars/avatar1.svg",
      //           },

      //           {
      //             name: "Mohamed Khairy",
      //             img: "/images/avatars/avatar1.svg",
      //           },
      //         ],
      //       },
      //     ],
      //   },
      //   {
      //     name: "Backlog 2",
      //     applications: [
      //       {
      //         id: 1,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 2,
      //         title: "Provide documentation on integrations",
      //         date: "Sep 12",
      //       },
      //       {
      //         id: 3,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //       },
      //       {
      //         id: 4,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 5,
      //         title: "Test checkout flow",
      //         date: "Sep 15",
      //         type: "QA",
      //       },
      //     ],
      //   },
      //   {
      //     name: "Backlog 3",
      //     applications: [
      //       {
      //         id: 1,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 2,
      //         title: "Provide documentation on integrations",
      //         date: "Sep 12",
      //       },
      //       {
      //         id: 3,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //       },
      //       {
      //         id: 4,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 5,
      //         title: "Test checkout flow",
      //         date: "Sep 15",
      //         type: "QA",
      //       },
      //     ],
      //   },
      //   {
      //     name: "In Progress",
      //     applications: [
      //       {
      //         id: 6,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //       },
      //       {
      //         id: 7,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 8,
      //         title: "Provide documentation on integrations",
      //         date: "Sep 12",
      //         type: "Backend",
      //       },
      //     ],
      //   },
      //   {
      //     name: "Review",
      //     applications: [
      //       {
      //         id: 9,
      //         title: "Provide documentation on integrations",
      //         date: "Sep 12",
      //       },
      //       {
      //         id: 10,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //       },
      //       {
      //         id: 11,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 12,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //       },
      //       {
      //         id: 13,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //     ],
      //   },
      //   {
      //     name: "Done",
      //     applications: [
      //       {
      //         id: 14,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //       {
      //         id: 15,
      //         title: "Design shopping cart dropdown",
      //         date: "Sep 9",
      //         type: "Design",
      //       },
      //       {
      //         id: 16,
      //         title: "Add discount code to checkout page",
      //         date: "Sep 14",
      //         type: "Feature Request",
      //       },
      //     ],
      //   },
      // ],
      form_id: "",
    };
  },
  watch: {
    navTemplates() {
      this.setCurrentBread();
    },
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
    ...mapState("app", ["navTemplates"]),
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: "المهام",
    });
    this.getData();
  },
  mounted() {
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
    ...mapActions("app", ["setBreadCrumb"]),
    async getData() {
      const response = await axios.get("tasks");
      const { tasks } = response?.data.data;
      this.columns = tasks;
    },

    initScrollBehavior() {
      this.$refs.listItem.forEach((item) => {
        item.addEventListener("dragover", (e) => {
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
.scroll-item {
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
