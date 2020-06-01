<template>
    <div>
        <v-card
            class="mx-auto"
            outlined
        >
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
                <v-form>   
                    <v-row>
                        <v-col class="d-flex" cols="12" sm="2">
                            <v-menu
                            ref="menu1"
                            v-model="menu1"
                            :close-on-content-click="false"
                            transition="scale-transition"
                            offset-y
                            max-width="290px"
                            min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                    v-model="par_date"
                                    label="PAR Date"
                                    hint="MM/DD/YYYY"
                                    persistent-hint
                                    prepend-icon="mdi-calendar"
                                    @blur="date = parseDate(par_date)"
                                    v-on="on"
                                    ></v-text-field>
                                </template>
                                <v-date-picker v-model="date" no-title @input="menu1 = false"></v-date-picker>
                            </v-menu>
                        </v-col>
                        <v-col class="d-flex" cols="12" sm="2">
                            <v-text-field
                            v-model="par_no"
                            :error-messages="parNoErrors"
                            label="PAR No."
                            @input="$v.par_no.$touch()"
                            @blur="$v.par_no.$touch()"
                            required
                            :counter="50"
                            ></v-text-field>
                        </v-col>
                        <v-col class="d-flex" cols="12" sm="8">
                            <v-text-field
                            v-model="description"
                            :error-messages="descriptionErrors"
                            label="Description"
                            @input="$v.description.$touch()"
                            @blur="$v.description.$touch()"
                            ></v-text-field>
                        </v-col>
                        <v-col class="d-flex" cols="12" sm="6">
                            <v-select
                            :items="sites"
                            label="Site"
                            v-model="site"
                            item-text="name"
                            item-value="id"
                            :clearable="true"
                            ></v-select>
                        </v-col>
                        <v-col class="d-flex" cols="12" sm="6">
                            <v-select
                            :items="divisions"
                            label="Division"
                            v-model="division"
                            item-text="name"
                            item-value="id"
                            :clearable="true"
                            ></v-select>
                        </v-col>
                        <v-col class="d-flex" cols="12" sm="6">
                            <v-select
                            :items="departments"
                            label="Department"
                            v-model="department"
                            item-text="name"
                            item-value="id"
                            :clearable="true"
                            ></v-select>
                        </v-col>
                        <v-col class="d-flex" cols="12" md="6">
                            <v-select
                            :items="employees"
                            label="Employee"
                            v-model="employee"
                            item-text="name"
                            item-value="id"
                            :clearable="true"
                            ></v-select>
                        </v-col>
                        <v-col class="d-flex" cols="12">
                            <v-file-input 
                            label="File input"
                            multiple
                            @change="fieldChange"
                            ref="fileInput"
                            ></v-file-input>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-data-table
                            :headers="headers"
                            :items="item_lists"
                            :item-key="item_lists.id"
                            class="elevation-1"
                            sort-by="property_no"
                            >
                                <template v-slot:top>
                                    <v-toolbar flat color="white">
                                        <v-toolbar-title>Item Lists</v-toolbar-title>
                                        <v-divider
                                        class="mx-4"
                                        inset
                                        vertical
                                        ></v-divider>
                                        <v-spacer></v-spacer>
                                        <v-dialog v-model="dialog" max-width="700px">
                                            <template v-slot:activator="{ on }">
                                                <v-btn color="primary" dark class="mb-2" v-on="on">New Item</v-btn>
                                            </template>
                                            <v-card>
                                                <v-card-title>
                                                    <span class="headline">{{ formTitle }}</span>
                                                </v-card-title>
                                                <v-card-text>
                                                    <v-container>
                                                        <v-row>
                                                            <v-col class="d-flex" cols="12"> 
                                                                <v-text-field
                                                                v-model="editedItem.property_no"
                                                                label="Property No."
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12"> 
                                                                <v-select
                                                                :items="equipments"
                                                                label="Equipment*"
                                                                v-model="editedItem.equipment"
                                                                item-text="name"
                                                                item-value="id"
                                                                :clearable="true"
                                                                ></v-select>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12">
                                                                <v-text-field
                                                                v-model="editedItem.sku"
                                                                label="Stock Keeping Unit (SKU)"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12">
                                                                <v-text-field
                                                                v-model="editedItem.serial_no"
                                                                label="Serial No."
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12" sm="6" md="6">
                                                                <v-text-field
                                                                v-model="editedItem.brand"
                                                                label="Brand"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12" sm="6" md="6">
                                                                <v-text-field
                                                                v-model="editedItem.model_no"
                                                                label="Model No."
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12" sm="12" md="12">
                                                                <v-select
                                                                :items="suppliers"
                                                                label="Supplier"
                                                                v-model="editedItem.supplier"
                                                                item-text="name"
                                                                item-value="id"
                                                                :clearable="true"
                                                                ></v-select>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12" sm="12" md="12">
                                                                <v-text-field
                                                                v-model="editedItem.quantity"
                                                                label="Quantity"
                                                                value=""
                                                                prefix="#"
                                                                ></v-text-field>
                                                            </v-col>
                                                            <v-col class="d-flex" cols="12" sm="12" md="12">
                                                                <v-text-field
                                                                v-model="editedItem.unit_value"
                                                                label="Unit Value"
                                                                value=""
                                                                prefix="$"
                                                                ></v-text-field>
                                                            </v-col>
                                                        </v-row>
                                                    </v-container>
                                                    <small>*indicates required field</small>
                                                </v-card-text>
                                                <v-card-actions>
                                                    <v-spacer></v-spacer>
                                                    <v-btn color="blue darken-1" text @click="close">Cancel</v-btn>
                                                    <v-btn color="blue darken-1" text @click="save">Save</v-btn>
                                                </v-card-actions>
                                            </v-card>
                                        </v-dialog>
                                    </v-toolbar>
                                </template>

                                <template v-slot:item.actions="{ item }">
                                    <v-icon
                                        small
                                        class="mr-2"
                                        @click="editItem(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon
                                        small
                                        @click="deleteItem(item)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </template>
                                <template v-slot:no-data>
                                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                    <v-btn class="mr-4" @click="submit">submit</v-btn>
                    <v-btn @click="clear">clear</v-btn>
                </v-form>
            </v-card-text>

            <v-pagination
            v-model="pagination.current_page"
            :length="pagination.total"
            @input="changePage"
            ></v-pagination>
            
            <v-card
                class="mx-auto mb-2"
                outlined
                v-for="equipment in manage_equipment"
                :key="equipment.id"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="overline mb-4">OVERLINE</div>
                        <v-list-item-title class="headline mb-1">{{ equipment.par_no }}</v-list-item-title>
                        <v-list-item-subtitle>{{ equipment.description }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                        tile
                        size="80"
                        color="grey"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn text @click="editManageEquipment(equipment)">Edit</v-btn>
                    <v-btn text @click="deleteManageEquipment(equipment.id)">Delete</v-btn>
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
            par_no: { required, maxLength: maxLength(255) },
            description: { maxLength: maxLength(255) },
        },
        data() {
            return {
                sticky: false,
                pagination: {},
                meta: {},
                edit: false,
                submitStatus: null,
                dialog: false,
                editedIndex: -1,
                id: '',
                par_date: this.formatDate(new Date().toISOString().substr(0, 10)),
                par_no: '',
                description: '',
                site: '',
                division: '',
                department: '',
                employee: '',
                fileInput: '',
                equipment: {
                    id: '',
                    par_date: '',
                    par_no: '',
                    description: '',
                    site: '',
                    division: '',
                    department: '',
                    employee: '',
                    file_paths: [],
                    item_lists: [],
                },
                manage_equipment: [],
                sites: [],
                divisions: [],
                departments: [],
                employees: [],
                suppliers: [],
                equipments: [],
                item_lists: [],
                file_paths: [],
                headers: [
                    { 
                        text: 'ID', 
                        value: 'id',
                        visible: false },
                    { text: 'Property No.', value: 'property_no' },
                    {
                        text: 'Equipment',
                        align: 'start',
                        sortable: false,
                        value: 'equipment',
                    },
                    { text: 'SKU', value: 'sku' },
                    { text: 'Serial No.', value: 'serial_no' },
                    { text: 'Brand', value: 'brand' },
                    { text: 'Model No.', value: 'model_no' },
                    { text: 'Supplier', value: 'supplier' },
                    { text: 'Quantity', value: 'quantity' },
                    { text: 'Unit Value', value: 'unit_value' }, 
                    { text: 'Actions', value: 'actions', sortable: false },
                ],
                editedItem: {
                    id: '',
                    property_no: '',
                    equipment: '',
                    sku: '',
                    serial_no: '',
                    brand: '',
                    model_no: '',
                    supplier: '',
                    quantity: '',
                    unit_value: '',
                },
                defaultItem: {
                    id: '',
                    property_no: '',
                    equipment: '',
                    sku: '',
                    serial_no: '',
                    brand: '',
                    model_no: '',
                    supplier: '',
                    quantity: '',
                    unit_value: '',
                },
                form: new FormData,
                date: new Date().toISOString().substr(0, 10), 
                menu1: false,
            }
        },
        computed: {
            parNoErrors () {
                const errors = []
                if (!this.$v.par_no.$dirty) return errors
                !this.$v.par_no.required && errors.push('PAR No. is required.')
                !this.$v.par_no.maxLength && errors.push('PAR No. must be at most 255 characters long')
                return errors
            },
            descriptionErrors () {
                const errors = []
                if (!this.$v.description.$dirty) return errors
                !this.$v.description.maxLength && errors.push('Description must be at most 255 characters long')
                return errors
            },
            formTitle () {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
            },
        },
        watch: {
            dialog (val) {
                val || this.close()
            },
            date (val) {
                this.par_date = this.formatDate(this.date)
            },
        },
        created() {
            this.initialize()
        },
        methods: {
            fetchSites() {
                axios.get('/api/sites')
                .then(response => {
                    this.sites = response.data.data
                })
                .catch(err => console.log(err))
            },
            fetchDivisions() {
                fetch('/api/divisions')
                .then(res => res.json())
                .then(res => {
                    this.divisions = res.data;
                })
                .catch(err => console.log(err));
            },
            fetchDepartments() {
                fetch('/api/departments')
                .then(res => res.json())
                .then(res => {
                    this.departments = res.data;
                })
                .catch(err => console.log(err));
            },
            fetchEmployees() {
                fetch('/api/employees')
                .then(res => res.json())
                .then(res => {
                    this.employees = res.data;
                })
                .catch(err => console.log(err));
            },
            fetchSuppliers() {
                fetch('/api/suppliers')
                .then(res => res.json())
                .then(res => {
                    this.suppliers = res.data;
                })
                .catch(err => console.log(err));
            },
            fetchEquipments() {
                fetch('/api/equipments')
                .then(res => res.json())
                .then(res => {
                    this.equipments = res.data
                })
                .catch(err => console.log(err));
            },
            fetchManageEquipment(page) {
                let url = `/api/manage-equipment?page=${page}`

                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.manage_equipment = res.data
                    this.meta = res.meta
                    this.makePagination(res.meta, res.links)
                })
                .catch(err => console.log(err.data))
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
                if (mod < 0.5 && mod > 0) {
                    num -= mod
                    num += 1
                } else {
                    num = Math.round(num)
                }
                return num
            },
            changePage(page) {
                this.fetchManageEquipment(page)
            },
            submit() {
                const config = { headers: { 'Content-Type': 'multipart/form-data' } }
                this.$v.$touch()
                if(this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    this.form.append('id', this.id)
                    this.form.append('par_date', this.parseDate(this.par_date))
                    this.form.append('par_no', this.par_no)
                    this.form.append('description', this.description)
                    this.form.append('site', this.site)
                    this.form.append('division', this.division)
                    this.form.append('department', this.department)
                    this.form.append('employee', this.employee)
                    this.form.append('item_lists', JSON.stringify(this.item_lists))

                    for (let i = 0; i < this.file_paths.length; i++) {
                        this.form.append('file_paths[]', this.file_paths[i])
                    }

                    console.log(this.form)

                    if (this.edit == false) {
                        // Add
                        axios.post('/api/manage-equipment', this.form, config)
                        .then(response => {
                            alert(response.data.success);
                            this.clear()
                            this.fetchManageEquipment()
                        })
                        .catch(err => console.log(err))

                    } else {
                        let equipment = {
                            'id': this.id,
                            'par_date': this.parseDate(this.par_date),
                            'par_no': this.par_no,
                            'description': this.description,
                            'site': this.site,
                            'division': this.division,
                            'department': this.department,
                            'employee': this.employee,
                            'item_lists': this.item_lists,
                            'file_paths': this.file_paths,
                        }

                        console.log(equipment)
                        // Update
                        axios.patch('/api/manage-equipment/'+this.id, equipment)
                        .then(response => {
                            alert(response.data.success)
                            this.clear()
                            this.fetchManageEquipment()
                        })
                        .catch(err => console.log(err))
                    }
                    this.submitStatus = 'PENDING'
                    this.edit = false
                    setTimeout(() => {
                        this.submitStatus = 'OK'
                    }, 500)
                }
            },
            initialize () {
                this.fetchSites();
                this.fetchDivisions();
                this.fetchDepartments()
                this.fetchEmployees()
                this.fetchSuppliers()
                this.fetchEquipments()
                this.fetchManageEquipment()
            },
            clear() {
                this.$v.$reset()
                this.id = ''
                this.par_date = this.formatDate(new Date().toISOString().substr(0, 10)),
                this.par_no = ''
                this.description = ''
                this.site = ''
                this.division = ''
                this.department = ''
                this.employee = ''
                this.$refs.fileInput.value = ''
                this.item_lists = []
            },
            editItem (item) {
                this.editedIndex = this.item_lists.indexOf(item)
                this.editedItem = Object.assign({}, item)
                this.dialog = true
            },
            deleteItem (item) {
                const index = this.item_lists.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.item_lists.splice(index, 1)
            },
            close () {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },
            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.item_lists[this.editedIndex], this.editedItem) 
                } else {
                    this.item_lists.push(this.editedItem)
                }
                this.close()
            },
            editManageEquipment(manage_equipment) {
                this.edit = true
                this.id = manage_equipment.id
                this.par_date = this.formatDate(manage_equipment.par_date)
                this.par_no = manage_equipment.par_no
                this.description = manage_equipment.description
                this.site = manage_equipment.site_id
                this.division = manage_equipment.division_id
                this.department = manage_equipment.department_id
                this.employee = manage_equipment.employee_id
                this.item_lists = manage_equipment.items
                this.file_paths = manage_equipment.attachments
                // Assign attachment files to file input
            },
            deleteManageEquipment(id) {
                if (confirm('Are you sure?')) {
                    fetch(`/api/manage-equipment/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Property Acknowledgment Receipt removed')
                        this.fetchManageEquipment()
                    })
                    .catch(err => console.log(err))
                }
            },
            fieldChange(event) {
                let selectedFiles = event
                
                if (!selectedFiles.length) {
                    return false
                }
                for (let i = 0; i < selectedFiles.length; i++) {
                    this.file_paths.push(selectedFiles[i])
                }
            },
            formatDate (date) {
                if (!date) return null

                const [year, month, day] = date.split('-')
                return `${month}/${day}/${year}`
            },
            parseDate (date) {
                if (!date) return null

                const [month, day, year] = date.split('/')
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`
            },
        },
    }
</script>

<style lang="scss" scoped>

</style>