<template>
    <div class="tabs-container">
        <v-tabs v-model="tab" background-color="#014c4f" color="#ffffff">
            <v-tab v-for="(tab, id) in tabs" :key="id">
                <span contenteditable="true">{{ tab.title }}</span>
                <v-icon class="mx-2" small @click="removeTab(tab)" v-if="id > 0">mdi-close</v-icon>
            </v-tab>
            <v-tab-item v-for="(tab, id) in tabs" :key="id" class="px-3 py-2">
                <slot></slot>
            </v-tab-item>
        </v-tabs>
        <div class="actions mt-2">
            <v-btn color="#014c4f" large>{{ $t("buttons.save") }}</v-btn>
        </div>
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
    methods: {
        removeTab(tab) {
            this.$emit("removeSelectedTab", tab)
        }
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