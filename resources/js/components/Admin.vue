<template>
  <div id="app">
    <v-app id="inspire" dark>
      <v-navigation-drawer clipped fixed v-model="drawer" app>
        <sidebar></sidebar>
      </v-navigation-drawer>

      <navbar :user="user"></navbar>

      <v-content>
        <v-container fluid fill-height>
          <v-layout justify-center>
            <v-flex shrink>
              <router-view></router-view>
            </v-flex>
          </v-layout>
        </v-container>
      </v-content>
      <v-footer app fixed>
        <span>&copy; 2019</span>
      </v-footer>
    </v-app>
  </div>
</template>

<script>
export default {
  components: {},
  data: () => ({
    drawer: null,
  }),
  props: ["user"],

  created() {
    Echo.join(`chat.${roomId}`)
      .here((users) => {
        console.log(users);
      })
      .joining((user) => {
        console.log(user.name);
      })
      .leaving((user) => {
        console.log(user.name);
      })
      .error((error) => {
        console.error(error);
      });
  },
};
</script>