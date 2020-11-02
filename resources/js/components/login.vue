<template>
  <v-app id="inspire">
    <v-content>
      <v-container fluid fill-height>
        <v-layout align-center justify-center>
          <v-flex xs12 sm8 md4>
            <v-card class="elevation-12">
              <v-toolbar dark color="primary">
                <v-toolbar-title>Login</v-toolbar-title>
              </v-toolbar>
              <v-card-text>
                <v-form>
                  <v-text-field
                    prepend-icon="person"
                    v-model="username"
                    label="Username"
                    type="text"
                    :rules="loginRules"
                    required
                    v-on:keyup.enter="send"
                  ></v-text-field>
                  <v-text-field
                    id="password"
                    prepend-icon="lock"
                    v-model="password"
                    label="Password"
                    type="password"
                    :rules="passRules"
                    required
                    v-on:keyup.enter="send"
                  ></v-text-field>
                </v-form>
              </v-card-text>
              <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn @click="send" color="primary">Login</v-btn>
              </v-card-actions>
            </v-card>
            <v-snackbar
              v-model="snackbar"
              :multi-line="true"
              :timeout="timeout"
              :top="true"
              color="error"
            >{{ text }}</v-snackbar>
          </v-flex>
        </v-layout>
      </v-container>
    </v-content>
  </v-app>
</template>

<script>
export default {
  mounted() {
    this.$store.dispatch('CHECK').then(pesponce =>{
      if(this.$store.getters.GET_KEY != null){this.$router.push('/');}
      });

  },
  data: () => ({
    token: "",
    username: "",
    password: "",
    loginRules: [v => !!v || "Login is required"],
    passRules: [v => !!v || "Password is required"],
    snackbar: false,
    timeout: 4000,
    text: "Login or password incorect.",
    pair: {
      login: "",
      password: ""
    }
  }),

  methods: {
    send() {
      if (this.username && this.password) {
        this.pair.login = this.username;
        this.pair.password = this.password;

        this.$store.dispatch("LOGIN", this.pair).then(
          response => {
            window.location = "/";
          },
          error => {
            this.snackbar = true;
          }
        );
      }
    }
  }
};
</script>
