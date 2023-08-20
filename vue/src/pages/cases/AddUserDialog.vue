<template>
  <v-row justify="center">
    <v-dialog
      v-model="dialog"
      max-width="500"
    >
      <v-card>
        <v-card-title class="text-h5 d-flex justify-space-between align-center border-bottom">
          {{ $t('cases.addUser')}}
          <v-btn icon @click="dialog = false">
            <v-icon>
              mdi-close
            </v-icon>
          </v-btn>
        </v-card-title>

        <v-card-text class="mt-4">
          <v-row>
            <v-row>
              <v-col cols="12">
                <v-text-field type="number" v-model="user.civil_number" :rules="[rules.required]" :label="$t('cases.civil')"
                              :error-messages="errors['civil_number']"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="user.name" :rules="[rules.required]" :label="$t('tables.name')"
                              :error-messages="errors['name']"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field type="number" v-model="user.phone" :rules="[rules.required]" :label="$t('tables.phone')"
                              :error-messages="errors['phone']"></v-text-field>
              </v-col>
              <v-col cols="12">
                <v-text-field v-model="user.email" type="email" :label="$t('tables.email')"></v-text-field>
              </v-col>
            </v-row>
          </v-row>
        </v-card-text>
        <v-divider></v-divider>
        <v-card-actions class="py-2">

          <v-spacer></v-spacer>

          <v-btn
            color="primary"
            class="mx-1"
            @click="save"
          >
            {{ $t('general.save') }}
          </v-btn>

          <v-btn
            color="grey lighten-3"
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
import axios from 'axios';

export default {
  name: "AddUserDialog",

  props: {
    value: Boolean,
    // eventItem: Object,
    // id: Number,
  },
  data() {
    return {
      user: {name:"", civil_number: "", phone:"", email:"" },
      rules: {
        required: (value) => (value && Boolean(value)) || this.$t("general.fieldRequired")
      },
      errors: {},
      loading: false,
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
    event: {
      get() {
        return this.$store.state.events.event;
      },
      set(val) {
        this.$store.commit("events/SET_EVENT", val);
      }
    },
  },
  watch:{
    // 'id'(){
    //   this.refresh()
    // }
  },
  methods:{
    ...mapActions('cases', ['createUser']),
    refresh() {
      this.loading = true;
      this.event = this.eventItem
      const {status, notes, id } = this.eventItem ?? {};
      this.form = { status,notes, id };
    },
    fetchData: function(){
      this.$root.$emit('userCreated')
    },
    save() {
      this.loading = true;
      this.errors = {};
      let data = {
        name:this.user.name,
        civil_number: Number(this.user.civil_number),
        phone: Number(this.user.phone),
        email:this.user.email,
      }
      this.createUser(data)
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
