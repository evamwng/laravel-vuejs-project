<template>
<div id="tasks">
        <b-card no-body class="bg-transparent">
            <b-tabs pills card align="center">
                <b-tab title="Daily" active><b-card-text>
                    <DailyTasksComponent></DailyTasksComponent>
                </b-card-text></b-tab>
                <b-tab title="Weekly"><b-card-text>
                    <WeeklyTasksComponent></WeeklyTasksComponent>
                </b-card-text></b-tab>
            </b-tabs>
        </b-card>
</div>
</template>

<script>
import DailyTasksComponent from "../components/DailyTasksComponent";
import WeeklyTasksComponent from "../components/WeeklyTasksComponent";
export default {
name: "Tasks",
    components: {
        WeeklyTasksComponent,
    DailyTasksComponent
    }
}
</script>

<style scoped>

</style>
