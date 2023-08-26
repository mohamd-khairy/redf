<template>
  <v-row justify="center">
    <v-dialog v-model="dialog" max-width="500">
      <v-card>
        <v-toolbar dark color="primary">
          <v-toolbar-title>{{ $t("cases.addUser") }}</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-toolbar-items>
            <v-btn icon dark @click="dialog = false">
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </v-toolbar-items>
        </v-toolbar>

        <v-card-text class="mt-4">
          <v-row dense>
            <v-col cols="12">
              <v-text-field
                type="number"
                v-model="user.civil_number"
                @keydown="handleInput"
                outlined
                dense
                :rules="civilRules"
                :label="$t('cases.civil')"
                :error-messages="showErrorMsg(user.civil_number)"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="user.name"
                outlined
                dense
                :rules="[rules.required]"
                :label="$t('tables.name')"
                @keydown="handlename"
                :error-messages="showErrorMsg(user.name)"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                type="text"
                v-model="user.phone"
                :rules="phoneNumberRules"
                outlined
                dense
                :label="$t('tables.phone')"
                :error-messages="showErrorMsg(user.phone)"
              ></v-text-field>
            </v-col>
            <v-col cols="12">
              <v-text-field
                type="email"
                v-model="user.email"
                :rules="emailRules"
                outlined
                dense
                :label="$t('tables.email')"
                :error-messages="showErrorMsg(user.email)"
              ></v-text-field>
            </v-col>
            <!-- <v-col cols="12">
              <v-select
                label="Select"
                :items="departments"
                item-text="name"
                item-value="id"
                outlined
                dense
                :label="$t('tables.department')"
                hide-details
                :rules="[rules.required]"
                v-model="user.department_id"
              ></v-select>
            </v-col> -->
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="py-2">
          <v-spacer></v-spacer>

          <v-btn color="primary" @click="save">
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
import axios from "axios";

export default {
  name: "AddUserDialog",

  props: {
    value: Boolean,
    // eventItem: Object,
    // id: Number,
  },
  data() {
    return {
      user: {
        name: "",
        showError: false,
        civil_number: "",
        phone: "",
        email: "",
        // department_id: "",
      },
      rules: {
        required: (value) =>
          (value && Boolean(value)) || this.$t("general.fieldRequired"),
      },
      phoneNumberRules: [
        (v) => !!v || "رقم الهاتف مطلوب",
        (v) => (v && v.length === 10) || "رقم الهاتف يجب أن يكون 10 أرقام",
        (v) => /^[0-9]+$/.test(v) || "يجب أن يحتوي رقم الهاتف على أرقام فقط",
      ],
      emailRules: [
        (v) => !!v || "البريد الالكتروني مطلوب",
        (v) =>
          /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
          "تنسيق البريد الإلكتروني غير صالح",
      ],
      civilRules: [(v) => (v && v.length === 10) || "يجب ان يكون 10 أرقام"],
      errors: {},
      loading: false,
    };
  },
  computed: {
    ...mapState("departments", ["departments"]),
    dialog: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("input", value);
      },
    },
    event: {
      get() {
        return this.$store.state.events.event;
      },
      set(val) {
        this.$store.commit("events/SET_EVENT", val);
      },
    },
  },
  watch: {
    // 'id'(){
    //   this.refresh()
    // }
  },
  methods: {
    ...mapActions("cases", ["createUser"]),
    refresh() {
      this.loading = true;
      this.event = this.eventItem;
      const { status, notes, id } = this.eventItem ?? {};
      this.form = { status, notes, id };
    },
    handleInput(event) {
      if (event.key.toLowerCase() === "e") {
        event.preventDefault();
      }
    },
    showErrorMsg(value) {
      const msg = this.$t("general.required_input");
      return this.showError && !value ? [msg] : [];
    },
    handlename(event) {
      if (/\d/.test(event.key)) {
        event.preventDefault();
      }
    },
    fetchData: function () {
      this.$root.$emit("userCreated");
      this.$emit("userCreated");
    },
    save() {
      this.loading = true;
      this.errors = {};
      if (
        !this.user.name ||
        !this.user.civil_number ||
        !this.user.phone ||
        !this.user.email
      ) {
        makeToast("error", "يرجي ملئ الحقول المطلوبة");
        return;
      }
      let data = {
        name: this.user.name,
        civil_number: Number(this.user.civil_number),
        phone: this.user.phone,
        email: this.user.email,
        // department_id: this.user.department_id,
      };
      this.createUser(data)
        .then((response) => {
          this.loading = false;
          this.dialog = false;
          this.fetchData();
          this.errors = {};
          makeToast("success", this.$t("general.new_beneficiary_added"));
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
