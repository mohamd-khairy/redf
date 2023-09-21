<template>
  <div class="">
    <v-row>
      <v-tabs class="mt-2" v-model="activeTab" hide-slider>
        <v-tab>one</v-tab>
        <v-tab>two</v-tab>
        <v-tab>three</v-tab>
        <v-tab>four</v-tab>
      </v-tabs>
    </v-row>
    <v-row>
      <v-col lg="2" md="3" sm="6" xs="12" v-for="i in 5">
        <v-card outlined :loading="isLoading">
          <v-card-text class="">
            <h5>اسم المعاملة</h5>
            <p>اسم المعاملة</p>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
    <v-row>
      <v-col class="my-1 py-0" cols="12">
        <h2>الوصف</h2>
      </v-col>
      <v-col class="my-0 py-0" cols="12">
        <v-card outlined :loading="isLoading" color="#F7F7F7">
          <v-card-text class="">
            <div>
              .موسبيإ ميرول صن نم خسن ىلع اًضيأ توح يتلاو ركيام جياب سودلأ لثم
              ينورتكلإلا رشنلا جمارب روهظ عم اَرخؤم ىرخأ ةرم رشتنيل داعو ،صنلا
              اذه نم عطاقم يوحت ةيكيتسالبلا تيسارتيل قئاقر رادصإ عم نرقلا اذه
              تايّنيتس يف ريبك لكشب رشتنا. ينورتكلإلا ديضنتلاو ةعابطلا يف يلصألا
              هلكشبو اًمدختسم راص ىتح هنا لب ،صنلا اذه ىلع يضقت مل نمزلا نم نورق
              ةسمخ. فرحألا هذهل يلكش عجرم وأ ليلد ةباثمب بيّتك نوّكتل ،صن نم
              اهتذخأ يئاوشع لكشب فرحألا نم ةعومجم صرب ةلوهجم ةعبطم تماق امدنع
              رشع سماخلا نرقلا ذنم يلكشلا صنلل رايعملا لازيالو موسبيإ ميرول ناك.
              رشنلا رودو عباطملا تاعانص يف مدختسيُو) ىوتحملا
            </div>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";
export default {
  name: "test",
  data() {
    return {
      isLoading: false,
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
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: "الاستشارات القانونية",
    });
  },
};
</script>

<style scoped>
.v-tab {
  padding: 0px !important;
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
</style>
