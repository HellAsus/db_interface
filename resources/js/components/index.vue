<template>
  <v-container grid-list-xs>
    <v-flex>
      <v-layout column wrap>
        <v-flex d-flex>
          <v-text-field
            color="red"
            v-on:keyup.enter="search"
            v-model="query"
            label="Иванов Иван Иванович"
            :rules="queryRules"
            single-line
            required
          ></v-text-field>
          <v-flex lg2>
              <v-btn color="primary" @click="search">Найти</v-btn>
          </v-flex>

        </v-flex>
        <v-flex d-flex>
          <v-form>
            <v-layout row wrap>
              <v-flex lg2>
                <v-text-field
                  color="red"
                  v-model="ip"
                  label="192.168.0.1"
                  :rules="queryRules"
                  single-line
                  required
                ></v-text-field>
              </v-flex>
              <v-flex lg2>
                <v-menu
                  v-model="menu"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="startDate"
                      label="From date"
                      prepend-icon="event"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="startDate" @input="menu = false"></v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex lg2>
                <v-menu
                  ref="menu"
                  v-model="menu2"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="startTime"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="startTime"
                      label="From time"
                      format="24hr"
                      prepend-icon="access_time"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="menu2"
                    v-model="startTime"
                    format="24hr"
                    full-width
                    @click:minute="$refs.menu.save(startTime)"
                  ></v-time-picker>
                </v-menu>
              </v-flex>
              <v-flex lg2>
                <v-menu
                  v-model="menu3"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  transition="scale-transition"
                  offset-y
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="endDate"
                      label="To date"
                      prepend-icon="event"
                      readonly
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-date-picker v-model="endDate" @input="menu3 = false"></v-date-picker>
                </v-menu>
              </v-flex>
              <v-flex lg2>
                <v-menu
                  ref="end"
                  v-model="menu4"
                  :close-on-content-click="false"
                  :nudge-right="40"
                  :return-value.sync="endTime"
                  transition="scale-transition"
                  offset-y
                  max-width="290px"
                  min-width="290px"
                >
                  <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                      v-model="endTime"
                      label="To time"
                      prepend-icon="access_time"
                      format="24hr"
                      v-bind="attrs"
                      v-on="on"
                    ></v-text-field>
                  </template>
                  <v-time-picker
                    v-if="menu4"
                    v-model="endTime"
                    format="24hr"
                    full-width
                    @click:minute="$refs.end.save(endTime)"
                  ></v-time-picker>
                </v-menu>
                <v-spacer></v-spacer>
              </v-flex lg2>
              <v-btn color="primary" @click="ipSearch">Найти</v-btn>
            </v-layout>
          </v-form>
        </v-flex>
      </v-layout>
    </v-flex>

    <v-layout row wrap>
      <v-flex>
        <v-data-table
          :headers="headers"
          :items="user"
          class="elevation-1"
          hide-actions
          :no-data-text="text"
        >
          <template v-slot:items="props">
            <td>{{ props.item.fio }}</td>
            <td>{{ props.item.address }}</td>
            <td>{{ props.item.ip }}</td>
            <td>{{ props.item.ipTime }}</td>
            <td>{{ props.item.duration }}</td>
          </template>
        </v-data-table>
      </v-flex>
    </v-layout>
  </v-container>
</template>

<script>
const ipRegex = require("ip-regex");
export default {
  data: () => ({
    headers: [
      { text: "ФИО", value: "fio", sortable: false },
      { text: "Адрес подключения", value: "address", sortable: false },
      { text: "IP адрес", value: "ip", sortable: false },
      { text: "Время выдачи IP", value: "ipTime", sortable: false },
      { text: "Длительность аренды IP", value: "duration", sortable: false },
    ],
    text: "Введите запрос",
    user: [],
    query: "",

    ip: "178.158.0.1",
    startDate: new Date().toISOString().substr(0, 10),
    startTime: "00:00",
    endDate: new Date().toISOString().substr(0, 10),
    endTime: "23:59",

    menu: false,
    menu2: false,
    menu3: false,
    menu4: false,

    queryRules: [(v) => !!v || "Введите запрос"],
  }),

  methods: {
    search: function (event) {
      axios.get("/api/check?query=" + this.query).then((response) => {
        if (response.status == 401) {
          window.location = "/login";
        }
        if (response.data.error) {
          this.user = [];
          this.text = response.data.error;
        } else {
          this.user = response.data.data;
        }
      });
    },

    ipSearch() {
      axios
        .post("/api/check", {
          ip: this.ip,
          startDate: this.startDate,
          startTime: this.startTime,
          endDate: this.endDate,
          endTime: this.endTime,
        })
        .then((response) => {
          if (response.status == 401) {
            window.location = "/login";
          }
          if (response.data.error) {
            this.user = [];
            this.text = response.data.error;
          } else {
            console.log(response.data.data);
            this.user = response.data.data;
          }
        });
    },
  },
};
</script>
