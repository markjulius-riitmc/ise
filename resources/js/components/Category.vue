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
            <v-card-text class="grey lighten-4">
            </v-card-text>
        </v-card>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ title }}</div>

                        <div class="card-body">
                            <form @submit.prevent="addCategory" class="mb-3">
                                <div class="form-group">
                                    <input type="text" class="form-control" placeholder="Name"
                                    v-model="category.name">
                                </div>
                                <div class="form-group">
                                    <textarea type="text" class="form-control" placeholder="Description"
                                    v-model="category.description"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Save</button>
                            </form>

                            <nav aria-label="Page navigation example">
                                <ul class="pagination">
                                    <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#"
                                    @click="fetchCategories(pagination.prev_page_url)">Previous</a></li>

                                    <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

                                    <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#"
                                    @click="fetchCategories(pagination.next_page_url)">Next</a></li>
                                </ul>
                            </nav>

                            <div class="card card-body mb-2" v-for="category in categories" v-bind:key="category.id">
                                <h3>{{ category.name }}</h3>
                                <p>{{ category.description }}</p>
                                <hr>
                                <button @click="editCategory(category)" class="btn btn-warning mb-2">Edit</button>
                                <button @click="deleteCategory(category.id)" class="btn btn-danger">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
  
<script>
    export default {
        props : ['title'],
        data() {
            return {
                categories: [],
                category: {
                    id: '',
                    name: '',
                    description: ''
                },
                category_id: '',
                pagination: {},
                edit:false,
                sticky: false
            }
        },
        created() {
            this.fetchCategories();
        },
        methods: {
            fetchCategories(page_url) {
                let vm = this;
                page_url = page_url || '/api/categories'
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.categories = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let pagination = {
                current_page: meta.current_page, 
                last_page: meta.last_page,
                next_page_url: links.next,
                prev_page_url: links.prev
                }

                this.pagination = pagination;
            },
            addCategory() {
                if (this.edit === false) {
                // Add
                fetch('api/category', {
                    method: 'post',
                    body: JSON.stringify(this.category),
                    headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.category.name = '';
                    this.category.description = '';
                    alert('Category added');
                    this.fetchCategories();
                })
                } else {
                // Update
                fetch('api/category', {
                    method: 'put',
                    body: JSON.stringify(this.category),
                    headers: {
                    'content-type': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.category.name = '';
                    this.category.description = '';
                    alert('Category updated');
                    this.fetchCategories();
                })
                }
            },
            editCategory(category) {
                this.edit = true;
                this.category.id = category.id;
                this.category.category_id = category.id;
                this.category.name = category.name;
                this.category.description = category.description;
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
        }
    }
</script>



  <!-- Styles -->
  <style scoped>

  </style>
