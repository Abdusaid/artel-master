<template>
    <div class="row">
        <div class="col s12">
            <div class="input-field col s3">
                <select  class="browser-default" v-model="importFirm">

                    <option :value="null">Фирма</option>
                    <option v-for="(firm, index) in firms" :value="firm.id" :key="index">{{firm.name}}</option>

                </select>
            </div>
            <div class="input-field col s3">
                <select  class="browser-default" v-model="importStatus">
                    <option :value="null">Статус</option>
                    <option v-for="(status, index) in statuses" :value="status.status" :key="index">{{status.name}}</option>
                </select>
            </div>
            <div class="input-field col s3">
                <select  class="browser-default" v-model="importType">
                    <option :value="null">Тип</option>
                    <option v-for="(type, index) in types" :value="type.type" :key="index">{{type.name}}</option>
                </select>
            </div>
            <div class="input-field col s3">
                <i class="material-icons prefix">search</i>
                <input class="search" type="text" placeholder="Поиск" v-model="importSearch" />
            </div>
        </div>
        <div class="col s12">
            <div class="">
                <table id="" class="highlight" :class="{ 'content-loading' : importHistoryLoading }">
                    <thead>
                    <tr>
                        <th>Наименование</th>
                        <th>Фирма</th>
                        <th>Номер партии</th>
                        <th>Примечание</th>
                        <th>Кол-во (кг)</th>
                        <th>Тип</th>
                        <th>Поставщик</th>
                        <th>Дата</th>
                    </tr>
                    </thead>

                    <tbody class="">
                        <tr v-for="(history, index) in importHistory" :class="{'green lighten-3' : (history.status==1), 'yellow lighten-2' : (history.status==2), 'red lighten-2' : (history.status==3)}" @click="chooseImportHistory(index)" :key='index'>
                            <td>{{ history.raw_name }}</td>
                            <td>{{ history.firm}}</td>
                            <td>{{ history.seria }}</td>
                            <td>{{ history.comment }}</td>
                            <td>{{ history.quantity }}</td>
                            <td>{{ history.type}}</td>
                            <td>{{ history.supplier}}</td>
                            <td>{{ history.date }}</td>
                        </tr>
                    </tbody>
                </table>
                <pagination v-model="currentPage" :pages="pages"></pagination>

                <div id="modal-import-show" ref="modalImportShow" class="modal">
                    <div class="modal-content">
                        <h5 class="center-align">Приход <i class="material-icons right modal-close">close</i> </h5>
                        <table class="bordered">
                            <tr>
                                <th>Наименование</th>
                                <td>{{ selectedImport.raw_name }}</td>
                            </tr>
                            <tr>
                                <th>Количество (кг)</th>
                                <td>{{ selectedImport.quantity }}</td>
                            </tr>
                            <tr>
                                <th>Тип</th>
                                <td>{{ rawTypes[selectedImport.type] }}</td>
                            </tr>

                            <tr>
                                <th>Новый</th>
                                <td><i class="material-icons">{{ selectedImport.isNew ? "check" : "close"}}</i></td>
                            </tr>

                            <tr v-if="selectedImport.type == 1 && selectedImport.isNew">
                                <th>Номер серии</th>
                                <td>{{ selectedImport.seria }}</td>
                            </tr>

                            <tr v-if="selectedImport.type == 1 && selectedImport.isNew">
                                <th>Поставщик</th>
                                <td>{{ selectedImport.supplier }}</td>
                            </tr>

                            <tr>
                                <th>Дата</th>
                                <td>{{ selectedImport.date }}</td>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="input-field col s12">
                                <i class="material-icons prefix">mode_edit</i>
                                <textarea id="comment" class="materialize-textarea" v-model="checkedImportComment"></textarea>
                                <label for="comment" :class="{active : checkedImportComment}">Примечание</label>
                            </div>
                        </div>
                        <div class="row">
                                <div class="input-field col s3">
                                    <p>
                                        <label>
                                            <input class="with-gap" type="radio" value="0" v-model="checkedImportStatus"/>
                                            <span tex>White</span>
                                        </label>
                                    </p>
                                </div>
                                <div class="input-field col s3">
                                        <p>
                                        <label>
                                            <input class="with-gap" type="radio" value="1" v-model="checkedImportStatus"/>
                                            <span class="green-text text-darken-2">Green</span>
                                        </label>
                                        </p>
                                    </div>
                                <div class="input-field col s3">
                                    <p>
                                        <label>
                                            <input class="with-gap"  type="radio" value="2" v-model="checkedImportStatus"/>
                                            <span class="yellow-text text-darken-3">Yellow</span>
                                        </label>
                                    </p>
                                </div>
                                <div class="input-field col s3">
                                    <p>
                                        <label>
                                            <input class="with-gap"  type="radio" value="3" v-model="checkedImportStatus"/>
                                            <span class="red-text">Red</span>
                                        </label>
                                    </p>
                                </div>
                                <button class="btn-small waves-effect waves-light right" type="button" @click="changeStatus" >Подтвердить
                                    <i class="material-icons right">send</i>
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
    td, th{
        border-radius: 0;
    }
