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
              label="Name"
              required
              @input="$v.name.$touch()"
              @blur="$v.name.$touch()"
              ></v-text-field>
              <v-text-field
              v-model="email"
              :error-messages="emailErrors"
              label="E-mail"
              required
              @input="$v.email.$touch()"
              @blur="$v.email.$touch()"
              ></v-text-field>
              <v-text-field
              v-model="password"
              type="password"
              :error-messages="passwordErrors"
              :counter="20"
              label="Password"
              required
              @input="$v.password.$touch()"
              @blur="$v.password.$touch()"
              ></v-text-field>
              <v-text-field
              v-model="password_confirmation"
              type="password"
              :error-messages="passwordConfirmationErrors"
              :counter="20"
              label="Confirm Password"
              required
              @input="$v.password_confirmation.$touch()"
              @blur="$v.password_confirmation.$touch()"
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
            v-for="user in users"
            :key="user.id"
          >
            <v-list-item three-line>
              <v-list-item-content>
                  <div class="overline mb-4">OVERLINE</div>
                  <v-list-item-title class="headline mb-1">{{ user.name }}</v-list-item-title>
                  <v-list-item-subtitle>{{ user.email }}</v-list-item-subtitle>
              </v-list-item-content>

              <v-list-item-avatar
                  tile
                  size="80"
                  color="grey"
              ></v-list-item-avatar>
            </v-list-item>

            <v-card-actions>
              <v-btn text @click="editUser(user)">Edit</v-btn>
              <v-btn text @click="deleteUser(user.id)">Delete</v-btn>
            </v-card-actions>
          </v-card>
        </v-card>
    </div>
</template>

<script>
  import { validationMixin } from 'vuelidate'
  import { required, maxLength, email, minLength, sameAs } from 'vuelidate/lib/validators'

  export default {
    props: ['title'],
    mixins: [validationMixin],
    validations: {
      name: { required },
      email: { required, email },
      password: { required, minLength: minLength(5), maxLength: maxLength(20) },
      password_confirmation: { sameAsPassword: sameAs('password') }
    },
    data: () => ({
      sticky:false,
      submitStatus: null,
      pagination: {},
      meta: {},
      edit: false,
      id: '',
      name: '',
      email: '',
      password: '',
      password_confirmation: '',
      user: {
        id: '',
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
      },
      users: [],
    }),
    created() {
      this.fetchUsers()
    },
    computed: {
      nameErrors () {
        const errors = []
        if (!this.$v.name.$dirty) return errors
        !this.$v.name.required && errors.push('Name is required.')
        return errors
      },
      emailErrors () {
        const errors = []
        
        if (!this.$v.email.$dirty) return errors
        !this.$v.email.email && errors.push('Must be valid e-mail')
        !this.$v.email.required && errors.push('E-mail is required')
        return errors
      },
      passwordErrors () {
        const errors = []
        if (this.edit == false) {
          if (!this.$v.password.$dirty) return errors
          !this.$v.password.minLength && errors.push('Password must be at least 5 characters long')
          !this.$v.password.maxLength && errors.push('Password must be at most 20 characters long')
          !this.$v.password.required && errors.push('Password is required.')
          return errors
        }
      },
      passwordConfirmationErrors () {
        const errors = []
        if (this.edit == false) {
          if (!this.$v.password_confirmation.$dirty) return errors
          !this.$v.password_confirmation.sameAsPassword && errors.push('Password confirmation did not match.')
          return errors
        }
      },
    },
    methods: {
      submit () {
        this.$v.$touch()

        let user = {
          name: this.name,
          email: this.email,
          password: this.password,
          password_confirmation: this.password_confirmation,
        }

        if (this.edit) {
          axios.put(`/api/user/`+ this.id, user)
            .then(res => {
              // console.log(res)
              console.log(res.data.success)
              this.fetchUsers()
            })
            .catch(err => console.log(err.response.data))
        } else {
          if (this.$v.$invalid) {
            this.submitStatus = 'ERROR'
          } else {
            axios.post('/api/user', user)
            .then(res => {
              // console.log(res)
              console.log(res.data.success)
              this.fetchUsers()
            })
            .catch(err => console.log(err.response.data))
          }
        }
        this.clear()
        this.submitStatus = 'PENDING'
        this.edit = false
        setTimeout(() => {
          this.submitStatus = 'OK'
        }, 500)  
      },
      fetchUsers(page){
        // let url = `/api/user?page=${page}`
        axios.get('/api/user')
        .then(res => {
          // console.log(res)
          this.users = res.data.data
          this.meta = res.data.meta;
          this.makePagination(res.data.meta, res.data.links);
        })
        .catch(err => console.log(err.response.data))
      },
      makePagination(meta, links) {
        let pagination = {
            current_page: meta.current_page, 
            last_page: meta.last_page,
            next_page_url: links.next,
            prev_page_url: links.prev,
            total: this.makeAbsNumber(meta.total/meta.per_page),
        }
        this.pagination = pagination;
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
      clear () {
        this.$v.$reset()
        this.name = ''
        this.email = ''
        this.password = ''
        this.password_confirmation = ''
      },
      editUser(user) {
          this.edit = true
          this.id = user.id
          this.name = user.name
          this.email = user.email
      },
      deleteSite(id) {
          if (confirm('Are you sure?')) {
            
              fetch(`/api/user/${id}`, {
                  method: 'delete'
              })
              .then(res => res.json())
              .then(data => {
                  alert('User removed')
                  this.fetchUsers()
              })
              .catch(err => console.log(err))
          }
      },
      changePage(page) {
        this.fetchUsers(page)
      },
      
    },
  }
</script>


<style lang="scss" scoped>

</style>