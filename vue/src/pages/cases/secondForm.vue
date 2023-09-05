<template>
  <div>
    <div class="d-flex align-center justify-content-between">
      <span> فضيلة رئيس المحكمة الإدارية العليا </span>

      <span> سلمه الله </span>
    </div>
    <div class="mt-2">السلام عليكم ورحمة الله وبركاته:</div>
    <div class="mt-2 text-center font-weight-bold">(طلب اعتراض)</div>
    <div>
      <span> في القضية رقم </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          type="number"
          label="رقم القضية"
          v-model="caseNumber"
        >
        </v-text-field>
      </span>
      <span> لعام </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          type="number"
          label="السنة"
          v-model="year"
        >
        </v-text-field>
      </span>
      <span> المقامة من المستأنف/ </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="هوية المستأنف"
          v-model="appellantId"
        >
        </v-text-field>
      </span>
      <span> ضده/ </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="هوية المستأنف الآخر"
          v-model="opponentId"
        >
        </v-text-field>
      </span>
    </div>
    <div class="mt-2 font-weight-bold">أولاً بيانات الأحكام:</div>
    <div>
      <span>- الحكم الابتدائي الصادر من الدائرة </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="رقم الدائرة الإدارية"
          v-model="circleNumber1"
        >
        </v-text-field>
      </span>
      <span> بالمحكمة الإدارية </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="اسم المحكمة الإدارية"
          v-model="adminCourtName1"
        >
        </v-text-field>
      </span>
      <span> في القضية رقم </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          type="number"
          label="رقم القضية"
          v-model="caseNumber1"
        >
        </v-text-field>
      </span>
      <span> لعام </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          type="number"
          label="السنة"
          v-model="year1"
        >
        </v-text-field>
      </span>
      <span> بتاريخ </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="التاريخ"
          v-model="date1"
        >
        </v-text-field>
      </span>
      <span> (مرفق1). </span>
    </div>
    <div>
      <span>- حكم الاستئناف الصادر من الدائرة </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="رقم الدائرة الإدارية"
          v-model="circleNumber2"
        >
        </v-text-field>
      </span>
      <span> بمحكمة الاستئناف الإدارية </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="اسم المحكمة الإدارية"
          v-model="adminCourtName2"
        >
        </v-text-field>
      </span>
      <span> في القضية رقم </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          type="number"
          label="رقم القضية"
          v-model="caseNumber2"
        >
        </v-text-field>
      </span>
      <span> لعام </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          type="number"
          label="السنة"
          v-model="year2"
        >
        </v-text-field>
      </span>
      <span> بتاريخ </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="التاريخ"
          v-model="date2"
        >
        </v-text-field>
      </span>
      <span> (مرفق2). </span>
    </div>
    <div class="mt-2 font-weight-bold">ثانياً- ملخص الحكم:</div>
    <div>
      <span> نص الحكم الابتدائي الصادر من الدائرة </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="رقم الدائرة الإدارية"
          v-model="circleNumber3"
        >
        </v-text-field>
      </span>
      <span> بالمحكمة الإدارية </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="اسم المحكمة الإدارية"
          v-model="adminCourtName3"
        >
        </v-text-field>
      </span>
      <span> بـ: </span>
      <span>
        <v-text-field
          hide-details
          class="d-inline-block"
          label="نص الحكم"
          v-model="judgmentText1"
        >
        </v-text-field>
      </span>
    </div>
    <div class="mt-2 font-weight-bold">ثالثاً- أسباب الاعتراض:</div>
    <div class="mt-2">
      <span>
        صاحب الفضيلة يود صندوق التنمية العقارية الاعتراض على الحكم محل الطلب
        وبيان الآتي:
      </span>
      <span>
        <v-textarea hide-details label="نص الاعتراض" v-model="objectionReason1">
        </v-textarea>
      </span>
    </div>
    <div class="mt-2">
      <span>
        صاحب الفضيلة يود صندوق التنمية العقارية الاعتراض على الحكم محل النقض
        وذلك
      </span>
      <span>
        <v-textarea hide-details label="نص الاعتراض" v-model="objectionReason2">
        </v-textarea>
      </span>
    </div>
    <div class="mt-2">
      <span> طلبات صندوق التنمية العقارية الآتي: </span>
      <div>
        <v-text-field hide-details label="الطلب الأول" v-model="request1">
        </v-text-field>
      </div>
      <div class="mt-1">
        <v-text-field hide-details label="الطلب الثاني" v-model="request2">
        </v-text-field>
      </div>
    </div>
    <div class="mt-2 text-center">والله ولي التوفيق،،</div>
    <br />
    <div class="d-inline-block text-left">ممثل صندوق التنمية العقارية</div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      courtName: "",
      caseNumber: "",
      year: "",
      appellantId: "",
      opponentId: "",
      circleNumber1: "",
      adminCourtName1: "",
      caseNumber1: "",
      year1: "",
      date1: "",
      circleNumber2: "",
      adminCourtName2: "",
      caseNumber2: "",
      year2: "",
      date2: "",
      circleNumber3: "",
      adminCourtName3: "",
      judgmentText1: "",
      objectionReason1: "",
      objectionReason2: "",
      request1: "",
      request2: "",
    };
  },
};
</script>
