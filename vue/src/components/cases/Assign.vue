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
                v-model="form.user_id"
              >
              </v-select>
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="form.date"
                type="date"
                outlined
                dense
                :label="$t('general.selectDate')"
              ></v-text-field>
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
  name: "Assign",

  props: {
    value: Boolean,
    // eventItem: Object,
    id: Number,
    // requests: Array,
  },
  data() {
    return {
      form: { user_id: null, date: "" },
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
  },
  watch: {
    requests() {
      // this.refresh()
    },
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
      let requestIds = [];
      // for (let i = 0; i < this.requests.length; i++) {
      requestIds.push(this.id);
      // }
      let data = {
        form_request_id: requestIds,
        user_id: this.form.user_id,
        date: this.form.date,
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
