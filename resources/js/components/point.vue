<template>
  <div>
    <toolbar v-if="toolbar"></toolbar>
    <v-content v-if="content">
      <router-view></router-view>
    </v-content>
  </div>
</template>

<script>
export default {
  mounted() {
    this.$store.dispatch("CHECK").then(pesponce => {
      if (this.$store.getters.GET_KEY == null) {
        if(this.$router.currentRoute.name != "login"){this.$router.push("login");}
        this.content = true;
      } else {
        this.toolbar = true;
        this.content = true;
      }
    });
  },
  data: () => ({
    toolbar: false,
    content: false
  })
};
</script>
