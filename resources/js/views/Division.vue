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
                v-for="division in divisions"
                :key="division.id"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="overline mb-4">OVERLINE</div>
                        <v-list-item-title class="headline mb-1">{{ division.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ division.description }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                        tile
                        size="80"
                        color="grey"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn text @click="editDivision(division)">Edit</v-btn>
                    <v-btn text @click="deleteDivision(division.id)">Delete</v-btn>
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
            description: { maxLength: maxLength(255) },
        },
        data() {
            return {
                sticky: false,
                id: '',
                name: '',
                description: '',
                divisions: [],
                division: {
                    id: '',
                    name: '',
                    description: '',
                    division_id: '',
                },
                pagination: {},
                meta: {},
                edit: false,
                submitStatus: null,
            }
        },
        created() {
            this.fetchDivisions();
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
        methods: {
            submit() {
                this.$v.$touch()

                if(this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    let division = {
                        id: this.id,
                        name: this.name,
                        description: this.description,
                        division_id: this.id
                    }

                    if (this.edit == false) {
                        // Add
                        fetch('/api/division', {
                            method: 'post',
                            body: JSON.stringify(division),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json()
                        .then(data => {
                            // this.name = '';
                            // this.description = '';
                            alert('Division added');
                            this.fetchDivisions();
                        }))
                    } else {
                        // Update
                        fetch('/api/division', {
                            method: 'put',
                            body: JSON.stringify(division),
                            headers: {
                                'content-type': 'application/json'
                            },
                        })
                        .then(res => res.json())
                        .then(data => {
                            // this.name = ''
                            // this.description = ''
                            alert('Division updated')
                            this.fetchDivisions();
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
                this.division_id = ''
                this.name = ''
                this.description = ''
                // this.$refs.name.focus()
            },
            fetchDivisions(page) {
                let vm = this;
                let url = `/api/divisions?page=${page}`
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.divisions = res.data;
                    this.meta = res.meta;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err));
            },
            makePagination(meta, links) {
                let vm = this;
                let pagination = {
                    current_page: meta.current_page,
                    total: vm.makeAbsNumber(meta.total/meta.per_page),
                }
                vm.pagination = pagination;
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
            editDivision(division) {
                this.edit = true
                this.id = division.id
                this.name = division.name
                this.description = division.description
                this.division_id = division.id
            },
            deleteDivision(id) {
                if (confirm('Are you sure?')) {
                    fetch(`/api/division/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Division removed')
                        this.fetchDivisions()
                    })
                    .catch(err => console.log(err))
                }
            },
            changePage(page) {
                this.fetchDivisions(page)
            },
        }
        
    }
</script>

<style lang="scss" scoped>

</style>