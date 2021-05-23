<template>
    <div class="card" :class="{ 'content-loading' : loading }">
        <div class="card-content">
            <table>
                <tr>
                    <th>Сырье</th>
                    <td>{{ request.raw_name }}</td>
                </tr>
                <tr>
                    <th>Фирма</th>
                    <td>{{ request.firm_name }}</td>
                </tr>
                <tr>
                    <th>Кол-во (кг)</th>
                    <td>{{ request.quantity }}</td>
                </tr>
                <tr>
                    <th>Тип</th>
                    <td>{{ types[request.type] }}</td>
                </tr>
                <tr>
                    <th>Кому</th>
                    <td>{{ tos[request.to] }}</td>
                </tr>
                <tr>
                    <th>Дата</th>
                    <td>{{ request.date }}</td>
                </tr>
            </table>

            <div class="row">
                <div class="col s6 center-align">
                    <button class="btn waves-effect waves-light" @click="confirm">Подтвердить</button>
                </div>
                <div class="col s6 center-align">
                    <button class="btn waves-effect waves-light red" @dblclick="cancel">Отменить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    td, th{
        padding: 4px;
    }
    table{
        margin-bottom: 20px;
    }
</style>

<script>
    import { types } from "../mixins.js";

    export default {
        mixins: [ types ],
        props:{
            request: Object
        },

        data: function(){
            return {
                loading: false
            }
        },

        methods: {
            confirm: function(){
                var self = this;
                self.loading = true;
                return axios.put('/api/request/'+self.request.id+'/confirm')
                            .then(function(response){
                                self.$emit('confirmed', response.data.data);
                                self.loading = false;
                            });
            },

            cancel: function(){
                var self = this;
                self.loading = true;
                return axios.delete('/api/export/'+self.request.id)
                            .then(function(response){
                                self.$emit('deleted', response.data.data);
                                self.loading = false;
                            });
            }
        }
    }
</script>