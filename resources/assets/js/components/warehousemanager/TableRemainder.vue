<template>
    <div class="my-table-div">
        <div class="my-table-name col s12">
            <div class="col s8">
                <p>{{ tittle }}</p>
            </div>
            <div class="input-field col s4">
                <i class="material-icons prefix">search</i>
                <input class="search" type="text" placeholder="Поиск" v-model="searchPervichka"/>
            </div>
        </div>

        <table class="row-border" :class=" { highlight : clickable&&!filtered} ">
            <col v-if="filtered" v-for="(col, index) in columns" :width="index==0 ? '25%' : 75/(columns.length-1)+'%'">
            <thead>
                <tr>
                    <th v-for="(col, index) in columns" @click="orderBy(index)">
                        <span>{{ col.label }}</span>
                        <span class="my-order-icons">
                            <span class="arrow-up" :class="orderKey==index ? orderType==true ? 'order-active' : 'order-inactive' : ''"></span>
                            <span class="arrow-down" :class="orderKey==index ? orderType==true ? 'order-inactive' : 'order-active' : ''"></span>
                        </span>
                    </th>
                </tr>
            </thead>

            <tbody>
                <my-tr v-if="filtered" v-for="tab in filteredTable" :key="tab.id" @choose="chooseRaw" :class="{ clickable : clickable }" :columns="columns" :balance="tab" :level="1">
                </my-tr>

                <tr v-if="!filtered" v-for="tab in filteredTable" :class="{ clickable : clickable }" @click="chooseRaw(tab)">
                    <td v-for="(col, index) in columns">{{ tab[col.name] }}</td>
                </tr>

                <tr v-if="summable">
                    <th v-for="(col, index) in columns">{{ sum(index) }}</th>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>

    .table{
        display: table;
    }

    .thead{
        display: table-header-group;
    }

    .tbody{
        display: table-row-group;
    }

    .tr{
        display: table-row;
        width: 100%;
    }

    .th{
        display: table-cell;
    }

    .td{
        display: table-cell;
    }
    .clickable{
        cursor: pointer;
    }

    .my-table-name{
        color: #2196f3;
        font-weight: bold;
        margin: 0;
        border-bottom: 1px solid #2196f3;
    }

    .my-table-name .input-field{
        margin: 0px;
    }

    .my-table-div{
        margin: 20px 0 40px 0;
        border: 1px solid #2196f3;
        box-shadow: 1px 1px 5px #2196f3;
    }
    .my-table-div th{
        cursor: pointer;
    }

    .my-table-div th, .my-table-div td{
        padding: 5px 8px;
    }
    thead th{
        top: 0;
        position: -webkit-sticky;
        position: sticky;
        background-color: white;
        z-index: 1;
    }

    td, th{
        border-radius: 0;
    }

    .my-order-icons{
        display: flex;
        flex-direction: column;
        justify-content: center;
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
    }

    .arrow-up{
        height: 0;
        width: 0;
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;

        border-bottom: 8px solid lightgray;
        margin-bottom: 1px;
    }
    .arrow-down{
        height: 0;
        width: 0;
        border-right: 4px solid transparent;
        border-left: 4px solid transparent;

        border-top: 8px solid lightgray;
    }

    .my-order-icons .arrow-up.order-active{
        border-bottom-color: black;
    }
    .my-order-icons .arrow-down.order-active{
        border-top-color: black;
    }
    .my-order-icons .order-inactive{
        border-color: transparent;
    }
</style>

<script>
    import Row from "./Row.vue";

    export default {
        components: {
            'my-tr': Row
        },
        props: {
            table: {
                type: Array,
                required: true
            },
            tittle: {
                type: String,
                required: true
            },
            columns: {
                type: Array,
                required: true
            },
            clickable: {
                type: Boolean,
                default: false
            },
            summable: {
                type: Boolean,
                default: false
            },

            filtered: {
                type: Boolean,
                default: false
            }
        },
        data: function(){
            return {
                searchPervichka: '',
                orderKey: 0,
                orderType: true
            }
        },
        computed: {
            filteredTable: function(){
                var self = this;
                var orderedTable = _.orderBy(self.table, self.columns[self.orderKey].name, self.orderType ? 'asc' : 'desc');
                return orderedTable.filter(function(row){
                    return row[self.columns[0].name].toLowerCase().includes(self.searchPervichka.toLowerCase().trim());
                });
            }
        },
        methods: {
            orderBy: function(key){
                if(key == this.orderKey)
                    this.orderType = this.orderType ? false : true;
                else
                    this.orderType = true;

                this.orderKey = key;
            },

            chooseRaw: function(raw){
                console.log(raw);
                this.$emit('choose', raw);
            },

            sum: function(index){
                if(index==0)
                    return "Общее";
                var self = this;
                return _.sumBy(self.filteredTable, function(o){ return o[self.columns[index].name]});
            }
        }
    }
</script>