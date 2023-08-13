<template>
  <div class="page-dynamic">
    <v-card>
      <ul class="options-list">
        <li class="option-item">
          <v-btn color="primary" large @click="addPage">
            <v-icon>
              mdi-tab
            </v-icon>
            <span class="mx-1">{{ $t("builder.page") }}</span>
          </v-btn>
        </li>
        <li class="option-item">
          <v-btn color="primary" large @click="addLabelElement">
            <v-icon>
              mdi-label
            </v-icon>
            <span class="mx-1">{{ $t("builder.label") }}</span>
          </v-btn>
        </li>
        <li class="option-item">
          <v-btn color="primary" large @click="addTextElement">
            <v-icon>
              mdi-form-textbox
            </v-icon>
            <span class="mx-1">{{ $t("builder.text") }}</span>
          </v-btn>
        </li>
        <!--                <li class="option-item">-->
        <!--                    <v-btn color="primary" large @click="appendTable">-->
        <!--                        <v-icon>-->
        <!--                            mdi-table-->
        <!--                        </v-icon>-->
        <!--                        <span class="mx-1">{{ $t("builder.table") }}</span>-->
        <!--                    </v-btn>-->
        <!--                </li>-->
        <li class="option-item">
          <v-btn color="primary" large @click="addCheckboxElement">
            <v-icon>
              mdi-list-box-outline
            </v-icon>
            <span class="mx-1">{{ $t("builder.select") }}</span>
          </v-btn>
        </li>
        <li class="option-item">
          <v-btn color="primary" large @click="addTextAreaElement">
            <v-icon>
              mdi-form-textarea
            </v-icon>
            <span class="mx-1">{{ $t("builder.textarea") }}</span>
          </v-btn>
        </li>
        <li class="option-item">
          <v-btn color="primary" large @click="addSelectElement">
            <v-icon>
              mdi-chart-box-outline
            </v-icon>
            <span class="mx-1">{{ $t("builder.poll") }}</span>
          </v-btn>
        </li>
        <!--                <li class="option-item">-->
        <!--                    <v-btn color="primary" large>-->
        <!--                        <v-icon>-->
        <!--                            mdi-file-tree-->
        <!--                        </v-icon>-->
        <!--                        <span class="mx-1">{{ $t("builder.tree") }}</span>-->
        <!--                    </v-btn>-->
        <!--                </li>-->
        <li class="option-item">
          <v-btn color="primary" large @click="addLineElement">
            <v-icon>
              mdi-table-row
            </v-icon>
            <span class="mx-1">{{ $t("builder.column") }}</span>
          </v-btn>
        </li>
        <li class="option-item">
          <v-btn color="primary" large @click="addRadioElement">
            <v-icon>
              mdi-radiobox-marked
            </v-icon>
            <span class="mx-1">{{ $t("builder.radio") }}</span>
          </v-btn>
        </li>
        <li class="option-item">
          <v-btn color="primary" large @click="addAttachmentElement">
            <v-icon>
              mdi-file-document-plus-outline
            </v-icon>
            <span class="mx-1">{{ $t("builder.attachment") }}</span>
          </v-btn>
        </li>
      </ul>

      <div class="row Edit-form w-100" style="overflow-x: auto;height: 500px;">
        <ejs-tab id="TabInstance" ref="TabInstance" :showCloseButton=true heightAdjustMode='Auto'
          overflowMode='Scrollable' :selecting="function (e) { if (e.isSwiped) e.cancel = true }">
          <div class="e-tab-header">
            <div>
            </div>
          </div>
          <div class="vue-tabs-attaching-line" style="display: none" :class="{ 'hidden': tabs < 2 }"
            :style="{ 'width': (tabs * 145 - 145) + 'px' }"></div>
          <div class="e-content">
            <div class="row ">
            </div>
          </div>
        </ejs-tab>
      </div>

      <!--            <Tabs :tabs="Tabs" @removeSelectedTab="removeSelectedTab"></Tabs>-->

      <div class="actions mt-2">
        <v-btn color="primary" large @click="update">{{ $t("buttons.save") }}</v-btn>
      </div>

    </v-card>
  </div>
</template>

<script>
import Vue from 'vue'
import Tabs from '../../../../components/dynamic-elements/Tabs.vue';
import "@syncfusion/ej2-base/styles/material.css";
import "@syncfusion/ej2-vue-navigations/styles/material.css";

