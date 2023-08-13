<template>
    <div class="d-flex flex-column flex-grow-1">
        <v-card>
            <!-- templates list -->
            <v-row dense class="pa-2 align-center">
                <v-col cols="6">
                    <v-menu offset-y left>
                        <template v-slot:activator="{ on }">
                            <transition name="slide-fade" mode="out-in">
                                <v-btn v-show="selectedtemplates.length > 0" v-on="on">
                                    {{ $t("general.actions") }}
                                    <v-icon right>mdi-menu-down</v-icon>
                                </v-btn>
                            </transition>
                        </template>
                        <v-list dense>

                            <v-list-item>
                                <v-list-item-title>{{
                                    $t("general.delete")
                                }}</v-list-item-title>
                            </v-list-item>
                        </v-list>
                    </v-menu>
                </v-col>
                <v-col cols="6" class="d-flex text-right align-center">
                    <v-text-field v-model="searchQuery" append-icon="mdi-magnify" class="flex-grow-1 mr-md-2" solo
                        hide-details dense clearable :placeholder="$t('general.search')"
                        @keyup.enter="searchtemplate(searchQuery)"></v-text-field>

                    <!-- <v-tooltip top>
                        <template v-slot:activator="{ on, attrs }">
                            <v-btn color="primary" class="mx-2 " elevation="0" v-bind="attrs" v-on="on"
                                to="/templates/types/create" v-can="'create-user'">
                                <v-icon>
                                    mdi-plus
                                </v-icon>
                            </v-btn>
                        </template>
                        <span>{{ $t("templates.createTemplate") }}</span>
                    </v-tooltip> -->
                    <v-btn color="#014c4f" elevation="0" :loading="isLoading" @click.prevent="open()" class="mx-2">
                        <v-icon>mdi-refresh</v-icon>
                    </v-btn>
                </v-col>
            </v-row>
            <v-data-table show-select v-model="selectedtemplates" :headers="headers" :items="templateItems"
                :options.sync="options" class="flex-grow-1" :loading="isLoading" :page="page" :pageCount="numberOfPages"
                :server-items-length="totaltemplates">
                <template v-slot:item.id="{ item }">
                    <div class="font-weight-bold">
                        # <copy-label :text="templateItems.indexOf(item) + 1 + ''" />
                    </div>
                </template>

                <template v-slot:item.created_at="{ item }">
                    <div>{{ item.created_at | formatDate("lll") }}</div>
                </template>

                <template v-slot:item.actions="{ item }">
                    <div class="actions">
                        <v-btn color="#014c4f" icon :to="`/templates/types/edit/${item.id}`">
                            <v-icon>mdi-pencil</v-icon>
                        </v-btn>
                        <v-btn color="error" icon @click.prevent="deleteItem(item.id)">
                            <v-icon>mdi-delete</v-icon>
                        </v-btn>
                    </div>
                </template>
                <template v-slot:no-data>
                    <div class="text-center my-2 primary--text" color="#014c4f">
                        <emptyDataSvg></emptyDataSvg>
                        <div class="dt-no_data">
                            {{ $t("general.no_data_available") }}
                        </div>
                    </div>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
import $ from 'jquery'
import CopyLabel from "@/components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
    components: {
        CopyLabel,
        emptyDataSvg
    },
    data() {
        return {
            page: 1,
            totaltemplates: 0,
            numberOfPages: 0,
            options: {},
            isLoading: false,
            breadcrumbs: [
                {
                    text: this.$t("menu.dashboard"),
                    disabled: false,
                    href: "/"
                },
                {
                    text: this.$t("menu.beneficiaries")
                }
            ],

            searchQuery: "",
            selectedtemplates: [],
            templateItems: [],
            headers: [
                {
                    text: this.$t("tables.id"),
                    value: "id"
                },
                {
                    text: this.$t("tables.name"),
                    value: "name"
                },
                {
                    text: this.$t("tables.created"),
                    value: "created_at"
                },
                {
                    text: this.$t("tables.actions"),
                    value: "actions"
                },

            ],
        };
    },
    watch: {
        selectedTemplates(val) { },
        options: {
            handler() {
                this.open();
            }
        },
        deep: true,
        searchQuery() {
            this.open();
        }
    },
    computed: {
        ...mapState("users", ["beneficiaries"])
    },
    created() {
        this.setBreadCrumb({
            breadcrumbs: this.breadcrumbs,
            pageTitle: this.$t("menu.beneficiaries")
        });
    },
    methods: {
        ...mapActions("users", ["getBeneficiaries"]),
        ...mapActions("app", ["setBreadCrumb"]),
        searchtemplate() { },
        open() {
            this.isLoading = true;
            let { page, itemsPerPage } = this.options;
            const direction = this.options.sortDesc[0] ? "asc" : "desc";

            let data = {
                search: this.searchQuery,
                pageSize: itemsPerPage,
                pageNumber: page,
                sortDirection: direction,
                sortColumn: this.options.sortBy[0] ?? ""
            };
            this.getBeneficiaries(data)
                .then(() => {
                    this.isLoading = false;
                    if (itemsPerPage != -1) {
                        this.templateItems = this.templates.data;
                        this.totaltemplates = this.templates.total;
                        this.numberOfPages = this.templates.last_page;
                    } else {
                        this.templateItems = this.templates;
                        this.totaltemplates = this.templates.length;
                        this.numberOfPages = 1;
                    }
                })
                .catch(() => {
                    this.isLoading = false;
                });
        },

        async deleteAlltemplates() {
            let data = {};
            let ids = [];
            const { isConfirmed } = await ask("Are you sure to delete it?", "info");
            if (isConfirmed) {
                if (this.selectedTemplates.length) {
                    this.selectedTemplates.forEach(item => {
                        ids.push(item.id);
                    });
                }
                data = {
                    ids: ids,
                    action: "delete",
                    value: 1
                };
                this.isLoading = true;
                this.deleteAll(data)
                    .then(response => {
                        makeToast("success", response.data.message);
                        this.open();
                        this.isLoading = false;
                    })
                    .catch(() => {
                        this.isLoading = false;
                    });
            }
        }
    }
};
</script>

<style lang="scss" scoped>
.slide-fade-enter-active {
    transition: all 0.3s ease;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1, 0.5, 0.8, 1);
}

.slide-fade-enter,
.slide-fade-leave-to {
    transform: translateX(10px);
    opacity: 0;
}
</style>
