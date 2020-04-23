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
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">{{ title }}</div>

                                <div class="card-body">
                                    <form @submit.prevent="addEquipment" class="mb-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Name"
                                            v-model="equipment.name">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" class="form-control" placeholder="Description"
                                            v-model="equipment.description">
                                        </div>
                                        <div class="form-group">
                                            <select v-model="equipment.category_id" class="form-control">
                                                <option disabled value="">Please select a category</option>
                                                <option v-for="category in categories" v-bind:key="category.id"
                                                v-bind:value="category.id">
                                                    {{ category.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-block">Save</button>
                                    </form>

                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li v-bind:class="[{disabled: !pagination.prev_page_url}]" class="page-item"><a class="page-link" href="#"
                                            @click="fetchEquipments(pagination.prev_page_url)">Previous</a></li>

                                            <li class="page-item disabled"><a class="page-link text-dark" href="#">Page {{ pagination.current_page }} of {{ pagination.last_page }}</a></li>

                                            <li v-bind:class="[{disabled: !pagination.next_page_url}]" class="page-item"><a class="page-link" href="#"
                                            @click="fetchEquipments(pagination.next_page_url)">Next</a></li>
                                        </ul>
                                    </nav>

                                    <div class="card card-body mb-2" v-for="equipment in equipments" v-bind:key="equipment.id">
                                        <h3>{{ equipment.name }}</h3>
                                        <p>{{ equipment.description }}</p>
                                        <p>{{ equipment.category_id }}</p>
                                        <hr>
                                        <button @click="editEquipment(equipment)" class="btn btn-warning mb-2">Edit</button>
                                        <button @click="deleteEquipment(equipment.id)" class="btn btn-danger">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    export default {
        props: ['title'],
        data() {
            return {
                equipments: [],
                equipment: {
                    id: '',
                    name: '',
                    description: '',
                    category_id: ''
                },
                equipment_id: '',
                pagination: {},
                edit: false,
                selected: '',
                categories: [],
                sticky: false,
            }
        },
        created() {
            this.fetchEquipments();
            this.fetchCategories();
        },
        methods: {
            fetchEquipments() {
                let vm = this;
                var page_url = page_url || '/api/equipments'
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.equipments = res.data;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
            },
            fetchCategories() {
                let vm = this;
                var page_url = page_url || '/api/categories'
                fetch(page_url)
                .then(res => res.json())
                .then(res => {
                    this.categories = res.data;
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
            },
            addEquipment() {
                if (this.edit === false) {
                    // Add
                    fetch('/api/equipment', {
                        method: 'post',
                        body: JSON.stringify(this.equipment),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.equipment.name = '';
                        this.equipment.description = '';
                        this.equipment.category_id = '';
                        alert('Equipment added');
                        this.fetchEquipments();
                    })
                } else {
                    // Update
                    fetch('/api/equipment', {
                        method: 'put',
                        body: JSON.stringify(this.equipment),
                        headers: {
                            'content-type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.equipment.name = '';
                        this.equipment.description = '';
                        this.equipment.category_id = '';
                        alert('Equipment updated');
                        this.fetchEquipments();
                    })
                }
            },
            editEquipment(equipment) {
                this.edit = true;
                this.equipment.id = equipment.id;
                this.equipment.equipment_id = equipment.id;
                this.equipment.name = equipment.name;
                this.equipment.description = equipment.description;
                this.equipment.category_id = equipment.category_id;
            },
            deleteEquipment(id) {
                if (confirm('Are you sure?')) {
                    fetch(`/api/equipment/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Equipment removed');
                        this.fetchEquipments();
                    })
                    .catch(err => console.log(err));
                }
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>