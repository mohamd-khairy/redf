<template>
  <v-card @click="taskPath(task.form_request?.id)" class="bg-white stage">
    <div class="d-flex justify-content-between">
      <v-card-title class="pa-0 case-title">
        {{ task.form_request?.name }}
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
<!--          <v-list-item>-->
<!--            <v-list-item-title>-->
<!--              <v-icon>mdi-plus-circle-outline</v-icon>-->
<!--              <span class="action-span">{{ $t("cases.add_action") }}</span>-->
<!--            </v-list-item-title>-->
<!--          </v-list-item>-->
<!--          <v-list-item>-->
<!--            <v-list-item-title>-->
<!--              <v-icon>mdi-timeline-text-outline</v-icon>-->
<!--              <span class="action-span">{{ $t("cases.view_timeline") }}</span>-->
<!--            </v-list-item-title>-->
<!--          </v-list-item>-->
          <v-list-item @click="openAssignAction">
            <v-list-item-title>
              <v-icon>mdi-at</v-icon>
              <span class="action-span">{{ $t("cases.assign_user") }}</span>
            </v-list-item-title>
          </v-list-item>
          <v-list-item @click="deleteApplication">
            <v-list-item-title>
              <v-icon>mdi-close</v-icon>
              <span class="action-span">{{ $t("cases.deleteRequest") }}</span>
            </v-list-item-title>
          </v-list-item>
<!--          <v-list-item>-->
<!--            <v-list-item-title>-->
<!--              <v-icon>mdi-pencil-outline</v-icon>-->
<!--              &lt;!&ndash; <v-icon>mdi-open-in-new</v-icon> &ndash;&gt;-->
<!--              <span class="action-span">{{ $t("cases.editCase") }}</span>-->
<!--            </v-list-item-title>-->
<!--          </v-list-item>-->
        </v-list>
      </v-menu>
    </div>
    <v-card-text class="p-0 mt-1">
      <div class="desc-cont">{{ task.form_request?.display_status }}</div>
      <div class="progress-cont mt-2">
        <div
          class="prog-title-cont d-flex justify-content-between align-center"
        >
          <div class="prog-title">
            <v-icon class="me-1"> mdi-list-status </v-icon>
            <span> تقدم </span>
          </div>
          <div class="prog-current">4/10</div>
        </div>
        <v-progress-linear
          class="mt-1"
          color="red darken-2"
          rounded
          value="30"
        ></v-progress-linear>
      </div>
      <div class="d-flex mt-3 justify-content-between align-center">
        <span class="text-sm text-gray-600">{{ task.date }}</span>
        <div class="avatar-container" v-if="task.form_request?.form_assigned_requests && task?.form_request?.form_assigned_requests?.length">
          <!-- More avatars -->
          <v-menu offset-y v-if="task?.form_request?.form_assigned_requests?.length > 3">
            <template v-slot:activator="{ on }">
              <v-btn icon small size="24" class="more-avatar" v-on="on">
                <v-icon>mdi-dots-horizontal</v-icon>
              </v-btn>
            </template>
            <v-list>
              <!-- Render avatars here -->
              <v-list-item v-for="(item, index) in task?.form_request?.form_assigned_requests" :key="item.user.name">
                <v-avatar size="24">
                  <img :src="'/images/avatars/avatar1.svg'" :alt="'Avatar ' + (index + 4)" />
                </v-avatar>
              </v-list-item>
            </v-list>
          </v-menu>
          <!-- Avatar 1 -->
          <v-avatar
            v-for="user in task?.form_request?.form_assigned_requests"
            size="30"
            class="avatar-item"
          >
            <img :src="'/images/avatars/avatar1.svg'" alt="Avatar 1" />
          </v-avatar>
        </div>
      </div>
    </v-card-text>
  </v-card>
</template>
<script>
export default {
  props: {
    task: {
      type: Object,
      default: () => ({}),
    },
  },
  computed: {
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
  methods:{
    taskPath(taskId)
    {
      let { id } = this.$route.params;
      this.$router.push(
        `/cases/${id}/request-review/edit/${taskId}`
      );
    },
    openAssignAction()
    {
      this.$emit("openAssign");
    },
    deleteApplication()
    {
      this.$emit("deleteItem");
    }
  }
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
