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
                v-for="site in sites"
                :key="site.id"
            >
                <v-list-item three-line>
                    <v-list-item-content>
                        <div class="overline mb-4">OVERLINE</div>
                        <v-list-item-title class="headline mb-1">{{ site.name }}</v-list-item-title>
                        <v-list-item-subtitle>{{ site.description }}</v-list-item-subtitle>
                    </v-list-item-content>

                    <v-list-item-avatar
                        tile
                        size="80"
                        color="grey"
                    ></v-list-item-avatar>
                </v-list-item>

                <v-card-actions>
                    <v-btn text @click="editSite(site)">Edit</v-btn>
                    <v-btn text @click="deleteSite(site.id)">Delete</v-btn>
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
                sticky:false,
                id: '',
                name: '',
                description: '',
                sites: [],
                site: {
                    id: '',
                    name: '',
                    description: ''
                },
                site_id: '',
                pagination: {},
                edit: false,
                submitStatus: null,
                meta: {},
                // total: 0,
            }
        },
        created() {
            this.fetchSites();
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
            submit () {
                this.$v.$touch()
                
                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    if (this.edit === false) {
                        // Add
                        fetch('/api/site', {
                            method: 'post',
                            body: JSON.stringify({id: this.id, name: this.name, description: this.description}),
                            headers: {
                            'content-type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            // this.name = '';
                            // this.description = '';
                            alert('Site added');
                            this.fetchSites();
                        })
                    } else {
                        // Updated
                        fetch('/api/site', {
                            method: 'put',
                            body: JSON.stringify({id: this.id, name: this.name, description: this.description, site_id: this.site_id}),
                            headers: {
                            'content-type': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            // this.name = '';
                            // this.description = '';
                            alert('Site updated');
                            this.fetchSites();
                        })
                    }
                    this.clear()
                    this.submitStatus = 'PENDING'
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
            },
            fetchSites(page) {
                let vm = this;
                let url = `/api/sites?page=${page}`
                // page_url = page_url || '/api/sites'
                fetch(url)
                .then(res => res.json())
                .then(res => {
                    this.sites = res.data;
                    this.meta = res.meta;
                    vm.makePagination(res.meta, res.links);
                })
                .catch(err => console.log(err)); 
            },
            makePagination(meta, links) {
                let vm = this;
                let pagination = {
                    current_page: meta.current_page, 
                    last_page: meta.last_page,
                    next_page_url: links.next,
                    prev_page_url: links.prev,
                    total: vm.makeAbsNumber(meta.total/meta.per_page),
                }
                this.pagination = pagination;
            },
            editSite(site) {
                this.edit = true
                this.id = site.id
                this.site_id = site.id
                this.name = site.name
                this.description = site.description
            },
            deleteSite(id) {
                if (confirm('Are you sure?')) {
                    fetch(`/api/site/${id}`, {
                        method: 'delete'
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert('Site removed')
                        this.fetchSites()
                    })
                    .catch(err => console.log(err))
                }
            },
            changePage(page) {
                this.fetchSites(page)
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
        },
    }
</script>

<style lang="scss" scoped>

</style>