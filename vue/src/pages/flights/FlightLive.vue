<template>
  <div class="d-flex flex-column flex-grow-1">
    <v-row>
      <v-col cols="12">
        <v-card class="live-stream_card">
          <v-card-text>
            <div class="img-wrapper">
              <img :src="image" alt="">
              <div class="no-selected-drone" v-if="!currentDrone">
                <h1>
                  Select drone to watch live stream
                </h1>
              </div>
              <v-btn icon class="fullscreen-btn" @click="toggleFullScreen" v-if="currentDrone">
                <v-icon color="green">
                  mdi-fullscreen
                </v-icon>
              </v-btn>
              <div class="selectedCam_info" v-if="currentDroneInfo">
                <div>{{ currentDroneInfo.name }} is selected</div>
                <v-icon class="inner-status" :color="currentdronestatus">
                  mdi-circle
                </v-icon>
              </div>
            </div>
          </v-card-text>
          <v-card-actions>
            <div class="cameras-cont">
              <div class="cam"
                :class="[dron.status === 'active' ? 'live' : '', dron.id === currentDrone ? 'selected' : '']"
                v-for="dron in drones" :key="dron.id" :id="'drone-' + dron.id" @click="selectDrone(dron.id)">
                <div class="icon">
                  <cameraSvg />
                </div>
                <div class="cam-info">
                  <div class="cam-name subtitle-2">
                    {{ dron.name }}
                  </div>
                  <div class="flight-info caption">
                    {{ dron.number }}
                  </div>
                </div>
              </div>

            </div>
          </v-card-actions>
        </v-card>
      </v-col>
    </v-row>
  </div>
</template>

<script>

import { mapActions, mapState } from "vuex";
import cameraSvg from "@/assets/images/illustrations/camera.svg";



