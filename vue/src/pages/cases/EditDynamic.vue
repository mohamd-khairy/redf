<template>
  <div class="d-flex flex-column flex-grow-1" style="margin: 50px">
    <edit-case v-if="id == 1"></edit-case>
    <edit-legal-advice v-else-if="id == 2"></edit-legal-advice>
<!--    <create-review-and-audit v-else-if="id == 3"></create-review-and-audit>-->
    <create v-else></create>
  </div>
</template>

<script>
import CreateCase from "@/pages/cases/CreateCase";
import CreateLegalAdvice from "@/pages/cases/CreateLegalAdvice";
import CreateReviewAndAudit from "@/pages/cases/CreateReviewAndAudit";
import Create from "@/pages/cases/Create";
import { mapActions } from "vuex";
import EditCase from "@/pages/cases/EditCase";
import EditLegalAdvice from "@/pages/cases/EditAdviceLegal";

export default {
  name: "CreateDynamic",
  components: {EditLegalAdvice, EditCase, Create},
  data() {
    return {
      id: 0,
      breadcrumbs: [
        {
          text: this.$t("menu.requests"),
          disabled: false,
          href: "#",
        },
      ],
    };
  },

  created() {
    // console.log(this.$route.params)
    let { formType } = this.$route.params;
    this.id = formType;

    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("cases.casesList"),
    });
  },

  computed: {},
  methods: {
    ...mapActions("app", ["setBreadCrumb"]),
  },
};
</script>

<style scoped></style>
