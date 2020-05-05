<template>
  <v-app id="inspire">
    <v-navigation-drawer
      v-model="drawer"
      app
    >
      <v-list dense>
        <v-list-item to="/" link>
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
      admins: [
        ['Management', 'people_outline'],
        ['Settings', 'settings'],
      ],
      cruds: [
        ['Create', 'add'],
        ['Read', 'insert_drive_file'],
        ['Update', 'update'],
        ['Delete', 'delete'],
      ],
    }),
  }
</script>

<style lang="stylus" scoped>

</style>