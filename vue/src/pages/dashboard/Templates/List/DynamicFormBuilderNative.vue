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
                <!-- <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendTable">
                        <v-icon>
                            mdi-table
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.table") }}</span>
                    </v-btn>
                </li> -->
                <li class="option-item">
                    <v-btn color="#014c4f" large @click="appendSelect">
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
                <!-- <li class="option-item">
                    <v-btn color="#014c4f" large>
                        <v-icon>
                            mdi-chart-box-outline
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.poll") }}</span>
                    </v-btn>
                </li> -->
                <!-- <li class="option-item">
                    <v-btn color="#014c4f" large>
                        <v-icon>
                            mdi-file-tree
                        </v-icon>
                        <span class="mx-1">{{ $t("builder.tree") }}</span>
                    </v-btn>
                </li> -->
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
                    show: true,
                },
            ],
            dropDownId: 1
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
    },
}
</script>

<style lang="scss">
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
</style>