<template>
    <div class="container">
        <div class="tasks-datepicker">
            <a @click="previous()" class="previous round"><img src="/images/path-left.png" /></a>
            <div class="tasks-date">
                <h2>{{startWeekDate}} - {{endWeekDate}}</h2>
                <p>{{startWeekDay}} - {{endWeekDay}}</p>
            </div>
            <a @click="next()" class="next round"><img src="/images/path-right.png" /></a>
        </div>
        <tasks-form-component></tasks-form-component>
        <tasks-list-component
            v-for="task in tasks"
            v-bind="task"
            :key="task.id"
        ></tasks-list-component>
    </div>
</template>

<script>
import TasksFormComponent from "./TasksFormComponent";
import TasksListComponent from "./TasksListComponent";
import {get, post} from "../helpers/api";
import Status from "../helpers/status";

export default {
name: "WeeklyTasksComponent",
    data(){
        return {
            form: {
                date: '',
                type: 'weekly',
                end_date: ''
            },
            targetDate: new Date(),
            targetEndDate: '',
            startWeekDate: this.getStartWeekDate(),
            endWeekDate: this.getEndWeekDate(),
            startWeekDay: this.getStartWeekDay(),
            endWeekDay: this.getEndWeekDay(),
            tasks: [],
            error: {},
            isProcessing: false,
            status: null,
        }
    },
    components: {
        TasksListComponent,
        TasksFormComponent
    },
    methods: {
        getStartWeekDate() {
            const current = this.targetDate ? this.targetDate : new Date();
            const month = current.toLocaleString('default', { month: 'long' });
            return `${current.getDate()}/${current.getMonth()}/${current.getFullYear()}`;
        },
        getEndWeekDate() {
            const current = this.targetEndDate ? this.targetEndDate : new Date();
            const numberOfDaysToAdd = 6;
            current.setDate(current.getDate() + numberOfDaysToAdd);
            this.targetEndDate = current;
            return `${current.getDate()}/${current.getMonth()}/${current.getFullYear()}`;
        },
        getStartWeekDay() {
            const current = this.targetDate ? this.targetDate : new Date();
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const day = days[current.getDay()];
            return `${day}`;
        },
        getEndWeekDay() {
            const current = this.targetEndDate ? this.targetEndDate : new Date();
            const numberOfDaysToAdd = 6;
            current.setDate(current.getDate() + numberOfDaysToAdd);
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const day = days[current.getDay()];
            return `${day}`;
        },
        next(){
            const numberOfDaysToAdd = 6;
            this.targetDate.setDate(this.targetDate.getDate() + numberOfDaysToAdd);
            this.startWeekDate = this.getStartWeekDate();
            this.endWeekDate = this.getEndWeekDate();
            this.startWeekDay = this.getStartWeekDay();
            this.endWeekDay = this.getEndWeekDay();
            this.getTasks();
        },
        previous(){
            const numberOfDaysToAdd = 6;
            this.targetDate.setDate(this.targetDate.getDate() - numberOfDaysToAdd);
            this.startWeekDate = this.getStartWeekDate();
            this.endWeekDate = this.getEndWeekDate();
            this.startWeekDay = this.getStartWeekDay();
            this.endWeekDay = this.getEndWeekDay();
            this.getTasks();
        },
        getTasks()
        {
            this.isProcessing = true;
            this.error = {};
            this.form.date = this.targetDate;
            this.form.end_date = this.targetEndDate;
            post('/api/tasks/search', this.form)
                .then((response) => {
                    if (response.data) {
                        console.log(response.data);
                        this.tasks = response.data;
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
        }
    },
    created() {
        this.getTasks();
    }
}
</script>

<style scoped>
.tasks-date{
    text-align: center;
}
.tasks-date h2{
    color: #ccd7e2;
}
.tasks-date p{
    color: #6f757e;
    font-weight: bold;
}
.tasks-datepicker{
    display: flex;
    justify-content: space-evenly;
}
.tasks-datepicker a {
    text-decoration: none;
    display: inline-block;
    padding: 8px 16px;
    color: black;
}
</style>
