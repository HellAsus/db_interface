<template>
  <v-container grid-list-xs>
    <v-card>
      <v-card-title>
        <v-spacer></v-spacer>
        <v-text-field v-model="search" append-icon="search" label="Search" single-line hide-details></v-text-field>
      </v-card-title>
      <v-data-table
        :headers="headers"
        :items="logs"
        :search="search"
        :rows-per-page-items="[20,50,100]"
      >
        <template v-slot:items="props">
          <td v-bind:style="[!props.item.type ? styleObject:'']">{{ props.item.user }}</td>
          <td v-bind:style="[!props.item.type ? styleObject:'']">{{ props.item.action }}</td>
          <td v-bind:style="[!props.item.type ? styleObject:'']">{{ props.item.login }}</td>
          <td v-bind:style="[!props.item.type ? styleObject:'']">{{ props.item.data }}</td>
          <td v-bind:style="[!props.item.type ? styleObject:'']">{{ props.item.time }}</td>
        </template>
        <template v-slot:no-results>
          <v-alert
            :value="true"
            color="error"
            icon="warning"
          >Your search for "{{ search }}" found no results.</v-alert>
        </template>
      </v-data-table>
    </v-card>
  </v-container>
</template>

<script>
export default {
  created() {
    this.get();
  },

  data: () => ({
    styleObject: {
      background: "#febab8"
    },
    search: "",
    headers: [
      {
        text: "User",
        sortable: false,
        value: "login"
      },
      { text: "Action", sortable: false },
      { text: "Login", sortable: false },
      { text: "Info", sortable: false },
      { text: "Time", sortable: false }
    ],
    logs: []
  }),

  methods: {
    get() {
      axios.get("/api/log").then(response => {
        response.status == 200 ? this.logs = response.data.data : window.location = "/";
      });
    }
  }
};
</script>