import PageTitle from "./dynamic-elements/PageTitle";
import TextElement from './dynamic-elements/TextElement'
import LabelElement from "./dynamic-elements/LabelElement";
import TableElement from "./dynamic-elements/TableElement";
import TextAreaElement from "./dynamic-elements/TextAreaElement";
import CheckboxElement from "./dynamic-elements/CheckboxElement";
import SelectElement from "./dynamic-elements/SelectElement";
import TreeElement from "./dynamic-elements/TreeElement";
import LineElement from "./dynamic-elements/LineElement";
import RadioElement from "./dynamic-elements/RadioElement";
import AttachmentElement from "./dynamic-elements/AttachmentElement";
import { makeToast } from "@/helpers";

const _PageTitle = Vue.extend(PageTitle)
const _TextElement = Vue.extend(TextElement)
const _LabelElement = Vue.extend(LabelElement)
const _TableElement = Vue.extend(TableElement)
const _TextAreaElement = Vue.extend(TextAreaElement)
const _CheckboxElement = Vue.extend(CheckboxElement)
const _SelectElement = Vue.extend(SelectElement)
const _TreeElement = Vue.extend(TreeElement)
const _LineElement = Vue.extend(LineElement)
const _RadioElement = Vue.extend(RadioElement)
const _AttachmentElement = Vue.extend(AttachmentElement)

