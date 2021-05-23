<template>
    <div class="my-add card">
        <div class="progress" v-if="loading">
            <div class="indeterminate"></div>
        </div>
        <div class="card-content">
            <slot></slot>

            <button class="btn btn-small waves-effect waves-light" :class="{ 'disabled' : invalid } " @click="store">{{ isChange ? "Изменить" : "Добавить" }}
            </button>

            <button v-if="isChange" class="btn btn-small waves-effect waves-light red" type="button" @click="cancelEdit">Отменить
            </button>
            <span v-show="false" ref="activate" class="activator"></span>
        </div>
        <div class="card-reveal" :class="isSuccess ? 'green lighten-5' : 'red lighten-5'">
            <div v-if="isSuccess" class="export-success">
                <slot name="success"></slot>

                <div class="card-action">
                    <button class="btn btn-small waves-effect waves-light" @click="confirm(1)">OK</button>
                </div>
            </div>

            <div class="export-fail" v-else>
                <p class="title center-align">Ошибка!</p>
                <p>{{ error }}</p>
                <div class="card-action">
                    <button class="btn btn-small waves-effect waves-light" @click="confirm(0)">OK</button>
                </div>
            </div>

            <span v-show="false" ref="close" class="card-title"></span>
        </div>
    </div>
</template>

<style scoped>
    .my-add{
        padding: 10px;
        box-shadow: 1px 1px 5px #2196f3, -1px 1px 5px #2196f3;
        text-align: center;
        position: relative;
    }

    .my-add .card-content{
        padding: 5px;
    }

    .my-add .card-reveal{
        padding: 10px;
    }


    .my-add .card-content p{
        text-align: left;
    }

    .card .card-content .my-checkbox{
        margin-bottom: 1rem;
    }

    .export-fail, .export-success{
        display: flex;
        flex-direction: column;
        height: 100%;
        justify-content: center;
    }

    .export-fail p.title{
        font-size: 120%;
        font-weight: bold;
        margin: 0;
    }

    th, td {
        padding: 3px;
    }

    .my-input-field{
        margin-bottom: 1em;
    }

    .progress{
        position: absolute;
        left: 0;
        top: 0;
        right: 0;
        margin-top: 0px;
    }

</style>


<script>

    import {types} from "../mixins";

    export default {
        mixins: [
            types
        ],
        props: {
            invalid: Boolean,
            isSuccess: Boolean,
            isChange: {
                type: Boolean,
                default: false
            },
            error: String,
        },

        data: function(){
            return {
                loading: false
            }
        },

        methods: {
            activate: function(){
                this.$refs.activate.click();
            },

            close: function(){
                this.$refs.close.click();
            },

            cancelEdit: function(){
                this.$emit('canceled');
            },

            store: function(){
                if(this.isChange){
                    this.$emit("edit");
                }else{
                    this.$emit("store");
                }

            },

            confirm: function(isSuccess){
                this.$emit("confirm", isSuccess);
            }
        }
    }
</script>
