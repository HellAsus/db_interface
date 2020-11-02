<template>
  <v-container grid-list-xs>
    <v-card>
        <v-toolbar color="grey darken-1" dark>
          <v-toolbar-title>Roles</v-toolbar-title>
          <v-spacer></v-spacer>
          <v-btn icon>
            <v-icon color="white">add_circle</v-icon>
          </v-btn>
        </v-toolbar>

        <v-list two-line subheader>
          <v-list-tile
            v-for="item in roles"
            :key="item.title"
            avatar
            @click=""
          >
            <v-list-tile-avatar>
              <v-icon >data_usage</v-icon>
            </v-list-tile-avatar>

            <v-list-tile-content>
              <v-list-tile-title>{{ item }}</v-list-tile-title>
              <v-list-tile-sub-title>{{ perms }}</v-list-tile-sub-title>
            </v-list-tile-content>

            <v-list-tile-action>
              <v-btn icon ripple>
                <v-icon color="grey lighten-1">info</v-icon>
              </v-btn>
            </v-list-tile-action>
          </v-list-tile>

          <v-divider inset></v-divider> 
        </v-list>
      </v-card>
  </v-container>
</template>


<script>
export default {
  created() {
    this.getRoles();
  },
  data: () => ({
    dialogNew: false,
    dialogEdit: false,
    roles: [],
    perms: ['view_logs','search users','view_users'],
    styleObject: {
      background: "#febab8"
    },

  }),


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
    editUser(item) {},
    deleteUser(user) {
      const index = this.users.indexOf(user);
      if (confirm("Delete user?")) {
        
        axios({
          method: "delete",
          url: "/api/duser/" + user.id
        }).then(response =>{
          if(response.status == 200)
          {
            this.users.splice(index, 1);
          }
        });
      }
    },

    close() {},

    addUser() {
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
            if (response.data.includes("error")) {
            } else {
              this.users.push(response.data.data);
            }
          }
          if (response.status == 401) {
            window.location = "/login";
          }
        });
      }
    }
  }
};
</script>