</style>
<script>
    import Pagination from "../warehouseman/Pagination.vue";
    import { socket } from "../mixins.js";

    export default {
        mixins: [ socket ],
        components: {
            'pagination': Pagination
        },
        data: function(){
            return {
                errorStatus: 1,

                firms: [
                ],
                statuses: [
                    { status: 0, name: "Белый" },
                    { status: 1, name: "Зеленный"},
                    { status: 2, name: "Желтый"},
                    { status: 3, name: "Красный"}
                ],
                importFirm: null,
                importStatus: null,
                importType: null,
                importSearch: '',
                importHistory: [
                ],
                selectedImport: {
                    id: 0,
                    raw_name: null,
                    firm: null,
                    status: 0,
                    quantity: 0,
                    type: 0,
                    isNew: false,
                    seriaNumber: null,
                    supplier: null,
                    date: null
                },
                types: [
                    { type: 1, name: "Первичка"},
                    { type: 2, name: "Вторичка"},
                    { type: 0, name: "Гранула"},
                ],
                rawTypes: { 1: "Первичка", 2: "Вторичка" , 0: "Гранула" },

                checkedImportStatus: 0,
                checkedImportComment: null,
                importHistoryLoading: false,

                pages: 1,
                currentPage: 0,
                chosenIndex: 0,
            }
        },
        computed:{
        },
        methods: {
            chooseImportHistory: function(index){
                this.chosenIndex = index;
                this.selectedImport = this.importHistory[index];
                this.checkedImportStatus = this.importHistory[index].status;
                this.checkedImportComment = this.importHistory[index].comment;
                var modalShow = M.Modal.getInstance(this.$refs.modalImportShow);
                modalShow.open();

            },
            dismisToast: function(){
                // Get toast DOM Element, get instance, then call dismiss function
                var toastElement = document.querySelector('.toast');
                var toastInstance = M.Toast.getInstance(toastElement);
                M.Toast.dismissAll();
            },
            changeStatus: function(){
                var self = this;
                var id = this.selectedImport.id;
                var status = this.checkedImportStatus;
                var comment = this.checkedImportComment;
                self.importHistoryLoading = true;
                //ajax
                axios.put('/api/laboratorian/import/'+id, {
                    status: status,
                    comment: comment
                }).then(function(response){
                    self.changePage(self.currentPage);
                    var toastHTML = '<span>Импорт статус успешно изменен!</span>';
                    M.toast({
                        html: toastHTML,
                        classes: 'rounded'
                    });

                }).catch(function(error){
                    if(error.response.data.code){
                        var toastHTML = '<span>' + error.response.data.message + '</span>';
                        M.toast({
                            html: toastHTML,
                            classes: 'rounded red darken-1'
                        });
                    }
                    self.errorStatus = 0;
                    console.log(error);

                    self.importHistoryLoading = false;

                });
                var modalShow = M.Modal.getInstance(this.$refs.modalImportShow);
                modalShow.close();
            },

            changePage: function(page){
                var self = this;
                var url = '';
                self.errorStatus = 2;
                self.importHistoryLoading = true;
                if(self.importFirm != null){
                    url = url+'&firm_id='+self.importFirm;
                }
                if(self.importStatus != null){
                    url = url +'&status='+self.importStatus;
                }
                if(self.importType != null){
                    url = url +'&type='+self.importType;
                }
                if(self.importSearch != ''){
                    url = url +'&search='+self.importSearch;
                }
                axios.get('/api/laboratorian/import?page='+page+url)
                    .then(function(response){
                        self.importHistory = response.data.data;
                        self.currentPage = response.data.meta.current_page;
                        self.pages = response.data.meta.last_page;
                        self.errorStatus = 1;
                        self.importHistoryLoading = false;
                    }).catch(function(error){
                        self.errorStatus = 0;
                    });
            },
            getFirms: function(){
                var self = this;
                self.errorStatus = 2;
                self.importHistoryLoading = true;
                axios.get('/api/firms').then(function(response){
                    self.firms = response.data.data;
                    self.importHistoryLoading = false;
                }).catch(function(error){
                    self.errorStatus = 0;
                });
            },

            listen: function(data, type){
                if(type=='import')
                    this.changePage(this.currentPage);
            }
        },
        watch: {
            currentPage: function(){
                this.changePage(this.currentPage);
            },

            importFirm: function(){
                this.changePage(1);
            },
            importStatus: function(){
                this.changePage(1);
            },
            importSearch: _.debounce(function(){
                    this.changePage(1);
                }, 500),
            importType: function(){
                this.changePage(1);
            }
        },
        mounted: function(){
            M.AutoInit();
            var elems = document.querySelectorAll('.dropdown-trigger');
            this.getFirms();
            this.changePage(1);
            M.Dropdown.init(elems, {
                coverTrigger: false,
                alignment: 'right'
            });

            this.connectSocket();

        }
    }
</script>
