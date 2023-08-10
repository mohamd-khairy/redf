<template>
    <div class="page-dynamic">
        <v-card>
            <ul class="options-list">
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendPage">
                        <v-icon>
                            mdi-tab
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.page") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendLabel">
                        <v-icon>
                            mdi-label
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.label") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendTextBox">
                        <v-icon>
                            mdi-form-textbox
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.text") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendTable">
                        <v-icon>
                            mdi-table
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.table") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large>
                        <v-icon>
                            mdi-list-box-outline
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.select") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendTextArea">
                        <v-icon>
                            mdi-form-textarea
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.textarea") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large>
                        <v-icon>
                            mdi-chart-box-outline
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.poll") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large>
                        <v-icon>
                            mdi-file-tree
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.tree") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendColumn">
                        <v-icon>
                            mdi-table-row
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.column") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendRadio">
                        <v-icon>
                            mdi-radiobox-marked
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.radio") }}</span>
                    </v-btn>
                </li>
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendAttachment">
                        <v-icon>
                            mdi-file-document-plus-outline
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.attachment") }}</span>
                    </v-btn>
                </li>
            </ul>
            <Tabs :tabs="Tabs" @removeSelectedTab="removeSelectedTab"></Tabs>
        </v-card>
    </div>
</template>

<script>
import Tabs from '../../../../components/dynamic-elements/Tabs.vue';
export default {
    components: { Tabs },
    props: ["id"],
    data() {
        return {
            tabs: [
                {
                    title: "Tab 1",
                    content: {
                        label: "Label 1"
                    },
                    show: true
                },
            ],
        }
    },
    computed: {
        Tabs() {
            return this.tabs.filter((act) => act.show === true)
        }
    },
    methods: {
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
            container.classList.add("created-element", "mb-2", "d-flex", "align-items-center")
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
            icon.classList.add("v-icon", "notranslate", "mdi", "mdi-label", "theme--light")
            span.setAttribute("contenteditable", true)
            span.classList.add("mx-1")
            optionsDropDown.appendChild(optionsDropDownDeleteBtn)
            optionsBtn.appendChild(optionsIcon)
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

            container.classList.add("created-element", "mb-2", "d-flex", "align-items-center")
            elementContainer.classList.add("element-container")
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
            optionsDropDown.appendChild(optionsDropDownDeleteBtn)
            optionsBtn.appendChild(optionsIcon)
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

            container.classList.add("created-element", "mb-2", "d-flex", "align-items-center")
            elementContainer.classList.add("element-container")
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
            optionsDropDown.appendChild(optionsDropDownDeleteBtn)
            optionsBtn.appendChild(optionsIcon)
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

            container.classList.add("created-element", "mb-2", "d-flex", "align-items-center")
            elementContainer.classList.add("element-container", "row")
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
            optionsDropDown.appendChild(optionsDropDownDeleteBtn)
            optionsBtn.appendChild(optionsIcon)
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

            container.classList.add("created-element", "mb-2", "d-flex", "align-items-center")
            elementContainer.classList.add("element-container", "form-check")
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
            optionsDropDown.appendChild(optionsDropDownDeleteBtn)
            optionsBtn.appendChild(optionsIcon)
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

            container.classList.add("created-element", "mb-2", "d-flex", "align-items-center")
            elementContainer.classList.add("element-container")
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
            optionsDropDown.appendChild(optionsDropDownDeleteBtn)
            optionsBtn.appendChild(optionsIcon)
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
                const cellHeadText = document.createTextNode(`Table Head Column`);
                cellHead.appendChild(cellHeadText);
                rowHead.appendChild(cellHead);
                for (let j = 0; j < 2; j++) {
                    const cellBody = document.createElement("td");
                    const cellBodyText = document.createTextNode(`Table Body Column`);
                    cellBody.appendChild(cellBodyText);
                    rowBody.appendChild(cellBody);
                }
                tblHead.appendChild(rowHead);
                tblBody.appendChild(rowBody);
            }

            // put the <tbody> in the <table>
            tbl.appendChild(tblHead);
            tbl.appendChild(tblBody);
            // appends <table> into <body>
            // document.body.appendChild(tbl);
            // sets the border attribute of tbl to '2'
            activeTab.appendChild(tbl)
        },
        appendPage() {
            this.tabs.push({
                title: `Tab`,
                content: {
                    label: "New"
                },
                show: true
            })
        },
        removeSelectedTab(tab) {
            let index = this.tabs.findIndex((t) => t === tab)
            this.tabs[index].show = false
        },
        toggleDropdown(e) {
            console.log("Working ...", e.target.parentElement.parentElement);
            // let dropdowns = document.getElementsByClassName("dropdown-content");
            // if (!event.target.matches('.dropdown_btn')) {
            //     var i;
            //     for (i = 0; i < dropdowns.length; i++) {
            //         var openDropdown = dropdowns[i];
            //         if (openDropdown.classList.contains('show')) {
            //             openDropdown.classList.remove('show');
            //         }
            //     }
            // }
        }
    },
    mounted() {
        //     let tabs = document.querySelectorAll(".page-dynamic .v-slide-group__wrapper .v-tab");
        //     tabs.forEach((tab) => {
        //         console.log(tab);
        //     })
    }
}
</script>

<style>
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
    min-width: 30px;
    border-radius: 4px;
    padding: 10px;
    /* overflow: auto; */
    box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
    z-index: 1;
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
</style>