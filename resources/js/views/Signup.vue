<template>
    <div>
        <v-container>
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
                <v-btn class="mr-4" @click="clear">clear</v-btn>
                <v-btn to="/login">login</v-btn>
            </form>
        </v-container>
        
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email, minLength, sameAs } from 'vuelidate/lib/validators'

    export default {
        mixins: [validationMixin],
        validations: {
            name: { required },
            email: { required, email },
            password: { required, minLength: minLength(5), maxLength: maxLength(20) },
            password_confirmation: { sameAsPassword: sameAs('password') }

        },
         data() {
            return {
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
                submitStatus: null,
                credentials: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                },
            }
        },
    
        computed: {
            nameErrors () {
                const errors = []
                if (!this.$v.name.$dirty) return errors
                !this.$v.name.required && errors.push('Name is required')
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
                if (!this.$v.password.$dirty) return errors
                !this.$v.password.minLength && errors.push('Password must be at least 5 characters long')
                !this.$v.password.maxLength && errors.push('Password must be at most 20 characters long')
                !this.$v.password.required && errors.push('Password is required.')
                return errors
            },
            passwordConfirmationErrors () {
                const errors = []
                if (!this.$v.password_confirmation.$dirty) return errors
                !this.$v.password_confirmation.sameAsPassword && errors.push('Password confirmation did not match.')
                return errors
            },
        },
        created() {
            if (User.loggedIn()) {
                this.$router.push({ name: 'home' })
            }
        },
        methods: {
            submit () {
                let credentials = {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                }

                this.$v.$touch()

                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    axios.post('/api/auth/signup', credentials)
                    .then(res => {
                        User.responseAfterLogin(res)
                        this.$router.push({ name: 'home' })
                        })
                    .catch(err => console.log(err.response.data))
                }
            },
            clear () {
                this.$v.$reset()
                this.name = ''
                this.email = ''
                this.password = ''
                this.password_confirmation = ''
            },
        },
    
    }
</script>

<style lang="scss" scoped>

</style>