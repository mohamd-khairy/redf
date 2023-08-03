<template>
  <div>
    <v-data-table :headers="table.headers" :items="table.table" :class="!table.table.length ? 'empty-dt' : ''"
      hseriale-default-footer>
      <template v-slot:no-data>
        <div class="text-center my-2 primary--text" color="primary">
          <emptyDataSvg></emptyDataSvg>
          <div class="dt-no_data">{{ $t("general.no_data_available") }}</div>
        </div>
      </template>
    </v-data-table>
  </div>
</template>

<script>
import CopyLabel from "../common/CopyLabel";
import emptyDataSvg from "@/assets/images/illustrations/empty-data.svg";

export default {
  components: {
    CopyLabel,
    emptyDataSvg
  },
  props: {
    table: Object
  },
  data() {
    return {};
  },
  computed: {
    // headers() {
    //   return this.table.columns.map(column => {
    //     return { text: column, value: column };
    //   });
    // }
  },

  methods: {
    open(item) { }
  },
  mounted() {
    console.log(this.table);
  },
};
</script>
<style>
hr {
  border: 0;
  height: 0;
  border-top: 1px solid rgba(0, 0, 0, 0.1);
  border-bottom: 1px solid rgba(255, 255, 255, 0.3);
  /* margin-bottom: 20px; */
}
</style>