export default {
  components: { Tabs, PageTitle },
  props: ["id"],
  data() {
    return {
      // tabs: [
      //   {
      //       title: "Tab 1",
      //       show: true
      //   },
      // ],
      dropDownId: 1,
      showTools: false,
      tabs: 0,
      current_tab: 0,
      template: {
        pages: [
          {
            title: 'Page Title',
            items: []
          }
        ]
      },
      validationErrors: [],
      errors: [],
      titles: [],
    }
  },
  computed: {
    // Tabs() {
    //     return this.tabs.filter((act) => act.show === true)
    // }
  },
  methods: {
    moveUp(e, x, y) {
      let current = e.target.closest('.element')
      let prev = current.previousElementSibling

      let parent = document.querySelector('.e-content>.e-active')

      if (parent.firstChild.nextSibling !== current) {
        // swap html
        parent.insertBefore(current, prev);
        // swap data
        let tmp = this.template.pages[x].items[y]
        this.template.pages[x].items[y] = this.template.pages[x].items[y - 1]
        this.template.pages[x].items[y - 1] = tmp
        // reindexing
        this.template.pages[x].items[y - 1].referenceY = y - 1
        this.template.pages[x].items[y].referenceY = y
      }
    },
    moveDown(e, x, y) {
      let current = e.target.closest('.element')
      let next = current.nextElementSibling

      let parent = document.querySelector('.e-content>.e-active')

      if (parent.lastChild !== current) {
        // swap html
        parent.insertBefore(next, current);
        // swap data
        let tmp = this.template.pages[x].items[y]
        this.template.pages[x].items[y] = this.template.pages[x].items[y + 1]
        this.template.pages[x].items[y + 1] = tmp
        // reindexing
        this.template.pages[x].items[y + 1].referenceY = y + 1
        this.template.pages[x].items[y].referenceY = y
      }
    },

    addPage(e, title = 'Page Title ..') {
      this.tabs++
      const tabObj = this.$refs.TabInstance.ej2Instances
      const item = {
        header: {
          text: '' +
            '<div class="text-center tab-' + this.tabs + '">\n' +
            '      <div class="badge bg-secondary">' + this.tabs + '</div>\n' +
            '      <div class="nav-link vue-tab-link">' +
            '          ' +
            '      </div>\n' +
            '    </div>'
        }, content: '<div class="row "></div>'
      }

      tabObj.addTab([item], this.tabs - 1)
      this.template.pages[this.tabs - 1] = { title: title, items: [] }

      const pageTitle = new _PageTitle()
      pageTitle.$data.title = title
      pageTitle.$data.editing = true
      pageTitle.$mount()
      document.querySelector('.tab-' + this.tabs).closest('.e-toolbar-item').appendChild(pageTitle.$el)
      if (this.tabs > 1)
        document.querySelector('.tab-' + (this.tabs - 1)).closest('.e-toolbar-item').classList.add('tab-item')
      this.template.pages[this.tabs - 1].title = pageTitle.$data
      this.titles[this.titles.length] = pageTitle

      tabObj.select(this.tabs - 1)

      return pageTitle
    },
    addTextElement() {
      const textElement = new _TextElement()
      textElement.moveUp = this.moveUp
      textElement.moveDown = this.moveDown
      textElement.$data.referenceX = this.current_tab
      textElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      textElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(textElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = textElement.$data
    },
    addLabelElement() {
      const labelElement = new _LabelElement()
      labelElement.moveUp = this.moveUp
      labelElement.moveDown = this.moveDown
      labelElement.$data.referenceX = this.current_tab
      labelElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      labelElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(labelElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = labelElement.$data
    },
    addTableElement() {
      const tableElement = new _TableElement()
      tableElement.moveUp = this.moveUp
      tableElement.moveDown = this.moveDown
      tableElement.$data.referenceX = this.current_tab
      tableElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      tableElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(tableElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = tableElement.$data
    },
    addTextAreaElement() {
      const textAreaElement = new _TextAreaElement()
      textAreaElement.moveUp = this.moveUp
      textAreaElement.moveDown = this.moveDown
      textAreaElement.$data.referenceX = this.current_tab
      textAreaElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      textAreaElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(textAreaElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = textAreaElement.$data
    },
    addCheckboxElement() {
      const checkboxElement = new _CheckboxElement()
      checkboxElement.moveUp = this.moveUp
      checkboxElement.moveDown = this.moveDown
      checkboxElement.$data.referenceX = this.current_tab
      checkboxElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      checkboxElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(checkboxElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = checkboxElement.$data
    },
    addSelectElement() {
      console.log('kkjjj')
      const selectElement = new _SelectElement()
      selectElement.moveUp = this.moveUp
      selectElement.moveDown = this.moveDown
      selectElement.$data.referenceX = this.current_tab
      selectElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      selectElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(selectElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = selectElement.$data
    },
    addTreeElement() {
      const treeElement = new _TreeElement()
      treeElement.moveUp = this.moveUp
      treeElement.moveDown = this.moveDown
      treeElement.$data.referenceX = this.current_tab
      treeElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      treeElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(treeElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = treeElement.$data
    },
    addLineElement() {
      const lineElement = new _LineElement()
      lineElement.moveUp = this.moveUp
      lineElement.moveDown = this.moveDown
      lineElement.$data.referenceX = this.current_tab
      lineElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      lineElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(lineElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = lineElement.$data
    },
    addRadioElement() {
      const radioElement = new _RadioElement()
      radioElement.moveUp = this.moveUp
      radioElement.moveDown = this.moveDown
      radioElement.$data.referenceX = this.current_tab
      radioElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      radioElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(radioElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = radioElement.$data
    },
    addAttachmentElement() {
      const attachmentElement = new _AttachmentElement()
      attachmentElement.moveUp = this.moveUp
      attachmentElement.moveDown = this.moveDown
      attachmentElement.$data.referenceX = this.current_tab
      attachmentElement.$data.referenceY = this.template.pages[this.current_tab].items.length
      attachmentElement.$mount()
      document.querySelector('.e-content>.e-active').appendChild(attachmentElement.$el)
      this.template.pages[this.current_tab].items[this.template.pages[this.current_tab].items.length] = attachmentElement.$data
    },


    update() {
      // this.validationErrors = []

      // for (let i = 0; i < this.template.pages.length; i++) {
      //   for (let j = 0; j < this.template.pages[i].items.length; j++) {
      //     console.log(this.template.pages[i].items[j].removed)
      //     if (this.template.pages[i].items[j].removed) {
      //       this.template.pages[i].items.splice(j, 1)
      //     }
      //   }
      // }
      let { id } = this.$route.params;
      this.$axios.put('update-form/' + id, this.template).then(response => {
        makeToast("success", response.data.message);
        this.$router.push({ name: "TemplatesList" });
        // if (typeof response.data.success !== 'undefined' && response.data.success === true) {
        //   return
        // }
      }).catch(error => {
        if (error.response.data.message === 'Validation Error')
          this.validationErrors = error.response.data.errors
      })
    },


    appendPage(e, title = 'Page Title ..') {
      this.tabs.push({
        title: `Tab`,
        // content: {
        //     label: "New"
        // },
        show: true
      })
    },

    appendLabel() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let icon = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")
      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      // optionsContainer.style.backgroundColor = "#fff"
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      icon.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      span.appendChild(labelContent)
      label.appendChild(icon)
      label.appendChild(span)
      container.appendChild(optionsContainer)
      container.appendChild(label)
      activeTab.appendChild(container)
    },
    appendTextBox() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let iconLbl = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let input = document.createElement("input")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      elementContainer.classList.add("element-container")
      elementContainer.style.width = "100%"
      iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      span.appendChild(labelContent)
      label.appendChild(iconLbl)
      label.appendChild(span)
      label.classList.add('d-block', 'mb-1')
      input.setAttribute("type", "text")
      input.setAttribute("placeholder", "Input Title")
      input.classList.add("form-control")

      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      elementContainer.appendChild(label)
      elementContainer.appendChild(input)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)
    },
    appendTextArea() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let iconLbl = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let input = document.createElement("textarea")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      elementContainer.classList.add("element-container")
      elementContainer.style.width = "100%"
      iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      span.appendChild(labelContent)
      label.appendChild(iconLbl)
      label.appendChild(span)
      label.classList.add('d-block', 'mb-1')
      input.setAttribute("type", "text")
      input.setAttribute("placeholder", "Input Title")
      input.classList.add("form-control")

      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      elementContainer.appendChild(label)
      elementContainer.appendChild(input)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)


    },
    appendColumn() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      // let label = document.createElement("label")
      let span = document.createElement("span")
      // let iconLbl = document.createElement("i")
      // let labelContent = document.createTextNode("Label Title")
      let input = document.createElement("div")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      elementContainer.classList.add("element-container", "row")
      elementContainer.style.width = "100%"
      // iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      // span.appendChild(labelContent)
      // label.appendChild(iconLbl)
      // label.appendChild(span)
      // label.classList.add('d-block', 'mb-1')
      input.classList.add("col")

      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      // elementContainer.appendChild(label)
      elementContainer.appendChild(input)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)

    },
    appendRadio() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let iconLbl = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let input = document.createElement("input")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      elementContainer.classList.add("element-container", "form-check")
      elementContainer.style.width = "100%"
      iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      span.appendChild(labelContent)
      label.appendChild(iconLbl)
      label.appendChild(span)
      label.classList.add('form-check-label')
      label.setAttribute("for", "flexRadioDefault1")
      input.setAttribute("type", "radio")
      input.setAttribute("name", "flexRadioDefault")
      input.setAttribute("id", "flexRadioDefault1")
      // input.setAttribute("placeholder", "Input Title")
      input.classList.add("form-check-input")

      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      elementContainer.appendChild(label)
      elementContainer.appendChild(input)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)
    },
    appendAttachment() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let iconLbl = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let input = document.createElement("input")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      elementContainer.classList.add("element-container")
      elementContainer.style.width = "100%"
      iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      span.appendChild(labelContent)
      label.appendChild(iconLbl)
      label.appendChild(span)
      label.classList.add('d-block', 'mb-1')
      input.setAttribute("type", "file")
      input.setAttribute("placeholder", "Input Title")
      input.classList.add("form-control")

      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      elementContainer.appendChild(label)
      elementContainer.appendChild(input)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)
    },
    appendSelect() {
      let activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let iconLbl = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let input = document.createElement("div")
      // let input = document.createElement("div")
      let inputBtn = document.createElement("button")
      let inputDropDown = document.createElement("div")
      let inputDropDownAddBtn = document.createElement("button")
      let inputDropDownAddBtnIcon = document.createElement("i")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      container.style.width = "33.33%"
      elementContainer.classList.add("element-container")
      elementContainer.style.width = "100%"
      iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      span.appendChild(labelContent)
      label.appendChild(iconLbl)
      label.appendChild(span)
      label.classList.add('d-block', 'mb-1')
      input.classList.add("dropdown-container", "position-relative")
      inputBtn.classList.add("dropdown_btn", "form-select")
      inputDropDown.classList.add("dropdown-content")
      inputDropDown.setAttribute("id", `myDropdown${this.dropDownId++}`)
      input.appendChild(inputBtn)
      let itemDef = document.createElement("div")
      itemDef.classList.add("dropdown-item")
      itemDef.innerText = "Item"
      itemDef.setAttribute("contenteditable", true)

      inputBtn.addEventListener("click", (e) => {
        console.log(e.target);
        let drop = e.target.parentElement.children[1];
        drop.classList.toggle("show")
      })
      inputDropDownAddBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-plus", "add", "theme--light")
      inputDropDownAddBtn.appendChild(inputDropDownAddBtnIcon)
      inputDropDownAddBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          let parent = e.target.parentElement.parentElement
          let item = document.createElement("div")
          item.classList.add("dropdown-item")
          item.classList.add("dropdown-item")
          item.innerText = `Item${this.dropDownId++}`
          item.setAttribute("contenteditable", true)
          parent.appendChild(item)
        }
        else {
          let parent = e.target.parentElement
          let item = document.createElement("div")
          item.classList.add("dropdown-item")
          item.setAttribute("contenteditable", true)
          item.innerText = `Item${this.dropDownId++}`
          parent.appendChild(item)
        }
      })
      inputDropDown.appendChild(inputDropDownAddBtn)
      inputDropDown.appendChild(itemDef)
      input.appendChild(inputDropDown)
      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        if (e.target.classList.contains("v-icon")) {
          const element = e.target.parentElement.parentElement.parentElement.parentElement
          element.remove()
        }
        else {
          const element = e.target.parentElement.parentElement.parentElement
          element.remove()

        }
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      elementContainer.appendChild(label)
      elementContainer.appendChild(input)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)
    },
    appendTable() {
      const activeTab = document.querySelector(".page-dynamic .v-tabs .v-window-item--active");
      let container = document.createElement("div")
      let elementContainer = document.createElement("div")
      let label = document.createElement("label")
      let span = document.createElement("span")
      let iconLbl = document.createElement("i")
      let labelContent = document.createTextNode("Label Title")
      let optionsContainer = document.createElement("div")
      let optionsBtn = document.createElement("button")
      let addHead = document.createElement("button")
      let addHeadIconLbl = document.createElement("i")
      let addRow = document.createElement("button")
      let addRowIconLbl = document.createElement("i")
      let addColumn = document.createElement("button")
      let addColumnIconLbl = document.createElement("i")
      let optionsDropDown = document.createElement("div")
      let optionsDropDownDeleteBtn = document.createElement("button")
      let optionsDropDownDeleteBtnIcon = document.createElement("i")
      let optionsIcon = document.createElement("i")

      const tbl = document.createElement("table");
      const tblHead = document.createElement("thead");
      const tblBody = document.createElement("tbody");
      const rowHead = document.createElement("tr");
      tbl.classList.add("table", "table-striped")
      // creating all cells
      for (let i = 0; i < 2; i++) {
        // creates a table row
        const rowBody = document.createElement("tr");
        const cellHead = document.createElement("th");
        cellHead.setAttribute("contenteditable", true)
        const cellHeadText = document.createTextNode(`Head`);
        cellHead.appendChild(cellHeadText);
        rowHead.appendChild(cellHead);
        for (let j = 0; j < 2; j++) {
          const cellBody = document.createElement("td");
          cellBody.setAttribute("contenteditable", true)
          const cellBodyText = document.createTextNode(`Column`);
          cellBody.appendChild(cellBodyText);
          rowBody.appendChild(cellBody);
        }
        tblHead.appendChild(rowHead);
        tblBody.appendChild(rowBody);
      }

      container.classList.add("created-element", "mb-2", "d-flex", "align-items-start")
      elementContainer.classList.add("element-container")
      iconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
      span.setAttribute("contenteditable", true)
      span.classList.add("mx-1")
      span.appendChild(labelContent)
      label.appendChild(iconLbl)
      label.appendChild(span)
      label.classList.add('d-block', 'mb-1')
      const cellHead = document.createElement("th");
      addHead.classList.add("btn", "p-0", "rotate-90")
      addHeadIconLbl.classList.add("v-icon", "notranslate", "mdi", "mdi-table-column-plus-before", "theme--light")
      addHead.appendChild(addHeadIconLbl)
      cellHead.appendChild(addHead)
      rowHead.appendChild(cellHead)

      optionsDropDown.classList.add("dropdown-content")
      optionsDropDown.setAttribute("id", "myDropdown")
      optionsBtn.classList.add("dropdown_btn")
      optionsBtn.addEventListener("click", (e) => {
        let drop = e.target.parentElement.parentElement.children[1];
        drop.classList.toggle("show")
      })
      optionsIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-dots-vertical", "theme--light")
      optionsDropDownDeleteBtnIcon.classList.add("v-icon", "notranslate", "mdi", "mdi-delete", "theme--light")
      optionsDropDownDeleteBtn.appendChild(optionsDropDownDeleteBtnIcon)
      optionsDropDownDeleteBtn.addEventListener("click", (e) => {
        const element = e.target.parentElement.parentElement.parentElement.parentElement
        element.remove()
      })
      optionsDropDown.appendChild(optionsDropDownDeleteBtn)
      optionsBtn.appendChild(optionsIcon)
      optionsContainer.classList.add("dropdown-container")
      optionsContainer.appendChild(optionsBtn)
      optionsContainer.appendChild(optionsDropDown)
      container.appendChild(optionsContainer)
      elementContainer.appendChild(label)
      tbl.appendChild(tblHead);
      tbl.appendChild(tblBody);
      elementContainer.appendChild(tbl)
      container.appendChild(elementContainer)
      activeTab.appendChild(container)
    },
    removeSelectedTab(tab) {
      let index = this.tabs.findIndex((t) => t === tab)
      this.tabs[index].show = false
    },
  },
  mounted() {
    let { id } = this.$route.params;
    // document.body.style.overflowY = 'hidden'
    // init
    const tabObj = this.$refs.TabInstance.ej2Instances
    let temp = this
    tabObj.spaceKeyDown = function (e) {
      if (e.key === ' ' && e.target.localName === 'input') {
        e.target.value += ' '
      }
    }
    tabObj.removed = function (e) {
      // e.removedIndex
      let index = e.removedIndex
      temp.titles[index].$el.remove()
      temp.tabs--
      temp.current_tab = 0
      tabObj.select(0)
      temp.titles.splice(index, 1)
      temp.template.pages.splice(index, 1)
    }
    tabObj.removing = function (e) {
      if (e.removedIndex === 0) {
        alert('can\'t remove this page')
        e.stop()
      } else {
        if (!confirm('Are you sure ?')) {
          e.stop()
        } else {
          if (document.querySelector('.tab-' + (temp.tabs - 1)) && document.querySelector('.tab-' + (temp.tabs - 1)).closest('.e-toolbar-item'))
            document.querySelector('.tab-' + (temp.tabs - 1)).closest('.e-toolbar-item').classList.remove('tab-item')

        }
      }
    }
    tabObj.animation.previous.effect = 'FadeOut'
    tabObj.animation.next.effect = 'FadeIn'
    tabObj.selected = (e) => {
      this.current_tab = e.selectedIndex
    }
    //tabObj.refresh()
    // load template
    this.$axios.get('get-form/' + id)
      .then(response => {
        if (typeof response.data.data.id !== 'undefined' && response.data.data.id) {
          // this.template = response.data.data
          let template = response.data.data

          // mount template pages
          // const tabObj = this.$refs.TabInstance.ej2Instances
          for (let i = 0; i < template.pages.length; i++) {
            // add page
            setTimeout(function () {
              temp.addPage(null, template.pages[i].title)
              // select page
              tabObj.select(i)
              // add page items
              for (let j = 0; j < template.pages[i].items.length; j++) {
                switch (template.pages[i].items[j].type) {
                  case 'text':
                    temp.addTextElement()
                    break;
                  case 'label':
                    temp.addLabelElement()
                    break;
                  case 'textarea':
                    temp.addTextAreaElement()
                    break;
                  case 'checkbox':
                    temp.addCheckboxElement()
                    break;
                  case 'select':
                    temp.addSelectElement()
                    break;
                  case 'table':
                    temp.addTableElement()
                    temp.template.pages[i].items[j].columns = template.pages[i].items[j].childList[0].length
                    temp.template.pages[i].items[j].rows = template.pages[i].items[j].childList.length
                    break;
                  case 'tree':
                    temp.addTreeElement()
                    break;
                  case 'line':
                    temp.addLineElement()
                    break;
                  case 'radio':
                    temp.addRadioElement()
                    break;
                  case 'file':
                    temp.addAttachmentElement()
                    break;
                }
                temp.template.pages[i].items[j].label = template.pages[i].items[j].label
                temp.template.pages[i].items[j].excel_name = template.pages[i].items[j].excel_name
                temp.template.pages[i].items[j].notes = template.pages[i].items[j].notes
                temp.template.pages[i].items[j].childList = template.pages[i].items[j].childList
                temp.template.pages[i].items[j].width = template.pages[i].items[j].width
                temp.template.pages[i].items[j].height = template.pages[i].items[j].height
                temp.template.pages[i].items[j].length = template.pages[i].items[j].length
                temp.template.pages[i].items[j].input_type = template.pages[i].items[j].input_type
                temp.template.pages[i].items[j].enabled = template.pages[i].items[j].enabled === 1
                temp.template.pages[i].items[j].required = template.pages[i].items[j].required === 1
              }
            }, 700 * i)
            setTimeout(function () {
              tabObj.select(0)
              // document.getElementById('editor').style.visibility = 'visible'
              // document.body.style.overflowY = 'auto'
              temp.loading = false
            }, 701 * template.pages.length)
          }

          // return
        }
      })
      .catch(error => {
        if (error.response.data.message === 'Validation Error')
          this.validationErrors = error.response.data.errors
        // window.jQueryToast(this.$t('Validation_Error'), 'danger');
        // window.jQueryEndLoading();
        this.$emit('error', this.$t('Can_not_load_this_templates'))
      })
  },
}
</script>

