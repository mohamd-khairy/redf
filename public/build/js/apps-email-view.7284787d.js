(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["apps-email-view"],{"0393":function(e,t,n){"use strict";n("0481"),n("4069");var a=n("5530"),i=(n("210b"),n("604c")),s=n("d9bd");t["a"]=i["a"].extend({name:"v-expansion-panels",provide:function(){return{expansionPanels:this}},props:{accordion:Boolean,disabled:Boolean,flat:Boolean,hover:Boolean,focusable:Boolean,inset:Boolean,popout:Boolean,readonly:Boolean,tile:Boolean},computed:{classes:function(){return Object(a["a"])(Object(a["a"])({},i["a"].options.computed.classes.call(this)),{},{"v-expansion-panels":!0,"v-expansion-panels--accordion":this.accordion,"v-expansion-panels--flat":this.flat,"v-expansion-panels--hover":this.hover,"v-expansion-panels--focusable":this.focusable,"v-expansion-panels--inset":this.inset,"v-expansion-panels--popout":this.popout,"v-expansion-panels--tile":this.tile})}},created:function(){this.$attrs.hasOwnProperty("expand")&&Object(s["a"])("expand","multiple",this),Array.isArray(this.value)&&this.value.length>0&&"boolean"===typeof this.value[0]&&Object(s["a"])(':value="[true, false, true]"',':value="[0, 2]"',this)},methods:{updateItem:function(e,t){var n=this.getValue(e,t),a=this.getValue(e,t+1);e.isActive=this.toggleMethod(n),e.nextIsActive=this.toggleMethod(a)}}})},"210b":function(e,t,n){},"3ce2":function(e,t,n){"use strict";n.r(t);var a=function(){var e=this,t=e.$createElement,n=e._self._c||t;return n("v-card",{staticClass:"min-w-0"},[n("div",{staticClass:"email-app-top px-2 py-1 d-flex align-center"},[n("v-btn",{attrs:{icon:""},on:{click:function(t){return e.$router.go(-1)}}},[n("v-icon",[e._v("mdi-arrow-left")])],1),n("div",{staticClass:"mx-3"},[n("v-btn",{attrs:{icon:""}},[n("v-icon",[e._v("bx-archive")])],1),n("v-btn",{attrs:{icon:""}},[n("v-icon",[e._v("mdi-email-mark-as-unread")])],1),n("v-btn",{attrs:{icon:""}},[n("v-icon",[e._v("bx-trash-alt")])],1)],1),n("v-spacer"),n("div",{staticClass:"caption mr-1"},[e._v("1 - 20 of 428")]),n("v-btn",{attrs:{icon:"",disabled:""}},[n("v-icon",[e._v("mdi-chevron-left")])],1),n("v-btn",{attrs:{icon:""}},[n("v-icon",[e._v("mdi-chevron-right")])],1)],1),n("v-divider"),n("div",{staticClass:"d-flex pa-2"},[n("div",{staticClass:"text-h6"},[e._v("Do you have Paris recommendations? Have you ever been?")]),n("v-spacer"),n("div",[n("v-btn",{attrs:{icon:""}},[n("v-icon",[e._v("mdi-printer")])],1)],1)],1),n("div",{staticClass:"pa-2"},[n("v-expansion-panels",{attrs:{multiple:""},model:{value:e.emailsExpanded,callback:function(t){e.emailsExpanded=t},expression:"emailsExpanded"}},e._l(e.emails,(function(t,a){return n("v-expansion-panel",{key:a},[n("v-expansion-panel-header",{scopedSlots:e._u([{key:"default",fn:function(a){var i=a.open;return[n("div",{staticClass:"d-flex"},[n("v-avatar",{attrs:{size:"36px"}},[n("img",{attrs:{src:t.avatar,alt:""}})]),n("div",{staticClass:"mx-3 min-w-0"},[n("div",{staticClass:"font-weight-bold mb-1"},[e._v(e._s(t.name))]),n("div",{directives:[{name:"show",rawName:"v-show",value:i,expression:"open"}]},[n("v-menu",{attrs:{"offset-y":"",right:"",transition:"slide-y-transition"},scopedSlots:e._u([{key:"activator",fn:function(t){var a=t.on;return[n("v-btn",e._g({staticClass:"pa-0",attrs:{text:""}},a),[e._v(" to me "),n("v-icon",{attrs:{small:"",right:""}},[e._v("mdi-chevron-down")])],1)]}}],null,!0)},[n("v-card",{staticClass:"pa-2"},[n("div",[n("span",{staticClass:"grey--text"},[e._v("from:")]),e._v(" johnnilson@whatthisisnotarealemail.com")]),n("div",[n("span",{staticClass:"grey--text"},[e._v("to:")]),e._v(" clara@whatthisisnotarealemail.com")])])],1)],1),n("div",{directives:[{name:"show",rawName:"v-show",value:!i,expression:"!open"}],staticClass:"text-truncate"},[e._v("Lorem ipsum dolor sit amet, consectetur adipisicing elit. Non ut, soluta temporibus, culpa magnam harum quod asperiores excepturi iste veniam possimus dignissimos laboriosam ipsum voluptas repellat consequuntur vitae debitis consequatur.")])]),n("v-spacer"),n("v-menu",{attrs:{"offset-y":"",left:"",transition:"slide-y-transition"},scopedSlots:e._u([{key:"activator",fn:function(t){var a=t.on;return[n("v-btn",e._g({attrs:{icon:""}},a),[n("v-icon",{attrs:{small:""}},[e._v("mdi-dots-vertical")])],1)]}}],null,!0)},[n("v-list",{attrs:{dense:"",nav:""}},[n("v-list-item",{attrs:{link:""}},[n("v-list-item-title",[e._v("Forward")])],1),n("v-list-item",{attrs:{link:""}},[n("v-list-item-title",[e._v("Delete")])],1)],1)],1)],1)]}},{key:"actions",fn:function(){return[n("span")]},proxy:!0}],null,!0)}),n("v-expansion-panel-content",[n("p",[e._v("Hello,")]),n("p",[e._v("Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.")]),n("p",[e._v("Best regards")])])],1)})),1),n("v-card",{staticClass:"mt-4"},[n("email-editor")],1)],1)],1)},i=[],s=n("440a"),o={components:{EmailEditor:s["a"]},data:function(){return{emailsExpanded:[3],emails:[{name:"Ubaldo Romaguera",avatar:"/images/avatars/avatar1.svg"},{name:"Ruben Breitenberg",avatar:"/images/avatars/avatar2.svg"},{name:"Blaze Carter",avatar:"/images/avatars/avatar3.svg"},{name:"Bernita Lehner",avatar:"/images/avatars/avatar4.svg"}]}}},r=o,l=(n("f286"),n("2877")),c=n("6544"),d=n.n(c),v=n("8212"),u=n("8336"),p=n("b0afa"),h=n("ce7e"),m=n("cd55"),x=n("49e2"),f=n("c865"),b=n("0393"),g=n("132d"),_=n("8860"),C=n("da13"),y=n("5d23"),w=n("e449"),k=n("2fa4"),B=Object(l["a"])(r,a,i,!1,null,"db3d74c6",null);t["default"]=B.exports;d()(B,{VAvatar:v["a"],VBtn:u["a"],VCard:p["a"],VDivider:h["a"],VExpansionPanel:m["a"],VExpansionPanelContent:x["a"],VExpansionPanelHeader:f["a"],VExpansionPanels:b["a"],VIcon:g["a"],VList:_["a"],VListItem:C["a"],VListItemTitle:y["k"],VMenu:w["a"],VSpacer:k["a"]})},"49e2":function(e,t,n){"use strict";var a=n("0789"),i=n("9d65"),s=n("a9ad"),o=n("3206"),r=n("80d2"),l=n("58df"),c=Object(l["a"])(i["a"],s["a"],Object(o["a"])("expansionPanel","v-expansion-panel-content","v-expansion-panel"));t["a"]=c.extend().extend({name:"v-expansion-panel-content",computed:{isActive:function(){return this.expansionPanel.isActive}},created:function(){this.expansionPanel.registerContent(this)},beforeDestroy:function(){this.expansionPanel.unregisterContent()},render:function(e){var t=this;return e(a["f"],this.showLazyContent((function(){return[e("div",t.setBackgroundColor(t.color,{staticClass:"v-expansion-panel-content",directives:[{name:"show",value:t.isActive}]}),[e("div",{class:"v-expansion-panel-content__wrap"},Object(r["u"])(t))])]})))}})},c865:function(e,t,n){"use strict";var a=n("5530"),i=n("0789"),s=n("9d26"),o=n("a9ad"),r=n("3206"),l=n("5607"),c=n("80d2"),d=n("58df"),v=Object(d["a"])(o["a"],Object(r["a"])("expansionPanel","v-expansion-panel-header","v-expansion-panel"));t["a"]=v.extend().extend({name:"v-expansion-panel-header",directives:{ripple:l["b"]},props:{disableIconRotate:Boolean,expandIcon:{type:String,default:"$expand"},hideActions:Boolean,ripple:{type:[Boolean,Object],default:!1}},data:function(){return{hasMousedown:!1}},computed:{classes:function(){return{"v-expansion-panel-header--active":this.isActive,"v-expansion-panel-header--mousedown":this.hasMousedown}},isActive:function(){return this.expansionPanel.isActive},isDisabled:function(){return this.expansionPanel.isDisabled},isReadonly:function(){return this.expansionPanel.isReadonly}},created:function(){this.expansionPanel.registerHeader(this)},beforeDestroy:function(){this.expansionPanel.unregisterHeader()},methods:{onClick:function(e){this.$emit("click",e)},genIcon:function(){var e=Object(c["u"])(this,"actions")||[this.$createElement(s["b"],this.expandIcon)];return this.$createElement(i["i"],[this.$createElement("div",{staticClass:"v-expansion-panel-header__icon",class:{"v-expansion-panel-header__icon--disable-rotate":this.disableIconRotate},directives:[{name:"show",value:!this.isDisabled}]},e)])}},render:function(e){var t=this;return e("button",this.setBackgroundColor(this.color,{staticClass:"v-expansion-panel-header",class:this.classes,attrs:{tabindex:this.isDisabled?-1:null,type:"button"},directives:[{name:"ripple",value:this.ripple}],on:Object(a["a"])(Object(a["a"])({},this.$listeners),{},{click:this.onClick,mousedown:function(){return t.hasMousedown=!0},mouseup:function(){return t.hasMousedown=!1}})}),[Object(c["u"])(this,"default",{open:this.isActive},!0),this.hideActions||this.genIcon()])}})},ca2c:function(e,t,n){},cd55:function(e,t,n){"use strict";var a=n("5530"),i=n("4e82"),s=n("3206"),o=n("80d2"),r=n("58df");t["a"]=Object(r["a"])(Object(i["a"])("expansionPanels","v-expansion-panel","v-expansion-panels"),Object(s["b"])("expansionPanel",!0)).extend({name:"v-expansion-panel",props:{disabled:Boolean,readonly:Boolean},data:function(){return{content:null,header:null,nextIsActive:!1}},computed:{classes:function(){return Object(a["a"])({"v-expansion-panel--active":this.isActive,"v-expansion-panel--next-active":this.nextIsActive,"v-expansion-panel--disabled":this.isDisabled},this.groupClasses)},isDisabled:function(){return this.expansionPanels.disabled||this.disabled},isReadonly:function(){return this.expansionPanels.readonly||this.readonly}},methods:{registerContent:function(e){this.content=e},unregisterContent:function(){this.content=null},registerHeader:function(e){this.header=e,e.$on("click",this.onClick)},unregisterHeader:function(){this.header=null},onClick:function(e){e.detail&&this.header.$el.blur(),this.$emit("click",e),this.isReadonly||this.isDisabled||this.toggle()},toggle:function(){var e=this;this.content&&(this.content.isBooted=!0),this.$nextTick((function(){return e.$emit("change")}))}},render:function(e){return e("div",{staticClass:"v-expansion-panel",class:this.classes,attrs:{"aria-expanded":String(this.isActive)}},Object(o["u"])(this))}})},f286:function(e,t,n){"use strict";n("ca2c")}}]);