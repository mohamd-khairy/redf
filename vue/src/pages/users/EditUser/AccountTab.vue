<template>
  <div class="my-2">
    <div>
      <v-card v-if="user.disabled" class="warning mb-4" light>
        <v-card-title>{{ $t("users.DeleteUser") }}</v-card-title>
        <v-card-subtitle>{{
          $t("users.ThisuserhasbeendisabledLoginaccessshasbeenrevoked")
        }}</v-card-subtitle>
        <v-card-text>
          <v-btn dark @click="user.disabled = false">
            <v-icon left small>mdi-account-check</v-icon>Enable User
          </v-btn>
        </v-card-text>
      </v-card>

      <v-card :loading="loading">
        <v-card-title>{{ $t("users.basicInformation") }}</v-card-title>
        <v-card-text>
          <div class="d-flex flex-column flex-sm-row">
            <!--            <div>-->
            <!--              <v-img-->
            <!--                :src="user.avatar"-->
            <!--                aspect-ratio="1"-->
            <!--                class="blue-grey lighten-4 rounded elevation-3"-->
            <!--                max-width="90"-->
            <!--                max-height="90"-->
            <!--              ></v-img>-->
            <!--              <input-->
            <!--                type="file"-->
            <!--                accept="image/*"-->
            <!--                id="update-avatar"-->
            <!--                class="d-none"-->
            <!--              />-->
            <!--              <v-btn class="mt-1" @click.prevent="changeImage()" small>-->
            <!--                {{ $t("users.EditAvatar") }}-->
            <!--              </v-btn>-->
            <!--            </div>-->
            <div class="flex-grow-1 pt-2 pa-sm-2">
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.name"
                    :label="$t('tables.username')"
                    outlined
                    dense
                    placeholder="username"
                    :error-messages="errors['username']"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.name"
                    outlined
                    dense
                    :label="$t('tables.name')"
                    placeholder="name"
                    :error-messages="errors['name']"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    outlined
                    dense
                    v-model="user.email"
                    :label="$t('tables.email')"
                    type="email"
                    :error-messages="errors['email']"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-select
                    label="Select"
                    :items="roles"
                    variant="underlined"
                    class="mx-1"
                    :label="$t('tables.role')"
                    item-text="name"
                    item-value="id"
                    hide-details
                    v-model="roleId"
                    dense
                    outlined
                  ></v-select>
                </v-col>
                <!-- <v-col cols="6">
                  <v-select
                    label="Select"
                    dense
                    :items="translatedLocations"
                    variant="underlined"
                    class=" mx-1"
                    :label="$t('general.location')"
                    item-text="name"
                    item-value="id"
                    hide-details
                    v-model="user.locations"
                    return-object
                    multiple
                    outlined
                  ></v-select>
                </v-col> -->
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.password"
                    :label="$t('check.newpassword')"
                    dense
                    :type="showPassword ? 'text' : 'password'"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="showPassword = !showPassword"
                    :error-messages="errors['password']"
                    outlined
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.confirm_password"
                    :label="$t('check.confirmation_password')"
                    type="password"
                    :error-messages="errors['confirm_password']"
                    outlined
                    dense
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="12" md="6">
                  <v-select
                    label="Select"
                    :items="types"
                    outlined
                    dense
                    class="mx-1"
                    :label="$t('tables.type')"
                    item-text="name"
                    item-value="value"
                    hide-details
                    v-model="user.type"
                  ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                  <v-select
                    label="Select"
                    :items="departments"
                    item-text="name"
                    item-value="id"
                    outlined
                    dense
                    :label="$t('tables.department')"
                    hide-details
                    v-model="user.department"
                  ></v-select>
                </v-col>
              </v-row>
              <!-- <hr /> -->
              <div class="mt-2">
                <v-btn
                  @click.prevent="updateProfile()"
                  color="primary"
                  :loading="loading"
                  :disabled="loading"
                >
                  {{ $t("general.save") }}
                </v-btn>
              </div>
            </div>
          </div>
        </v-card-text>
      </v-card>
    </div>

    <!-- disable modal -->
    <v-dialog v-model="disableDialog" max-width="290">
      <v-card>
        <v-card-title class="headline">{{
          $t("users.DisableUser")
        }}</v-card-title>
        <v-card-text>{{
          $t("users.Areyousureyouwanttodisablethisuser")
        }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="disableDialog = false">Cancel</v-btn>
          <v-btn
            color="warning"
            @click="
              user.disabled = true;
              disableDialog = false;
            "
            >Disable{{ $t("users.DangerZone") }}</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>

    <!-- delete modal -->
    <v-dialog v-model="deleteDialog" max-width="290">
      <v-card>
        <v-card-title class="headline">{{
          $t("users.DeleteUser")
        }}</v-card-title>
        <v-card-text>{{
          $t("users.Areyousureyouwanttodeletethisuser")
        }}</v-card-text>
        <v-card-actions>
          <v-spacer></v-spacer>
          <v-btn @click="deleteDialog = false">Cancel</v-btn>
          <v-btn color="error" @click="deleteDialog = false">{{
            $t("users.Delete")
          }}</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import { mapActions, mapState } from "vuex";

export default {
  props: {
    // user: {
    //   type: Object,
    //   default: () => ({})
    // },
    errors: {
      type: Object,
      default: {},
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      panel: [1],
      deleteDialog: false,
      disableDialog: false,
      avatar: {},
      showPassword: false,
      selectedLocations: [],
      roleId: "",
      types: [
        { name: this.$t("users.types.company"), value: "company" },
        { name: this.$t("users.types.employee"), value: "employee" },
        { name: this.$t("users.types.user"), value: "user" },
        { name: this.$t("users.types.governorate"), value: "governorate" },
      ],
    };
  },
  computed: {
    ...mapState("roles", ["roles"]),
    ...mapState("departments", ["departments"]),
    user: {
      get() {
        return this.$store.state.users.user;
      },
      set(val) {
        this.$store.commit("users/SET_USER", val);
      },
    },
  },

  methods: {
    ...mapActions("roles", ["getRoles"]),
    ...mapActions("users", ["getUser"]),
    ...mapActions("departments", ["getDepartments"]),

    fetchRoles() {
      this.isLoading = true;
      this.getRoles()
        .then(() => {
          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    fetchUser() {
      this.isLoading = true;
      const { id } = this.$route.params;
      this.getUser(id)
        .then(() => {
          this.roleId =
            this.user.roles && this.user.roles.length > 0
              ? this.user.roles[0].id
              : "";

          this.isLoading = false;
        })
        .catch(() => {
          this.isLoading = false;
        });
    },
    changeImage() {
      document.getElementById("update-avatar").click();
    },
    updateProfile() {
      const { email, name, password, confirm_password, username, roles } =
        this.user;
      let data = {
        email,
        name,
        password,
        confirm_password,
        username,
        roles,
      };
      if (this.avatar.length) {
        data["avatar"] = this.avatar[0];
      }
      if (this.roleId) {
        data["roles"] = this.roleId;
      }
      let form = this.buildForm(data);
      this.$emit("updateUser", form);
      // document.getElementById("update-avatar").files = null;
      // this.avatar = {};
    },
    buildForm(data) {
      let keys = Object.keys(data);
      let form = new FormData();
      for (let index = 0; index < keys.length; index++) {
        const key = keys[index];
        if (data[key]) {
          form.set(key, data[key]);
        }

        if (key == "password" && data["password"]) {
          form.set("confirm_password", data["confirm_password"]);
        }
      }
      return form;
    },
  },
  created() {
    this.roleId =
      this.user.roles && this.user.roles.length > 0
        ? this.user.roles[0].id
        : "";

    this.fetchRoles();
    this.fetchUser();
    this.getDepartments();
  },
  mounted() {
    // document.getElementById("update-avatar").addEventListener("change", e => {
    //   this.avatar = e.target.files;
    // });
  },
};
</script>
