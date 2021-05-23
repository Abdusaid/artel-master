<template>
    <div class="my-import-history">
        <table id="my-import-history-table" class="bordered highlight" :class="{ 'content-loading' : loadingHistory }">
            <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Кол-во (кг)</th>
                    <th>Дата</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
            <tr v-for="(his, index) in history" @click="choose(index)" :class="{'green lighten-5' : (his.status==1), 'lime lighten-5' : (his.status==2), 'red lighten-5' : (his.status==3)}">
                <td>{{ his.raw_name }} ({{ types[his.type] }})</td>
                <td>{{ his.quantity }}</td>
                <td>{{ his.date }}</td>
                <td>
                    <a v-if="isToday(his.date)" class="waves-effect waves-light blue-text" @click.stop="edit(index)"><i class="material-icons">edit</i></a>
                    <a v-if="isToday(his.date)" class="waves-effect waves-light red-text" @click.stop="prepareDelete(index)"><i class="material-icons">delete</i></a>
                    <a v-if="his.type==1 && his.isNew" class="waves-effect waves-light grey-text right hover-icon" @click.stop="downloadImport(index)"><i class="material-icons">cloud_download</i></a>
                </td>

            </tr>

            </tbody>
        </table>
        <my-pagination :pages="pages" v-model="currentPage"></my-pagination>

        <div v-if="history.length>0" id="modal-import-delete" ref="modalImportDelete" class="modal">
            <div class="modal-content">
                <h4>Удалить?</h4>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col s4 offset-s1">
                        <button class="btn waves-effect waves-light red" @click="confirmDelete">Да</button>
                    </div>

                    <div class="col s4 offset-s2">
                        <button class="btn waves-effect waves-light modal-close">Нет</button>
                    </div>
                </div>

            </div>
        </div>

        <div v-if="history.length>0" id="modal-import-show" ref="modalImportShow" class="modal" >
            <div class="modal-content">
                <h5 class="center-align">{{ title }} <i class="material-icons right modal-close">close</i> </h5>
                <table class="bordered">
                    <tr>
                        <th>Наименование</th>
                        <td>{{ history[selectedIndex].raw_name }}</td>
                    </tr>
                    <tr>
                        <th>Фирма</th>
                        <td>{{ history[selectedIndex].firm_name }}</td>
                    </tr>
                    <tr>
                        <th>Количество (кг)</th>
                        <td>{{ history[selectedIndex].quantity }}</td>
                    </tr>
                    <tr>
                        <th>Тип</th>
                        <td>{{ types[history[selectedIndex].type] }}</td>
                    </tr>

                    <tr v-if="history[selectedIndex].isNew!=null">
                        <th>Новый</th>
                        <td><i class="material-icons">{{ history[selectedIndex].isNew ? "check" : "close"}}</i></td>
                    </tr>

                    <tr v-if="history[selectedIndex].seria">
                        <th>Номер серии</th>
                        <td>{{ history[selectedIndex].seria }}</td>
                    </tr>

                    <tr v-if="history[selectedIndex].supplier">
                        <th>Поставщик</th>
                        <td>{{ history[selectedIndex].supplier }}</td>
                    </tr>

                    <tr v-if="history[selectedIndex].container">
                        <th>Контейнер</th>
                        <td>{{ history[selectedIndex].container }}</td>
                    </tr>

                    <tr v-if="history[selectedIndex].comment">
                        <th>Примечание</th>
                        <td>{{ history[selectedIndex].comment }}</td>
                    </tr>


                    <tr v-if="history[selectedIndex].to">
                        <th>Куда</th>
                        <td>{{ tos[history[selectedIndex].to] }}</td>
                    </tr>

                    <tr>
                        <th>Дата</th>
                        <td>{{ history[selectedIndex].date }}</td>
                    </tr>

                </table>
            </div>
        </div>
    </div>
</template>

<style scoped>
    tr{
        cursor: pointer;
    }

    tbody tr:first-child{

    }
    tr:hover{
        filter: brightness(96%);
    }
    th, td{
        padding: 3px;
    }
    .modal h4{
        text-align: center;
        margin: 0;
    }

    .modal .modal-footer{
        text-align: center;
    }

    .modal .modal-footer button{
        width: 100%;
    }

</style>

<script>
    import Pagination from "./Pagination.vue";
    import {types} from "../mixins.js";
    import moment from "moment";

    export default {
        props: {
            url: {
                type: String,
                required: true,
            },

            status: Number,
            title: String
        },
        mixins: [ types ],
        components: {
            'my-pagination': Pagination
        },
        data: function(){
            return {
                history: [

                ],

                loadingHistory: false,
                selectedIndex: 0,
                currentPage: 1,
                pages: 0
            }
        },


        methods: {

            isToday: function(date){
                return moment(date, "DD.MM.YY, HH:mm").isAfter(moment().subtract(10, "days"));
            },

            choose: function(index){
                this.selectedIndex = index;
                var self = this;
                var modalShow = M.Modal.init(this.$refs.modalImportShow, {
                    onCloseEnd: function(){
                        self.selectedIndex = 0;
                    }
                });
                modalShow.open();
            },

            edit: function(index){
                this.$emit('edit', this.history[index]);
            },

            update: function(updatedImport){
                var index = this.history.findIndex(item => item.id==updatedImport.id);
                console.log(index);
                if(index != -1){
                    this.history.splice(index, 1, updatedImport);
                }
            },

            destroy: function(id){
                var self = this;
                return axios.delete(self.url+'/'+id);
            },
            prepareDelete: function(index){
                this.selectedIndex = index;
                var self = this;
                var modalShow = M.Modal.init(this.$refs.modalImportDelete, {
                    onCloseEnd: function(){
                        self.selectedIndex = 0;
                    }
                });
                modalShow.open();
            },

            confirmDelete: function(){
                var self = this;
                var modalShow = M.Modal.getInstance(this.$refs.modalImportDelete);
                modalShow.close();
                this.destroy(self.history[self.selectedIndex].id)
                    .then(function(response){
                        self.$emit('deleted', response.data.data);
                        return self.loadHistory(self.currentPage);
                    })
                    .catch(error => {
                        if(error.response.data.code){
                            var toastHTML = '<span>' + error.response.data.message + '</span>';
                            M.toast({
                                html: toastHTML,
                                classes: 'red darken-1'
                            });
                        }
                    });
            },

            loadHistory: function(page){
                var self = this;
                self.loadingHistory = true;
                page = page ? page : self.currentPage;
                return axios.get(self.url, {
                    params: {
                        paginate: 1,
                        page: page,
                        status: self.status
                    }
                }).then(function(response){
                        self.history = response.data.data;
                        self.pages = response.data.meta.last_page;
                        self.loadingHistory = false;
                    });
            },

            downloadImport: function(index){
                axios.get('/api/notice/word?import_id='+this.history[index].id)
                    .then(response => {
                        window.location = "/api/notice/word?import_id="+this.history[index].id;
                    });
            }
        },

        watch: {
            currentPage: function(){
                this.loadHistory(this.currentPage);
            }
        },

        mounted: function(){
            this.loadHistory(this.currentPage);
        }
    }
</script>
