<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center py-3">
      <div>
        <div class="display-1">{{ $t('reports.draftedReports') }}</div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
    </div> -->
    <v-card>
      <!-- drafted list -->
      <v-row dense class="pa-2 align-center">
        <v-col cols="6">
          <v-menu offset-y left>
            <template v-slot:activator="{ on }">
              <transition name="slide-fade" mode="out-in">
                <v-btn v-show="selected.length > 0" v-on="on">
                  {{ $t("general.actions") }}
                  <v-icon right>mdi-menu-down</v-icon>
                </v-btn>
              </transition>
            </template>
            <v-list dense>
              <!--              <v-list-item >-->
              <!--                <v-list-item-title>{{ $t('general.verify') }}</v-list-item-title>-->
              <!--              </v-list-item>-->
              <!--              <v-list-item >-->
              <!--                <v-list-item-title>{{ $t('general.disabled') }}</v-list-item-title>-->
              <!--              </v-list-item>-->
              <!--              <v-divider></v-divider>-->
              <v-list-item @click="deleteAll()">
                <v-list-item-title>{{
                  $t("general.delete")
                }}</v-list-item-title>
              </v-list-item>
            </v-list>
          </v-menu>
        </v-col>
        <v-col cols="6" class="d-flex text-right align-center">
          <v-text-field
            v-model="searchQuery"
            append-icon="mdi-magnify"
            class="flex-grow-1 mr-md-2"
            solo
            hide-details
            dense
            clearable
            :placeholder="$t('general.search')"
          ></v-text-field>
          <v-btn
            :loading="isLoading"
            icon
            @click.prevent="open()"
            small
            class="ml-2"
          >
            <v-icon>mdi-refresh</v-icon>
          </v-btn>
        </v-col>
      </v-row>
      <v-data-table
        show-select
        v-model="selected"
        :headers="headers"
        :items="items"
        :options.sync="options"
        :loading="isLoading"
        :page="page"
        :pageCount="numberOfPages"
        :server-items-length="total"
      >
        <template v-slot:item.id="{ item }">
          <div class="font-weight-bold">
            # <copy-label :text="item.id + ''" />
          </div>
        </template>

        <!--        <template v-slot:item.title="{ item }">-->
        <!--          <div class="d-flex align-center py-1">-->
        <!--            <div class="ml-1 caption font-weight-bold">-->
        <!--              <copy-label :text="item.title" />-->
        <!--            </div>-->
        <!--          </div>-->
        <!--        </template>-->

        <template v-slot:item.report_type="{ item }">
          <div>
            {{ item.report_type }}
          </div>
        </template>

        <!--        <template v-slot:item.start="{ item }">-->
        <!--          <div>-->
        <!--            {{ item.start }}-->
        <!--          </div>-->
        <!--        </template>-->

        <!--        <template v-slot:item.end="{ item }">-->
        <!--          <div>-->
        <!--            {{ item.end }}-->
        <!--          </div>-->
        <!--        </template>-->

        <template v-slot:item.timeRange="{ item }">
          <span v-if="item.time_range === '15'">{{
            $t("general.lastMonth")
          }}</span>
          <span v-else-if="item.time_range === '16'">{{
            $t("general.thisMonth")
          }}</span>
          <span v-else-if="item.time_range === '13'">{{
            $t("general.lastWeek")
          }}</span>
          <span v-else-if="item.time_range === '14'">{{
            $t("general.thisWeek")
          }}</span>
          <span v-else-if="item.time_range === 'today'">{{
            $t("general.today")
          }}</span>
        </template>

        <!--        <template v-slot:item.model_type="{ item }">-->
        <!--          <div>-->
        <!--            {{ item.model_type }}-->
        <!--          </div>-->
        <!--        </template>-->

        <template v-slot:item.sites="{ item }">
          <v-chip
            label
            small
            color="primary"
            v-for="(site, index) in item.sites"
            :key="index"
            class="font-weight-bold ma-1"
          >
            {{ site.name }}
          </v-chip>
        </template>

        <template v-slot:item.created_at="{ item }">
          <div>{{ item.created_at | formatDate("lll") }}</div>
        </template>

        <template v-slot:item.createdBy="{ item }">
          <div>{{ item.created_by.name }}</div>
        </template>

        <template v-slot:item.action="{ item }">
          <div class="actions">
            <v-btn
              dark
              small
              icon
              color="primary"
              :to="`/reports/drafted/show/${item.id}`"
              v-can="'update-user'"
            >
              <v-icon>mdi-eye</v-icon>
            </v-btn>
            <v-btn
              icon
              small
              dark
              color="green"
              :to="`/reports/drafted/edit/${item.id}`"
            >
              <v-icon>mdi-open-in-new</v-icon>
            </v-btn>
            <v-btn
              icon
              small
              dark
              color="error"
              @click.prevent="deleteItem(item.id)"
            >
              <v-icon>mdi-close</v-icon>
            </v-btn>
          </div>
        </template>
        <template v-slot:no-data>
          <div class="text-center my-2 primary--text" color="primary">
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
import CopyLabel from "../../../components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
  name: "Drafted",
  components: {
    CopyLabel,
    emptyDataSvg,
  },
  data() {
    return {
      page: 1,
      total: 0,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("reports.reports"),
          disabled: true,
        },
        {
          text: this.$t("reports.draftedReports"),
        },
      ],

      searchQuery: "",
      selected: [],
      items: [],
      headers: [
        { text: this.$t("tables.id"), value: "id" },
        { text: this.$t("reports.title"), value: "title" },
        { text: this.$t("reports.reportType"), value: "report_type" },
        { text: this.$t("reports.timeRange"), value: "timeRange" },
        { text: this.$t("reports.fromDate"), value: "start", width: "10%" },
        { text: this.$t("reports.toDate"), value: "end", width: "10%" },
        { text: this.$t("reports.model"), value: "model_type" },
        { text: this.$t("reports.sites"), value: "sites", width: "20%" },
        { text: this.$t("tables.created"), value: "created_at" },
        { text: this.$t("reports.createdBy"), value: "createdBy" },
        {
          text: "",
          sortable: false,
          align: "right",
          value: "action",
          width: "15%",
        },
      ],
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("reports.draftedReports"),
    });
  },
  watch: {
    selected(val) {},
    options: {
      handler() {
        this.open();
      },
    },
    deep: true,
    searchQuery() {
      this.open();
    },
  },
  computed: {
    ...mapState("reports", ["drafts", "draftTitle"]),
  },

  methods: {
    ...mapActions("reports", ["getDrafted", "deleteDraft", "deleteAllDrafts"]),
    ...mapActions("app", ["setBreadCrumb"]),
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      const direction = this.options.sortDesc[0] ? "asc" : "desc";
      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        sortDirection: direction,
        sortColumn: this.options.sortBy[0] ?? "",
      };
      this.getDrafted(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.items = this.drafts.data;
            this.total = this.drafts.total;
            this.numberOfPages = this.drafts.last_page;
          } else {
            this.items = this.drafts;
            this.total = this.drafts.length;
            this.numberOfPages = 1;
          }
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    async deleteItem(id) {
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");

      if (isConfirmed) {
        this.isLoading = true;
        this.deleteDraft(id)
          .then((response) => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },

    async deleteAll() {
      let data = {};
      let ids = [];
      const { isConfirmed } = await ask("Are you sure to delete it?", "info");
      if (isConfirmed) {
        if (this.selected.length) {
          this.selected.forEach((item) => {
            ids.push(item.id);
          });
        }
        data = {
          ids: ids,
          action: "delete",
          value: 1,
        };
        this.isLoading = true;
        this.deleteAllDrafts(data)
          .then((response) => {
            makeToast("success", response.data.message);
            this.open();
            this.isLoading = false;
          })
          .catch(() => {
            this.isLoading = false;
          });
      }
    },
  },
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
