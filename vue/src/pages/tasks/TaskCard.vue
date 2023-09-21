<template>
  <v-card class="bg-white stage">
    <div class="d-flex justify-content-between">
      <!-- {{ task }} -->
      <v-card-title class="pa-0 case-title">
        {{ task.name }}
      </v-card-title>

      <v-menu offset-y left>
        <template v-slot:activator="{ on }">
          <transition name="slide-fade" mode="out-in">
            <v-btn small icon v-on="on" color="primary" class="ms-1 me-0">
              <v-icon>mdi-dots-horizontal</v-icon>
            </v-btn>
          </transition>
        </template>
        <v-list dense>
          <v-list-item>
            <v-list-item-title @click="deleteItem(task.id)">
              <a>
                <v-icon class="text-error" color="error">mdi-close</v-icon>
                <span class="action-span">{{ $t("cases.delete") }}</span>
              </a>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>
              <v-icon>mdi-timeline-text-outline</v-icon>
              <span class="action-span">{{ $t("cases.view_timeline") }}</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>
              <v-icon>mdi-at</v-icon>
              <span class="action-span">{{ $t("cases.assign_user") }}</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item>
            <v-list-item-title>
              <v-icon>mdi-pencil-outline</v-icon>
              <!-- <v-icon>mdi-open-in-new</v-icon> -->
              <span class="action-span">{{ $t("cases.editCase") }}</span>
            </v-list-item-title>
          </v-list-item>
        </v-list>
      </v-menu>
    </div>
    <v-card-text class="p-0 mt-1">
      <div class="desc-cont">{{ task.details }}</div>
      <div>
        <v-chip v-if="progress == 25" color="#f7e5ed" text-color="#db2777">
          بحاجة الي المراجعة
        </v-chip>
        <v-chip v-if="progress == 50" color="#f0e6f8" text-color="#a454ed">
          جاري العمل عليها
        </v-chip>
        <v-chip v-if="progress == 75" color="#f5efe1" text-color="#ca8a04">
          متأخر
        </v-chip>
        <v-chip v-if="progress == 100" color="#e6f2ea" text-color="#2FAD5E">
          مكتمل
        </v-chip>
        <v-chip class="font-weight-black mx-1" outlined text-color="#606C80">
          #{{ task.id }}
        </v-chip>
      </div>
      <!-- <div class="progress-cont mt-2">
        <div
          class="prog-title-cont d-flex justify-content-between align-center"
        >
          <div class="prog-title">
            <v-icon class="me-1"> mdi-list-status </v-icon>
            <span> تقدم </span>
          </div>
          <div class="prog-current">{{ progress }} %</div>
        </div>
        <v-progress-linear
          class="mt-1"
          :color="calculatedColor"
          rounded
          :value="progress"
        ></v-progress-linear>
      </div> -->
      <div class="d-flex mt-3 justify-content-between align-center">
        <div class="avatar-container" v-if="task.user">
          <v-tooltip top>
            <template v-slot:activator="{ on, attrs }">
              <v-avatar v-bind="attrs" v-on="on" size="40">
                <v-img
                  :src="
                    task.user.avatar
                      ? task.user.avatar
                      : '/images/avatars/avatar1.svg'
                  "
                ></v-img>
              </v-avatar>
            </template>
            <span>{{ task.name }}</span>
          </v-tooltip>
        </div>
        <span class="text-sm text-gray-600">
          {{ task.due_date.split(" ")[0] }}
        </span>
      </div>
    </v-card-text>
  </v-card>
</template>
<script>
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
export default {
  props: {
    task: {
      type: Object,
      default: () => ({}),
    },
    progress: {
      type: Number,
      default: 0,
    },
  },
  computed: {
    calculatedColor() {
      let color = "#D82027";
      if (this.progress == 25) {
        color = "#D82027";
      } else if (this.progress == 50) {
        color = "#3AD820";
      } else if (this.progress == 75) {
        color = "#02A98B";
      } else if (this.progress == 100) {
        color = "#005A4E";
      }
      return color;
    },
    badgeColor() {
      const mappings = {
        Design: "purple",
        "Feature Request": "teal",
        Backend: "blue",
        QA: "green",
        default: "teal",
      };
      return mappings[this.task.type] || mappings.default;
    },
  },
  methods: {
    ...mapActions("tasks", ["deleteTask", "getTasks"]),
    async deleteItem(id) {
      this.$emit("delete-task", id);
    },
  },
};
</script>
<style>
.avatar-item {
  border: 1px solid #fff;
}
.avatar-item:not(:last-child) {
  margin-left: -8px;
}
</style>
