<template>
  <v-container grid-list-xs>
    <v-toolbar flat color="white">
      <v-spacer></v-spacer>
      <v-dialog persistent v-model="dialogNew" max-width="500px">
        <template v-slot:activator="{ on }">
          <v-btn @click="getRoles" color="primary" dark class="mb-2" v-on="on">Add User</v-btn>
        </template>
        <v-card>
          <v-card-title>
            <span class="headline">Add user</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-form ref="form" lazy-validation>
                <v-text-field
                  v-model="newUser.login"
                  :counter="15"
                  :rules="loginRules"
                  label="Login"
                  required
                ></v-text-field>

                <v-text-field v-model="newUser.fio" label="FIO" required></v-text-field>

                <v-text-field
                  v-model="newUser.pass"
                  :counter="15"
                  :rules="passRules"
                  label="Password"
                  required
                  ref="password"
                  type="password"
                  data-vv-name="pass"
                  :error-messages="errors.collect('pass')"
                ></v-text-field>

                <v-text-field
                  v-model="newUser.passConf"
                  :counter="15"
                  :rules="passRules"
                  label="Confirm password"
                  required
                  type="password"
                  v-validate="'confirmed:password'"
                  data-vv-name="Password"
                  :error-messages="errors.collect('Password')"
                ></v-text-field>

                <v-select
                  v-model="newUser.role"
                  :items="roles"
                  :rules="[v => !!v || 'Item is required']"
                  label="Role"
                  required
                ></v-select>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="success" @click="add">Add</v-btn>
                  <v-btn color="error" @click="dialogNew = false">Cancel</v-btn>
                </v-card-actions>
              </v-form>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog persistent v-model="dialogEdit" max-width="500px">
        <v-card>
          <v-card-title>
            <span class="headline">Edit user</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-form ref="form">
                <v-text-field v-model="editUser.login" label="Login" readonly></v-text-field>
                <v-text-field v-model="editUser.fio" label="FIO"></v-text-field>
                <v-select v-model="editUser.role" :items="roles" label="Role"></v-select>
                <v-card-actions>
                  <v-btn color="primary" @click="dialogEditPass = true">Change password</v-btn>
                  <v-spacer></v-spacer>
                  <v-btn color="success" @click="save">Save</v-btn>
                  <v-btn color="error" @click="dialogEdit=false">Cancel</v-btn>
                </v-card-actions>
              </v-form>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
      <v-dialog persistent v-model="dialogEditPass" max-width="500px">
        <v-card>
          <v-card-title>
            <span class="headline">Change password</span>
          </v-card-title>

          <v-card-text>
            <v-container grid-list-md>
              <v-form ref="form_pass" lazy-validation>
                <v-text-field
                  v-model="editUser.pass"
                  :counter="15"
                  :rules="passRules"
                  label="Password"
                  required
                  ref="password"
                  type="password"
                  data-vv-name="pass"
                  :error-messages="errors.collect('pass')"
                ></v-text-field>
                <v-text-field
                  v-model="editUser.passConf"
                  :counter="15"
                  :rules="passRules"
                  label="Confirm password"
                  required
                  type="password"
                  v-validate="'confirmed:password'"
                  data-vv-name="Password"
                  :error-messages="errors.collect('Password')"
                ></v-text-field>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="success" @click="savePass()">Save</v-btn>
                  <v-btn color="error" @click="dialogEditPass=false">Cancel</v-btn>
                </v-card-actions>
              </v-form>
            </v-container>
          </v-card-text>
        </v-card>
      </v-dialog>
    </v-toolbar>
    <v-data-table :headers="headers" :items="users" class="elevation-1" hide-actions>
      <template v-slot:items="props">
        <td>{{ props.item.login }}</td>
        <td>{{ props.item.fio }}</td>
        <td>{{ props.item.last }}</td>
        <td>{{ props.item.role }}</td>
        <td class="justify-center">
          <v-icon small class="mr-2" @click="edit(props.item)">edit</v-icon>
          <v-icon small class="mr-2" @click="delete(props.item)">delete</v-icon>
        </td>
      </template>
    </v-data-table>
  </v-container>
</template>


<script>
export default {
  created() {
    this.getUsers();
  },
  data: () => ({
    dialogNew: false,
    dialogEdit: false,
    dialogEditPass: false,
    roles: [],
    users: [],
    styleObject: {
      background: "#febab8"
    },
    newUser: {
      login: "",
      fio: "",
      pass: "",
      passConf: "",
      role: ""
    },

    editUser: {
      login: "",
      fio: "",
      pass: "",
      passConf: "",
      role: ""
    },

    loginRules: [
      v => !!v || "Login is required",
      v => (v && v.length <= 15) || "Login must be less than 15 characters",
      v => (v && v.length > 5) || "Login must be more than 6 characters"
    ],
    passRules: [
      v => !!v || "Password is required",
      v => (v && v.length <= 15) || "Password must be less than 15 characters",
      v => (v && v.length > 5) || "Password must be more than 6 characters"
    ],
    search: "",
    headers: [
      { text: "Login", sortable: false },
      { text: "Fio", sortable: false },
      { text: "Last Login", sortable: false },
      { text: "Role", sortable: false },
      { text: "Action", sortable: false }
    ]
  }),

  computed: {},

  methods: {
    getUsers() {
      axios.get("/api/user").then(response => {
        if (response.status == 200) {
          this.users = response.data.data;
        }
        if (response.status == 401) {
          window.location = "/login";
        }
      });
    },

    getRoles() {
      axios.get("/api/pgr").then(response => {
        if (response.status == 200) {
          this.roles = response.data.roles;
        }
        if (response.status == 401) {
          window.location = "/login";
        }
      });
    },
    
    add() {
      if (this.$refs.form.validate() && this.$validator.validateAll()) {
        axios({
          method: "post",
          url: "/api/cuser",
          data: {
            login: this.newUser.login,
            password: this.newUser.pass,
            password_confirmation: this.newUser.passConf,
            fio: this.newUser.fio,
            role: this.newUser.role
          }
        }).then(response => {
          if (response.status == 200) {
            console.log(response.data);
            if (response.data.error) {
            } else {
              this.users.push(response.data.data);
            }
          }
          if (response.status == 401) {
            window.location = "/login";
          }
        });
      }
    },

    edit(user) {
      this.getRoles();
      this.editUser = Object.assign({}, user);
      this.dialogEdit = true;
    },

    save() {
      if (this.$refs.form.validate()) {
        console.log(this.editUser.id);
      }
    },

    savePass()
    {
      if(this.$refs.form_pass.validate() && this.$validator.validateAll()){this.dialogEditPass=false};
    },

    delete(user) {
      const index = this.users.indexOf(user);
      if (confirm("Delete user?")) {
        axios({
          method: "delete",
          url: "/api/duser/" + user.id
        }).then(response => {
          if (response.status == 200) {
            this.users.splice(index, 1);
          }
        });
      }
    }
  }
};
</script>
