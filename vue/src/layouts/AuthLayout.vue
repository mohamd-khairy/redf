<template>
  <div class="d-flex text-center flex-column flex-md-row flex-grow-1">
    <v-sheet class="layout-side mx-auto mx-md-1 d-none d-md-flex flex-md-column justify-space-between px-2">
      <div class="mt-3 mt-md-10 pa-2">
        <div class="display-2 font-weight-bold primary--text">
          {{ product.name }}
        </div>
        <div class="title my-2">
          Welcome! Let's build amazing things together.
        </div>
        <v-btn to="/" class="my-4">Take me back</v-btn>
      </div>
      <!-- <img class="w-full" :src="require('@/assets/images/logo.png') 
      " />-->
      <img class="w-full" :src="websiteLoginIcon" />
    </v-sheet>

    <div class="pa-2 pa-md-4 flex-grow-1 align-center justify-center d-flex flex-column">
      <div class="layout-content ma-auto w-full">
        <slot></slot>
      </div>
      <!--      <div class="overline mt-4">{{ product.name }} - {{ product.version }}</div>-->
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  computed: {
    ...mapState("app", ["product"]),
    ...mapState("app", {
      websiteLoginIcon: state => {
        if (!state.websiteLoginIcon) {
          return "/images/logo.svg";
        }
        return state.websiteLoginIcon;
      }
    })
  }
};
</script>

<style scoped>
.layout-side {
  width: 320px;
  background: #014c4f;
  color: #fff;
}

.layout-content {
  max-width: 480px;
}
</style>
