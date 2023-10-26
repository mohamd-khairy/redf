<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-card :loading="isLoading">
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
            @click.prevent="getTasksData()"
            small
            class="ml-2"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <!-- cases list -->

      <div
        class="min-h-screen d-flex overflow-x-scroll py-4 px-4 kanban-scroll-container"
        ref="scrollContainer"
      >
        <div
          v-for="(column, i) in tasks"
          :key="column.id"
          class="bg-gray-100 px-3 py-3 column-width stage-cont"
          :class="i > 0 ? 'mr-4' : ''"
        >
          <p class="stage-title">
            <v-chip
              v-if="column.id == 1"
              class="ma-2"
              color="#f7e5ed"
              text-color="#db2777"
            >
              {{ column.tasks.length }}
            </v-chip>
            <v-chip
              v-if="column.id == 2"
              class="ma-2"
              color="#f0e6f8"
              text-color="#a454ed"
            >
              {{ column.tasks.length }}
            </v-chip>
            <v-chip
              v-if="column.id == 3"
              class="ma-2"
              color="#f5efe1"
              text-color="#ca8a04"
            >
              {{ column.tasks.length }}
            </v-chip>
            <v-chip
              v-if="column.id == 4"
              class="ma-2"
              color="#e6f2ea"
              text-color="#2FAD5E"
            >
              {{ column.tasks.length }}
            </v-chip>
            {{ column.name }}
          </p>

          <draggable
            :list="column.tasks"
            :animation="200"
            ghost-class="ghost-card"
            :id="`stage_${column.id}`"
            group="tasks"
            :scroll-sensitivity="500"
            :force-fallback="true"
            @start="onDragStart"
            @end="onDragEnd"
          >
            <task-card
              v-for="task in column.tasks"
              :progress="(100 / tasks.length) * column.id"
              :type="column.name"
              :key="task.id"
              :id="`task_${task.id}`"
              @delete-task="deleteItem"
              :task="task"
              class="mt-3 cursor-move scroll-item"
              ref="listItem"
            ></task-card>
          </draggable>
        </div>
      </div>
    </v-card>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import CopyLabel from "../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
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
    };
  },
  watch: {
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
    ...mapState("tasks", ["tasks"]),
    calculatedColor(id) {
      let color = "#D82027";
      if (this.progress == 1) {
        color = "#D82027";
      } else if (this.progress == 2) {
        color = "#3AD820";
      } else if (this.progress == 3) {
        color = "#02A98B";
      } else if (this.progress == 4) {
        color = "#005A4E";
      }
      return color;
    },
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: "المهام",
    });
    // this.getTasks();
    this.getTasksData();
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
    ...mapActions("tasks", ["updateTask", "getTasks", "deleteTask"]),
    ...mapActions("app", ["setBreadCrumb"]),
    async deleteItem(id) {
      const msg = this.$t("general.delete_confirmation");
      const { isConfirmed } = await ask(msg, "error");
      if (isConfirmed) {
        this.isLoading = true;
        this.deleteTask(id)
          .then((response) => {
            makeToast("success", response.data.message);
            this.getTasksData();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },
    async getTasksData() {
      this.isLoading = true;
      this.getTasks()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
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
    onDragEnd(event) {
      this.isDragging = false;
      console.log("event", event);
      const stepId = event.to.getAttribute("id").split("_")[1];
      const taskId = event.clone.getAttribute("id").split("_")[1];
      console.log("data", stepId, taskId);
      let data = {
        id: taskId,
        stage_id: stepId,
      };
      this.updateTask(data);
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
