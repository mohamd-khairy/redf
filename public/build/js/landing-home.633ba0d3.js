(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["landing-home"],{"6a56":function(t,e,a){t.exports=a.p+"img/feature1.78b4cfb3.jpg"},"72c9":function(t,e,a){},"8adc":function(t,e,a){},adcc:function(t,e,a){"use strict";a("72c9")},cc20:function(t,e,a){"use strict";a("4de4"),a("4160");var i=a("3835"),s=a("5530"),l=(a("8adc"),a("58df")),c=a("0789"),n=a("9d26"),r=a("a9ad"),o=a("4e82"),u=a("7560"),d=a("f2e7"),v=a("1c87"),m=a("af2b"),p=a("d9bd");e["a"]=Object(l["a"])(r["a"],m["a"],v["a"],u["a"],Object(o["a"])("chipGroup"),Object(d["b"])("inputValue")).extend({name:"v-chip",props:{active:{type:Boolean,default:!0},activeClass:{type:String,default:function(){return this.chipGroup?this.chipGroup.activeClass:""}},close:Boolean,closeIcon:{type:String,default:"$delete"},closeLabel:{type:String,default:"$vuetify.close"},disabled:Boolean,draggable:Boolean,filter:Boolean,filterIcon:{type:String,default:"$complete"},label:Boolean,link:Boolean,outlined:Boolean,pill:Boolean,tag:{type:String,default:"span"},textColor:String,value:null},data:function(){return{proxyClass:"v-chip--active"}},computed:{classes:function(){return Object(s["a"])(Object(s["a"])(Object(s["a"])(Object(s["a"])({"v-chip":!0},v["a"].options.computed.classes.call(this)),{},{"v-chip--clickable":this.isClickable,"v-chip--disabled":this.disabled,"v-chip--draggable":this.draggable,"v-chip--label":this.label,"v-chip--link":this.isLink,"v-chip--no-color":!this.color,"v-chip--outlined":this.outlined,"v-chip--pill":this.pill,"v-chip--removable":this.hasClose},this.themeClasses),this.sizeableClasses),this.groupClasses)},hasClose:function(){return Boolean(this.close)},isClickable:function(){return Boolean(v["a"].options.computed.isClickable.call(this)||this.chipGroup)}},created:function(){var t=this,e=[["outline","outlined"],["selected","input-value"],["value","active"],["@input","@active.sync"]];e.forEach((function(e){var a=Object(i["a"])(e,2),s=a[0],l=a[1];t.$attrs.hasOwnProperty(s)&&Object(p["a"])(s,l,t)}))},methods:{click:function(t){this.$emit("click",t),this.chipGroup&&this.toggle()},genFilter:function(){var t=[];return this.isActive&&t.push(this.$createElement(n["b"],{staticClass:"v-chip__filter",props:{left:!0}},this.filterIcon)),this.$createElement(c["g"],t)},genClose:function(){var t=this;return this.$createElement(n["b"],{staticClass:"v-chip__close",props:{right:!0,size:18},attrs:{"aria-label":this.$vuetify.lang.t(this.closeLabel)},on:{click:function(e){e.stopPropagation(),e.preventDefault(),t.$emit("click:close"),t.$emit("update:active",!1)}}},this.closeIcon)},genContent:function(){return this.$createElement("span",{staticClass:"v-chip__content"},[this.filter&&this.genFilter(),this.$slots.default,this.hasClose&&this.genClose()])}},render:function(t){var e=[this.genContent()],a=this.generateRouteLink(),i=a.tag,l=a.data;l.attrs=Object(s["a"])(Object(s["a"])({},l.attrs),{},{draggable:this.draggable?"true":void 0,tabindex:this.chipGroup&&!this.disabled?0:l.attrs.tabindex}),l.directives.push({name:"show",value:this.active}),l=this.setBackgroundColor(this.color,l);var c=this.textColor||this.outlined&&this.color;return t(i,this.setTextColor(c,l),e)}})},d4e2:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("v-sheet",[a("v-container",{staticClass:"py-6 pt-lg-15 text-center"},[a("h1",{staticClass:"text-h4 text-sm-h3 text-md-h2 text-lg-h1"},[t._v("Your awesome "),a("span",{staticClass:"primary--text"},[t._v("web project")])]),a("h2",{staticClass:"text-h6 text-sm-h5 mt-4 w-full w-md-8-12 w-xl-half mx-auto"},[t._v("Lorem ipsum dolor sit, amet consectetur adipisicing elit. Commodi ex facilis ad atque natus tenetur debitis qui quisquam iure amet.")]),a("div",{staticClass:"mt-8"},[a("v-btn",{staticClass:"my-1 mx-sm-1 w-full w-sm-auto",attrs:{"x-large":"",color:"primary"}},[t._v("Start free trial")]),a("v-btn",{staticClass:"my-1 mx-sm-1 w-full w-sm-auto",attrs:{"x-large":""}},[t._v("Learn more")])],1)])],1),a("Partners"),a("Stats"),a("v-container",[a("v-divider")],1),a("Feature2"),a("Feature1"),a("Pricing"),a("CallToAction")],1)},s=[],l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-sheet",{staticClass:"d-flex flex-column"},[a("svg",{staticClass:"logos-bg",attrs:{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 1442 163"}},[a("path",{attrs:{d:"m-3.90909,6l48.30303,16c48.30303,16 144.90908,48 241.51514,48c96.60606,0 193.21211,-32 289.81817,-32c96.60606,0 193.21211,32 289.81817,53.3c96.60606,21.7 193.21211,31.7 289.81817,16c96.60606,-16.3 193.21211,-58.3 241.51514,-80l48.30303,-21.3l0,160l-48.30303,0c-48.30303,0 -144.90908,0 -241.51514,0c-96.60606,0 -193.21211,0 -289.81817,0c-96.60606,0 -193.21211,0 -289.81817,0c-96.60606,0 -193.21211,0 -289.81817,0c-96.60606,0 -193.21211,0 -241.51514,0l-48.30303,0l0,-160z"}})])])},c=[],n=(a("adcc"),a("2877")),r=a("6544"),o=a.n(r),u=a("8dd9"),d={},v=Object(n["a"])(d,l,c,!1,null,"30056104",null),m=v.exports;o()(v,{VSheet:u["a"]});var p=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-container",{staticClass:"pt-0 pb-4"},[a("v-row",t._l(t.stats,(function(e,i){return a("v-col",{key:i,attrs:{cols:"12",sm:"6",lg:"3"}},[a("div",{staticClass:"text-center"},[a("div",{staticClass:"text-h2 text-number font-weight-light"},[t._v(t._s(e.value))]),a("v-responsive",{staticClass:"mx-auto",attrs:{"max-width":"300"}},[a("div",{staticClass:"font-weight-regular my-2"},[t._v("Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit.")]),a("div",{staticClass:"text-h6 text-lg-h5"},[t._v(t._s(e.title))])])],1)])})),1)],1)},h=[],f={data:function(){return{stats:[{title:"Projects",value:"4,253"},{title:"API Requests",value:"1,283,787"},{title:"Subscribers",value:"1,348"},{title:"Businesses",value:"331,234"}]}}},g=f,b=a("62ad"),C=a("a523"),x=a("6b53"),_=a("0fd9"),w=Object(n["a"])(g,p,h,!1,null,null,null),y=w.exports;o()(w,{VCol:b["a"],VContainer:C["a"],VResponsive:x["a"],VRow:_["a"]});var V=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-container",{staticClass:"pt-4 pt-md-8 pb-10",attrs:{id:"pricing"}},[a("v-responsive",{staticClass:"mx-auto text-center",attrs:{"max-width":"1200"}},[a("h2",{staticClass:"text-h3 mb-2"},[t._v("Pricing")]),a("div",{staticClass:"text-h6 text-lg-h5"},[t._v("Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus impedit error labore doloremque fugit.")])]),a("v-row",{staticClass:"mt-10"},t._l(t.plans,(function(e,i){return a("v-col",{key:i,attrs:{cols:"12",lg:"4"}},[a("v-card",{staticClass:"text-body-1 pa-4",attrs:{outlined:"",elevation:"2"}},[a("div",{staticClass:"d-flex justify-space-between"},[a("div",{staticClass:"mr-2"},[a("div",{staticClass:"d-flex align-center"},[a("div",{staticClass:"text-h4 font-weight-black"},[t._v(t._s(e.title))]),e.featured?a("div",{staticClass:"ml-2"},[a("v-chip",{staticClass:"font-weight-black",attrs:{small:"",color:"primary"}},[t._v("Popular")])],1):t._e()]),a("div",{staticClass:"mt-1"},[t._v("Lorem ipsum dolor sit amet consectetur adipisicing elit.")])]),a("div",{staticClass:"text-right"},[a("div",{staticClass:"d-flex align-center"},[a("div",{staticClass:"text-h5 font-weight-light mr-1"},[t._v("$")]),a("div",{staticClass:"text-h3 text-number font-weight-black"},[t._v(t._s(e.price))])]),a("div",{staticClass:"justify-end overline"},[t._v("/month")])])]),a("v-divider",{staticClass:"my-4"}),a("div",{staticClass:"text-h6"},t._l(e.features,(function(e,i){return a("div",{key:i,staticClass:"d-flex align-center justify-space-between my-1"},[a("div",[e.value?a("span",{staticClass:"font-weight-black mr-1"},[t._v(t._s(e.value))]):t._e(),a("span",{staticClass:"text-truncate font-weight-regular"},[t._v(t._s(e.label))])]),a("v-icon",{attrs:{color:"primary"}},[t._v("mdi-check")])],1)})),0),a("v-divider",{staticClass:"my-4"}),a("v-btn",{attrs:{"x-large":"",block:"",outlined:!e.featured,color:e.featured?"primary":"",to:"/auth/signup"}},[t._v(" Subscribe ")])],1)],1)})),1)],1)},k=[],B={data:function(){return{plans:[{title:"Basic",price:"29",features:[{value:"5",label:"Accounts"},{value:"100GB",label:"Bandwith"},{value:"10GB",label:"Disk Space"},{value:"",label:"Unlimited Emails"}]},{title:"Startup",price:"49",featured:!0,features:[{value:"20",label:"Accounts"},{value:"1TB",label:"Bandwith"},{value:"100GB",label:"Disk Space"},{value:"",label:"Unlimited Emails"}]},{title:"Enterprise",price:"149",features:[{value:"100",label:"Accounts"},{value:"10TB",label:"Bandwith"},{value:"10TB",label:"Disk Space"},{value:"",label:"Unlimited Emails"}]}]}}},j=B,S=a("8336"),E=a("b0afa"),$=a("cc20"),O=a("ce7e"),q=a("132d"),L=Object(n["a"])(j,V,k,!1,null,null,null),A=L.exports;o()(L,{VBtn:S["a"],VCard:E["a"],VChip:$["a"],VCol:b["a"],VContainer:C["a"],VDivider:O["a"],VIcon:q["a"],VResponsive:x["a"],VRow:_["a"]});var G=function(){var t=this,e=t.$createElement,i=t._self._c||e;return i("v-sheet",[i("v-container",{staticClass:"py-4 py-lg-8"},[i("v-row",[i("v-col",{attrs:{cols:"12",lg:"6"}},[i("div",{staticClass:"text-uppercase font-weight-bold body-2 primary--text mb-2 mt-0 mt-xl-10"},[t._v("Work with us")]),i("h2",{staticClass:"text-h3"},[t._v("Get your startup ready for business")]),i("div",{staticClass:"text-h6 text-lg-h5 mt-5"},[t._v("Lorem ipsum dolor sit amet consectetur adipisicing elit. Natus impedit error labore doloremque fugit! Dolor fugit molestiae vero quos quisquam nobis, eos debitis magni omnis ea incidunt amet voluptate dignissimos!")])]),i("v-col",{attrs:{cols:"12",lg:"6"}},[i("v-img",{staticClass:"elevation-6",attrs:{src:a("6a56"),"max-height":"480"}})],1)],1)],1)],1)},P=[],I=a("adda"),R={},D=Object(n["a"])(R,G,P,!1,null,null,null),T=D.exports;o()(D,{VCol:b["a"],VContainer:C["a"],VImg:I["a"],VRow:_["a"],VSheet:u["a"]});var F=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-sheet",[a("v-container",{staticClass:"py-8"},[a("div",{staticClass:"d-flex flex-column flex-lg-row justify-space-between align-center"},[a("div",{staticClass:"text-center text-lg-left"},[a("div",{staticClass:"text-h3"},[t._v("Ready to talk?")]),a("div",{staticClass:"text-h3 primary--text"},[t._v("Our team is here to help.")])]),a("div",{staticClass:"mt-4 mt-lg-0"},[a("v-btn",{staticClass:"my-1 mx-sm-2 w-full w-sm-auto",attrs:{"x-large":"",color:"primary"}},[t._v("Contact Sales")]),a("v-btn",{staticClass:"my-1 w-full w-sm-auto",attrs:{"x-large":""}},[t._v("Learn more")])],1)])])],1)},z=[],U={},J=Object(n["a"])(U,F,z,!1,null,null,null),N=J.exports;o()(J,{VBtn:S["a"],VContainer:C["a"],VSheet:u["a"]});var W=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("v-container",{staticClass:"py-4"},[a("v-row",t._l(t.features,(function(e,i){return a("v-col",{key:i,attrs:{cols:"12",md:"6"}},[a("div",{staticClass:"d-flex"},[a("div",{staticClass:"mr-2"},[a("v-sheet",{staticClass:"pa-2 elevation-1",attrs:{outlined:"",rounded:""}},[a("v-icon",[t._v(t._s(e.icon))])],1)],1),a("div",[a("div",{staticClass:"text-h5"},[t._v(t._s(e.title))]),a("div",{staticClass:"text-h6 font-weight-light mt-1"},[t._v(t._s(e.description))])])])])})),1)],1)},Y=[],H={data:function(){return{features:[{icon:"mdi-account-check-outline",title:"Account Verification",description:"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{icon:"mdi-lifebuoy",title:"Dedicated Support",description:"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{icon:"mdi-email-open-multiple-outline",title:"Email Integration",description:"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"},{icon:"mdi-clock-outline",title:"Save Time",description:"Lorem ipsum dolor sit amet consectetur adipisicing elit. Esse expedita fugit quam aliquam. Autem assumenda"}]}}},K=H,M=Object(n["a"])(K,W,Y,!1,null,null,null),Q=M.exports;o()(M,{VCol:b["a"],VContainer:C["a"],VIcon:q["a"],VRow:_["a"],VSheet:u["a"]});var X={components:{Partners:m,Stats:y,Pricing:A,Feature1:T,Feature2:Q,CallToAction:N}},Z=X,tt=Object(n["a"])(Z,i,s,!1,null,null,null);e["default"]=tt.exports;o()(tt,{VBtn:S["a"],VContainer:C["a"],VDivider:O["a"],VSheet:u["a"]})}}]);