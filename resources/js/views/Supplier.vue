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
                    v-model="address"
                    :error-messages="addressErrors"
                    label="Address"
                    @input="$v.address.$touch()"
                    @blur="$v.address.$touch()"
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
                v-for="supplier in suppliers"
                :key="supplier.id"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="overline mb-4">OVERLINE</div>
                        <v-list-item-title class="headline mb-1">{{ supplier.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ supplier.address }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                        tile
                        size="80"
                        color="grey"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn text @click="editSupplier(supplier)">Edit</v-btn>
                    <v-btn text @click="deleteSupplier(supplier.id)">Delete</v-btn>
                </v-card-actions>
             </v-card>
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
            address: { maxLength: maxLength(255) },
        },
        computed: {
            nameErrors () {
                const errors = []
                if (!this.$v.name.$dirty) return errors
                !this.$v.name.maxLength && errors.push('Name must be at most 50 characters long')
                !this.$v.name.required && errors.push('Name is required.')
                return errors
            },
            addressErrors () {
                const errors = []
                if (!this.$v.address.$dirty) return errors
                !this.$v.address.maxLength && errors.push('Must be at most 255 characters long')
                return errors
            },
        },
        data() {
            return {
                sticky: false,
                id: '',
                name: '',
                address: '',
                suppliers: [],
                supplier: {
                    id: '',
                    name: '',
                    address: '',
                    supplier_id: '',
                },
                pagination: {},
                meta: {},
                edit:false,
                submitStatus: null,
            }
        },
        created() {
            this.fetchSuppliers();
        },
        methods: {
            fetchSuppliers(page) {
                let vm = this;
                let url = `/api/suppliers?page=${page}`
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.suppliers = res.data;
                    this.meta = res.meta;
                    vm.makePagination(res.meta, res.links)
                })
                .catch(err => console.log(err))
            },
            makePagination(meta, links) {
                let pagination = {
                    current_page: meta.current_page,
                    total: this.makeAbsNumber(meta.total/meta.per_page),
                }
                this.pagination = pagination
            },
            makeAbsNumber(num) {
                var mod = (num % 1)

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
                    let supplier = {
                        id: this.id,
                        name: this.name,
                        address: this.address,
                        supplier_id: this.id
                    }

                    if (this.edit == false) {
                        // Add
                        fetch('/api/supplier', {
                            method: 'post',
                            body: JSON.stringify(supplier),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json()
                        .then(data => {
                            alert('Supplier added');
                            this.fetchSuppliers();
                        }))
                    } else {
                        // Update
                        fetch('/api/supplier', {
                            method: 'put',
                            body: JSON.stringify(supplier),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert('Supplier updated')
                            this.fetchSuppliers();
                        })
                    }
                    this.clear()
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
                this.address = ''
                this.supplier_id = ''
                // this.$refs.name.focus()
            },
            editSupplier(supplier) {
                this.edit = true
                this.id = supplier.id
                this.name = supplier.name
                this.address = supplier.address
                this.supplier_id = supplier.id
            },
            deleteSupplier(id) {
                if (confirm('Are you sure?')) {
                    fetch(`/api/supplier/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Supplier removed')
                        this.fetchSuppliers()
                    })
                    .catch(err => console.log(err))
                }
            },
            changePage(page) {
                this.fetchSuppliers(page)
            },
        },
        
    }
</script>

<style lang="scss" scoped>

</style>