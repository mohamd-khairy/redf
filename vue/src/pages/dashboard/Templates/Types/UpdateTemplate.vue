<template>
    <div class="flex-grow-1 my-2">
        <v-card>
            <v-card-title>{{ $t('templates.updateTemplate') }}</v-card-title>
            <v-card-text>
                <div class="d-flex flex-column flex-sm-row">
                    <div class="flex-grow-1 pt-2 pa-sm-2">
                        <v-row>
                            <v-col cols="6">
                                <v-text-field v-model="template.name" :rules="[rules.required]" :label="$t('tables.name')"
                                    :error-messages="errors['name']" color="primary"></v-text-field>
                            </v-col>
                        </v-row>

                        <div class="mt-2">
                            <v-btn :loading="loading" :disabled="loading" @click="updateTemplateII" color="primary">
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
        axios.get(`templates/${this.$route.params.id}`).then((data) => {
            this.template = data.data.data
        })
    },
    methods: {
        ...mapActions("templates", ["updateTemplate"]),
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
            console.log("Update in progress ...");
            this.loading = true;
            this.errors = {};
            let form = this.buildForm(this.template)
            this.updateTemplate()
            this.updateTemplate(form)
                .then(response => {
                    this.loading = false;
                    makeToast("success", response.data.message);
                    this.$router.push({ name: "Templates" });
                })
                .catch(error => {
                    this.loading = false;
                    if (error.response.status == 422) {
                        const { errors } = error?.response?.data ?? {};
                        this.errors = errors ?? {};
                    }
                });
        },

    }
};
</script>
