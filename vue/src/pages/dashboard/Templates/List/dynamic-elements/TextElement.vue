<template>
  <div v-if="!removed" :style="{ 'height': height }" class="element row" :class="{
    'col-3': width === 'col-3', 'col-4': width === 'col-4', 'col-6': width === 'col-6', 'col-8': width === 'col-8', 'col-12': width === 'col-12',
  }" @mouseover="fillNotes = true" @mouseout="fillNotes = false">
    <input v-if="!reviewing && !filling" type="text" v-model="label" class="col border-0">
    <label v-else class="row border-0 text-bold">
      <span v-html="$globals.linkParser(label)"></span>
      <span v-if="showRequiredNotify && required && !fill" class="text-danger"> *</span>
    </label>

    <span class="col-1 menu-icon material-icons cursor-pointer" @click="menuOpen = !menuOpen"
      v-if="!reviewing && !filling">
      <i class="v-icon notranslate mdi mdi-dots-horizontal"></i>
    </span>

    <div v-if="!reviewing && !filling" class="col-1 moving-tool">
      <span class="moving-up text-center" @click="moveUp($event, referenceX, referenceY)">
        <i class="v-icon notranslate mdi mdi-arrow-up-bold-box-outline"></i>
        <!-- <v-icon> mdi-arrow-up-bold-box-outline </v-icon> -->
      </span>
      <span class="moving-down" @click="moveDown($event, referenceX, referenceY)">
        <i class="v-icon notranslate mdi mdi-arrow-down-bold-box-outline"></i>
        <!-- <v-icon> mdi-arrow-down-bold-box-outline </v-icon> -->
      </span>
    </div>

    <div v-if="filling && review.comment !== '' && review.comment !== null" class="alert alert-warning">
      <p class="mb-0" v-html="$globals.linkParser(review.comment)"></p>
    </div>

    <div v-if="previewing" class="ml-5 p-5">{{ fill }}</div>
    <input v-else-if="!reviewing && !filling" type="text" class="form-control" readonly>
    <input v-else type="text" class="form-control ml-5 p-5" v-model="fill" :required="required"
      :disabled="reviewing || !enabled" :class="{ 'border-danger': showRequiredNotify && required && !fill }">

    <div v-if="!previewing" class="note-content"
      :class="{ 'fadeIn': filling && fillNotes && notes !== null, 'fadeOut': !(filling && fillNotes && notes !== null) }"
      @mouseover="fillNotes = true" @mouseout="fillNotes = false">
      <p v-html="$globals.linkParser(notes)"></p>
    </div>

    <div v-if="reviewing && !previewing" class="reviewing-box">
      <div class="mt-1 text-left">
        <span class="mr-1 check-input">
          <label class="checkbox-container">
            <input type="checkbox" v-model="review.review">
            <span class="checkmark"></span>
          </label>
        </span>
        <span class="add-note" title="Add Note" @click="commenting = !commenting">
          <img src="/images/add-icon.svg">
        </span>
        <div class="note-input" v-if="commenting">
          <textarea class="form-control" v-model="review.comment"
            style="min-height:60px;max-height: 60px;height: 60px; background: 0 !important;border: 0;"></textarea>
        </div>
      </div>
    </div>

    <ul class="context-menu-list context-menu-root" v-if="menuOpen" v-on:clickout="menuOpen = false">
      <li class="context-menu-item cursor-pointer text-danger" @click="confirmRemove">
        <i class="v-icon notranslate mdi mdi-delete-forever">
        </i>
        <span class="mx-2">Remove</span>
      </li>
      <li class="context-menu-item">
        <i class="v-icon notranslate mdi mdi-wrench-outline">
        </i>
        <span class="mx-2">Width</span>
        <select v-model="width" class="width form-control-sm">
          <option value="col-3">25%</option>
          <option value="col-4">33.3%</option>
          <option value="col-6">50%</option>
          <option value="col-8">66.6%</option>
          <option value="col-12">100%</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="v-icon notranslate mdi mdi-wrench-outline">
        </i>
        <span class="mx-2">Height</span>
        <select v-model="height" class="height form-control-sm">
          <option value="auto">Fit</option>
          <option value="65px">Small</option>
          <option value="90px">Medium</option>
          <option value="115px">Large</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="v-icon notranslate mdi mdi-wrench-outline">
        </i>
        <span class="mx-2">Alignment</span>
        <i class="cursor-pointer v-icon notranslate mdi mdi-format-align-left" @click="notes = 'left'">
        </i>
        <i class="cursor-pointer v-icon notranslate mdi mdi-format-align-center" @click="notes = 'center'">
        </i>
        <i class="cursor-pointer v-icon notranslate mdi mdi-format-align-right" @click="notes = 'right'">
        </i>
      </li>
      <li class="context-menu-item">
<!--        <i class="material-icons">-->
<!--          keyboard-->
<!--        </i>-->
        <span>Required</span>
        <select v-model="required" class="width form-control-sm">
          <option value="true">Yes</option>
          <option value="false">No</option>
        </select>
      </li>
      <li class="context-menu-item">
        <i class="v-icon notranslate mdi mdi-earth">
        </i>
        <span class="mx-2">Website View</span>
        <select v-model="website_view" class="height form-control-sm">
          <option value="true">Yes</option>
          <option value="false">No</option>
        </select>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'TextElement',
  watch: {
    showRequiredNotify: function () {
      if (this.showRequiredNotify && this.required && !this.fill) {
        if (this.$el.closest('.e-item') != null) {
          let tabIndex = this.$el.closest('.e-item').id.replace('e-content-TabInstance_', '')
          document.querySelectorAll('.nav-index')[parseInt(tabIndex) - 1].classList.add('bg-danger')
        }
      }
    },
  },
  data() {
    return {
      showRequiredNotify: false,
      removed: false,
      type: 'text',
      input_type: 'text',
      excel_name: '',
      enabled: true,
      required: false,
      website_view: true,
      label: 'Title here ..',
      width: 'col-4',
      height: 'auto',
      length: 100,
      notes: '',
      childList: [],
      noting: false,
      menuOpen: false,

      reviewing: false,
      commenting: false,
      review: {
        review: false,
        comment: '',
      },
      filling: false,
      fill: '',
      fillNotes: false,

      previewing: false,

      id: 0,

      referenceX: 0,
      referenceY: 0,
    }
  },
  created: function () {
    let self = this;
    window.addEventListener('click', function (e) {
      // close dropdown when clicked outside
      if (!self.$el.contains(e.target)) {
        self.noting = false
        self.menuOpen = false
        self.commenting = false
      }
    })
  },
  methods: {
    moveUp(e, x, y) {

    },
    moveDown(e, x, y) {

    },
    confirmRemove() {
      this.removed = confirm('Are you sure ?')
    }
  }
}
</script>

<style scoped></style>
