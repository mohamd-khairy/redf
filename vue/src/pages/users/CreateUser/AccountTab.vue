<template>
  <div class="my-2">
    <div>
      <v-card>
        <v-card-title>{{ $t("users.basicInformation") }}</v-card-title>
        <v-card-text>
          <div class="d-flex flex-column flex-sm-row">
            <!--            <div>-->
            <!--              <v-img :src="user.avatar" aspect-ratio="1" class="blue-grey lighten-4 rounded elevation-3" max-width="90"-->
            <!--                max-height="90" :loading="loading" />-->
            <!--              <input type="file" accept="image/*" id="update-avatar" class="d-none" />-->
            <!--              <v-btn @click.prevent="changeImage()" :loading="loading" :disabled="loading" class="mt-1" small>-->
            <!--                {{ $t("users.EditAvatar") }}-->
            <!--              </v-btn>-->
            <!--            </div>-->
            <div class="flex-grow-1 pt-2 pa-sm-2">
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.username"
                    :rules="[rules.required]"
                    :label="$t('tables.username')"
                    outlined
                    dense
                    :error-messages="errors['username']"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.name"
                    :rules="[rules.required]"
                    :label="$t('tables.name')"
                    outlined
                    dense
                    :error-messages="errors['name']"
                  ></v-text-field>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.email"
                    type="email"
                    :rules="[rules.required]"
                    :label="$t('tables.email')"
                    outlined
                    dense
                    :error-messages="errors['email']"
                  ></v-text-field>
                </v-col>
                <v-col cols="6">
                  <v-select
                    label="Select"
                    :items="roles"
                    outlined
                    dense
                    class="mx-1"
                    :label="$t('tables.role')"
                    item-text="name"
                    item-value="id"
                    hide-details
                    v-model="user.roles"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-text-field
                    v-model="user.password"
                    :label="$t('general.password')"
                    :type="showPassword ? 'text' : 'password'"
                    :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="showPassword = !showPassword"
                    :rules="[rules.required]"
                    :error-messages="errors['password']"
                    outlined
                    dense
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
                    :items="[
                      {
                        name: `${$t('users.types.company')}`,
                        value: 'company',
                      },
                      {
                        name: `${$t('users.types.employee')}`,
                        value: 'employee',
                      },
                      { name: `${$t('users.types.user')}`, value: 'user' },
                      {
                        name: `${$t('users.types.governorate')}`,
                        value: 'governorate',
                      },
                    ]"
                    outlined
                    dense
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
                    v-model="user.department_id"
                  ></v-select>
                </v-col>
              </v-row>
              <v-row>
                <v-col cols="6">
                  <v-file-input
                    outlined
                    dense
                    counter
                    show-size
                    :v-model="user.image"
                    :label="$t('users.user_photo')"
                    @change="(file) => handleFileUpload(file)"
                    click:clear="handleRemoveFile"
                    :error-messages="errors['avatar']"
                  >
                  </v-file-input>
                </v-col>
              </v-row>

              <!--              <div class="d-flex flex-column">-->
              <!--                <v-checkbox v-model="user.verified" dense :label="$t('users.emailVerified')"></v-checkbox>-->
              <!--                <div>-->
              <!--                  <v-btn-->
              <!--                    v-if="!user.verified"-->
              <!--                  >-->
              <!--                    <v-icon left small>mdi-email</v-icon>-->
              <!--                    {{ $t('users.sendVerificationEmail') }}-->
              <!--                  </v-btn>-->
              <!--                </div>-->
              <!--              </div>-->

              <div class="mt-2">
                <v-btn
                  :loading="loading"
                  :disabled="loading"
                  @click="updateProfile"
                  color="primary"
                >
                  {{ $t("general.save") }}
                </v-btn>
              </div>
            </div>
          </div>
        </v-card-text>
      </v-card>

      <!--      <v-expansion-panels v-model="panel" multiple class="mt-3">-->
      <!--        <v-expansion-panel>-->
      <!--          <v-expansion-panel-header class="title">{{ $t('tables.actions') }}</v-expansion-panel-header>-->
      <!--          <v-expansion-panel-content>-->
      <!--            <div class="mb-2">-->
      <!--              <div class="title">{{ $t('users.ResetUserPassword') }}</div>-->
      <!--              <div class="subtitle mb-2">{{ $t('users.Sendsaresetpasswordemailtotheuser') }}</div>-->
      <!--              <v-btn-->
      <!--                class="mb-2"-->
      <!--                @click-->
      <!--              >-->
      <!--                <v-icon left small>mdi-email</v-icon>{{ $t('users.SendResetPasswordEmail') }}-->
      <!--              </v-btn>-->
      <!--            </div>-->

      <!--            <v-divider></v-divider>-->

      <!--            <div class="my-2">-->
      <!--              <div class="title">{{ $t('users.ExportAccountData') }}</div>-->
      <!--              <div class="subtitle mb-2">{{ $t('users.Exportalltheplatformmetadataforthisuser') }}</div>-->
      <!--              <v-btn class="mb-2">-->
      <!--                <v-icon left small>mdi-clipboard-account</v-icon>{{ $t('users.ExportUserData') }}-->
      <!--              </v-btn>-->
      <!--            </div>-->

      <!--            <v-divider></v-divider>-->

      <!--            <div class="my-2">-->
      <!--              <div class="error&#45;&#45;text title">{{ $t('users.DangerZone') }}</div>-->
      <!--              <div class="subtitle mb-2">{{ $t('users.Fulladministratorwithaccesstothisdashboard') }}</div>-->

      <!--              <div class="my-2">-->
      <!--                <v-btn-->
      <!--                  v-if="user.role === 'ADMIN'"-->
      <!--                  color="primary"-->
      <!--                  @click="user.role = 'USER'"-->
      <!--                >-->
      <!--                  <v-icon left small>mdi-security</v-icon>{{ $t('users.Removeadminaccess') }}-->
      <!--                </v-btn>-->
      <!--                <v-btn v-else color="primary" @click="user.role = 'ADMIN'">-->
      <!--                  <v-icon left small>mdi-security</v-icon>{{ $t('users.SetUserasAdmin') }}-->
      <!--                </v-btn>-->
      <!--              </div>-->

      <!--              <v-divider></v-divider>-->

      <!--              <div class="subtitle mt-3 mb-2">{{ $t('users.Preventtheuserfromsigninginontheplatform') }}</div>-->
      <!--              <div class="my-2">-->
      <!--                <v-btn-->
      <!--                  v-if="user.disabled"-->
      <!--                  color="warning"-->
      <!--                  @click="user.disabled = false"-->
      <!--                >-->
      <!--                  <v-icon left small>mdi-account-check</v-icon>{{ $t('users.EnableUser') }}-->
      <!--                </v-btn>-->
      <!--                <v-btn-->
      <!--                  v-else-->
      <!--                  color="warning"-->
      <!--                  @click="disableDialog = true"-->
      <!--                >-->
      <!--                  <v-icon left small>mdi-cancel</v-icon>{{ $t('users.DisableUser') }}-->
      <!--                </v-btn>-->
      <!--              </div>-->
      <!--            </div>-->
      <!--          </v-expansion-panel-content>-->
      <!--        </v-expansion-panel>-->
      <!--      </v-expansion-panels>-->
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
import avatar from "@/assets//images/avatars/avatar1.svg";
export default {
  props: {
    user: {
      type: Object,
      default: () => ({}),
    },
    errors: {
      type: Object,
      default: () => {},
    },
    loading: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      panel: [1],
      showPassword: false,
      avatar: {},
      deleteDialog: false,
      disableDialog: false,
      rules: {
        required: (value) =>
          (value && Boolean(value)) || this.$t("general.fieldRequired"),
      },
    };
  },
  computed: {
    ...mapState("roles", ["roles"]),
    ...mapState("departments", ["departments"]),
  },
  methods: {
    ...mapActions("roles", ["getRoles"]),
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
    init() {
      this.fetchRoles();
      this.getDepartments({
        search: "",
        pageSize: -1,
        page: "",
        sortDirection: "",
        sortCoulmn: "",
      }).then((_) => console.log(this.departments));
    },
    changeImage() {
      document.getElementById("update-avatar").click();
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
    updateProfile() {
      const {
        avatar,
        email,
        name,
        password,
        confirm_password,
        username,
        roles,
        type,
        department_id,
      } = this.user;
      let data = {
        avatar,
        email,
        name,
        password,
        confirm_password,
        username,
        roles,
        type,
        department_id,
      };
      if (this.avatar.length) {
        data["avatar"] = this.avatar[0];
      }

      let form = this.buildForm(data);
      this.$emit("createUser", form);
      // document.getElementById("update-avatar").files = null;
      this.avatar = {};
    },
    handleFileUpload(file, input) {
      if (file) {
        const fileName = file.name.split(".")[0];
        const fileExtension = file.name.split(".")[1];
        this.user.avatar = file;
      }
    },
    handleRemoveFile() {
      this.user.avatar = null;
    },
  },
  created() {
    // document.getElementById("update-avatar").addEventListener("change", (e) => {
    //   this.avatar = e.target.files;
    // });
    this.init();
  },
};
</script>
