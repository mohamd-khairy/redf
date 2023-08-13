<template>
    <div class="flex-grow-1 my-2">
        <v-tabs background-color="#014c4f" center-active dark>
            <v-tab>
                {{ $t("templates.basic_data") }}
            </v-tab>
            <v-tab>
                {{ $t("templates.template_edit") }}
            </v-tab>

            <v-tab-item>
                <v-card>
                    <v-card-title>{{ $t('templates.updateTemplate') }}</v-card-title>
                    <v-card-text>
                        <div class="d-flex flex-column flex-sm-row">
                            <div class="flex-grow-1 pt-2 pa-sm-2">
                                <v-row>
                                    <v-col cols="6">
                                        <v-text-field v-model="template.name" :rules="[rules.required]"
                                            :label="$t('tables.name')" :error-messages="errors['name']"
                                            color="#014c4f"></v-text-field>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-text-field v-model="template.description" :rules="[rules.required]"
                                            :label="$t('tables.description')" :error-messages="errors['description']"
                                            color="#014c4f"></v-text-field>
                                    </v-col>
                                </v-row>

                                <div class="mt-2">
                                    <v-btn :loading="loading" :disabled="loading" @click="updateTemplateII" color="#014c4f">
                                        {{ $t("general.save") }}
                                    </v-btn>
                                </div>
                            </div>
                        </div>
                    </v-card-text>
                </v-card>
            </v-tab-item>
            <v-tab-item>
                <DynamicFormBuilder :id="1"></DynamicFormBuilder>
            </v-tab-item>
        </v-tabs>

    </div>
</template>
  
<script>
import { mapActions } from "vuex";
import { makeToast } from "@/helpers";
import DynamicFormBuilder from "./DynamicFormBuilder.vue"
import axios from 'axios';

export default {
    data() {
        return {
            template: {
                name: "",
                description: "",
                template_id: "",
            },
            errors: {},
            loading: false,
            breadcrumbs: [
                {
                    text: this.$t("menu.templates"),
                    disabled: false,
                    href: "#"
                },
                {
                    text: this.$t("templates.templatesList"),
                    to: "/templates",
                    exact: true
                },
                {
                    text: `${this.$t("templates.updateTemplate")}`
                },
            ],
            rules: {
                required: (value) => (value && Boolean(value)) || this.$t("general.fieldRequired")
            }
        };
    },
    created() {
        this.setBreadCrumb({
            breadcrumbs: this.breadcrumbs,
            pageTitle: this.$t("templates.updateTemplate")
        });
        axios.get(`get-form/${this.$route.params.id}`).then((data) => {
            this.template = data.data.data
        })
    },
    methods: {
        ...mapActions("templates", ["updateForm"]),
        ...mapActions("app", ["setBreadCrumb"]),
        buildForm(data) {
            let keys = Object.keys(data);
            let form = new FormData();
            form.append("_method", "PUT")
            for (let index = 0; index < keys.length; index++) {
                const key = keys[index];
                if (data[key]) {
                    form.set(key, data[key]);
                }
            }
            return form;
        },
        updateTemplateII() {
            this.loading = true;
            this.errors = {};
            let form = this.buildForm(this.template)
            this.updateForm(form)
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
        },

    },
    components: {
        DynamicFormBuilder
    }
};
</script>
  