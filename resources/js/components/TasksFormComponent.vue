<template>
    <div>
        <form @submit.prevent="tasks" id="tasks-form">
            <div class="container">
                    <small class="error__control" v-if="Object.keys(error).length !== 0">{{error}}</small><br />
                        <b-form-input
                            id="input-1"
                            name="name"
                            v-model="form.name"
                            type="text"
                            placeholder="Add a task..."
                            required
                        ></b-form-input>
            </div>
        </form>
    </div>
</template>

<script>
import {post} from '../helpers/api'
import Status from '../helpers/status'
import Auth from "../store/auth";
export default {
    name: "TasksFormComponent",
    data(){
        return {
            form: {
                name: '',
            },
            error: {},
            isProcessing: false,
            status: null,
        }
    },
    methods: {
        tasks() {
            this.isProcessing = true;
            this.error = {};
            post('/api/tasks', this.form)
                .then((response) => {
                    if (response) {
                        console.log(response.data);
                        Status.setSuccess(response.data.message);
                        this.status = Status;
                        location.reload();
                    }
                    this.isProcessing = false;
                })
                .catch((err) => {
                    if (err.response.data.error) {
                        this.error = err.response.data.error;
                    }
                    this.isProcessing = false;
                })
        },
    }
}
</script>

<style scoped>

</style>
