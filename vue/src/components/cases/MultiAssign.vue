<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" max-width="500">
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ $t("cases.assignUser") }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn icon @click="dialog = false">
              <v-icon> mdi-close </v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>

        <v-card-text class="mt-4">
          <v-row>
            <v-col cols="12">
              <v-select
                :items="users"
                :label="$t('cases.employee')"
                :item-text="(item) => item.name"
                :item-value="(item) => item.id"
                hide-details
                outlined
                dense
                v-model="user_ids"
                multiple
              >
              </v-select>
            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="py-2">
          <v-spacer></v-spacer>

          <v-btn color="primary" @click="assignUser">
            {{ $t("general.save") }}
          </v-btn>

          <v-btn color="grey" class="ms-2" @click="dialog = false">
            {{ $t("general.cancel") }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>

<script>
import { mapActions, mapState } from "vuex";
import { makeToast } from "@/helpers";

export default {
  name: "MultiAssign",

  props: {
    value: Boolean,
    // item: Object,
    id: Number,
    userIds: Array,
  },
  data() {
    return {
      form: { user_id: this.userIds ?? [], date: "" },
      users: [],
    };
  },
  computed: {
    dialog: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
    user_ids: {
      get: function() {
        return this.userIds
      },
      set: function(value) {
        this.$emit('updateUserIds', value)
      }
    }
  },
  watch: {
    requests() {
      // this.refresh()
    },
  },
  created() {
  },
  mounted() {
    this.getEmployees();
  },
  methods: {
    ...mapActions("cases", ["assignRequest"]),
    refresh() {
      this.loading = true;
      this.event = this.eventItem;
      const { status, notes, id } = this.eventItem ?? {};
      this.form = { status, notes, id };
    },
    fetchData: function () {
      this.$root.$emit("table_component");
    },
    getEmployees() {
      this.isLoading = true;
      this.$axios
        .get("user-employee")
        .then((response) => {
          this.users = response.data.data.users;
          this.isLoading = false;
        })
        .catch((error) => {
          this.isLoading = false;
        });
    },
    assignUser() {
      this.loading = true;
      this.errors = {};
      let data = {
        form_request_id: this.id,
        user_id: this.user_ids,
      };
      this.assignRequest(data)
        .then((response) => {
          this.loading = false;
          this.dialog = false;
          this.fetchData();
          this.errors = {};
          this.$emit("userAssigned");
          makeToast("success", response.data.message);
        })
        .catch((error) => {
          this.loading = false;
          // if (error.response.status == 422) {
          //   const { errors } = error?.response?.data;
          //   this.errors = errors ?? {};
          // }
        });
    },
  },
};
</script>

<style scoped></style>
