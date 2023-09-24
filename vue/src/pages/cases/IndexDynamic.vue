<template>
  <div class="d-flex flex-column flex-grow-1">
    <index v-if="id == 1"></index>
    <legal-index v-else-if="id == 2"></legal-index>
<!--    <create-review-and-audit v-else-if="id == 3"></create-review-and-audit>-->
<!--    <create v-else></create>-->
  </div>
</template>

<script>
import Index from "@/pages/cases/index";
import LegalIndex from "@/pages/cases/LegalIndex";
import { mapActions } from "vuex";

export default {
  name: "CreateDynamic",
  components: { Index , LegalIndex},
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
