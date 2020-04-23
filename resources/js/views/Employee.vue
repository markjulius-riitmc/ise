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
                    v-model="last_name"
                    :error-messages="lastNameErrors"
                    :counter="50"
                    label="Last Name"
                    required
                    @input="$v.last_name.$touch()"
                    @blur="$v.last_name.$touch()"
                    ></v-text-field>

                    <v-text-field
                    v-model="first_name"
                    :error-messages="firstNameErrors"
                    :counter="50"
                    label="First Name"
                    required
                    @input="$v.first_name.$touch()"
                    @blur="$v.first_name.$touch()"
                    ></v-text-field>

                    <v-text-field
                    v-model="middle_name"
                    :error-messages="middleNameErrors"
                    :counter="50"
                    label="Middle Name"
                    required
                    @input="$v.middle_name.$touch()"
                    @blur="$v.middle_name.$touch()"
                    ></v-text-field>

                    <v-text-field
                    v-model="prefix"
                    :error-messages="prefixErrors"
                    label="Prefix"
                    @input="$v.prefix.$touch()"
                    @blur="$v.prefix.$touch()"
                    ></v-text-field>

                    <v-text-field
                    v-model="suffix"
                    :error-messages="suffixErrors"
                    label="Suffix"
                    @input="$v.suffix.$touch()"
                    @blur="$v.suffix.$touch()"
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
                v-for="employee in employees"
                :key="employee.id"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="overline mb-4">OVERLINE</div>
                        <v-list-item-title class="headline mb-1">{{ employee.last_name + ', ' + employee.first_name + ' ' + employee.middle_name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ employee.prefix + ' | ' + employee.suffix }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                        tile
                        size="80"
                        color="grey"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn text @click="editEmployee(employee)">Edit</v-btn>
                    <v-btn text @click="deleteEmployee(employee.id)">Delete</v-btn>
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
            last_name: { required, maxLength: maxLength(50) },
            first_name: { required, maxLength: maxLength(50) },
            middle_name: { required, maxLength: maxLength(50) },
            prefix: { maxLength: maxLength(50) },
            suffix: { maxLength: maxLength(50) },
        },
        computed: {
            lastNameErrors () {
                const errors = []
                if (!this.$v.last_name.$dirty) return errors
                !this.$v.last_name.maxLength && errors.push('Last name must be at most 50 characters long')
                !this.$v.last_name.required && errors.push('Last name is required.')
                return errors
            },
            firstNameErrors () {
                const errors = []
                if (!this.$v.first_name.$dirty) return errors
                !this.$v.first_name.maxLength && errors.push('First name must be at most 50 characters long')
                !this.$v.first_name.required && errors.push('First name is required.')
                return errors
            },
            middleNameErrors () {
                const errors = []
                if (!this.$v.middle_name.$dirty) return errors
                !this.$v.middle_name.maxLength && errors.push('Middle name must be at most 50 characters long')
                !this.$v.middle_name.required && errors.push('Middle name is required.')
                return errors
            },
            prefixErrors () {
                const errors = []
                if (!this.$v.prefix.$dirty) return errors
                !this.$v.prefix.maxLength && errors.push('Must be at most 50 characters long')
                return errors
            },
            suffixErrors () {
                const errors = []
                if (!this.$v.suffix.$dirty) return errors
                !this.$v.suffix.maxLength && errors.push('Must be at most 50 characters long')
                return errors
            },
            fullName: function () {
                return this.first_name + '' + this.last_name
            },
            
        },
        data() {
            return {
                sticky: false,
                id: '',
                last_name: '',
                first_name: '',
                middle_name: '',
                prefix: '',
                suffix: '',
                employees: [],
                employee: {
                    id: '',
                    last_name: '',
                    first_name: '',
                    middle_name: '',
                    prefix: '',
                    suffix: '',
                    employee_id: '',
                },
                pagination: {},
                meta: {},
                edit: false,
                submitStatus: null,
            }
        },
        created() {
            this.fetchEmployees()
        },
        methods: {
            fetchEmployees(page) {
                let url = `/api/employees?page=${page}`
                
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.employees = res.data
                    this.meta = res.meta
                    this.makePagination(res.meta, res.links)
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

                if (mod < 0.5 && mod > 0) {
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
                    let employee = {
                        id: this.id,
                        last_name: this.last_name,
                        first_name: this.first_name,
                        middle_name: this.middle_name,
                        prefix: this.prefix,
                        suffix: this.suffix,
                        employee_id: this.id,
                    }

                    if (this.edit == false) {
                        // Add
                        fetch('/api/employee', {
                            method: 'post',
                            body: JSON.stringify(employee),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json()
                        .then(data => {
                            alert('Employee added');
                            this.fetchEmployees();
                        }))
                    } else {
                        // Update
                        fetch('/api/employee', {
                            method: 'put',
                            body: JSON.stringify(employee),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            alert('Employee updated')
                            this.fetchEmployees();
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
                this.last_name = ''
                this.first_name = ''
                this.middle_name = ''
                this.prefix = ''
                this.suffix = ''
            },
            editEmployee(employee) {
                this.edit = true
                this.id = employee.id
                this.last_name = employee.last_name
                this.first_name = employee.first_name
                this.middle_name = employee.middle_name
                this.prefix = employee.prefix
                this.suffix = employee.suffix
            },
            deleteEmployee(id) {
                if (confirm('Are you sure?')) {
                    fetch(`/api/employee/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Employee removed')
                        this.fetchEmployees()
                    })
                    .catch(err => console.log(err))
                }
            },
            changePage(page) {
                this.fetchEmployees(page)
            },
        }
        
    }
</script>

<style lang="scss" scoped>

</style>