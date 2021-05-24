<template>
    <form @submit.prevent="register" id="login-form">
        <div class="heading">Registration</div>
        <div class="container">
        <div class="auth-form">
            <small class="error__control" v-if="Object.keys(error).length !== 0">{{error.email}}</small>
            <b-form-group
                id="input-group-1"
                label="Email address:"
                label-for="input-1"
            >
                <b-form-input
                    id="input-1"
                    name="email"
                    v-model="form.email"
                    type="email"
                    placeholder="Enter your email address"
                    required
                ></b-form-input>
            </b-form-group>
                <small class="error__control" v-show="Object.keys(error).length !== 0">{{error.password}}</small>
                <b-form-group id="input-group-2" label="Password:" label-for="input-2">
                    <b-form-input
                        id="input-2"
                        name="password"
                        type="password"
                        v-model="form.password"
                        placeholder="Enter your password"
                        required
                    ></b-form-input>
                </b-form-group>
            <b-form-group id="input-group-2" label="Confirm Password:" label-for="passConfirm">
                <b-form-input
                    id="passConfirm"
                    name="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    placeholder="Enter password again"
                    required
                ></b-form-input>
            </b-form-group>

            <input :disabled="isProcessing" type="submit" value="Register" />
        </div>
        <div class="separator">Or</div>
        <div class="socials">
            <a @click.stop="socialLogin('google')" class="google-plus">
                <img src="/images/google.png"/> Sign up with Google
            </a>
            <p class="signIn">Already have an account? <a href="/#/login"> Sign In</a></p>
        </div>
        </div>
    </form>
</template>
<script type="text/javascript">
    import {get,post} from '../../helpers/api'
    import Status from '../../helpers/status'
    import Auth from '../../store/auth'

    export default {
        data(){
            return {
                form: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: ''
                },
                error: {},
                isProcessing: false
            }
        },
        methods: {
            register(){
                this.isProcessing = true;
                this.error = {};
                get("/sanctum/csrf-cookie", { baseURL: "http://localhost:8000" }).then(() => {
                    post('/api/register', this.form)
                        .then((response) => {
                            if (response.data) {
                                Auth.set(response.data.access_token, response.data.user_id);
                                Status.setSuccess('You have successfully created an account!');
                                this.$router.push('/home');
                            }
                            this.isProcessing = false;
                        })
                        .catch((err) => {
                            if (err.response.status === 422 && err.response.data.errors) {
                                this.error = err.response.data.errors;
                            }
                            this.isProcessing = false;
                        })
                })
            },
            socialLogin(provider) {
                this.isProcessing = true;
                this.error = {};
                get("/sanctum/csrf-cookie", { baseURL: "http://localhost:8000" }).then(() => {
                    get(`/api/socialite/${provider}`)
                        .then((response) => {
                            if (response.data.error) {
                                this.error = err.response.data.error;
                            } else if (response.data.redirectUrl) {
                                window.location.href = response.data.redirectUrl;
                            }
                        })
                        .catch((err) => {
                            if (err.response.data.error) {
                                this.error = err.response.data.error;
                            }
                            this.isProcessing = false;
                        });
                })
                this.isProcessing = false;
            }
        }
    }
</script>
