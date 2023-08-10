<template>
    <div class="tabs-container">
        <v-tabs v-model="tab" background-color="primary" color="#ffffff">
            <v-tab v-for="(tab, index) in tabs" :key="index">
                <span contenteditable="true" @input="event => onInput(event, index)"
                      @keyup.delete="onRemove(index)" :id="`content-${index}`"
                >{{ tab.title }}</span>
                <v-icon class="mx-2" small @click="removeTab(tab)" v-if="index > 0">mdi-close</v-icon>
            </v-tab>
            <v-tab-item v-for="(tab, index) in tabs" :key="index" class="px-3 py-2">
                <slot></slot>
            </v-tab-item>
        </v-tabs>
    </div>
</template>

<script>
export default {
  props: ["tabs"],
  data() {
      return {
        tab: 1,
      }
  },
  mounted() {
    this.updateAllTabs();
  },
  methods: {
    removeTab(tab) {
        this.$emit("removeSelectedTab", tab)
    },
    onInput(event, index) {
      const value = event.target.innerText;
      this.tabs[index].title = value;
    },
    onRemove(index) {
      if (this.tabs.length > 1 && this.tabs[index].title.length === 0) {
        this.$delete(this.tabs, index);
        this.updateAllTabs();
      }
    },
    updateAllTabs() {
      this.tabs.forEach((c, index) => {
        const el = document.getElementById(`content-${index}`);
        el.innerText = c.title;
      });
    },
  }
}
</script>

<style lang="scss" scoped>
.v-window-item {
    padding: 10px 20px;
    min-height: 300px;
    background: #2e2e2e3a;
}

.theme--light.v-tabs>.v-tabs-bar .v-tab:not(.v-tab--active),
.theme--light.v-tabs>.v-tabs-bar .v-tab:not(.v-tab--active)>.v-icon,
.theme--light.v-tabs>.v-tabs-bar .v-tab:not(.v-tab--active)>.v-btn,
.theme--light.v-tabs>.v-tabs-bar .v-tab--disabled {
    color: rgb(255 255 255 / 70%) !important;
}
</style>
