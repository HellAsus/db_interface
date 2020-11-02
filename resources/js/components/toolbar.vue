<template>
  <div>
    <v-navigation-drawer v-model="drawer" fixed clipped app>
      <v-list>
        <v-list-tile
          v-for="(menu,i) in menuItems"
          :key="`drmenu${i}`"
          :to="menu.route"
          @click="actions(menu.metod)"
        >
          <v-list-tile-content>
            <v-list-tile-title v-bind:style="{ color: menu.color}" v-text="menu.title"></v-list-tile-title>
          </v-list-tile-content>
          <v-list-tile-action>
            <v-icon color="primary" v-html="menu.icon"></v-icon>
          </v-list-tile-action>
        </v-list-tile>
      </v-list>
      <v-list v-if="userRole == 'admin'">
        <v-list-group no-action>
          <template v-slot:activator>
            <v-list-tile>
              <v-list-tile-title>Admin</v-list-tile-title>
            </v-list-tile>
          </template>

          <v-list-tile v-for="(admin, i) in adminItems" :key="i" :to="admin.route">
            <v-list-tile-content>
              <v-list-tile-title v-bind:style="{ color: admin.color}" v-text="admin.title"></v-list-tile-title>
            </v-list-tile-content>
            <v-list-tile-action>
              <v-icon color="primary" v-html="admin.icon"></v-icon>
            </v-list-tile-action>
          </v-list-tile>
        </v-list-group>
      </v-list>
    </v-navigation-drawer>

    <v-toolbar app fixed clipped-left class="grey darken-1" dark>
      <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
    </v-toolbar>
  </div>
</template>

<script>
export default {
  mounted() {
    this.userRole = this.$store.getters.GET_ROLES;
    this.menuItems = this.menuItems.filter(item => item.roles.includes(this.userRole));
  },
  data: () => ({
    drawer: null,
    roles: [],
    userRole: null,
    menuItems: [
      {
        title: "Search",
        route: "/",
        icon: "search",
        metod: "",
        color: "black",
        roles: ['admin', 'user']
      },
      {
        title: "Logs",
        route: "/logs",
        icon: "view_list",
        metod: "",
        color: "black",
        roles: ['admin']
      },
      {
        title: "Logout",
        route: "",
        icon: "rounded_corner",
        metod: "logout",
        color: "red",
        roles: ['admin', 'user']
      }
    ],
    adminItems: [
      {
        title: "Users",
        route: "/admUsers",
        icon: "search",
        metod: "",
        color: "black",
        roles: ['admin']
      },
      {
        title: "Permissions",
        route: "/admPerms",
        icon: "search",
        metod: "",
        color: "black",
        roles: ['admin']
      }
    ]
  }),
  computed: {},
  methods: {
    actions(metod) {
      if (metod == "logout") {
        this.logout();
      }
    },

    logout() {
      this.$store.dispatch("LOGOUT").then(pesponce => {
        window.location = "/login";
      });
    }
  }
};
</script>