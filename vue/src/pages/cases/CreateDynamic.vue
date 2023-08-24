<template>
  <div class="d-flex flex-column flex-grow-1" style="margin: 50px">
    <create-case v-if="id == 1"></create-case>
    <create-legal-advice v-else-if="id == 2"></create-legal-advice>
    <create-review-and-audit v-else-if="id == 3"></create-review-and-audit>
    <create v-else></create>
  </div>
</template>

<script>
import CreateCase from "@/pages/cases/CreateCase";
import CreateLegalAdvice from "@/pages/cases/CreateLegalAdvice";
import CreateReviewAndAudit from "@/pages/cases/CreateReviewAndAudit";
import Create from "@/pages/cases/Create";
import { mapActions } from "vuex";

export default {
  name: "CreateDynamic",
  components: { Create, CreateReviewAndAudit, CreateLegalAdvice, CreateCase },
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
    let { id } = this.$route.params;
    this.id = id;
    // console.log(id)

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
