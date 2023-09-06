<template>
  <div>
    <div
      lang="ar"
      dir="rtl"
      style="direction: rtl; text-align: right"
      id="source-html"
    >
      <h6
        style="text-align: center; font-size: 24px; font-weight: bold"
        class="text-center"
      >
        طلب استئناف حكم ابتدائي صاحب الفضيلة
      </h6>
      <div>
        <span style="text-align: right; display: inline">
          <span> رئيس محكمة الاستئناف الإدارية ب </span>
          <span>
            {{ pages[0].items[getFieldIndex("branch")].value }}
          </span>
        </span>
        <span style="text-align: left; float: left" class="float-left">
          حفظه الله
        </span>
      </div>
      <div style="text-align: center" class="mt-2 text-center">
        السلام عليكم ورحمة الله وبركاته..
      </div>
      <div style="text-align: right">
        <span> فأشير إلى الحكم الابتدائي الصادر من الدائرة </span>
        <span>
          {{ pages[0].items[getFieldIndex("court")].value }}
        </span>
        <span> في المحكمة الإدارية ب </span>
        <span>
          {{ pages[0].items[getFieldIndex("redf_location")].value }}
        </span>
        <span> في القضية رقم </span>
        <span> ({{ caseNumber }}) </span>
        <span> لعام </span>
        <span>
          {{ pages[0].items[getFieldIndex("hijri_year")].value }}
        </span>
        <span> ضد فرع صندوق التنمية العقارية بمنطقة </span>
        <span>
          {{ pages[0].items[getFieldIndex("redf_location")].value }}
        </span>
        <span> ، من المدعي/ </span>
        <span>
          {{ claimant.name }}
        </span>
        <span> ، القاضي ب </span>
        <span>
          {{ pages[0].items[getFieldIndex("judge")].value }}
        </span>
      </div>
      <div style="text-align: right">
        <div class="mt-2">أسباب الحكم:</div>
        <div class="mt-2">ذكرت الدائرة الموقرة في أسباب الحكم ما نصه</div>
        <span>
          {{ pages[0].items[getFieldIndex("judgment")].value }}
        </span>
      </div>
      <div style="text-align: right">
        <div class="mt-2">أصحاب الفضيلة:</div>
        <div>
          يود صندوق التنمية العقارية الايضاح لفضيلتكم حيال ما نص عليه الحكم من
        </div>
        <div>
          <span>
            {{ pages[0].items[getFieldIndex("judgment_reasons")].value }}
          </span>
        </div>
        <div class="d-flex align-center mt-2">
          <span>وهنا يؤكد صندوق التنمية العقارية</span>
          <span class="flex-sm-grow-1 mx-1">
            {{ pages[0].items[getFieldIndex("judgment_proves")].value }}
          </span>
          <span> لما سبق ايضاحه اعلاه </span>
        </div>
        <div style="text-align: right">
          <span>
            عليه يطلب صندوق التنمية العقارية من فضيلتكم نقض الحكم الابتدائي
            الصادر من الدائرة الإدارية
          </span>
          <span>
            {{ pages[0].items[getFieldIndex("court")].value }}
          </span>
          <span> فرع </span>
          <span>
            {{ pages[0].items[getFieldIndex("branch")].value }}
          </span>
          <span> والله يحفظكم ويرعاكم، </span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  props: {
    claimant: {
      type: Object,
      default: { id: 0, name: "" },
    },
    caseNumber: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      caseDateDialog: false,
    };
  },
  computed: {
    ...mapState("cases", ["pages"]),
  },
  methods: {
    getFieldIndex(type) {
      const elm = this.pages[0].items.findIndex((item) => item.key === type);
      if (elm === -1) return;

      return elm;
    },
  },
};
</script>
