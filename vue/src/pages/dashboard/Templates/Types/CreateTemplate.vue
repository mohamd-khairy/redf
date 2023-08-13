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
                                    :error-messages="errors['name']" color="primary"></v-text-field>
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
                    text: this.$t("menu.templatesTypes"),
                    to: "/templates/types",
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
    },
    methods: {
        ...mapActions("templates", ["storeTemplate"]),
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
            this.storeTemplate(form)
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
        }
    }
};
</script>
