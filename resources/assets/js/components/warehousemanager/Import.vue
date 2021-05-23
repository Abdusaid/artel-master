<template>
    <div id="import">

        <div class="row">
            <div class="col s11">
                <my-tab :firms="firms" :all="true" v-model="selectedFirm"></my-tab>
            </div>

            <div class="col s1">
                <button class="btn-floating waves-effect waves-light right blue lighten-1" @click.stop="showFilter = !showFilter"><i class="material-icons">schedule</i></button>
            </div>

        </div>

        <div class="row" v-show="showFilter">
            <div class="filter col s12">
                <div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Фильтр</span>
                        <div class="row">

                            <div class="col s11">
                                <div class="input-field col s2">
                                    <select v-model="isNew">
                                        <option value="2">Все</option>
                                        <option value="1">Новый</option>
                                        <option value="0">С Производства</option>
                                    </select>
                                    <label>Откуда</label>
                                </div>

                                <div class="input-field col s2">
                                    <select v-model="type">
                                        <option value="3">Все</option>
                                        <option value="1">Первичка</option>
                                        <option value="2">Вторичка</option>
                                        <option value="0">Гранула</option>
                                    </select>
                                    <label>Тип</label>
                                </div>

                                <div class="input-field col s2">
                                    <select v-model="status">
                                        <option value="4">Все</option>
                                        <option value="0">Белый</option>
                                        <option value="1">Зеленый</option>
                                        <option value="2">Желтый</option>
                                        <option value="3">Красный</option>
                                    </select>
                                    <label>Статус</label>
                                </div>

                                <div class="input-field col s2">
                                    <input ref="startDate" type="text" class="text" v-model.lazy="startDate"/>
                                    <label>Дата начала</label>
                                </div>

                                <div class="input-field col s2">
                                    <input ref="endDate" type="text" class="text" v-model.lazy="endDate"/>
                                    <label>Дата конца</label>
                                </div>

                                <div class="input-field col s2">
                                    <input type="text" v-model="search"/>
                                    <label>Поиск</label>
                                </div>
                            </div>

                            <div class="input-field col s1">
                                <button class="btn waves-effect waves-light blue lighten-2" @click="loadHistory(1)"><i class="material-icons">search</i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div :class="{ 'content-loading' : loadingTable }">
            <div class="row">
                <div class="col s3">
                    <div class="card">
                        <div class="card-content">
                            <span class="title blue lighten-2">Общее</span>
                            <table>
                                <tr>
                                    <th>Новый</th>
                                    <td>{{ sum.all_new }}</td>
                                </tr>
                                <tr>
                                    <th>С производства</th>
                                    <td>{{ sum.all }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Первичка</span>
                        <table>
                            <tr>
                                <th>Новый</th>
                                <td>{{ sum.pervichka_new }}</td>
                            </tr>
                            <tr>
                                <th>С производства</th>
                                <td>{{ sum.pervichka }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Вторичка</span>
                        <table>
                            <tr>
                                <th>Новый</th>
                                <td>{{ sum.vtorichka_new }}</td>
                            </tr>
                            <tr>
                                <th>С производства</th>
                                <td>{{ sum.vtorichka }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Гранула</span>
                        <table>
                            <tr>
                                <th>Новый</th>
                                <td>{{ sum.granula_new }}</td>
                            </tr>
                            <tr>
                                <th>С производства</th>
                                <td>{{ sum.granula }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
            </div>

            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table>
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Фирма</th>
                                <th>Тип</th>
                                <th>Количество</th>
                                <th>Новый</th>
                                <th>Контейнер</th>
                                <th>Примечание</th>
                                <th>Дата</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="his in history" class="lighten-5" :class="his.status==1 ? 'green' : his.status==2 ? 'yellow' : his.status==3 ? 'red' : ''" >
                                <td>{{ his.raw_name }}</td>
                                <td>{{ his.firm_name }}</td>
                                <td>{{ types[his.type] }}</td>
                                <td>{{ his.quantity }}</td>
                                <td><i class="material-icons">{{ his.isNew ? 'check' : 'close' }}</i></td>
                                <td>{{ his.container }}</td>
                                <td>{{ his.comment }}</td>
                                <td>{{ his.date }}</td>
                            </tr>
                            </tbody>
                        </table>

                        <pagination v-model="currentPage" :pages="pages"></pagination>
                    </div>
                </div>


            </div>
        </div>


    </div>
</template>

<style scoped>

    .tabs{
        margin-bottom: 20px;
    }
    .table-wrapper{
        padding: 5px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);;
    }

    table th, table td{
        padding: 5px;
    }


    .card .row{
        margin-bottom: 0;
    }
</style>
<script>
    import Tab from "../warehouseman/Tab.vue";
    import moment from "moment";
    import Pagination from "../warehouseman/Pagination.vue";
    import { socket } from "../mixins.js";

    export default {
        mixins: [ socket ],
        props: {
            firms: Array
        },
        components: {
            'my-tab': Tab,
            'pagination': Pagination
        },
        data: function(){
            return {
                selectedFirm: null,
                isNew: 2,
                type: 3,
                status: 4,
                startDate: null,
                endDate: null,
                search: '',
                showFilter: false,

                types: {
                    1: 'Первичка',
                    2: 'Вторичка',
                    0: 'Гранула'
                },

                history: [
                ],

                sum: {
                    all_new: 0,
                    all: 0,
                    pervichka_new: 0,
                    pervichka: 0,
                    vtorichka_new: 0,
                    vtorichka: 0,
                    granula_new: 0,
                    granula: 0

                },
                currentPage: 1,
                pages: 10,
                loadingTable: false
            }
        },
        watch: {
            selectedFirm: function(){
                this.loadHistory(1);
            },

            currentPage: function(){
                this.loadHistory(this.currentPage);
            }
        },
        methods: {
            loadHistory: function(page){
                var self = this;
                self.loadingTable = true;
                axios.get('/api/import', {
                    params: {
                        'firm_id': self.selectedFirm==0 ? null : self.selectedFirm,
                        'is_new': self.isNew==2 ? null : self.isNew,
                        'type': self.type==3 ? null : self.type,
                        'status': self.status==4 ? null : self.status,
                        'start_date': self.startDate,
                        'end_date': self.endDate,
                        'search': self.search,
                        'paginate': 1,
                        'page': page
                    }
                }).then(function(response){
                    self.sum = response.data.sum;
                    self.history = response.data.data;
                    self.currentPage = page;
                    self.pages = response.data.meta.last_page;
                    self.loadingTable = false;
                    NProgress.done();
                }).catch(function(error){
                    console.log(error);
                });
            },

            listen: function(data, type){
                if(type=='import' && (this.selectedFirm==0 || this.selectedFirm==data.import.firm_id)
                    && moment(moment(data.import.date, "DD.MM.YY, HH:mm")).isSameOrAfter(this.startDate, 'day')
                    && moment(moment(data.import.date, "DD.MM.YY, HH:mm")).isSameOrBefore(this.endDate, 'day')){
                    this.loadHistory(this.currentPage);
                }
            }
        },

        mounted: function(){
            var selects = M.FormSelect.init(document.querySelectorAll("select"));
            var date = new Date();
            var startDate = M.Datepicker.init(this.$refs.startDate, {
                defaultDate: new Date(date.getFullYear(), date.getMonth(), 1),
                setDefaultDate: true
            });
            var endDate = M.Datepicker.init(this.$refs.endDate, {
                defaultDate: date,
                setDefaultDate: true
            });

            var self = this;

            self.connectSocket();
        }

    }
</script>
