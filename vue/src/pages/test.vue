<template>
  <div class="">
    <v-row>
      <v-tabs class="my-2" v-model="activeTab" hide-slider>
        <v-tab>تفاصيل</v-tab>
        <v-tab>نموذج المعاملة</v-tab>
        <v-tab>نموذج الاستشارة</v-tab>
        <v-tab>المرفقات</v-tab>
        <v-tab>السجلات</v-tab>
      </v-tabs>
    </v-row>
    <v-row>
      <v-tabs-items v-model="activeTab">
        <v-tab-item>
          <v-row>
            <v-col lg="2" md="3" sm="6" xs="12">
              <v-card outlined :loading="isLoading">
                <v-card-text class="">
                  <h5 class="card-title-text">اسم المعاملة</h5>
                  <div class="card-body-text">
                    {{ treatmentInfo.name || "-" }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col lg="2" md="3" sm="6" xs="12">
              <v-card outlined :loading="isLoading">
                <v-card-text class="">
                  <h5 class="card-title-text">رقم المعاملة</h5>
                  <div class="card-body-text">
                    {{ treatmentInfo.treatment_number || "-" }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col lg="2" md="3" sm="6" xs="12">
              <v-card outlined :loading="isLoading">
                <v-card-text class="">
                  <h5 class="card-title-text">تاريخ المعاملة</h5>
                  <div class="card-body-text">
                    {{ treatmentInfo.date || "-" }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col lg="2" md="3" sm="6" xs="12">
              <v-card outlined :loading="isLoading">
                <v-card-text class="">
                  <h5 class="card-title-text">نوع المعاملة</h5>
                  <div class="card-body-text">
                    {{ treatmentInfo.type || "-" }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
            <v-col lg="2" md="3" sm="6" xs="12">
              <v-card outlined :loading="isLoading">
                <v-card-text class="">
                  <h5 class="card-title-text">الادارة</h5>
                  <div class="card-body-text">
                    {{ treatmentInfo.department.name || "-" }}
                  </div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
          <v-row v-if="treatmentInfo.type == 'preparing_speech'">
            <v-col class="my-1 py-0" cols="12">
              <h2 class="text-h6 font-weight-regular">الوصف</h2>
            </v-col>
            <v-col class="my-0 py-0" cols="12">
              <v-card outlined :loading="isLoading" color="#F7F7F7">
                <v-card-text class="">
                  <div>{{ treatmentInfo.description }}</div>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
          <v-row v-else class="mt-3">
            <div class="d-flex justify-center flex-column align-center">
              <svg
                data-v-cd081d9c=""
                width="134"
                height="134"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
                class=""
              >
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M108.875 74.573h-83.75v38.76a4.189 4.189 0 002.984 4.011c8.414 2.523 32.078 9.623 37.687 11.306a4.2 4.2 0 002.408 0c5.61-1.683 29.273-8.783 37.687-11.306a4.188 4.188 0 002.984-4.011v-38.76z"
                  fill="url(#paint0_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M108.876 74.573H67.001s-.406 54.253 0 54.253.81-.058 1.204-.176c5.609-1.683 29.273-8.783 37.687-11.306a4.188 4.188 0 002.984-4.011v-38.76z"
                  fill="url(#paint1_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M67 88.16L25.125 74.572 14.5 90.327a2.094 2.094 0 001.09 3.163l37.676 12.24a2.093 2.093 0 002.38-.816L67 88.159z"
                  fill="url(#paint2_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M108.875 74.573L67 88.16l11.354 16.755a2.093 2.093 0 002.381.816l37.677-12.24a2.104 2.104 0 001.334-1.31 2.097 2.097 0 00-.245-1.854c-3.283-4.865-10.626-15.753-10.626-15.753z"
                  fill="url(#paint3_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M56.864 46.033a2.094 2.094 0 00-2.378-.812c-6.172 2.004-29.725 9.656-37.68 12.242a2.09 2.09 0 00-1.088 3.162l9.407 13.948L67 60.941 56.864 46.033z"
                  fill="url(#paint4_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M118.282 60.625a2.09 2.09 0 00-1.089-3.162c-7.954-2.586-31.506-10.238-37.679-12.242a2.094 2.094 0 00-2.378.812L67 60.941l41.875 13.632 9.407-13.948z"
                  fill="url(#paint5_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M108.875 74.573L67 60.941 25.125 74.573 67 88.16l41.875-13.587z"
                  fill="url(#paint6_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M67 88.16V60.94L25.125 74.574 67 88.16z"
                  fill="url(#paint7_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M58.587 66.954a18.334 18.334 0 01-2.353-1.817 2.092 2.092 0 10-2.83 3.084 22.353 22.353 0 002.892 2.238 2.094 2.094 0 002.291-3.505zM52.063 60.522a18.18 18.18 0 01-1.579-2.54 2.096 2.096 0 00-2.816-.917 2.096 2.096 0 00-.917 2.816 22.492 22.492 0 001.94 3.124 2.095 2.095 0 002.928.444 2.096 2.096 0 00.444-2.927zM49.002 51.977a9.694 9.694 0 01.371-2.447 2.095 2.095 0 00-4.026-1.154c-.32 1.122-.5 2.293-.532 3.497a2.093 2.093 0 004.188.104zM52.223 44.984a12.376 12.376 0 011.8-1.375 2.095 2.095 0 00.656-2.888 2.098 2.098 0 00-2.89-.655 16.45 16.45 0 00-2.412 1.847 2.094 2.094 0 002.846 3.071zM60.01 41.373a23.75 23.75 0 013.346-.369 2.097 2.097 0 002.003-2.182 2.096 2.096 0 00-2.181-2.003 28.268 28.268 0 00-3.93.435 2.095 2.095 0 00.762 4.119zM70.922 41.249c1.786.02 3.449-.042 4.992-.178a2.096 2.096 0 001.9-2.272 2.095 2.095 0 00-2.27-1.899 45.789 45.789 0 01-4.573.162 2.094 2.094 0 00-.049 4.187zM82.795 39.788c2.073-.635 3.823-1.447 5.29-2.379a2.095 2.095 0 00-2.25-3.532c-1.18.75-2.594 1.394-4.265 1.905a2.095 2.095 0 001.225 4.006zM93.452 31.549a13.004 13.004 0 001.271-6.288 2.094 2.094 0 00-4.183.195 8.825 8.825 0 01-.859 4.271 2.096 2.096 0 00.974 2.797 2.097 2.097 0 002.797-.975zM92.12 17.858c-1.527-2.063-3.598-3.5-5.855-3.962a2.097 2.097 0 00-2.473 1.631c-.23 1.133.5 2.24 1.631 2.473 1.303.266 2.45 1.16 3.332 2.351a2.094 2.094 0 103.365-2.494z"
                  fill="currentColor"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M64.766 14.302c2.265-12.828 21.898-11.999 10.468 0H64.766z"
                  fill="url(#paint8_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M64.766 17.552c2.265 12.826 21.898 12 10.468 0H64.766z"
                  fill="url(#paint9_linear_530_18345)"
                ></path>
                <path
                  data-v-cd081d9c=""
                  fill-rule="evenodd"
                  clip-rule="evenodd"
                  d="M64.578 18.042h14.02a2.095 2.095 0 000-4.188h-14.02a2.096 2.096 0 00-2.094 2.094c0 1.156.94 2.094 2.094 2.094z"
                  fill="currentColor"
                ></path>
                <defs data-v-cd081d9c="">
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint0_linear_530_18345"
                    x1="25.125"
                    y1="101.7"
                    x2="108.875"
                    y2="101.7"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#CADCF0"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#A4BBDB"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint1_linear_530_18345"
                    x1="66.821"
                    y1="101.7"
                    x2="108.876"
                    y2="101.7"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#A4BBDB"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#8DA3BE"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint2_linear_530_18345"
                    x1="25.404"
                    y1="74.556"
                    x2="31.256"
                    y2="113.277"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#E9F3FC"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#CADCF0"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint3_linear_530_18345"
                    x1="67.17"
                    y1="88.017"
                    x2="111.314"
                    y2="113.406"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#E9F3FC"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#CADCF0"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint4_linear_530_18345"
                    x1="19.644"
                    y1="66.447"
                    x2="25.961"
                    y2="36.669"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#E9F3FC"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#CADCF0"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint5_linear_530_18345"
                    x1="78.21"
                    y1="44.716"
                    x2="80.469"
                    y2="81.472"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#E9F3FC"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#CADCF0"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint6_linear_530_18345"
                    x1="25.125"
                    y1="74.55"
                    x2="108.875"
                    y2="74.55"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#CADCF0"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#A4BBDB"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint7_linear_530_18345"
                    x1="71.288"
                    y1="60.331"
                    x2="72.19"
                    y2="94.152"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#A4BBDB"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#8DA3BE"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint8_linear_530_18345"
                    x1="64.766"
                    y1="9.646"
                    x2="78.783"
                    y2="9.646"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#E9F3FC"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#CADCF0"
                    ></stop>
                  </linearGradient>
                  <linearGradient
                    data-v-cd081d9c=""
                    id="paint9_linear_530_18345"
                    x1="64.766"
                    y1="22.208"
                    x2="78.783"
                    y2="22.208"
                    gradientUnits="userSpaceOnUse"
                  >
                    <stop data-v-cd081d9c="" stop-color="#E9F3FC"></stop>
                    <stop
                      data-v-cd081d9c=""
                      offset="1"
                      stop-color="#CADCF0"
                    ></stop>
                  </linearGradient>
                </defs>
              </svg>
              <span>لا يوجد وصف</span>
            </div>
          </v-row>
        </v-tab-item>
        <v-tab-item> tab2 </v-tab-item>
      </v-tabs-items>
    </v-row>
    <!-- {{ treatmentInfo }} -->
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
import axios from "axios";
export default {
  name: "test",
  data() {
    return {
      isLoading: false,
      treatmentInfo: [],
      activeTab: "",
      breadcrumbs: [
        {
          text: this.$t("tasks.tasksManagement"),
          disabled: false,
          href: "#",
        },

        {
          text: this.$t("tasks.tasksList"),
          to: "/tasks/list",
          exact: true,
        },
      ],
    };
  },
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
    getTreatmentInfo() {
      axios.get("treatments/1").then((res) => {
        this.treatmentInfo = res.data.data;
        console.log("res", res);
      });
    },
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: "الاستشارات القانونية",
    });
    this.getTreatmentInfo();
  },
};
</script>

<style scoped>
.v-tabs {
  background-color: #f9fafb;
}
.v-tab {
  padding: 0px 35px !important;
  color: #98a2b2 !important;
  height: 40px !important;
  font-size: 12px !important;
  font-weight: 400 !important;
}
.v-tab--active {
  color: #fff !important;
  background-color: #014c4f !important;
  border-radius: 8px;
  text-decoration: none !important;
}
.v-tabs-slider-wrapper {
  display: none !important;
  height: unset !important;
  left: 0 !important;
  width: 0 !important;
}
.card-title-text {
  color: var(--gray-600, #475467);
  font-size: 14px;
  font-weight: 300;
}
.card-body-text {
  color: #d6a859;
  font-size: 16px;
  font-weight: 500;
  line-height: 27px;
}

.v-window {
  overflow: unset !important;
}
</style>
