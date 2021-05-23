<template>
    <div>
        <div class="row">
            <my-tab :firms="firms" v-model="selectedFirm"></my-tab>
        </div>

        <div class="row" :class=" {  'content-loading' : loadingTable }">
            <my-table :table="pervichka" tittle="Первичка" :columns="columns"></my-table>
            <my-table :table="vtorichka" tittle="Вторичка" :columns="columns"></my-table>
            <my-table :table="granula" tittle="Гранула" :columns="columns"></my-table>
        </div>

    </div>
</template>


<script>
    import TableRemainder from "../warehousemanager/TableRemainder.vue";
    import Tab from "./Tab.vue";
    import { firms, socket } from "../mixins.js";

    export default {

        mixins: [ firms, socket ],

        components: {
            'my-table': TableRemainder,
            'my-tab': Tab,
        },

        data: function(){
            return {
                pervichka: [

                ],

                vtorichka: [

                ],

                granula: [

                ],

                columns: [
                    { name: 'name', label: 'Наименование' },
                    { name: 'position', label: 'Позиция' },
                    { name: 'quantity', label: 'Количество (кг)' },
                    { name: 'valid_quantity', label: 'Доступное кол-во (кг)'}
                ],
                loadingTable: false,
                selectedFirm: 0,
                failed: false
            }
        },

        methods: {
            loadTables: function(firm_id){
                var self = this;
                self.loadingTable = true;
                return axios.get('/api/firms/'+firm_id+'/balance')
                            .then(function(response){
                                self.pervichka = response.data.data.pervichka;
                                self.vtorichka = response.data.data.vtorichka;
                                self.granula = response.data.data.granula;
                                self.loadingTable = false;
                            })
                            .catch(error => {
                                console.log(error.response.data);
                            });
            },

            reload: function(){
                var self = this;
                self.loadFirms()
                    .then(function(response){
                        self.selectedFirm = self.firms[0].id;
                        self.failed = false;
                    })
                    .catch(function(error){
                        console.log(error);
                        self.failed = true;
                    });
            },

            listen: function(data, type){
                if(data[type].firm_id == this.selectedFirm){
                    var table = data[type].type==1 ? "pervichka" : data[type].type==2 ? "vtorichka" : "granula";
                    var index = this[table].findIndex(item => item.id==data.rawFirm.id);
                    this[table].splice(index, 1, data.rawFirm);
                }
            },

        },

        watch: {
            selectedFirm: function(){
                this.loadTables(this.selectedFirm);
            }
        },

        mounted: function(){
            this.reload();
            this.connectSocket();
        }
    }
</script>