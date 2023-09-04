<template>
  <div>
    <h6 class="text-center">طلب استئناف حكم ابتدائي صاحب الفضيلة</h6>
    <div>
      <span> رئيس محكمة الاستئناف الإدارية ب </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="المحكمة الادارية"
          v-model="pages[0].items[getFieldIndex('branch')].value"
        >
        </v-text-field>
      </span>
      <span class="float-left"> حفظه الله </span>
    </div>
    <div class="mt-2 text-center">السلام عليكم ورحمة الله وبركاته..</div>
    <span> فأشير إلى الحكم الابتدائي الصادر من الدائرة </span>
    <span>
      <v-text-field
        hide-details
        class="d-inline-block"
        label="الدائرة"
        v-model="pages[0].items[getFieldIndex('court')].value"
      >
      </v-text-field>
    </span>
    <span> في المحكمة الإدارية ب </span>
    <span>
      <v-text-field
        hide-details
        class="d-inline-block"
        label="منطقة"
        v-model="pages[0].items[getFieldIndex('redf_location')].value"
      >
      </v-text-field>
    </span>
    <span> في القضية رقم </span>
    <span>
      (
      <v-text-field
        hide-details
        class="d-inline-block"
        label="رقم"
        :value="caseNumber"
        disabled
      >
      </v-text-field
      >)
    </span>
    <span> لعام </span>
    <span>
      <!-- <v-text-field
        hide-details
        class="d-inline-block"
        label="عام"
        v-model="pages[0].items[getFieldIndex('hijri_year')].value"
      >
      </v-text-field> -->

      <v-dialog
        ref="caseDateDialog"
        v-model="caseDateDialog"
        :return-value.sync="pages[0].items[getFieldIndex('hijri_year')].value"
        persistent
        width="290px"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="pages[0].items[getFieldIndex('hijri_year')].value"
            :label="$t('tables.date')"
            class="d-inline-block"
            readonly
            v-bind="attrs"
            v-on="on"
            required="true"
          ></v-text-field>
        </template>
        <v-date-picker
          v-model="pages[0].items[getFieldIndex('hijri_year')].value"
          scrollable
        >
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="caseDateDialog = false">
            Cancel
          </v-btn>
          <v-btn
            text
            color="primary"
            @click="
              $refs.caseDateDialog.save(
                pages[0].items[getFieldIndex('hijri_year')].value
              )
            "
          >
            OK
          </v-btn>
        </v-date-picker>
      </v-dialog>
    </span>
    <span> ضد فرع صندوق التنمية العقارية بمنطقة </span>
    <span>
      <v-text-field
        hide-details
        class="d-inline-block"
        label="منطقة"
        v-model="pages[0].items[getFieldIndex('redf_location')].value"
      >
      </v-text-field>
    </span>
    <span> ، من المدعي/ </span>
    <span>
      <v-text-field
        hide-details
        class="d-inline-block"
        label="اسم المدعي"
        disabled
        :value="claimant.id"
      >
      </v-text-field>
    </span>
    <span> ، القاضي ب </span>
    <span>
      <v-text-field
        hide-details
        class="d-inline-block"
        label="اسم القاضي"
        v-model="pages[0].items[getFieldIndex('judge')].value"
      >
      </v-text-field>
    </span>
    <div class="mt-2">أسباب الحكم:</div>
    <div class="mt-2">ذكرت الدائرة الموقرة في أسباب الحكم ما نصه</div>
    <span>
      <v-textarea
        hide-details
        class=""
        label="نص الحكم"
        v-model="pages[0].items[getFieldIndex('judgment')].value"
      >
      </v-textarea>
    </span>
    <div class="mt-2">أصحاب الفضيلة:</div>
    <div>
      يود صندوق التنمية العقارية الايضاح لفضيلتكم حيال ما نص عليه الحكم من
    </div>
    <div>
      <span>
        <v-textarea
          hide-details
          label="النص"
          v-model="pages[0].items[getFieldIndex('judgment_reasons')].value"
        >
        </v-textarea>
      </span>
    </div>
    <div class="d-flex align-center mt-2">
      <span>وهنا يؤكد صندوق التنمية العقارية</span>
      <span class="flex-sm-grow-1 mx-1">
        <v-text-field
          class="d-inline-block w-100 pt-0 mt-0"
          hide-details
          label="التأكيد"
          v-model="pages[0].items[getFieldIndex('judgment_proves')].value"
        >
        </v-text-field>
      </span>
      <span> لما سبق ايضاحه اعلاه </span>
    </div>
    <div>
      <span>
        عليه يطلب صندوق التنمية العقارية من فضيلتكم نقض الحكم الابتدائي الصادر
        من الدائرة الإدارية
      </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="الدائرة الإدارية"
          v-model="pages[0].items[getFieldIndex('court')].value"
        >
        </v-text-field>
      </span>
      <span> فرع </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="الفرع"
          v-model="pages[0].items[getFieldIndex('branch')].value"
        >
        </v-text-field>
      </span>
      <span> والله يحفظكم ويرعاكم، </span>
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
  created() {
    console.log(this.caseNumber);
  },
  data() {
    return {
      courtName: "",
      caseDateDialog: false,
      circleNumber: "",
      courtLocation: "",
      plaintiffName: "",
      judgeName: "",
      reason: "",
      clarification: "",
      confirmation: "",
      adminCircle: "",
      branch: "",
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
