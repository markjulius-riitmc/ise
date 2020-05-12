<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>

        <router-link v-for="item in items"
          :key="item.title"
          :to="item.to"
          v-if="item.show">
          <v-list-item
          link>
            <v-list-item-action>
              <v-icon>{{ item.icon }}</v-icon>
            </v-list-item-action>
            <v-list-item-content>
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item-content>
          </v-list-item>
        </router-link>
        

        <!-- <v-list-item to="/" link>
          <v-list-item-action>
            <v-icon>mdi-home</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Home</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/category" link>
          <v-list-item-action>
            <v-icon>mdi-shape</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Categories</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/equipment" link>
          <v-list-item-action>
            <v-icon>mdi-tools</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Equipment</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/user" link>
          <v-list-item-action>
            <v-icon>mdi-account</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Users</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/site" link>
          <v-list-item-action>
            <v-icon>mdi-map-marker</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Sites</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/division" link>
          <v-list-item-action>
            <v-icon>mdi-select-group</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Divisions</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/department" link>
          <v-list-item-action>
            <v-icon>mdi-domain</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Departments</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/employee" link>
          <v-list-item-action>
            <v-icon>mdi-account-group</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Employees</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/supplier" link>
          <v-list-item-action>
            <v-icon>mdi-semantic-web</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Suppliers</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/manage-equipment" link>
          <v-list-item-action>
            <v-icon>mdi-calendar-text</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Manage Equipment</v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        
        <v-list-item link>
          <v-list-item-action>
            <v-icon>mdi-logout</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Logout</v-list-item-title>
          </v-list-item-content>
        </v-list-item>

        <v-list-item to="/login" link>
          <v-list-item-action>
            <v-icon>mdi-login</v-icon>
          </v-list-item-action>
          <v-list-item-content>
            <v-list-item-title>Login</v-list-item-title>
          </v-list-item-content>
        </v-list-item> -->
      </v-list>
    </v-navigation-drawer>

    <v-app-bar
      app
      color="indigo"
      dark
    >
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title>Application</v-toolbar-title>
    </v-app-bar>

    <v-content>
      <v-container fluid>
        <v-row
          align="center"
          justify="center"
        >
          <v-col class="text-center">
              <router-view></router-view>
          </v-col>
        </v-row>
      </v-container>
    </v-content>
    <v-footer
      color="indigo"
      app
    >
      <span class="white--text">&copy; 2019</span>
    </v-footer>
  </v-app>
</template>

<script>
  export default {
    props: {
      source: String,
    },
    data: () => ({
      drawer: null,
      items: [
        { icon: 'mdi-home', title: 'Home', to:'/', show: true },
        { icon: 'mdi-shape', title: 'Category', to:'/category', show: User.loggedIn() },
        { icon: 'mdi-tools', title: 'Equipment', to:'/equipment', show: User.loggedIn() },
        { icon: 'mdi-account', title: 'User', to:'/user', show: User.loggedIn() },
        { icon: 'mdi-map-marker', title: 'Site', to:'/site', show: User.loggedIn() },
        { icon: 'mdi-select-group', title: 'Division', to:'/division', show: User.loggedIn() },
        { icon: 'mdi-domain', title: 'Department', to:'/department', show: User.loggedIn() },
        { icon: 'mdi-account-group', title: 'Employee', to:'/employee', show: User.loggedIn() },
        { icon: 'mdi-semantic-web', title: 'Supplier', to:'/supplier', show: User.loggedIn() },
        { icon: 'mdi-calendar-text', title: 'Manage Equipment', to:'/manage-equipment', show: User.loggedIn() },
        { icon: 'mdi-login', title: 'Login', to:'/login', show: !User.loggedIn() },
        { icon: 'mdi-logout', title: 'Logout', to:'/logout', show: User.loggedIn() },
      ],
    }),
    created() {
      EventBus.$on('logout', () => {
        User.logout()
      })
    },
  }
</script>

<style lang="stylus" scoped>

</style>