<style lang="scss">
.context-menu-list {
  background: #eee;
  list-style: none;
  border-radius: 8px;
  padding: 8px 12px;

  .context-menu-item {
    padding: 3px 0;
  }
}

.dropdown_btn.form-select {
  min-height: 40px;
}

.rotate-90 {
  transform: rotate(90deg);
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content.show {
  display: block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f1f1f1;
  min-width: 100%;
  max-height: 150px;
  overflow-y: auto;
  border-radius: 4px;
  padding: 10px;
  /* overflow: auto; */
  box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
  z-index: 999;

  button {
    width: 100%;
    padding-top: 10px;

    .v-icon {
      &.add {
        color: rgb(1, 76, 79) !important;
      }
    }
  }

  .dropdown-item {
    padding: 10px 0;
    text-align: center;

    &:first-child {
      border-top: 1px solid rgb(1, 76, 79);
    }

    &:not(:last-child) {
      border-bottom: 1px solid rgb(1, 76, 79);
    }
  }
}
</style>
<style lang="scss" scoped>
.options-list {
  width: 100%;
  padding: 10px 0;
  display: flex;
  flex-wrap: wrap;

  .option-item {
    margin: 5px;

    button {
      padding: 10px 15px;
    }
  }
}

:global(.w-50) {
  width: 50% !important;
}


#e-item-TabInstance_0,
#e-item-TabInstance_1 .e-icons.e-close-icon {
  display: none;
}

