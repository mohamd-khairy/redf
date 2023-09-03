<template>
  <v-container class="">
    <!-- <div class="d-flex align-center pb-3">
      <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t("menu.pipesModel") }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
    </div> -->

    <v-row>
      <v-col v-if="loading">
        <v-card-text v-if="loading" class="dialog-loading-cont">
          <v-row align-content="center" justify="center">
            <v-col class="text-subtitle-1 text-center" cols="12">
              {{ $t("general.getting_data") }}
            </v-col>
            <v-col cols="12">
              <v-progress-linear
                color="primary accent-4"
                indeterminate
                rounded
                height="6"
              ></v-progress-linear>
            </v-col>
          </v-row>
        </v-card-text>
      </v-col>
      <v-col v-else-if="!forms.length && !loading" cols="12">
        <div class="text-center mt-7 primary--text" color="primary">
          <emptyDataSvg></emptyDataSvg>
          <div class="dt-no_data">
            {{ $t("general.no_forms") }}
          </div>
        </div>
      </v-col>
      <v-col v-else v-for="form in forms" :key="form.id" cols="4">
        <v-hover v-slot="{ hover }">
          <v-card
            class="formType-card"
            :class="{ 'on-hover': hover }"
            @click="openModels(form.id)"
            style="overflow: hidden"
          >
            <v-card-title class="card-title-cont">
              <v-avatar
                rounded
                class="me-2 v-avatar--variant-tonal primary--text"
                size="56"
                ><v-icon>mdi-scale-balance </v-icon></v-avatar
              >
              <div class="title-cont">
                <!--                <h6 class="text-caption">Form</h6>-->
                {{ form.name }}
              </div>
            </v-card-title>

            <v-sheet v-if="hover" class="overlay" color="rgba(0, 0, 0, 0.5)">
              <v-icon class="link-icon" size="48" color="white">
                <!-- mdi-link-variant -->
                mdi-open-in-new
              </v-icon>
            </v-sheet>
          </v-card>
        </v-hover>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
import { mapActions, mapState } from "vuex";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  name: "FormTypes",
  components: {
    emptyDataSvg,
  },
  data() {
    return {
      loading: false,
      breadcrumbs: [
        {
          text: this.$t("menu.requests"),
          disabled: false,
          href: "#",
        },
      ],
      x: 0,
      y: 0,
    };
  },
  computed: {
    ...mapState("cases", ["forms"]),
    ...mapState("app", ["navTemplates"]),
  },
  watch: {
    navTemplates() {
      this.setCurrentBread();
    },
  },
  created() {
    this.open();
  },
  mounted() {},
  unmounted() {},
  methods: {
    ...mapActions("cases", ["getForms"]),
    ...mapActions("app", ["setBreadCrumb"]),
    searchUser() {},
    setCurrentBread() {
      const { id } = this.$route.params;

      const currentPage = this.navTemplates.find((nav) => {
        return nav.id === +id;
      });

      if (currentPage) {
        this.breadcrumbs.push({
          text: currentPage.title,
          disabled: false,
          href: `/cases/${id}`,
        });
        console.log(this.breadcrumbs);
      }
      this.setBreadCrumb({
        breadcrumbs: this.breadcrumbs,
        pageTitle: this.$t("cases.casesList"),
      });
    },
    open() {
      let { id } = this.$route.params;
      this.loading = true;
      this.getForms(id)
        .then(() => {
          console.log(this.forms);
          this.loading = false;
        })
        .catch(() => {
          this.loading = false;
        });
    },
    openModels(id) {
      let { id: formTypeId } = this.$route.params;
      this.$router.push(`/cases/${formTypeId}/create/` + id);
    },
  },
};
</script>

<style>
.dialog-loading-cont {
  height: calc(100vh - 135px);
  text-align: center;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
}
</style>

<style lang="scss" scoped>
.animated-card {
  background-color: #e0f2f8;
  transition: all 0.2s ease-in-out;
}
.animated-card:hover {
  transform: scale(1.1);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.reg-img path {
  cursor: pointer;
  fill: #e1e1e1;
  stroke: #898989;
}

.reg-img path:hover {
  fill: #777676;
  cursor: pointer;
}

.reg-img .pathDis {
  pointer-events: none;
  cursor: default;
}

.RegionsNameInMap {
  position: fixed;
  z-index: 12;
  color: #ffffff;
  background: #034d86;
  padding: 2px 5px;
  font-size: 12px;
  border-radius: 5px;
  display: none;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.link-icon {
  transition: transform 0.3s ease-in-out;
}

.overlay:hover .link-icon {
  transform: scale(1.1);
}
.formType-card .title-cont {
  word-break: break-word;
}
.formType-card .card-title-cont {
  flex-wrap: nowrap;
}

////
</style>
