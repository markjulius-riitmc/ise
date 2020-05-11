<template>
    <div>
        <v-container>
            <form>
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

                <v-btn class="mr-4" @click="submit">submit</v-btn>
                <v-btn @click="clear">clear</v-btn>
            </form>
        </v-container>
        
    </div>
</template>

<script>
    import { validationMixin } from 'vuelidate'
    import { required, maxLength, email, minLength } from 'vuelidate/lib/validators'

    export default {
        mixins: [validationMixin],
        validations: {
            email: { required, email },
            password: { required, minLength: minLength(5), maxLength: maxLength(20) },
        },
         data() {
            return {
                email: '',
                password: '',
                submitStatus: null,
                credentials: {
                    email: '',
                    password: '',
                },
            }
        },

        computed: {
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
        },
        methods: {
            submit () {
                let credentials = {
                    email: this.email,
                    password: this.password,
                }
                
                this.$v.$touch()

                if (this.$v.$invalid) {
                    this.submitStatus = 'ERROR'
                } else {
                    User.login(credentials)
                }
            },
            clear () {
                this.$v.$reset()
                this.email = ''
                this.password = ''
            },
        },
    
    }
</script>

<style lang="scss" scoped>

</style>