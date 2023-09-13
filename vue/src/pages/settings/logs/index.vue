<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-row>
      <v-col cols="12">
        <v-card class="mb-60">
          <v-card-title>
            <v-row dense class=" align-center">
              <v-col cols="3" class="d-flex text-right align-center">
                <v-select :disabled="files.length === 0" class=" mx-1" :label="$t('general.types')" dense :items="files"
                  item-text="name" item-value="identifier" hide-details v-model="selectedFile" return-object
                  @change="getLogsData" solo></v-select>
              </v-col>
              <v-col cols="4">
                <v-text-field :disabled="items.length === 0" v-model="searchQuery" append-icon="mdi-magnify"
                  class="flex-grow-1  mx-1" hide-details dense solo clearable :placeholder="$t('general.search')"
                  @keyup.enter="getLogsData(searchQuery)"></v-text-field>
              </v-col>

              <v-spacer></v-spacer>
              <v-col cols="1" class="d-flex justify-end">
                <v-btn :loading="isLoading" icon small class="mx-1" @click.prevent="getLogsData()">
                  <v-icon>mdi-refresh</v-icon>
                </v-btn>
              </v-col>
            </v-row>
          </v-card-title>
          <v-card-text>
            <v-data-table v-model="logsData" show-select :headers="headers" :items="items" item-key="index" show-expand
              :single-expand="true" class="flex-grow-1 cursor-pointer" :page="page" :pageCount="numberOfPages"
              :server-items-length="total" :show-select="false" :footer-props="{
                'items-per-page-options': [10, 25, 50]
              }">
              <template v-slot:item.id="{ item }">
                <div class="font-weight-bold">{{ item.id }}</div>
              </template>

              <template v-slot:item.date="{ item }">
                <div>{{ formatDateTime(item.date).date }}</div>
              </template>
              <template v-slot:item.environment="{ item }">
                <div>{{ item.environment }}</div>
              </template>

              <template v-slot:item.level="{ item }">
                <v-chip class="ma-1" :color="item.level === 'error' ? 'red' : 'blue'" text-color="white" small>
                  {{ item.level }}
                </v-chip>
              </template>

              <template v-slot:item.full_text="{ item }">
                <div class="truncate d-flex align-center text-no-wrap">
                  {{ item.full_text }}
                </div>
              </template>
              <!-- <template v-slot:item.url="{ item }">
                <div class="truncate d-flex align-center text-no-wrap">
                  <copy-label text="copy" :value="item.url" />
                </div>
              </template> -->
              <template v-slot:expanded-item="{ headers, item }">
                <td :colspan="headers.length">
                  {{ item.full_text }}
                </td>
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
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
import CopyLabel from "../../../components/common/CopyLabel.vue";

export default {
  components: { emptyDataSvg, CopyLabel },
  data() {
    return {
      isLoading: false,
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.settings"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.logs")
        }
      ],
      searchQuery: "",
      logsData: [],
      items: [],
      options: {},
      page: 1,
      total: 0,
      numberOfPages: 0,
      files: [],
      selectedFile: null,
      headers: [
        { text: this.$t("tables.index"), align: "start", value: "index" },
        { text: this.$t("tables.level"), align: "start", value: "level" },
        { text: this.$t("tables.datetime"), value: "datetime" },
        { text: this.$t("tables.environment"), value: "environment" },
        {
          text: this.$t("tables.description"),
          sortable: false,
          value: "full_text"
        },
        // { text: this.$t("tables.url"), sortable: false, value: "url" }
        { text: "", value: "data-table-expand" }
      ]
    };
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.logs")
    });
    this.getFiles();
    // this.getLogsData();
  },
  watch: {},
  computed: {},
  mounted() { },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    getFiles() {
      this.$axios
        .get(
          "https://drones-api.wakeb.tech/api/log-viewer/api/folders?direction=desc"
        )
        .then(response => {
          const { data } = response;
          console.log(data);
          if (!data.length) return;

          this.files = data[0].files;
          if (!this.files.length) return;
          this.selectedFile = this.files[0].identifier;
          this.getLogsData();
          console.log(this.selectedFile);
        });
    },
    getLogsData(query = "") {
      this.$axios
        .get(
          `https://drones-api.wakeb.tech/api/log-viewer/api/logs?file=${this.selectedFile}&direction=asc&query=${query}&per_page=25&levels[]=debug&levels[]=info&levels[]=notice&levels[]=warning&levels[]=error&levels[]=critical&levels[]=alert&levels[]=emergency&levels[]=processing&levels[]=processed&levels[]=failed&levels[]=&shorter_stack_traces=false`
        )
        .then(response => {
          const data = response.data;
          const { current_page: currentPage, last_page: lastPage } = data;
          this.items = data?.logs || [];

          this.page = currentPage;
          this.total = lastPage;
          this.numberOfPages = lastPage;
        });
    }
  }
};
</script>

<style>
.truncate {
  max-width: 200px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>
