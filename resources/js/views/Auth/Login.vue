<template>
    <form @submit.prevent="login" id="login-form">
        <div class="heading">Sign In</div>
        <div class="container">
            <div class="auth-form">
                <small class="error__control" v-if="Object.keys(error).length !== 0">{{error.all}}</small>
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
                    <b-form-group id="input-group-2" label="Password:" label-for="input-2">
                        <b-form-input
                            id="input-2"
                            name="password"
                            v-model="form.password"
                            placeholder="Enter your password"
                            required
                        ></b-form-input>
                    </b-form-group>

                <input :disabled="isProcessing" type="submit" value="Login" />

            </div>
            <div class="separator">Or</div>
            <div class="socials">
                <a @click.stop="socialLogin('google')" class="google-plus">
                    <img src="/images/google.png"/> Sign In with Google
                </a>
                <p class="signIn">Don't have an account? <a href="/#/register"> Register</a></p>
            </div>
        </div>
    </form>
</template>
<script type="text/javascript">
    import Auth from '../../store/auth'
    import {get,post} from '../../helpers/api'
    import Status from '../../helpers/status'
    export default {
        created(){
            if(Auth.state.error){
                this.error = Auth.state.error.includes('+')
                    ? Auth.state.error.replace(/\+/gi,' ') : Auth.state.error;
            }
        },
        data(){
            return {
                form: {
                    email: '',
                    password: ''
                },
                error: {},
                isProcessing: false,
                auth: null,
                status: null,
            }
        },
        methods: {
            login(){
                this.isProcessing = true;
                this.error = {};
                get("/sanctum/csrf-cookie", { baseURL: "http://localhost:8000" }).then(() => {
                    post('/api/login', this.form)
                        .then((response) => {
                            if (response.data) {
                                Auth.set(response.data.access_token, response.data.user_id);
                                Status.setSuccess('You have successfully logged in!');
                                this.auth = Auth;
                                this.status = Status;
                                this.$router.push('/home');
                            }
                            this.isProcessing = false;
                        })
                        .catch((err) => {
                            if (err.response.data.error) {
                                this.error = err.response.data.error;
                            }else{
                                this.error.all = err.response.data.message;
                            }
                            this.isProcessing = false;
                        })
                })
            },
            socialLogin(provider){
                this.isProcessing = true;
                this.error = {};
                get(`/api/socialite/${provider}`)
                    .then((response) => {
                        if(response.data.error){
                            this.error = err.response.data.error;
                        } else if(response.data.redirectUrl){
                            window.location.href = response.data.redirectUrl;
                        }
                    })
                    .catch((err) => {
                        if(err.response.data.error){
                            this.error = err.response.data.error;
                        }
                        this.isProcessing = false;
                    });
                    this.isProcessing = false;
            }
        }
    }
</script>
