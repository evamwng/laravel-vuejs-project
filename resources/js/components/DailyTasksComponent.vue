<template>
    <div class="container">
     <div class="tasks-datepicker">
         <a @click="previous()" class="previous round"><img src="/images/path-left.png" /></a>
         <div class="tasks-date">
             <h2 v-model="currentDay">{{currentDay}}</h2>
             <p v-model="currentDate">{{currentDate}}</p>
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
    name: "DailyTasksComponents",
    data(){
        return {
            form: {
                date: '',
                type: 'daily'
            },
            targetDate: new Date(),
            currentDate: this.getcurrentDate(),
            currentDay: this.getcurrentDay(),
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
        getcurrentDate() {
            const current = this.targetDate ? this.targetDate : new Date();
            const month = current.toLocaleString('default', { month: 'long' });
            return `${month} ${current.getDate()}, ${current.getFullYear()}`;
        },
        getcurrentDay() {
            const current = this.targetDate ? this.targetDate : new Date();
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
            const day = days[current.getDay()];
            return `${day}`;
        },
        next(){
            const numberOfDaysToAdd = 1;
            this.targetDate.setDate(this.targetDate.getDate() + numberOfDaysToAdd);
            this.currentDate = this.getcurrentDate();
            this.currentDay = this.getcurrentDay();
            this.getTasks();
        },
        previous(){
            const numberOfDaysToAdd = 1;
            this.targetDate.setDate(this.targetDate.getDate() - numberOfDaysToAdd);
            this.currentDate = this.getcurrentDate();
            this.currentDay = this.getcurrentDay();
            this.getTasks();
        },
        getTasks()
        {
            this.isProcessing = true;
            this.error = {};
            this.form.date = this.targetDate;
            console.log(this.form.date);
            post('/api/tasks/search', this.form)
                .then((response) => {
                    if (response.data) {
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
