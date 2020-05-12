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
                    <v-select
                    :items="categories"
                    label="Category"
                    v-model="category"
                    item-text="name"
                    item-value="id"
                    :clearable="true"
                    ></v-select>

                    <v-btn class="mr-4" @click="submit">submit</v-btn>
                    <v-btn @click="clear">clear</v-btn>
                </form>

                <v-pagination
                    v-model="pagination.current_page"
                    :length="pagination.total"
                    @input="changePage"
                ></v-pagination>

                <v-card
                    class="mx-auto"
                    outlined
                    v-for="equipment in equipments"
                    :key="equipment.id"
                >
                    <v-list-item three-line>
                        <v-list-item-content>
                            <div class="overline mb-4">OVERLINE</div>
                            <v-list-item-title class="headline mb-1">{{ equipment.name }}</v-list-item-title>
                            <v-list-item-subtitle>{{ equipment.description }}</v-list-item-subtitle>
                            <v-list-item-subtitle>{{ equipment.category }}</v-list-item-subtitle>
                        </v-list-item-content>
                        <v-list-item-avatar
                            tile
                            size="80"
                            color="grey"
                        ></v-list-item-avatar>
                    </v-list-item>
                    <v-card-actions>
                        <v-btn text @click="editEquipment(equipment)">Edit</v-btn>
                        <v-btn text @click="deleteEquipment(equipment.id)">Delete</v-btn>
                    </v-card-actions>
                </v-card>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email } from 'vuelidate/lib/validators'

    export default {
        props: ['title'],
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
                category: '',
                categories: [],
                equipments: [],
                equipment: {
                    id: '',
                    name: '',
                    description: '',
                    category: '',
                    equipment_id: '',
                },
                pagination: {},
                meta: {},
                edit: false,
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
            this.fetchEquipments();
            this.fetchCategories();
        },
        methods: {
            fetchEquipments(page) {
                let  url = `/api/equipments?page=${page}`
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.equipments = res.data;
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
            fetchCategories() {
                let url = '/api/categories'
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.categories = res.data;
                })
                .catch(err => console.log(err));
            },
            submit() {
                this.$v.$touch()

                if(this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    let equipment = {
                        id: this.id,
                        name: this.name,
                        description: this.description,
                        category_id: this.category,
                        equipment_id: this.id
                    }
                    // Add
                    if (this.edit == false) {
                        fetch('/api/equipment', {
                            method: 'post',
                            body: JSON.stringify(equipment),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json()
                        .then(data => {
                            alert('Equipment added');
                            this.clear()
                            this.fetchEquipments();
                        }))
                    } else { // Update
                        fetch('/api/equipment', {
                            method: 'put',
                            body: JSON.stringify(equipment),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert('Equipment updated')
                            this.clear()
                            this.fetchEquipments();
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
                this.category = ''
                this.equipment_id = ''
            },
            editEquipment(equipment) {
                this.edit = true;
                this.id = equipment.id;
                this.name = equipment.name;
                this.description = equipment.description;
                this.category = equipment.category_id;
                this.equipment_id = equipment.id;
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
            changePage(page) {
                this.fetchEquipments(page)
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>