<template>
    <div class="my-table-div">
        <div class="my-table-name col s12">
            <div class="col s8">
                <p>Смесь</p>
            </div>
            <div class="input-field col s4">
                <i class="material-icons prefix">search</i>
                <input class="search" type="text" placeholder="Поиск..." v-model="search"/>
            </div>
        </div>

        <table class="row-border">
            <col width="35%">
            <col width="30%">
            <col width="10%">
            <col width="10%">
            <col width="15%">
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
                <tr v-for="tab in filteredTable">
                    <td>{{ tab.name }}</td>
                    <td colspan="3" class="complex-cell">
                        <table class="inner-table">
                            <col width="60%">
                            <col width="20%">
                            <col width="20%">
                            <tr v-for="raw in tab.raws">
                                <td>{{ raw.name }}</td>
                                <td>{{ raw.percent }} </td>
                                <td>{{ tab.quantity * raw.percent / 100 }}</td>
                            </tr>
                        </table>
                    </td>

                    <td>{{ tab.quantity }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>


<script>
    export default{
        data(){
            return {
                search: '',
                orderKey: 0,
                orderType: true,
                columns: [
                    { name: 'name', label: 'Наименование' },
                    { name: 'raw_name', label: 'Сырье' },
                    { name: 'procent', label: 'Процент' },
                    { name: 'quantity', label: 'Кол-во (кг)' },
                    { name: 'quantity', label: 'Общее кол-во (кг)' },
                ],

                mixtures:[
                    {
                        id: 1,
                        name: 'PP J360 (80%) красный',
                        quantity: 40,
                        raws:[
                            { name: 'PP J360', percent: 80 },
                            { name: 'Наполнитель', percent: 10 },
                            { name: 'Краситель (красный)', percent: 10 },
                        ]
                    },

                    {
                        id: 2,
                        name: 'ABS Крашенный (96%) синий',
                        quantity: 120,
                        raws:[
                            { name: 'ABS Крашенный', percent: 96 },
                            { name: 'Наполнитель', percent: 2 },
                            { name: 'Краситель (синий)', percent: 2 },
                        ]
                    },

                    {
                        id: 3,
                        name: 'PP J740 (90%) серый',
                        quantity: 40,
                        raws:[
                            { name: 'PP J740', percent: 90 },
                            { name: 'PP серый', percent: 4 },
                            { name: 'Наполнитель', percent: 2 },
                            { name: 'Краситель (серый)', percent: 4 },
                        ]
                    }
                ]
            }
        },

        computed: {
            filteredTable(){
                var orderedTable = _.orderBy(this.mixtures, this.columns[this.orderKey].name, this.orderType ? 'asc' : 'desc');
                return orderedTable.filter(row => {
                    return row[this.columns[0].name].toLowerCase().includes(this.search.toLowerCase().trim());
                });
            }
        },

        methods:{
            orderBy: function(key){
                if(key == 0 || key ==4 ) {
                    if (key == this.orderKey)
                        this.orderType = this.orderType ? false : true;
                    else
                        this.orderType = true;

                    this.orderKey = key;
                }
            }
        }
    }
</script>

<style scoped>


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

    .inner-table td{
        border: 1px solid rgba(0, 0, 0, 0.12);
    }

    .inner-table tr:first-of-type td{
        border-top: none;
    }

    .inner-table tr:last-of-type td{
        border-bottom: none;
    }

    .inner-table tr{
        border: none;
    }

    .complex-cell{
        padding: 0;
    }
</style>