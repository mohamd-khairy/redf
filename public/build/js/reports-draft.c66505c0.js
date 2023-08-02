(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["reports-draft"],{"09bd":function(t,e,i){},"169a":function(t,e,i){"use strict";i("7db0"),i("caad"),i("45fc"),i("a9e3"),i("2532"),i("498a");var a=i("5530"),s=i("2909"),n=i("ade3"),r=(i("368e"),i("480e")),o=i("4ad4"),l=i("b848"),c=i("75eb"),d=i("e707"),u=i("e4d3"),f=i("21be"),v=i("f2e7"),h=i("a293"),p=i("58df"),m=i("d9bd"),g=i("80d2"),b=Object(p["a"])(o["a"],l["a"],c["a"],d["a"],u["a"],f["a"],v["a"]);e["a"]=b.extend({name:"v-dialog",directives:{ClickOutside:h["b"]},props:{dark:Boolean,disabled:Boolean,fullscreen:Boolean,light:Boolean,maxWidth:{type:[String,Number],default:"none"},noClickAnimation:Boolean,origin:{type:String,default:"center center"},persistent:Boolean,retainFocus:{type:Boolean,default:!0},scrollable:Boolean,transition:{type:[String,Boolean],default:"dialog-transition"},width:{type:[String,Number],default:"auto"}},data:function(){return{activatedBy:null,animate:!1,animateTimeout:-1,isActive:!!this.value,stackMinZIndex:200,previousActiveElement:null}},computed:{classes:function(){var t;return t={},Object(n["a"])(t,"v-dialog ".concat(this.contentClass).trim(),!0),Object(n["a"])(t,"v-dialog--active",this.isActive),Object(n["a"])(t,"v-dialog--persistent",this.persistent),Object(n["a"])(t,"v-dialog--fullscreen",this.fullscreen),Object(n["a"])(t,"v-dialog--scrollable",this.scrollable),Object(n["a"])(t,"v-dialog--animated",this.animate),t},contentClasses:function(){return{"v-dialog__content":!0,"v-dialog__content--active":this.isActive}},hasActivator:function(){return Boolean(!!this.$slots.activator||!!this.$scopedSlots.activator)}},watch:{isActive:function(t){var e;t?(this.show(),this.hideScroll()):(this.removeOverlay(),this.unbind(),null==(e=this.previousActiveElement)||e.focus())},fullscreen:function(t){this.isActive&&(t?(this.hideScroll(),this.removeOverlay(!1)):(this.showScroll(),this.genOverlay()))}},created:function(){this.$attrs.hasOwnProperty("full-width")&&Object(m["e"])("full-width",this)},beforeMount:function(){var t=this;this.$nextTick((function(){t.isBooted=t.isActive,t.isActive&&t.show()}))},beforeDestroy:function(){"undefined"!==typeof window&&this.unbind()},methods:{animateClick:function(){var t=this;this.animate=!1,this.$nextTick((function(){t.animate=!0,window.clearTimeout(t.animateTimeout),t.animateTimeout=window.setTimeout((function(){return t.animate=!1}),150)}))},closeConditional:function(t){var e=t.target;return!(this._isDestroyed||!this.isActive||this.$refs.content.contains(e)||this.overlay&&e&&!this.overlay.$el.contains(e))&&this.activeZIndex>=this.getMaxZIndex()},hideScroll:function(){this.fullscreen?document.documentElement.classList.add("overflow-y-hidden"):d["a"].options.methods.hideScroll.call(this)},show:function(){var t=this;!this.fullscreen&&!this.hideOverlay&&this.genOverlay(),this.$nextTick((function(){t.$nextTick((function(){t.previousActiveElement=document.activeElement,t.$refs.content.focus(),t.bind()}))}))},bind:function(){window.addEventListener("focusin",this.onFocusin)},unbind:function(){window.removeEventListener("focusin",this.onFocusin)},onClickOutside:function(t){this.$emit("click:outside",t),this.persistent?this.noClickAnimation||this.animateClick():this.isActive=!1},onKeydown:function(t){if(t.keyCode===g["A"].esc&&!this.getOpenDependents().length)if(this.persistent)this.noClickAnimation||this.animateClick();else{this.isActive=!1;var e=this.getActivator();this.$nextTick((function(){return e&&e.focus()}))}this.$emit("keydown",t)},onFocusin:function(t){if(t&&this.retainFocus){var e=t.target;if(e&&![document,this.$refs.content].includes(e)&&!this.$refs.content.contains(e)&&this.activeZIndex>=this.getMaxZIndex()&&!this.getOpenDependentElements().some((function(t){return t.contains(e)}))){var i=this.$refs.content.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'),a=Object(s["a"])(i).find((function(t){return!t.hasAttribute("disabled")}));a&&a.focus()}}},genContent:function(){var t=this;return this.showLazyContent((function(){return[t.$createElement(r["a"],{props:{root:!0,light:t.light,dark:t.dark}},[t.$createElement("div",{class:t.contentClasses,attrs:Object(a["a"])({role:"document",tabindex:t.isActive?0:void 0},t.getScopeIdAttrs()),on:{keydown:t.onKeydown},style:{zIndex:t.activeZIndex},ref:"content"},[t.genTransition()])])]}))},genTransition:function(){var t=this.genInnerContent();return this.transition?this.$createElement("transition",{props:{name:this.transition,origin:this.origin,appear:!0}},[t]):t},genInnerContent:function(){var t={class:this.classes,ref:"dialog",directives:[{name:"click-outside",value:{handler:this.onClickOutside,closeConditional:this.closeConditional,include:this.getOpenDependentElements}},{name:"show",value:this.isActive}],style:{transformOrigin:this.origin}};return this.fullscreen||(t.style=Object(a["a"])(Object(a["a"])({},t.style),{},{maxWidth:"none"===this.maxWidth?void 0:Object(g["h"])(this.maxWidth),width:"auto"===this.width?void 0:Object(g["h"])(this.width)})),this.$createElement("div",t,this.getContentSlot())}},render:function(t){return t("div",{staticClass:"v-dialog__container",class:{"v-dialog__container--attached":""===this.attach||!0===this.attach||"attach"===this.attach},attrs:{role:"dialog"}},[this.genActivator(),this.genContent()])}})},"368e":function(t,e,i){},"80ae":function(t,e,i){"use strict";i.r(e);var a=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("div",{staticClass:"d-flex flex-grow-1 flex-column"},[i("v-card",{staticClass:"model-card mb-4"},[i("v-card-text",{staticClass:"pb-0"},[i("div",{staticClass:"card-header custom-header border-bottom"},[i("v-list-item-avatar",{attrs:{tile:"",size:"60",color:"grey"}}),i("div",{staticClass:"content-cont"},[i("div",{staticClass:"d-flex justify-space-between "},[i("h3",{staticClass:"c-title"},[t._v(" "+t._s(t.$t("reports.detectionReport"))+" ")])])])],1)]),i("div",{staticClass:"card-body multi-content"},[i("div",{staticClass:"item-desc"},[i("h5",{staticClass:"i-title"},[t._v(t._s(t.$t("reports.fromDate")))]),i("p",{staticClass:"num"},[t._v(t._s(t.filterData.start))])]),i("div",{staticClass:"item-desc"},[i("h5",{staticClass:"i-title"},[t._v(t._s(t.$t("reports.toDate")))]),i("p",{staticClass:"num"},[t._v(t._s(t.filterData.end))])]),i("div",{staticClass:"item-desc"},[i("h5",{staticClass:"i-title"},[t._v(t._s(t.$t("reports.timeType")))]),i("p",{staticClass:"num"},[t._v(t._s(t.filterData.time_type))])]),i("div",{staticClass:"item-desc"},[i("h5",{staticClass:"i-title"},[t._v(t._s(t.$t("reports.menuType")))]),i("p",{staticClass:"num"},[t._v(t._s(t.filterData.groupBy))])])])],1),i("v-row",{staticClass:"flex-grow-0 mb-2 draft-cont",attrs:{dense:""}},[i("v-col",{attrs:{cols:"12 mb-2"}},[i("v-card",[i("v-card-title",{staticClass:"d-flex justify-space-between"},[i("div",{staticClass:"d-flex justify-between"},[t._v(" "+t._s(t.$t("general.moreCards"))+" ")]),i("div",{staticClass:"action-cont"},[i("v-btn",{attrs:{small:"",outlined:"",color:"primary"},on:{click:function(e){return t.addToPin(t.draftDetails.card)}}},[i("v-icon",[t._v(" mdi-plus ")]),t._v(" "+t._s(t.$t("reports.addToPinned"))+" ")],1)],1)]),i("hr",{staticClass:"mb-0"}),i("v-card-text")],1)],1),!t.loading&&t.draftDetails.bar?i("v-col",{staticClass:"mb-2",attrs:{cols:"12",lg:"6"}},[i("v-card",[t.isLoading?i("div",{staticClass:"d-flex flex-grow-1 align-center justify-center"},[i("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("v-card-title",{staticClass:"d-flex justify-space-between"},[i("div",{staticClass:"d-flex justify-between"},[t._v(" "+t._s(t.draftDetails.bar.title)+" ")]),i("div",{staticClass:"action-cont"},[i("v-btn",{attrs:{small:"",outlined:"",color:"primary"},on:{click:function(e){return t.addToPin(t.draftDetails.bar)}}},[i("v-icon",[t._v(" mdi-plus ")]),t._v(" "+t._s(t.$t("reports.addToPinned"))+" ")],1)],1)]),i("hr"),i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("column",{attrs:{labels:t.draftData.bar.sites,series:t.draftData.bar.result}})],1)],1)])],1):t._e(),!t.loading&&t.draftDetails.line?i("v-col",{staticClass:"mb-2",attrs:{cols:"12",lg:"6"}},[i("v-card",[t.isLoading?i("div",{staticClass:"d-flex flex-grow-1 align-center justify-center"},[i("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("v-card-title",{staticClass:"d-flex justify-space-between"},[i("div",{staticClass:"d-flex"},[t._v(" "+t._s(t.draftDetails.line.title)+" ")]),i("div",{staticClass:"action-cont"},[i("v-btn",{attrs:{small:"",outlined:"",color:"primary"},on:{click:function(e){return t.addToPin(t.draftDetails.line)}}},[i("v-icon",[t._v(" mdi-plus ")]),t._v(" "+t._s(t.$t("reports.addToPinned"))+" ")],1)],1)]),i("hr"),i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("line-chart",{attrs:{labels:t.draftData.line.sites,series:t.draftData.line.result}})],1)],1)])],1):t._e(),t._l(t.draftData.pie,(function(e,a){return!t.loading&&t.draftDetails.pie?i("v-col",{key:a,staticClass:"mb-2",attrs:{cols:"12",lg:"6"}},[i("v-card",[t.isLoading?i("div",{staticClass:"d-flex flex-grow-1 align-center justify-center"},[i("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("v-card-title",{staticClass:"d-flex justify-space-between"},[i("div",{staticClass:"d-flex"},[t._v(" "+t._s(t.draftDetails.pie.title)+" "+t._s(t.$t("general."+a))+" ")]),i("div",{staticClass:"action-cont"},[i("v-btn",{attrs:{small:"",outlined:"",color:"primary"},on:{click:function(e){return t.addToPin(t.draftDetails.pie)}}},[i("v-icon",[t._v(" mdi-plus ")]),t._v(" "+t._s(t.$t("reports.addToPinned"))+" ")],1)],1)]),i("hr"),i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("pie",{attrs:{labels:e.name,series:e.value}})],1)],1)])],1):t._e()})),!t.loading&&t.draftData.table?i("v-col",{staticClass:"mb-2",attrs:{cols:"12",lg:"6"}},[i("v-card",[t.isLoading?i("div",{staticClass:"d-flex flex-grow-1 align-center justify-center"},[i("v-progress-circular",{attrs:{indeterminate:"",color:"primary"}})],1):i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("v-card-title",{staticClass:"d-flex justify-space-between"},[t._v(" "+t._s(t.draftDetails.table.title)+" "),i("div",{staticClass:"action-cont"},[i("v-btn",{attrs:{small:"",outlined:"",color:"primary"},on:{click:function(e){return t.addToPin(t.draftDetails.table)}}},[i("v-icon",[t._v(" mdi-plus ")]),t._v(" "+t._s(t.$t("reports.addToPinned"))+" ")],1)],1)]),i("hr"),i("div",{staticClass:"d-flex flex-column flex-grow-1"},[i("table-card",{staticClass:"h-full",attrs:{table:t.draftData.table}})],1)],1)])],1):t._e()],2),i("v-dialog",{attrs:{"max-width":"600"},model:{value:t.pinDialog,callback:function(e){t.pinDialog=e},expression:"pinDialog"}},[i("v-card",[i("v-card-title",{staticClass:"text-h5 d-flex justify-space-between align-center border-bottom"},[t._v(" "+t._s(t.$t("reports.chooseThePinnedReportToAdd"))+" "),i("v-btn",{attrs:{icon:""},on:{click:function(e){t.pinDialog=!1}}},[i("v-icon",[t._v(" mdi-close ")])],1)],1),t.openDialogLoader?i("v-card-text",{staticClass:"mt-4"},[i("div",{staticClass:"text-center"},[i("v-progress-circular",{attrs:{size:50,color:"primary",indeterminate:""}})],1)]):i("v-card-text",{staticClass:"mt-4"},[i("v-row",[i("v-col",{attrs:{cols:"12"}},[i("v-select",{attrs:{"hide-details":"auto",items:t.pinned,"item-text":"title","item-value":"id",chips:"",label:t.$t("reports.chhoseFromSavedPinnedReports"),multiple:"",outlined:""},model:{value:t.selectedPinnedReports,callback:function(e){t.selectedPinnedReports=e},expression:"selectedPinnedReports"}}),i("div",{directives:[{name:"show",rawName:"v-show",value:t.validationError&&0===t.selectedPinnedReports.length,expression:"validationError && selectedPinnedReports.length === 0"}],staticClass:"mt-1 red--text"},[t._v(" "+t._s(t.$t("general.selectAtLeastOnePineed"))+" ")])],1),i("v-col",{attrs:{cols:"12"}},t._l(t.pins,(function(e,a){return i("div",{key:a,staticClass:"d-flex mb-2"},[i("v-text-field",{attrs:{dense:"",outlined:"",label:t.$t("reports.addNewTitle"),"hide-details":"auto",rules:t.requiredRule},model:{value:e.text,callback:function(i){t.$set(e,"text",i)},expression:"pin.text"}}),i("v-btn",{staticClass:"mr-1",attrs:{color:"error",elevation:"0"},on:{click:function(e){return t.removePin(a)}}},[i("v-icon",[t._v(" mdi-close ")])],1)],1)})),0),i("v-col",{attrs:{cols:"12"}},[i("v-btn",{attrs:{color:"primary"},on:{click:t.addNewPin}},[i("v-icon",[t._v(" mdi-plus ")]),t._v(" "+t._s(t.$t("reports.newPin"))+" ")],1)],1)],1)],1),i("v-divider"),i("v-card-actions",{staticClass:"py-2"},[i("v-spacer"),i("v-btn",{staticClass:"mx-1",attrs:{color:"primary",elevation:"0",disabled:t.pinLoading,loading:t.pinLoading},on:{click:t.storePinned}},[t._v(" "+t._s(t.$t("general.save"))+" ")]),i("v-btn",{attrs:{color:"grey lighten-3",elevation:"0"},on:{click:function(e){t.pinDialog=!1}}},[t._v(" "+t._s(t.$t("general.cancel"))+" ")])],1)],1)],1)],1)},s=[],n=(i("4de4"),i("4160"),i("d81d"),i("a434"),i("ac1f"),i("5319"),i("159b"),i("2909")),r=i("5530"),o=i("2f62"),l=i("a328"),c=i("d7d3"),d=i("0308"),u=i("4acb"),f=i("75b8"),v={components:{column:l["a"],lineChart:c["a"],pie:d["a"],tableCard:u["a"],showBuilderCards:f["a"]},created:function(){var t=this.$route.params.id;this.setBreadCrumb({breadcrumbs:this.breadcrumbs,pageTitle:this.$t("reports.showReport")}),t||window.location.replace("/reports/drafted"),this.fetchConfig(),this.fetchDrafted(t)},data:function(){var t=this;return{loading:!0,isLoading:!1,pinLoading:!1,openDialogLoader:!1,pinDialog:!1,selectedPinnedReports:[],selectedChartId:null,pins:[],requiredRule:[function(e){return!!e||t.$t("general.fieldRequired")}],validationError:!1,breadcrumbs:[{text:this.$t("menu.reports"),disabled:!0},{text:this.$t("reports.draftedReports"),href:"/reports/drafted"},{text:this.$t("reports.showReport")}]}},computed:Object(r["a"])({},Object(o["e"])("reports",{showDraft:function(t){return t.showDraft},draftData:function(t){return t.showDraft.data?t.showDraft.data:{}},draftDetails:function(t){return t.showDraft.details?t.showDraft.details:{}},filterData:function(t){return t.showDraft.filter?t.showDraft.filter:{}},config:function(t){return t.config},pinned:function(t){return t.pinnedReports}})),methods:Object(r["a"])(Object(r["a"])(Object(r["a"])({},Object(o["b"])("reports",["getDraft","builderConfigs","getSavedPinned","savePins","getRelatedPinned"])),Object(o["b"])("app",["setBreadCrumb"])),{},{fetchConfig:function(){var t=this;this.isLoading=!0,this.builderConfigs().then((function(){t.isLoading=!1})).catch((function(){t.isLoading=!1}))},fetchDrafted:function(t){var e=this;this.loading=!0,this.getDraft(t).then((function(){e.loading=!1})).catch((function(){e.loading=!1}))},addToPin:function(t){var e=this,i=t.id;i&&(this.pins=[],this.selectedPinnedReports=[],this.selectedChartId=i,this.openDialogLoader=!0,this.validationError=!1,this.pinDialog=!0,this.getRelatedPinned(i).then((function(t){var i=null===t||void 0===t?void 0:t.data.data,a=i.chart_pinneds;e.selectedPinnedReports=Object(n["a"])(a),e.openDialogLoader=!1})))},addNewPin:function(){this.pins.push({text:""})},removePin:function(t){this.pins.splice(t,1)},storePinned:function(){var t=this,e=!0;if(this.pins.forEach((function(t){t.text||(e=!1)})),e){var i=this.pins.map((function(t){return t.text}));i.length||this.selectedPinnedReports.length?(this.pinLoading=!0,this.savePins({titles:i,pinned_ids:this.selectedPinnedReports,chart_id:this.selectedChartId}).then((function(e){t.pinLoading=!1,t.pinDialog=!1})).catch((function(e){console.log(e),t.pinLoading=!0}))):this.validationError=!0}}})},h=v,p=(i("dbc3"),i("2877")),m=i("6544"),g=i.n(m),b=i("8336"),C=i("b0afa"),x=i("99d9"),_=i("62ad"),w=i("169a"),y=i("ce7e"),D=i("132d"),$=i("8270"),j=i("490a"),O=i("0fd9"),k=i("b974"),P=i("2fa4"),T=i("8654"),A=Object(p["a"])(h,a,s,!1,null,null,null);e["default"]=A.exports;g()(A,{VBtn:b["a"],VCard:C["a"],VCardActions:x["b"],VCardText:x["d"],VCardTitle:x["e"],VCol:_["a"],VDialog:w["a"],VDivider:y["a"],VIcon:D["a"],VListItemAvatar:$["a"],VProgressCircular:j["a"],VRow:O["a"],VSelect:k["a"],VSpacer:P["a"],VTextField:T["a"]})},"99d9":function(t,e,i){"use strict";i.d(e,"b",(function(){return n})),i.d(e,"c",(function(){return r})),i.d(e,"d",(function(){return o})),i.d(e,"e",(function(){return l}));var a=i("b0afa");i.d(e,"a",(function(){return a["a"]}));var s=i("80d2"),n=Object(s["j"])("v-card__actions"),r=Object(s["j"])("v-card__subtitle"),o=Object(s["j"])("v-card__text"),l=Object(s["j"])("v-card__title");a["a"]},dbc3:function(t,e,i){"use strict";i("09bd")}}]);