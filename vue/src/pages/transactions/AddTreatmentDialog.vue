<template>
    <v-row justify="center">
        <v-dialog v-model="dialog" max-width="600">
            <v-card>
                <v-toolbar dark color="primary">
                    <v-toolbar-title>{{ $t("transactions.new_transaction") }}</v-toolbar-title>
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
                            <v-text-field type="text" v-model="treatment.name" @keydown="handleInput" outlined dense
                                :label="$t('transactions.name')"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field type="number" v-model="treatment.treatment_number" @keydown="handleInput" outlined
                                dense :label="$t('transactions.number')"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field type="date" v-model="treatment.date" @keydown="handleInput" outlined dense
                                :rules="[rules.required]" :label="$t('transactions.date')"
                                :error-messages="showErrorMsg(treatment.date)"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-select v-model="treatment.type" :label="$t('transactions.type')" outlined dense
                                :items="types" item-value="value" item-text="name" @change="loadConsalts"></v-select>
                        </v-col>
                        <v-col cols="12" v-if="treatment.type == 'consultation'">
                            <v-select v-model="selectedConsultations" :label="$t('transactions.consultations')" outlined
                                dense :items="consultations" item-value="value" item-text="name"></v-select>
                        </v-col>
                        <v-col cols="12">
                            <v-select v-model="treatment.department_id" :label="$t('transactions.department')" outlined
                                dense :items="departments" item-value="id" item-text="name"></v-select>
                        </v-col>
                        <!-- <v-col cols="12">
                            <v-text-field type="number" v-model="user.civil_number" @keydown="handleInput" outlined dense
                                :rules="civilRules" :label="$t('cases.civil')"
                                :error-messages="showErrorMsg(user.civil_number)"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field v-model="user.name" outlined dense :rules="[rules.required]"
                                :label="$t('tables.name')" @keydown="handlename"
                                :error-messages="showErrorMsg(user.name)"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field type="text" v-model="user.phone" :rules="phoneNumberRules" outlined dense
                                :label="$t('tables.phone')" :error-messages="showErrorMsg(user.phone)"></v-text-field>
                        </v-col>
                        <v-col cols="12">
                            <v-text-field type="email" v-model="user.email" :rules="emailRules" outlined dense
                                :label="$t('tables.email')" :error-messages="showErrorMsg(user.email)"></v-text-field>
                        </v-col> -->
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
            isLoading: false,
            types: [
                { name: `${this.$t('transactions.types.preparing_speech')}`, value: 'preparing_speech' },
                { name: `${this.$t('transactions.types.consultation')}`, value: 'consultation' },
                { name: `${this.$t('transactions.types.normal')}`, value: 'normal' },
                { name: `${this.$t('transactions.types.another_treatment')}`, value: 'another_treatment' },
            ],
            selectedConsultations: [],
            showError: false,
            treatment: {
                name: "",
                treatment_number: "",
                department_id: "",
                status: "",
                type: "",
                date: "",
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
        ...mapState("users", ["consultations"]),
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
    mounted() {
        this.fetchData()
    },
    methods: {
        ...mapActions("users", ["getConsultations", "storeTreatment"]),
        ...mapActions("departments", ["getDepartments"]),
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
        fetchData() {
            // Departments
            this.isLoading = true;
            let data = {
                pageSize: -1,
            };
            this.getDepartments(data)
                .then((response) => {
                    this.isLoading = false;
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },
        loadConsalts() {
            if (this.treatment.type == "consultation") {
                this.getConsultations()
                    .then((response) => {
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            }

        },
        save() {
            this.isLoading = true;
            this.errors = {};
            if (this.selectedConsultations.length > 0) {
                this.treatment.consultations = this.selectedConsultations
            }
            let data = this.treatment
            this.storeTreatment(data)
                .then((response) => {
                    this.isLoading = true;
                    this.dialog = false;
                    this.errors = {};
                    makeToast("success", this.$t("general.new_treatment_added"));
                })
                .catch((error) => {
                    this.isLoading = true;
                    // if (error.response.status == 422) {
                    //   const { errors } = error?.response?.data;
                    //   this.errors = errors ?? {};
                    // }
                });
        },
    },
};
</script>
  