.e-tab .e-tab-header.e-close-show .e-icons.e-close-icon {
  margin-top: -25px;
  margin-right: -15px;
}

#dynamic-form-builder-tools {
  border: 1px solid #ddd;
  padding: 5px 0;
  background: #f1f1f1;
  width: 100% !important;
  margin: 0 auto;
}

.e-tab .e-tab-header .e-toolbar-item:not(.e-separator) {
  margin: 0;
  min-height: 75px;
  padding: 0;
  width: 120px;
  text-align: center;
}

.e-tab .e-tab-header .e-toolbar-item .e-tab-wrap {
  height: 36px;
  padding: 0 18px;
  margin-top: -20px;
  width: 100%;
}

.element {
  float: left;
  margin: 10px 0;
  padding: 0 5px;
}

.element .menu-icon {
  width: 24px;
  padding: 0;
}

.element .moving-tool {
  padding: 0;
  width: 24px;
  top: -20px;
  position: relative;
  margin: 0;
}

.element .context-menu-item {
  line-height: 24px;
  padding: 0 !important;
}

.element .context-menu-item span {
  padding: 0 !important;
}

.element .context-menu-root {
  width: max-content;
  top: 25px;
  z-index: 9;
  padding: 5px;
}

.element .hidden {
  display: none;
}

.element .form-control-sm {
  padding: 3px;
  margin: 0;
  border: 1px solid #eee;
  float: right;
  width: 110px;
}

.element .notes-text {
  position: absolute;
  top: 0;
  left: 103%;
  width: 300px;
  height: 100px;
  background: #f9f9f9;
  padding: 5px;
}

.element .ml-5 {
  margin-left: 5px;
}

.element .text-right {
  text-align: right;
}

.rotate-90 {
  transform: rotate(90deg);
}
</style>

