<template>
    <div class="flex-grow-1 my-2">
        <v-card>
            <v-card-title>{{ $t('templates.createTemplate') }}</v-card-title>
            <v-card-text>
                <div class="d-flex flex-column flex-sm-row">
                    <div class="flex-grow-1 pt-2 pa-sm-2">
                        <v-row>
                            <v-col cols="6">
                                <v-text-field v-model="template.name" :rules="[rules.required]" :label="$t('tables.name')"
                                    :error-messages="errors['name']" color="#014c4f"></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-text-field v-model="template.description" :rules="[rules.required]"
                                    :label="$t('tables.description')" :error-messages="errors['description']"
                                    color="#014c4f"></v-text-field>
                            </v-col>
                            <v-col cols="6">
                                <v-select v-model="template.template_id" :items="templatesList" item-text="name"
                                    item-value="id" :label="$t('tables.templates')" single-line></v-select>
                            </v-col>
                        </v-row>

                        <div class="mt-2">
                            <v-btn :loading="loading" :disabled="loading" @click="createTemplate" color="primary">
                                {{ $t("general.save") }}
                            </v-btn>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
import { mapActions } from "vuex";
import { makeToast } from "@/helpers";
import axios from 'axios';

export default {
    data() {
        return {
            template: {
                name: "",
                description: "",
                template_id: "",
            },
            select: { state: 'Florida', abbr: 'FL' },
            items: [
                { state: 'Florida', abbr: 'FL' },
                { state: 'Georgia', abbr: 'GA' },
                { state: 'Nebraska', abbr: 'NE' },
                { state: 'California', abbr: 'CA' },
                { state: 'New York', abbr: 'NY' },
            ],
            templatesList: [],
            errors: {},
            loading: false,
            breadcrumbs: [
                {
                    text: this.$t("menu.templates"),
                    disabled: false,
                    href: "#"
                },
                {
                    text: this.$t("menu.templatesTypes"),
                    to: "/templates/list",
                    exact: true
                },
                {
                    text: this.$t("templates.createTemplate")
                }
            ],
            rules: {
                required: (value) => (value && Boolean(value)) || this.$t("general.fieldRequired")
            }
        };
    },
    created() {
        this.setBreadCrumb({
            breadcrumbs: this.breadcrumbs,
            pageTitle: this.$t("templates.createTemplate")
        });
        axios.get("templates").then((data) => {
            this.templatesList = data.data.data.templates.data;
        })
    },
    methods: {
        ...mapActions("templates", ["storeForm"]),
        ...mapActions("app", ["setBreadCrumb"]),
        buildForm(data) {
            let keys = Object.keys(data);
            let form = new FormData();
            for (let index = 0; index < keys.length; index++) {
                const key = keys[index];
                if (data[key]) {
                    form.set(key, data[key]);
                }
            }
            return form;
        },
        createTemplate() {
            this.loading = true;
            this.errors = {};
            let form = this.buildForm(this.template)
            this.storeForm(form)
                .then(response => {
                    this.loading = false;
                    makeToast("success", response.data.message);
                    this.$router.push({ name: "TemplatesList" });
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status == 422) {
                        const { errors } = error?.response?.data ?? {};
                        this.errors = errors ?? {};
                    }
                });
        }
    }
};
</script>
