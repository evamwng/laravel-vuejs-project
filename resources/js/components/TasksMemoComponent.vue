<template>
    <div>
        <b-modal
            :id="'memo-modal' + id"
            ref="modal"
            :title="modalTitle"
            @show="resetModal"
            @hidden="resetModal"
        >
            <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                    label-for="textarea"
                    invalid-feedback="Description is required"
                    :state="nameState"
                >
                    <b-form-textarea
                        id="textarea"
                        v-model="form.description"
                        placeholder="Enter task memo"
                        rows="3"
                        max-rows="6"
                        :state="nameState"
                        required
                    ></b-form-textarea>
                </b-form-group>
            </form>
            <template #modal-footer>
                <div class="w-100">
                    <b-button
                        variant="primary"
                        size="md"
                        @click="handleOk"
                    >
                        {{ modalTitle | properCase }}
                    </b-button>
                    <b-button
                        variant="secondary"
                        size="md"
                        @click="$bvModal.hide('memo-modal' + id)"
                    >
                        Cancel
                    </b-button>
                </div>
            </template>
        </b-modal>
    </div>
</template>

<script>

import {post} from "../helpers/api";
import Status from "../helpers/status";
export default {
name: "TasksMemoComponent",
    props: ['id', 'description'],
    filters: {
        properCase(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
    },
    data() {
        return {
            nameState: null,
            modalTitle: this.description ? 'EDIT MEMO' : 'ADD A MEMO',
            form: {
                id: this.id,
                description: this.description,
            },
            error: {},
            isProcessing: false,
            status: null,
        }
    },
    methods: {
        checkFormValidity() {
            const valid = this.$refs.form.checkValidity()
            this.nameState = valid
            return valid
        },
        resetModal() {
            this.name = ''
            this.nameState = null
        },
        handleOk(bvModalEvt) {
            // Prevent modal from closing
            bvModalEvt.preventDefault()
            // Trigger submit handler
            this.handleSubmit()
        },
        handleSubmit() {
            // Exit when the form isn't valid
            if (!this.checkFormValidity()) {
                return
            }
            this.isProcessing = true;
            this.error = {};
            console.log(this.form);
            post('/api/tasks/update', this.form)
                .then((response) => {
                    if (response) {
                        Status.setSuccess('You have successfully logged in!');
                        this.$nextTick(() => {
                            this.$bvModal.hide('memo-modal' + id)
                        })
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
        }
    }
}
</script>

<style scoped>

</style>
