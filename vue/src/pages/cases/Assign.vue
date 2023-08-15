<template>
  <v-row justify="center">
    <v-dialog
      v-model="dialog"
      max-width="500"
    >
      <v-card>
        <v-card-title class="text-h5 d-flex justify-space-between align-center border-bottom">
          {{ $t('cases.assignUser')}}
          <v-btn icon @click="dialog = false">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text class="mt-4">
          <v-row>

            <v-col cols="12">
              <v-select
                :items="users"
                :label="$t('cases.user')"
                :item-text="item => item.name"
                :item-value="item => item.id"
                hide-details
                v-model="form.user_id"
              >
              </v-select>
            </v-col>
            <v-col cols="12">
              <v-text-field
                v-model="form.date"
                type="date"
                :label="$t('general.selectDate')"
              ></v-text-field>

            </v-col>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="py-2">

          <v-spacer></v-spacer>

          <v-btn
            color="primary"
            class="mx-2"
            @click="assignUser"
          >
            {{ $t('general.save') }}
          </v-btn>

          <v-btn
            color="grey"

            @click="dialog = false"
          >
            {{ $t('general.cancel') }}
          </v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-row>
</template>


<script>
import {mapActions, mapState} from "vuex";
import {makeToast} from "@/helpers";

export default {
  name: "Assign",

  props: {
    value: Boolean,
    // eventItem: Object,
    id: Number,
    // requests: Array,
  },
  data() {
    return {
      form: {user_id:null, date: ""},
      users:[],
    }
  },
  computed: {
    dialog: {
      get() {
        return this.value
      },
      set(value) {
        this.$emit('input', value)
      }
    },
  },
  watch:{
    'requests'(){
      // this.refresh()
    }
  },
  mounted() {
    this.getEmployees()
  },
  methods:{
    ...mapActions('cases', ['assignRequest']),
    refresh() {
      this.loading = true;
      this.event = this.eventItem
      const {status, notes, id } = this.eventItem ?? {};
      this.form = { status,notes, id };
    },
    fetchData: function(){
      this.$root.$emit('table_component')
    },
    getEmployees(){
      this.isLoading = true;
      this.$axios.get('user-employee')
        .then(response => {
          this.users = response.data.data.users
          this.isLoading = false;
        })
        .catch(error => {
          this.isLoading = false;
        })
    },
    assignUser() {
      this.loading = true;
      this.errors = {};
      let requestIds = [];
      // for (let i = 0; i < this.requests.length; i++) {
        requestIds.push(this.id);
      // }
      let data = {
        form_request_id: requestIds,
        user_id:this.form.user_id,
        date:this.form.date
      }
      this.assignRequest(data)
        .then((response) => {
          this.loading = false;
          this.dialog = false
          this.fetchData()
          this.errors = {};
          makeToast("success", response.data.message);
        })
        .catch(error => {
          this.loading = false;
          // if (error.response.status == 422) {
          //   const { errors } = error?.response?.data;
          //   this.errors = errors ?? {};
          // }
        });
    }
  }
}
</script>

<style scoped>

</style>
