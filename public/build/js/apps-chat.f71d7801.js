(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["apps-chat"],{"169a":function(t,e,n){"use strict";n("7db0"),n("caad"),n("45fc"),n("a9e3"),n("2532"),n("498a");var i=n("5530"),a=n("2909"),s=n("ade3"),o=(n("368e"),n("480e")),c=n("4ad4"),r=n("b848"),l=n("75eb"),d=n("e707"),h=n("e4d3"),u=n("21be"),v=n("f2e7"),f=n("a293"),m=n("58df"),g=n("d9bd"),p=n("80d2"),b=Object(m["a"])(c["a"],r["a"],l["a"],d["a"],h["a"],u["a"],v["a"]);e["a"]=b.extend({name:"v-dialog",directives:{ClickOutside:f["b"]},props:{dark:Boolean,disabled:Boolean,fullscreen:Boolean,light:Boolean,maxWidth:{type:[String,Number],default:"none"},noClickAnimation:Boolean,origin:{type:String,default:"center center"},persistent:Boolean,retainFocus:{type:Boolean,default:!0},scrollable:Boolean,transition:{type:[String,Boolean],default:"dialog-transition"},width:{type:[String,Number],default:"auto"}},data:function(){return{activatedBy:null,animate:!1,animateTimeout:-1,isActive:!!this.value,stackMinZIndex:200,previousActiveElement:null}},computed:{classes:function(){var t;return t={},Object(s["a"])(t,"v-dialog ".concat(this.contentClass).trim(),!0),Object(s["a"])(t,"v-dialog--active",this.isActive),Object(s["a"])(t,"v-dialog--persistent",this.persistent),Object(s["a"])(t,"v-dialog--fullscreen",this.fullscreen),Object(s["a"])(t,"v-dialog--scrollable",this.scrollable),Object(s["a"])(t,"v-dialog--animated",this.animate),t},contentClasses:function(){return{"v-dialog__content":!0,"v-dialog__content--active":this.isActive}},hasActivator:function(){return Boolean(!!this.$slots.activator||!!this.$scopedSlots.activator)}},watch:{isActive:function(t){var e;t?(this.show(),this.hideScroll()):(this.removeOverlay(),this.unbind(),null==(e=this.previousActiveElement)||e.focus())},fullscreen:function(t){this.isActive&&(t?(this.hideScroll(),this.removeOverlay(!1)):(this.showScroll(),this.genOverlay()))}},created:function(){this.$attrs.hasOwnProperty("full-width")&&Object(g["e"])("full-width",this)},beforeMount:function(){var t=this;this.$nextTick((function(){t.isBooted=t.isActive,t.isActive&&t.show()}))},beforeDestroy:function(){"undefined"!==typeof window&&this.unbind()},methods:{animateClick:function(){var t=this;this.animate=!1,this.$nextTick((function(){t.animate=!0,window.clearTimeout(t.animateTimeout),t.animateTimeout=window.setTimeout((function(){return t.animate=!1}),150)}))},closeConditional:function(t){var e=t.target;return!(this._isDestroyed||!this.isActive||this.$refs.content.contains(e)||this.overlay&&e&&!this.overlay.$el.contains(e))&&this.activeZIndex>=this.getMaxZIndex()},hideScroll:function(){this.fullscreen?document.documentElement.classList.add("overflow-y-hidden"):d["a"].options.methods.hideScroll.call(this)},show:function(){var t=this;!this.fullscreen&&!this.hideOverlay&&this.genOverlay(),this.$nextTick((function(){t.$nextTick((function(){t.previousActiveElement=document.activeElement,t.$refs.content.focus(),t.bind()}))}))},bind:function(){window.addEventListener("focusin",this.onFocusin)},unbind:function(){window.removeEventListener("focusin",this.onFocusin)},onClickOutside:function(t){this.$emit("click:outside",t),this.persistent?this.noClickAnimation||this.animateClick():this.isActive=!1},onKeydown:function(t){if(t.keyCode===p["A"].esc&&!this.getOpenDependents().length)if(this.persistent)this.noClickAnimation||this.animateClick();else{this.isActive=!1;var e=this.getActivator();this.$nextTick((function(){return e&&e.focus()}))}this.$emit("keydown",t)},onFocusin:function(t){if(t&&this.retainFocus){var e=t.target;if(e&&![document,this.$refs.content].includes(e)&&!this.$refs.content.contains(e)&&this.activeZIndex>=this.getMaxZIndex()&&!this.getOpenDependentElements().some((function(t){return t.contains(e)}))){var n=this.$refs.content.querySelectorAll('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'),i=Object(a["a"])(n).find((function(t){return!t.hasAttribute("disabled")}));i&&i.focus()}}},genContent:function(){var t=this;return this.showLazyContent((function(){return[t.$createElement(o["a"],{props:{root:!0,light:t.light,dark:t.dark}},[t.$createElement("div",{class:t.contentClasses,attrs:Object(i["a"])({role:"document",tabindex:t.isActive?0:void 0},t.getScopeIdAttrs()),on:{keydown:t.onKeydown},style:{zIndex:t.activeZIndex},ref:"content"},[t.genTransition()])])]}))},genTransition:function(){var t=this.genInnerContent();return this.transition?this.$createElement("transition",{props:{name:this.transition,origin:this.origin,appear:!0}},[t]):t},genInnerContent:function(){var t={class:this.classes,ref:"dialog",directives:[{name:"click-outside",value:{handler:this.onClickOutside,closeConditional:this.closeConditional,include:this.getOpenDependentElements}},{name:"show",value:this.isActive}],style:{transformOrigin:this.origin}};return this.fullscreen||(t.style=Object(i["a"])(Object(i["a"])({},t.style),{},{maxWidth:"none"===this.maxWidth?void 0:Object(p["h"])(this.maxWidth),width:"auto"===this.width?void 0:Object(p["h"])(this.width)})),this.$createElement("div",t,this.getContentSlot())}},render:function(t){return t("div",{staticClass:"v-dialog__container",class:{"v-dialog__container--attached":""===this.attach||!0===this.attach||"attach"===this.attach},attrs:{role:"dialog"}},[this.genActivator(),this.genContent()])}})},"368e":function(t,e,n){},"99d9":function(t,e,n){"use strict";n.d(e,"b",(function(){return s})),n.d(e,"c",(function(){return o})),n.d(e,"d",(function(){return c})),n.d(e,"e",(function(){return r}));var i=n("b0afa");n.d(e,"a",(function(){return i["a"]}));var a=n("80d2"),s=Object(a["j"])("v-card__actions"),o=Object(a["j"])("v-card__subtitle"),c=Object(a["j"])("v-card__text"),r=Object(a["j"])("v-card__title");i["a"]},b863:function(t,e,n){"use strict";n.r(e);var i=function(){var t=this,e=t.$createElement,n=t._self._c||e;return n("div",{staticClass:"d-flex flex-grow-1 flex-row mt-2"},[n("v-navigation-drawer",{staticClass:"elevation-1 rounded flex-shrink-0",class:[t.$vuetify.rtl?"ml-3":"mr-3"],attrs:{app:t.$vuetify.breakpoint.mdAndDown,permanent:t.$vuetify.breakpoint.lgAndUp,floating:"",right:t.$vuetify.rtl,width:"240"},model:{value:t.drawer,callback:function(e){t.drawer=e},expression:"drawer"}},[n("div",{staticClass:"px-2 py-1"},[n("div",{staticClass:"title font-weight-bold primary--text"},[t._v("Chat Template")]),n("div",{staticClass:"overline"},[t._v("1.0.0")])]),n("v-list",{attrs:{dense:""}},[n("v-subheader",{staticClass:"ml-1 overline"},[t._v(t._s(t.$tc("chat.channel",2)))]),n("div",{staticClass:"mx-2 mb-2"},[n("v-btn",{attrs:{outlined:"",block:""},on:{click:function(e){t.showCreateDialog=!0}}},[n("v-icon",{attrs:{small:"",left:""}},[t._v("mdi-plus")]),t._v(" "+t._s(t.$t("chat.addChannel"))+" ")],1)],1),t._l(t.channels,(function(e){return n("v-list-item",{key:e,attrs:{to:"/apps/chat/channel/"+e,exact:""}},[n("v-list-item-content",[n("v-list-item-title",[t._v("# "+t._s(e))])],1)],1)}))],2)],1),n("v-card",{staticClass:"flex-grow-1"},[n("router-view",{key:t.$route.fullPath,attrs:{user:t.user},on:{"toggle-menu":function(e){t.drawer=!t.drawer}}})],1),n("v-dialog",{attrs:{"max-width":"400"},model:{value:t.showCreateDialog,callback:function(e){t.showCreateDialog=e},expression:"showCreateDialog"}},[n("v-card",[n("v-card-title",{staticClass:"title"},[t._v(t._s(t.$t("chat.addChannel")))]),n("div",{staticClass:"pa-3"},[n("v-text-field",{ref:"channel",attrs:{label:t.$tc("chat.channel",1),maxlength:"20",counter:"20",autofocus:""},on:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.addChannel()}},model:{value:t.newChannel,callback:function(e){t.newChannel=e},expression:"newChannel"}})],1),n("v-card-actions",{staticClass:"pa-2"},[n("v-spacer"),n("v-btn",{on:{click:function(e){t.showCreateDialog=!1}}},[t._v(t._s(t.$t("common.cancel")))]),n("v-btn",{attrs:{loading:t.isLoadingAdd,color:"success"},on:{click:function(e){return t.addChannel()}}},[t._v(t._s(t.$t("common.add")))])],1)],1)],1)],1)},a=[],s={data:function(){return{drawer:null,user:{id:12,name:"John Cena",avatar:"/images/avatars/avatar1.svg"},channels:["general","production","qa","staging","random"],showCreateDialog:!1,isLoadingAdd:!1,newChannel:""}},methods:{addChannel:function(){var t=this;this.newChannel?(this.isLoadingAdd=!0,setTimeout((function(){t.isLoadingAdd=!1,t.channels.push(t.newChannel),t.showCreateDialog=!1,t.$router.push("/apps/chat/channel/".concat(t.newChannel)),t.newChannel=""}),300)):this.$refs.channel.focus()}}},o=s,c=n("2877"),r=n("6544"),l=n.n(r),d=n("8336"),h=n("b0afa"),u=n("99d9"),v=n("169a"),f=n("132d"),m=n("8860"),g=n("da13"),p=n("5d23"),b=n("f774"),w=n("2fa4"),C=n("e0c7"),k=n("8654"),x=Object(c["a"])(o,i,a,!1,null,null,null);e["default"]=x.exports;l()(x,{VBtn:d["a"],VCard:h["a"],VCardActions:u["b"],VCardTitle:u["e"],VDialog:v["a"],VIcon:f["a"],VList:m["a"],VListItem:g["a"],VListItemContent:p["g"],VListItemTitle:p["k"],VNavigationDrawer:b["a"],VSpacer:w["a"],VSubheader:C["a"],VTextField:k["a"]})}}]);