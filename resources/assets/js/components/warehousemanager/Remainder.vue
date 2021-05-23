<template>
    <div id="remainder" @click="showDate = false">

        <div class="row">
            <div class="col s11">
                <my-tab :firms="firms" v-model="selectedFirm"></my-tab>
            </div>

            <div class="col s1">
                <button class="btn-floating waves-effect waves-light right blue lighten-1" @click.stop="showDate = !showDate"><i class="material-icons">schedule</i></button>
            </div>
        </div>

        <div class="row-filter" >
            <div class="filter" v-show="showDate" @click.stop>
                <div class="input-field">
                    <input ref="startDate" type="text" class="datepicker" v-model.lazy="startDate" />
                    <label>Дата начала</label>
                </div>
                <div class="input-field">
                    <input ref="endDate" type="text" class="datepicker" v-model.lazy="endDate"/>
                    <label>Дата конца</label>
                </div>

                <p>
                    <label>
                        <input type="checkbox" class="filled-in" checked="checked" v-model="filteredPrepare"/>
                        <span>Округленный</span>
                    </label>
                </p>
                <div class="center-align">
                    <button class="btn waves-effect waves-light blue lighten-2" @click="loadTables">Показать</button>
                </div>
            </div>
        </div>

        <div class="row content-fail" :class="{ 'content-loading': loadingTable }">
            <my-table :table="pervichka" :columns="columns" tittle="Первичка" :clickable="true" :summable="true" :filtered="filtered" @choose="chooseRaw"></my-table>
            <my-table :table="vtorichka" :columns="columns" tittle="Вторичка" :clickable="true" :summable="true" :filtered="filtered" @choose="chooseRaw"></my-table>
            <my-table :table="granula" :columns="columns" tittle="Гранула" :clickable="true" :summable="true" :filtered="filtered" @choose="chooseRaw"></my-table>
        </div>

        <!-- RawFirm history modal structure -->
        <div ref="modalHistory" class="modal bottom-sheet">
                <div class="modal-content">
                    <a href="#" class="modal-close right"><i class="material-icons">close</i></a>
                    <h4 class="center-align green-text">{{ rawHistory.name }}</h4>
                    <table class="table-rawHistory">
                        <thead>
                        <tr>
                            <th class="yellow lighten-5">Дата</th>
                            <th class="blue lighten-5">Приход новый</th>
                            <th class="indigo lighten-5">Приход с Прв</th>
                            <th class="red lighten-5">Расход на Прв</th>
                            <th class="purple lighten-5">Расход на ГР</th>
                            <th class="green lighten-5">Расход по Заводам</th>
                            <th class="orange lighten-5">Расход возврат</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="date in rawHistory.dates">
                            <td class="yellow lighten-5">{{ date.date }}</td>
                            <td class="blue lighten-5">{{ date.import_new }}</td>
                            <td class="indigo lighten-5">{{ date.import_pr }}</td>
                            <td class="red lighten-5">{{ date.export_pr }}</td>
                            <td class="purple lighten-5">{{ date.export_gran }}</td>
                            <td class="green lighten-5">{{ date.export_factory }}</td>
                            <td class="orange lighten-5">{{ date.export_back }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</template>

<style scoped>

    .row-filter{
        position: relative;
    }
    .filter{
        width: 250px;
        position: absolute;
        right: 0;
        top: -20px;
        background-color: white;
        z-index: 100;
        padding: 20px;
        box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.14), 0 3px 1px -2px rgba(0, 0, 0, 0.12), 0 1px 5px 0 rgba(0, 0, 0, 0.2);
    }
</style>
<script>

    import TableRemainder from './TableRemainder.vue';
    import Tab from "../warehouseman/Tab.vue";
    import moment from "moment";
    import { socket } from "../mixins.js";

    export default {
        mixins: [ socket ],
        props: {
            firms: Array
        },
        components: {
            'my-table': TableRemainder,
            'history' : History,
            'my-tab' : Tab
        },
        data: function(){
            return {

                selectedFirm: null,

                columns: [
                    { name: 'raw_name', label: 'Наименование' },
                    { name: 'balance_before', label: 'Остаток на начало' },
                    { name: 'import_new', label: 'Приход новый' },
                    { name: 'import_tpa', label: 'Приход с ТПА' },
                    { name: 'export_tpa', label: 'Расход на ТПА' },
                    { name: 'export_gran', label: 'Расход на ГР' },
                    { name: 'export_factory', label: 'Расход по заводам' },
                    { name: 'export_back', label: 'Расход возврат' },
                    { name: 'balance', label: 'Остаток' }
                ],

                pervichka: [

                ],

                vtorichka: [
                ],

                granula: [
                ],

                rawHistory: {
                    id: 1,
                    name: "HIPS",
                    dates: [
                    ]
                },

                showDate: false,
                error: true,
                startDate: null,
                endDate: null,
                filtered: false,
                filteredPrepare: false,
                loadingTable: false
            }
        },

        watch: {
            selectedFirm: function(){
                this.loadTables();
            }
        },

        methods: {
            chooseRaw: function(raw){
                this.loadHistory(raw);
                var modal = M.Modal.init(this.$refs.modalHistory);
                modal.open();
            },


            loadTables: function(){
                var self = this;
                self.loadingTable = true;
                self.showDate = false;
                var filtered = self.filteredPrepare;
                var url = filtered ? '/api/balance/filtered' : '/api/balance';
                axios.get(url, {
                    params: {
                        'firm_id': self.selectedFirm,
                        'start_date': self.startDate,
                        'end_date': self.endDate,
                        'filtered': 0
                    }
                }).then(function(response){
                    self.pervichka = response.data.data.pervichka;
                    self.vtorichka = response.data.data.vtorichka;
                    self.granula = response.data.data.granula;
                    self.loadingTable = false;
                    self.filtered = filtered;
                    NProgress.done();
                }).catch(function(error){
                    console.log(error);
                });

            },

            loadHistory: function(raw){
                var self = this;
                self.rawHistory.name='';
                self.rawHistory.dates = [];
                axios.get('/api/import/export/history', {
                    params: {
                        'raw_firm_id': raw.id,
                        'parent_id': raw.parent_id,
                        'start_date': self.startDate,
                        'end_date': self.endDate,
                        'filtered': 0
                    }
                }).then(function(response){
                    self.rawHistory = response.data.data;
                });
            },

            listen: function(data, type){
                if(data[type].firm_id == this.selectedFirm
                    && moment(moment(data[type].date, "DD.MM.YY, HH:mm")).isSameOrAfter(this.startDate, 'day')
                    && moment(moment(data[type].date, "DD.MM.YY, HH:mm")).isSameOrBefore(this.endDate, 'day')){
                    this.loadTables();
                }
            }
        },

        watch: {
            selectedFirm: function(){
                this.loadTables();
            }
        },

        mounted: function(){
            var date = new Date();
            M.Datepicker.init(this.$refs.startDate, {
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
