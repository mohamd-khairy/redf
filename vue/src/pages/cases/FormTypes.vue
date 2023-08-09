<template>
  <div class="d-flex flex-column flex-grow-1">
    <!-- <div class="d-flex align-center pb-3">
      <div class="d-flex flex-column ">
        <div class="d-flex align-baseline">
          <div class="display-1">{{ $t("menu.pipesModel") }}</div>
        </div>
        <v-breadcrumbs :items="breadcrumbs" class="pa-0 py-2"></v-breadcrumbs>
      </div>
      <v-spacer></v-spacer>
    </div> -->

    <v-row>
      <v-col v-for="form in forms" :key="form.id" cols="4">
        <v-hover v-slot="{ hover }">
          <v-card
            class="formType-card"
            :class="{ 'on-hover': hover }"
            @click="openModels(form.id)"
            style="overflow: hidden"
          >
            <v-card-title class="card-title-cont">
              <v-avatar
                rounded
                class="me-2 v-avatar--variant-tonal primary--text"
                size="56"
                ><v-icon>mdi-scale-balance </v-icon></v-avatar
              >
              <div class="title-cont">
                <!--                <h6 class="text-caption">Form</h6>-->
                {{ form.name }}
              </div>
            </v-card-title>

            <v-sheet v-if="hover" class="overlay" color="rgba(0, 0, 0, 0.5)">
              <v-icon class="link-icon" size="48" color="white">
                <!-- mdi-link-variant -->
                mdi-open-in-new
              </v-icon>
            </v-sheet>
          </v-card>
        </v-hover>
      </v-col>
    </v-row>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  name: "FormTypes",
  components: {},
  data() {
    return {
      breadcrumbs: [
        {
          text: this.$t("menu.cases"),
          disabled: false,
          href: "#",
        },
        {
          text: this.$t("cases.caseType"),
        },
      ],
      x: 0,
      y: 0,
    };
  },
  computed: {
    ...mapState("cases", ["forms"]),
  },
  created() {
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.cases"),
    });
  },
  mounted() {
    this.open();
  },
  unmounted() {},
  methods: {
    ...mapActions("cases", ["getForms"]),
    ...mapActions("app", ["setBreadCrumb"]),
    searchUser() {},
    open() {
      let { id } = this.$route.params;
      this.isLoading = true;
      this.getForms(id)
        .then(() => {
          console.log(this.forms);
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    openModels(id) {
      this.$router.push("/cases/create/" + id);
    },
    mouseEnterFunction(e, path) {
      // console.log("mouseEnterEv");
      const rel = path.getAttribute("class");
      const title = path.getAttribute("title");

      document
        .getElementsByClassName("RegionsNameInMap")
        .forEach((elm) => (elm.style.display = "block"));
      document.getElementsByClassName("RegionsNameInMap").innerHTML = title;
    },
    mouseLeaveFunction() {
      document.getElementsByClassName("RegionsNameInMap").forEach((elm) => {
        elm.innerHTML = "";
        elm.style.display = "none";
      });
    },
    mouseMoveFunction(event) {
      this.x = event.clientX; // Get the horizontal coordinate
      this.y = event.clientY; // Get the vertical coordinate

      document.getElementsByClassName("RegionsNameInMap").forEach((element) => {
        element.style.left = this.x + 10 + "px";
        element.style.top = this.y + 10 + "px";
      });
    },
  },
};
</script>

<style lang="scss" scoped>
.animated-card {
  background-color: #e0f2f8;
  transition: all 0.2s ease-in-out;
}
.animated-card:hover {
  transform: scale(1.1);
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.reg-img path {
  cursor: pointer;
  fill: #e1e1e1;
  stroke: #898989;
}

.reg-img path:hover {
  fill: #777676;
  cursor: pointer;
}

.reg-img .pathDis {
  pointer-events: none;
  cursor: default;
}

.RegionsNameInMap {
  position: fixed;
  z-index: 12;
  color: #ffffff;
  background: #034d86;
  padding: 2px 5px;
  font-size: 12px;
  border-radius: 5px;
  display: none;
}

.overlay {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  display: flex;
  justify-content: center;
  align-items: center;
}

.link-icon {
  transition: transform 0.3s ease-in-out;
}

.overlay:hover .link-icon {
  transform: scale(1.1);
}
.formType-card .title-cont {
  word-break: break-word;
}
.formType-card .card-title-cont {
  flex-wrap: nowrap;
}

////
</style>
