<template>
     <div>
         <b-dropdown-item @click="pin($event)"><img :src="pinIcon"/> {{pinText}}</b-dropdown-item>
         <b-dropdown-item v-b-modal="'memo-modal' + id"><img :src="memoIcon"/> {{ memoText }} memo</b-dropdown-item>
         <b-dropdown-item @click="detroy($event)"><img src="/images/bin.png"> Delete</b-dropdown-item>
     </div>
</template>

<script>
import {destroy,post} from "../helpers/api";
import Status from "../helpers/status";
export default {
    name: "TaskOptionsComponent",
    props: ['id', 'pinned','description'],

    data(){
        return {
            pinIcon : this.pinned ? '/images/unpin.png' : '/images/pin.png',
            pinText : this.pinned ? 'Unpin from top' : 'Pin to the top',
            memoIcon : this.description ? '/images/edit.png' :'/images/add.png',
            memoText : this.description ? 'Edit' : 'Add a',
            pinnedTask: this.pinned ? true : false,
            form: {
                id: this.id
            },
            error: {},
            isProcessing: false,
            status: null,
        }
    },
    methods: {
        pin(e) {
            this.isProcessing = true;
            this.error = {};
            console.log(e);
            post('/api/tasks/pin', this.form)
                .then((response) => {
                    if (response) {
                        Status.setSuccess('You have successfully logged in!');
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
        detroy(e) {
            this.isProcessing = true;
            this.error = {};
            destroy('/api/tasks/delete/' + this.form.id)
                .then((response) => {
                    if (response) {
                        Status.setSuccess('You have successfully logged in!');
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
