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
            </v-banner>
            <v-card-text class="gray lighten-4">
                <form>        
                    <v-text-field
                    v-model="name"
     
                    :counter="50"
                    label="Name"
                    required
                    @input="$v.name.$touch()"
                    @blur="$v.name.$touch()"
                    ref="name"
                    ></v-text-field>

                    <v-text-field
                    v-model="description"
               
                    label="Description"
                    @input="$v.description.$touch()"
                    @blur="$v.description.$touch()"
                    ></v-text-field>

                    <v-btn class="mr-4" @click="onSubmit">submit</v-btn>
                    <!-- <v-btn @click="clear">clear</v-btn> -->
                </form>
            </v-card-text>

            <!-- <v-pagination
                v-model="pagination.current_page"
                :length="pagination.total"
                @input="changePage"
            ></v-pagination> -->

            <v-card
                class="mx-auto"
                outlined
                v-for="category in allCategories"
                :key="category.id"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="overline mb-4">OVERLINE</div>
                        <v-list-item-title class="headline mb-1">{{ category.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ category.description }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                        tile
                        size="80"
                        color="grey"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn text @click="editCategory(category)">Edit</v-btn>
                    <v-btn text @click="deleteCategory(category.id)">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-card>
    </div>
</template>
  
<script>
    import { mapGetters, mapActions } from 'vuex';
    import { validationMixin } from 'vuelidate'
    import { required, maxLength } from 'vuelidate/lib/validators'

    export default {
        props : ['title'],
        mixins: [validationMixin],
        validations: {
            name: { required, maxLength: maxLength(50) },
            description: { maxLength: maxLength(255) },
        },
        data() {
            return {
                sticky: false,
                // id: '',
                name: '',
                description: '',
                // pagination: {},
                // meta: {},
                // edit:false,
                // submitStatus: null,
            }
        },
        methods: {
            ...mapActions(['fetchCategories', 'addCategory']),
            onSubmit() {
                // this.$v.$touch()

                // if(this.$v.$invalid) {
                //     this.submitStatus = 'ERROR'
                // } else {

                    let category = {
                        name: this.name,
                        description: this.description
                    }
                    // Add
                    // if (this.edit == false) {
                        this.addCategory(category);
                        // fetch('/api/category', {
                        //     method: 'post',
                        //     body: JSON.stringify(category),
                        //     headers: {
                        //     'content-type': 'application/json'
                        //     },
                        // })
                        // .then(res => res.json()
                        // .then(data => {
                        //     alert('Category added');
                        //     this.clear()
                        //     this.fetchCategories();
                        // }))
                    // } else { // Update
                        // fetch('/api/category', {
                        //     method: 'put',
                        //     body: JSON.stringify(category),
                        //     headers: {
                        //         'content-type': 'application/json'
                        //     },
                        // })
                        // .then(res => res.json())
                        // .then(data => {
                        //     alert('Category updated')
                        //     this.clear()
                        //     this.fetchCategories();
                        // })
                    // }
                    // this.submitStatus = 'PENDING'
                    // this.edit = false
                    // setTimeout(() => {
                    //     this.submitStatus = 'OK'
                    // }, 500)
                // }
            },
            // clear() {
            //     this.$v.$reset()
            //     this.id = ''
            //     this.name = ''
            //     this.description = ''
            //     this.category_id = ''
            // },
            // editCategory(category) {
            //     this.edit = true;
            //     this.id = category.id;
            //     this.name = category.name;
            //     this.description = category.description;
            //     this.category_id = category.id;
            // },
            // deleteCategory(id) {
            //     if (confirm('Are you sure?')) {
            //     fetch(`/api/category/${id}`, {
            //         method: 'delete'
            //     })
            //     .then(res => res.json())
            //     .then(data => {
            //         alert('Category removed');
            //         // this.fetchCategories();
            //     })
            //     .catch(err => console.log(err));
            //     }
            // },
            // changePage(page) {
            //     // this.$store.dispatch('fetchCategories', page)
            // },
        },
        // computed: {
        //     nameErrors () {
        //         const errors = []
        //         if (!this.$v.name.$dirty) return errors
        //         !this.$v.name.maxLength && errors.push('Name must be at most 50 characters long')
        //         !this.$v.name.required && errors.push('Name is required.')
        //         return errors
        //     },
        //     descriptionErrors () {
        //         const errors = []
        //         if (!this.$v.description.$dirty) return errors
        //         !this.$v.description.maxLength && errors.push('Must be at most 255 characters long')
        //         return errors
        //     },
        //     categories () {
        //         return this.$store.state.categories
        //     },
            
        // },
        computed: mapGetters(['allCategories']),
        created() {
            // this.$store.dispatch('fetchCategories')
            this.fetchCategories()
        },
        
    }
</script>

<!-- Styles -->
<style scoped>

</style>