export default {
  sockets: {
    connect: function () {
      console.log("socket connected");
    },
    itemAdded: function (data) {
      let { id: currentLocationId } = this.$route.params;
      const { image, status, drone_id, location_id } = data
      if (+currentLocationId !== location_id) return
      if (this.currentDrone === drone_id) {
        this.image = image;
        let innerStatusElm = document.querySelector('.inner-status')
        innerStatusElm.classList.add('live')
      }
      let droneElm = document.querySelector(`#drone-${drone_id}`)
      if (!droneElm) return
      if (status === 1) {
        droneElm.classList.add('live')
      } else {
        droneElm.classList.remove('live')
        if (this.currentDrone === drone_id) {

          let innerStatusElm = document.querySelector('.inner-status')
          innerStatusElm.classList.remove('live')
        }
      }
    }
  },
  components: {
    cameraSvg



  },

  data() {
    return {
      isLoading: false,
      isFullscreen: false,
      drones: [],
      currentDrone: undefined,
      currentDroneInfo: null,
      image: '',
      breadcrumbs: [
        {
          text: this.$t("menu.flights"),
          disabled: false,
          href: "#"
        },
        {
          text: this.$t("menu.live_stream"),
          disabled: false,
          href: "/flights/locations"
        },
        {
          text: ""
        }
      ],

      location: ""
    };
  },
  computed: {
    ...mapState("events", [
      "locations",
    ]),
    ...mapState("app", ["isRTL", "notifications"]),
    currentdronestatus() {
      if (!this.currentDroneInfo) return "red"
      let status = this.currentDroneInfo.status
      if (status === 'active') {
        return 'green'
      } return 'red'
    }




  },
  created() {
    // this.endDate.setDate(this.endDate.getDate() + 6)
    // this.dateRange = {
    //   startDate , endDate
    // }

    let { id } = this.$route.params;
    this.fetchLocations()
    this.setBreadCrumb({
      breadcrumbs: this.breadcrumbs,
      pageTitle: this.$t("menu.pipesModel")
    });

    this.getdrones()

  },
  mounted() {
    document.querySelector('.img-wrapper img').addEventListener('fullscreenchange', function () {
      if (document.fullscreenElement) {
        console.log("entered Full screen");
      } else {
        console.log("out");
        this.isFullscreen = !this.isFullscreen;
        this.classList.remove('object-cover')
      }
    })
  },
  watch: {
    isFullscreen(newVal) {

    }

  },

  methods: {
    ...mapActions("events", [
      "getLocations"
    ]),
    ...mapActions("app", ["setBreadCrumb"]),
    fetchLocations() {
      this.isLoading = true;
      this.getLocations()
        .then(() => {
          this.isLoading = false;
          let { id } = this.$route.params;
          // let selectedLocation = this.locations[id] || 'not_selected';
          let selectedLocation =
            this.locations.find(item => item.id == id) || "not_selected";
          // this.breadcrumbs[2].text = this.$t(`breadcrumbs.${selectedLocation.name}`);
          this.breadcrumbs[2].text = selectedLocation.name;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },

    open() {
      this.isLoading = true;
      const { id } = this.$route.params;

    },
    selectDrone(droneId) {
      this.image = ''
      this.currentDrone = droneId
      this.currentDroneInfo = this.drones.find(drone => drone.id === droneId)

    },



    getdrones() {
      this.isLoading = true;
      const { id } = this.$route.params;
      this.$axios.get(`get-drones/${id}`)
        .then((res) => {
          this.drones = res?.data?.data ?? []
          if (this.drones.length) {
            this.currentDrone = this.drones[0].id
            this.currentDroneInfo = this.drones[0]
          }
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },

    toggleFullScreen() {
      this.isFullscreen = true;

      const imgElement = document.querySelector('.img-wrapper img');

      if (this.isFullscreen) {
        if (imgElement.requestFullscreen) {
          imgElement.requestFullscreen();
        } else if (imgElement.mozRequestFullScreen) { // Firefox
          imgElement.mozRequestFullScreen();
        } else if (imgElement.webkitRequestFullscreen) { // Chrome, Safari and Opera
          imgElement.webkitRequestFullscreen();
        } else if (imgElement.msRequestFullscreen) { // IE/Edge
          imgElement.msRequestFullscreen();
        }
        imgElement.classList.add('object-cover')
      } else {
        if (document.exitFullscreen) {
          document.exitFullscreen();
        } else if (document.mozCancelFullScreen) { // Firefox
          document.mozCancelFullScreen();
        } else if (document.webkitExitFullscreen) { // Chrome, Safari and Opera
          document.webkitExitFullscreen();
        } else if (document.msExitFullscreen) { // IE/Edge
          document.msExitFullscreen();
        }

      }
    }




  },

};
</script>

<style lang="scss" scoped>
.live-stream_card {
  display: flex;
  justify-content: space-between;
  align-items: center;

  height: 70vh;
  min-height: 380px;

  .v-card__text {
    height: 100%;
  }

  .v-card__actions {
    flex: 0 0 300px;
    height: 100%;
  }
}

.img-wrapper {
  width: 100%;
  height: 100%;

  min-height: 350px;
  background: #f2f2f2;
  border-radius: 6px;
  position: relative;

  img {
    width: 100%;
    max-height: 100%;
    object-fit: cover;

    &.object-cover {
      object-fit: contain;
    }
  }

  .fullscreen-btn {
    position: absolute;
    top: 5px;
    right: 5px;
  }
}

.cameras-cont {
  width: 100%;
  display: flex;
  flex-direction: column;
  flex-wrap: nowrap;
  overflow-y: auto;
  overflow-x: hidden;
  height: 100%;
  gap: 10px;
  padding: 10px;
  align-items: flex-start;

  .cam {
    cursor: pointer;
    width: 100%;
    height: 80px;
    box-shadow: 0 0 2px 2px #f7f7f7;
    border-radius: 6px;
    flex-shrink: 0;
    display: flex;
    flex-direction: row-reverse;
    justify-content: space-between;
    align-items: center;
    padding: 5px;
    gap: 10px;

    &.selected {
      background: #edf2ef;
      box-shadow: 0 0 2px 2px #f7f7f7;
    }

    &.live {
      .icon {
        background: #c8ddd1;
        color: green;
      }
    }

    .icon {
      width: 60px;
      height: 60px;
      display: flex;
      background: #e4c8c4;
      color: red;
      justify-content: center;
      align-items: center;

      border-radius: 6px;

      svg {
        widows: 40px;
        height: 40px;
        fill: currentColor;
      }


    }

    .cam-info {
      flex-grow: 1;
      height: 100%;
      padding: 10px 0;
      display: flex;
      flex-direction: column;
      justify-content: end;
      align-items: end;
    }



  }


}

.no-selected-drone {
  position: absolute;
  inset: 0;
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 4;
  user-select: none;
}

.selectedCam_info {
  display: flex;
  align-items: center;
  justify-content: space-between;
  position: absolute;
  top: 8px;
  left: 8px;
  width: 200px;
  height: 35px;
  background: rgba(0, 0, 0, 0.6);
  border-radius: 6px;
  text-align: left;
  padding: 4px 10px;
  color: #fff;
}

.inner-status.live {
  color: green !important;
}
</style>
