<script>
import $ from "jquery";
import CopyLabel from "@/components/common/CopyLabel";
import { mapActions, mapState } from "vuex";
import { ask, makeToast } from "@/helpers";
import AddTreatmentDialog from "./AddTreatmentDialog.vue";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";
export default {
  components: {
    CopyLabel,
    emptyDataSvg,
    AddTreatmentDialog,
  },
  data() {
    return {
      page: 1,
      dialog: false,
      totaltemplates: 0,
      numberOfPages: 0,
      options: {},
      isLoading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.transactions"),
        },
      ],

      searchQuery: null,
      selectedtemplates: [],
      templateItems: [],
      headers: [
        {
          text: this.$t("tables.id"),
          value: "id",
        },
        {
          text: this.$t("tables.name"),
          value: "name",
        },
        // {
        //   text: this.$t("tables.type"),
        //   value: "type",
        // },
        {
          text: this.$t("tables.email"),
          value: "email",
        },
        {
          text: this.$t("tables.created"),
          value: "created_at",
        },
        // {
        //     text: this.$t("tables.actions"),
        //     value: "actions"
        // },
      ],
    };
  },
  watch: {
    selectedTemplates(val) {},
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
    ...mapState("treatments", ["treatments"]),
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.transactions"),
    });
    this.getDepartments({
      pageSize: -1,
    });
  },
  methods: {
    ...mapActions("treatments", ["getTreatments"]),
    ...mapActions("app", ["setBreadCrumb"]),
    ...mapActions("departments", ["getDepartments"]),
    searchtemplate() {},
    treatmentCreated() {
      this.open(true);
    },
    open() {
      this.isLoading = true;
      let { page, itemsPerPage } = this.options;
      // const direction = this.options.sortDesc[0] ? "asc" : "desc";

      let data = {
        search: this.searchQuery,
        pageSize: itemsPerPage,
        pageNumber: page,
        // sortDirection: direction,
        // sortColumn: this.options.sortBy[0] ?? "",
      };
      this.getTreatments(data)
        .then(() => {
          this.isLoading = false;
          if (itemsPerPage != -1) {
            this.templateItems = this.treatments.data;
            this.totaltemplates = this.treatments.total;
            this.numberOfPages = this.treatments.last_page;
          } else {
            this.templateItems = this.treatments;
            this.totaltemplates = this.treatments.length;
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
          this.selectedTemplates.forEach((item) => {
            ids.push(item.id);
          });
        }
        data = {
          ids: ids,
          action: "delete",
          value: 1,
        };
        this.isLoading = true;
        this.deleteAll(data)
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

    pushRoute(id) {
      // console.log("id", id);
      this.$router.push(`/treatments/list/${id}`);
    },
  },
  mounted() {
    this.open(true);
  },
};
</script>

<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-btn color="primary" class="py-3 mb-2" @click="dialog = true">{{
      $t("transactions.new_transaction")
    }}</v-btn>
    <v-card class="px-3 pt-2" v-if="!isLoading">
      <div
        class="transaction-card mb-5"
        v-for="treat in templateItems"
        :key="treat.id"
      >
        <v-row class="py-0 px-2" align="center">
          <v-col cols="3">
            <h4>هـ1409نظام المركز الوطني للوثائق والمحفوظات لعام</h4>
          </v-col>
          <v-col cols="8" class="py-0">
            <div class="sm-cards">
              <div class="sm-crd py-1">
                <span>اسم المعاملة</span>
                <h5>{{ treat.name ? treat.name : "-" }}</h5>
              </div>
              <div class="sm-crd py-1">
                <span>رقم المعاملة</span>
                <h5>
                  {{ treat.treatment_number ? treat.treatment_number : "-" }}
                </h5>
              </div>
              <div class="sm-crd py-1">
                <span>تاريخ المعاملة</span>
                <h5>{{ treat.date ? treat.date : "-" }}</h5>
              </div>
              <div class="sm-crd py-1">
                <span>نوع المعاملة</span>
                <h5>{{ treat.type ? treat.type : "-" }}</h5>
              </div>
              <div class="sm-crd py-1">
                <span>الإدارة</span>
                <h5>{{ treat.department ? treat.department.name : "-" }}</h5>
              </div>
            </div>
          </v-col>
          <v-col cols="1" class="py-0" justify="end">
            <div class="sm-actions d-flex flex-column p-2 gap-2">
              <!-- <router-link
                :to="`list/${treat.id}`"
                class="d-flex align-center text-decoration-none mr-2"
              >
                <v-icon small>mdi-eye-outline</v-icon>
              </router-link> -->
              <v-btn color="white" @click="pushRoute(treat.id)">
                <v-icon small>mdi-eye-outline</v-icon>
              </v-btn>
              <v-btn color="primary">
                <v-icon small>mdi-trash-can-outline</v-icon>
              </v-btn>
            </div>
          </v-col>
        </v-row>
      </div>
    </v-card>
    <v-card class="loader-wrap text-center p-3" v-else>
      <!-- <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div> -->
      <div class="loading">
        <div class="effect-1 effects"></div>
        <div class="effect-2 effects"></div>
        <div class="effect-3 effects"></div>
      </div>
    </v-card>
    <add-treatment-dialog
      @treatmentCreated="treatmentCreated"
      v-if="dialog"
      v-model="dialog"
    ></add-treatment-dialog>
  </div>
</template>

<style lang="scss" scoped>
.loader-wrap {
  position: relative;

  .loading {
    inset-block-start: 0% !important;
    position: relative !important;
  }
}

.transaction-card {
  width: 100%;
  min-height: 100px;
  // min-height: 150px;
  background: #f9f9f9;

  .sm-cards {
    display: flex;
    // flex-wrap: wrap;
    background: #ececec;
    border-radius: 8px;
    padding: 10px;

    .sm-crd {
      display: flex;
      flex-direction: column;
      width: calc((100% / 5) - 5px);
      max-width: calc((100% / 5) - 5px);
      background: #fff;
      border-radius: 8px;
      margin: 0 5px;
      padding: 0 5px;

      span {
        font-size: small;
        color: #475467;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      h5 {
        color: #d6a859;
        width: 100%;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }
    }
  }

  .sm-actions {
    background: #ececec;
    border-radius: 8px;
    width: fit-content;
    margin-right: auto;

    button {
      width: 36px !important;
      min-width: 36px !important;
      margin: 0 auto;
    }
  }

  h4 {
    color: #0f2056;
  }
}

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
