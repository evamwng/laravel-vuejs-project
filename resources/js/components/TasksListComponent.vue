<template>
    <div>
    <b-list-group class="task pinned" v-if="pinned">
        <span class="pin-icon"><img src="/images/pinboard.png"/></span>
        <b-list-group-item class="d-flex justify-content-between">
            <b-form-checkbox
                :id="'checkbox-' + id"
                :checked="completedTask"
                name="completed"
                unchecked-value="false"
                @change="complete($event)"
            >
            </b-form-checkbox>
            <div class="d-flex w-100 justify-content-between">
                <p class="task-name">{{ name | properCase }}</p>
                <b-dropdown :id = "'dropdown-' + id" class="m-md-2">
                    <template #button-content>
                        <img src="/images/menu-icon.png"/>
                    </template>
                    <task-options-component :pinned="pinned" :description="description" :id="id"></task-options-component>
                </b-dropdown>
            </div>
        </b-list-group-item>
        <p class="mb-1 task-desc">{{ description }}</p>
    </b-list-group>
    <b-list-group class="task" v-else>
        <b-list-group-item class="d-flex justify-content-between ">
            <b-form-checkbox
                :id="'checkbox-' + id"
                :checked="completedTask"
                name="completed"
                unchecked-value="false"
                @change="complete($event)"
            >
            </b-form-checkbox>
            <div class="d-flex w-100 justify-content-between">
             <p class="task-name">{{ name | properCase }}</p>
            <b-dropdown :id = "'dropdown-' + id" class="m-md-2">
                <template #button-content>
                    <img src="/images/menu-icon.png"/>
                </template>
                <task-options-component :pinned="pinned" :description="description" :id="id"></task-options-component>
            </b-dropdown>
         </div>
     </b-list-group-item>
        <p class="mb-1 task-desc">{{ description }}</p>
    </b-list-group>
        <tasks-memo-component :description="description" :id="id"></tasks-memo-component>
    </div>
</template>

<script>
import {post} from '../helpers/api'
import Status from '../helpers/status'
import TaskOptionsComponent from "./TaskOptionsComponent";
import TasksMemoComponent from "./TasksMemoComponent";
export default {
name: "TasksListComponent",
    components: {TasksMemoComponent, TaskOptionsComponent},
    props: ['id','name', 'description', 'completed', 'pinned'],
    filters: {
        properCase(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    },

    data() {
        return {
            completedTask: this.completed ? true : false,
            form: {
                id: this.id
            },
            error: {},
            isProcessing: false,
            status: null,
        }
    },
    methods: {
        complete(e) {
            this.isProcessing = true;
            this.error = {};
            console.log(e);
            post('/api/tasks/complete', this.form)
                .then((response) => {
                    if (response) {
                        console.log(response);
                        Status.setSuccess('You have successfully logged in!');
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
.task .list-group-item{
    background-color: transparent;
    border: none;
    padding: 0 1.25rem;
}
.task-name{
    color: #ccd7e2;
}
span.pin-icon{
    position: relative;
    top: 25px;
    right: 10px;
}
span.pin-icon img {
    width: 21px;
    height: 20px;
}

.task-desc{
    margin-left: 8%;
    color: #6f757e;
}
</style>
