<template>
    <div>
        <div class="row">
            <my-tab :firms="tabs" v-model="selectedTab"></my-tab>
        </div>

        <div class="row">
            <raw-table v-show="selectedTab == 1" tittle="Сырье" :table="raws" :columns="columns"></raw-table>
            <mixture-table v-show="selectedTab == 2"></mixture-table>
        </div>
    </div>

</template>

<script>
    import Tab from "../warehouseman/Tab.vue";
    import MixtureTable from "./MixtureTable.vue";
    import RawTable from "../warehousemanager/TableRemainder.vue";

    export default{
        components: {
            'my-tab': Tab,
            'mixture-table': MixtureTable,
            'raw-table': RawTable
        },

        data(){
            return {
                tabs: [
                    { id: 1, name: 'Сырье' },
                    { id: 2, name: 'Смесь' },
                ],

                selectedTab: 1,

                columns: [
                    { name: 'name', label: 'Наименование'},
                    { name: 'type', label: 'Тип'},
                    { name: 'quantity', label: 'Количество (кг)'},
                ],

                raws: [
                    { id: 1, name: 'ABS 360', type: 'Первичка', quantity: 40 },
                    { id: 2, name: 'PP J340', type: 'Первичка', quantity: 10 },
                    { id: 3, name: 'Краситель (красный)', type: 'Краситель', quantity: 23 },
                    { id: 4, name: 'Краситель (синий)', type: 'Краситель', quantity: 26 },
                    { id: 5, name: 'PP серый', type: 'Вторичка', quantity: 34 },
                ]
            }
        }

    }
</script>