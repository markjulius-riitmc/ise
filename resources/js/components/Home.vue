<template>
  <div>
    <v-card>
        <v-system-bar></v-system-bar>
        <v-toolbar flat>
            <v-toolbar-title>{{ title }}</v-toolbar-title>
            <v-spacer></v-spacer>
            <div>
                <v-switch
                v-model="sticky"
                label="Sticky Banner"
                hide-details
                ></v-switch>
            </div>
        </v-toolbar>
        <v-banner
        single-line
        :sticky="sticky"
        >
            <!-- Welcome to {{ title }} -->
            <p class="font-weight-bold">{{ inspire }}</p>
          
            <template v-slot:actions>
                <v-btn
                text
                color="deep-purple accent-4"
                >Get Online</v-btn>
            </template>
        </v-banner>
        <v-card-text class="grey lighten-4">
            <v-sheet
                max-width="800"
                height="300"
                class="mx-auto"
            ></v-sheet>
        </v-card-text>
    </v-card>
  </div>
</template>

<script>
    export default {
        props : ['title'],
        data: () =>({
            sticky: false,
            inspire: null,
        }),
        created() {
            axios.get('/api/get-inspiration')
                .then(res => this.inspire = res.data.inspire)
                .catch(err => console.log(err))
        },
    }
</script>

  <!-- Styles -->
  <style scoped>

  </style>
