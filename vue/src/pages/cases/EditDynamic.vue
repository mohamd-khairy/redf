<template>
  <div class="d-flex flex-column flex-grow-1" style="margin: 50px">
    <edit-case v-if="id == 1"></edit-case>
    <edit-legal-advice v-else-if="id == 2"></edit-legal-advice>
    <edit-review-and-audit v-else-if="id == 3"></edit-review-and-audit>
    <edit v-else></edit>
  </div>
</template>

<script>
import { mapActions } from "vuex";
import EditCase from "@/pages/cases/EditCase";
import EditLegalAdvice from "@/pages/cases/EditAdviceLegal";
import EditReviewAndAudit from "@/pages/cases/EditReviewAndAudit";
import Edit from "@/pages/cases/Edit";

export default {
  name: "EditDynamic",
  components: {Edit, EditReviewAndAudit, EditLegalAdvice, EditCase},
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
    console.log(this.$route.params)
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
