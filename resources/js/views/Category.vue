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
                    :error-messages="nameErrors"
                    :counter="50"
                    label="Name"
                    required
                    @input="$v.name.$touch()"
                    @blur="$v.name.$touch()"
                    ref="name"
                    ></v-text-field>

                    <v-text-field
                    v-model="description"
                    :error-messages="descriptionErrors"
                    label="Description"
                    @input="$v.description.$touch()"
                    @blur="$v.description.$touch()"
                    ></v-text-field>

                    <v-btn class="mr-4" @click="submit">submit</v-btn>
                    <v-btn @click="clear">clear</v-btn>
                </form>
            </v-card-text>

            <v-pagination
                v-model="pagination.current_page"
                :length="pagination.total"
                @input="changePage"
            ></v-pagination>

            <v-card
                class="mx-auto"
                outlined
                v-for="category in categories"
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
    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email } from 'vuelidate/lib/validators'

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
                id: '',
                name: '',
                description: '',
                categories: [],
                category: {
                    id: '',
                    name: '',
                    description: '',
                    category_id: '',
                },
                pagination: {},
                meta: {},
                edit:false,
                submitStatus: null,
            }
        },
        computed: {
            nameErrors () {
                const errors = []
                if (!this.$v.name.$dirty) return errors
                !this.$v.name.maxLength && errors.push('Name must be at most 50 characters long')
                !this.$v.name.required && errors.push('Name is required.')
                return errors
            },
            descriptionErrors () {
                const errors = []
                if (!this.$v.description.$dirty) return errors
                !this.$v.description.maxLength && errors.push('Must be at most 255 characters long')
                return errors
            },
        },
        created() {
            this.fetchCategories();
        },
        methods: {
            fetchCategories(page) {
                let url = `/api/categories?page=${page}`
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.categories = res.data;
                    this.meta = res.meta
                    this.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    total: this.makeAbsNumber(meta.total/meta.per_page),
                }
                this.pagination = pagination;
            },
            makeAbsNumber(num) {
                let mod = (num % 1)
                if (mod < 0.5 && num > 0) {
                    num -= mod
                    num += 1
                } else {
                    num = Math.round(num)
                }
                return num
            },
            submit() {
                this.$v.$touch()

                if(this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    let category = {
                        id: this.id,
                        name: this.name,
                        description: this.description,
                        category_id: this.id
                    }

                    // Add
                    if (this.edit == false) {
                        fetch('/api/category', {
                            method: 'post',
                            body: JSON.stringify(category),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json()
                        .then(data => {
                            alert('Category added');
                            this.clear()
                            this.fetchCategories();
                        }))
                    } else { // Update
                        fetch('/api/category', {
                            method: 'put',
                            body: JSON.stringify(category),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert('Category updated')
                            this.clear()
                            this.fetchCategories();
                        })
                    }
                    this.submitStatus = 'PENDING'
                    this.edit = false
                    setTimeout(() => {
                        this.submitStatus = 'OK'
                    }, 500)
                }
            },
            clear() {
                this.$v.$reset()
                this.id = ''
                this.name = ''
                this.description = ''
                this.category_id = ''
            },
            editCategory(category) {
                this.edit = true;
                this.id = category.id;
                this.name = category.name;
                this.description = category.description;
                this.category_id = category.id;
            },
            deleteCategory(id) {
                if (confirm('Are you sure?')) {
                fetch(`/api/category/${id}`, {
                    method: 'delete'
                })
                .then(res => res.json())
                .then(data => {
                    alert('Category removed');
                    this.fetchCategories();
                })
                .catch(err => console.log(err));
                }
            },
            changePage(page) {
                this.fetchCategories(page)
            },
        }
    }
</script>

<!-- Styles -->
<style scoped>

</style>
