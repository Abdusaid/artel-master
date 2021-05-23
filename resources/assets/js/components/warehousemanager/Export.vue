<template>
    <div id="export">
        <div class="row">
            <div class="col s11">
                <my-tab :firms="firms" :all="true" v-model="selectedFirm"></my-tab>
            </div>

            <div class="col s1">
                <button class="btn-floating waves-effect waves-light right blue lighten-1" @click.stop="showFilter = !showFilter"><i class="material-icons">schedule</i></button>
            </div>
        </div>

        <div class="row" v-show="showFilter">
            <div class="filter col s12" >
                <div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Фильтр</span>
                        <div class="row">
                            <div class="input-field col s2">
                                <select v-model="to">
                                    <option value="0">Все</option>
                                    <option value="-1">На производство</option>
                                    <option value="-2">На гранулятор</option>
                                    <option value="-3">По заводам</option>
                                    <option value="-4">Возврат</option>
                                </select>
                                <label>Куда</label>
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
                                <input ref="startDate" type="text" class="text" v-model.lazy="startDate"/>
                                <label>Дата начала</label>
                            </div>

                            <div class="input-field col s2">
                                <input ref="endDate" type="text" class="text" v-model.lazy="endDate"/>
                                <label>Дата конца</label>
                            </div>

                            <div class="input-field col s3">
                                <input type="text" v-model="search"/>
                                <label>Поиск</label>
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
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Общее</span>
                        <table>
                            <tr>
                                <th>На производство</th>
                                <td>{{ sum.all_pr }}</td>
                            </tr>
                            <tr>
                                <th>На гранулятор</th>
                                <td>{{ sum.all_gran }}</td>
                            </tr>
                            <tr>
                                <th>По заводам</th>
                                <td>{{ sum.all_factory }}</td>
                            </tr>
                            <tr>
                                <th>Возврат</th>
                                <td>{{ sum.all_back }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Первичка</span>
                        <table>
                            <tr>
                                <th>На производство</th>
                                <td>{{ sum.pervichka_pr }}</td>
                            </tr>
                            <tr>
                                <th>На гранулятор</th>
                                <td>{{ sum.pervichka_gran }}</td>
                            </tr>
                            <tr>
                                <th>По заводам</th>
                                <td>{{ sum.pervichka_factory }}</td>
                            </tr>

                            <tr>
                                <th>Возврат</th>
                                <td>{{ sum.pervichka_back }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Вторичка</span>
                        <table>
                            <tr>
                                <th>На производство</th>
                                <td>{{ sum.vtorichka_pr }}</td>
                            </tr>
                            <tr>
                                <th>На гранулятор</th>
                                <td>{{ sum.vtorichka_gran }}</td>
                            </tr>
                            <tr>
                                <th>По заводам</th>
                                <td>{{ sum.vtorichka_factory }}</td>
                            </tr>
                            <tr>
                                <th>Возврат</th>
                                <td>{{ sum.vtorichka_back }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
                <div class="col s3"><div class="card">
                    <div class="card-content">
                        <span class="title blue lighten-2">Гранула</span>
                        <table>
                            <tr>
                                <th>На производство</th>
                                <td>{{ sum.granula_pr }}</td>
                            </tr>
                            <tr>
                                <th>На гранулятор</th>
                                <td>{{ sum.granula_gran }}</td>
                            </tr>
                            <tr>
                                <th>По заводам</th>
                                <td>{{ sum.granula_factory }}</td>
                            </tr>

                            <tr>
                                <th>Возврат</th>
                                <td>{{ sum.granula_back }}</td>
                            </tr>
                        </table>
                    </div>
                </div></div>
            </div>

            <div class="row">
                <div class="col s12">
                    <div class="table-wrapper">
                        <table class="striped">
                            <thead>
                            <tr>
                                <th>Наименование</th>
                                <th>Фирма</th>
                                <th>Тип</th>
                                <th>Количество</th>
                                <th>Куда</th>
                                <th>Примечание</th>
                                <th>Дата</th>
                            </tr>
                            </thead>

                            <tbody>
                            <tr v-for="his in history" >
                                <td>{{ his.raw_name }}</td>
                                <td>{{ his.firm_name }}</td>
                                <td>{{ types[his.type] }}</td>
                                <td>{{ his.quantity }}</td>
                                <td>{{ tos[his.to] }}</td>
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

    .card{
        position: relative;
    }

    .card span.title{
        position: absolute;
        top: 0;
        left: 10px;
        transform: translateY(-50%);
        padding: 5px;
        border-radius: 10px;
        color: white;
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
                to: 0,
                type: 3,
                startDate: null,
                endDate: null,
                search: '',
                showFilter: false,

                types: {
                    1: 'Первичка',
                    2: 'Вторичка',
                    0: 'Гранула'
                },

                tos:{
                    '-1': 'На производство',
                    '-2': 'На гранулятор',
                    '-3': 'По заводам',
                    '-4': 'Возврат'
                },

                history: [],

                sum: {
                    all_pr: 0,
                    all_gran: 0,
                    all_factory: 0,
                    all_back: 0,
                    pervichka_pr: 0,
                    pervichka_gran: 0,
                    pervichka_factory: 0,
                    pervichka_back: 0,
                    vtorichka_pr: 0,
                    vtorichka_gran: 0,
                    vtorichka_factory: 0,
                    vtorichka_back: 0,
                    granula_pr: 0,
                    granula_gran: 0,
                    granula_factory: 0,
                    granula_back: 0,
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
                axios.get('/api/export', {
                    params: {
                        'firm_id': self.selectedFirm==0 ? null : self.selectedFirm,
                        'to': self.to==0 ? null : self.to,
                        'type': self.type==3 ? null : self.type,
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
                if(type=='export' && (this.selectedFirm==0 || this.selectedFirm==data.export.firm_id)
                    && moment(moment(data.export.date, "DD.MM.YY, HH:mm")).isSameOrAfter(this.startDate, 'day')
                    && moment(moment(data.export.date, "DD.MM.YY, HH:mm")).isSameOrBefore(this.endDate, 'day')){
                    this.loadHistory(this.currentPage);
                }
            }
        },


        mounted: function(){
            var self = this;
            var selects = M.FormSelect.init(document.querySelectorAll("select"));
            var date = new Date();
            var sDate = M.Datepicker.init(this.$refs.startDate, {
                defaultDate: new Date(date.getFullYear(), date.getMonth(), 1),
                setDefaultDate: true,

            });
            var eDate = M.Datepicker.init(this.$refs.endDate, {
                defaultDate: date,
                setDefaultDate: true,

            });

            self.connectSocket();

        }
    }
</script>