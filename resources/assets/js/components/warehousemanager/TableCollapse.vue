<template>
    <table>
        <my-tr v-for="raw in raws" :balance="raw" :columns="columns"></my-tr>
    </table>
</template>

<script>
    import Row from "./Row.vue";

    export default {
        components: {
            'my-tr': Row
        },

        data: function(){
            return {
                columns: [
                    { name: 'id', label: 'ID' },
                    { name: 'name', label: 'Name' },
                    { name: 'last', label: 'Last' },
                ],

                raws: [
                    { id: 1, name: 'Temur', last: 'Kodirov'},
                    { id: 2, name: 'Temur', last: 'Kodirov'},
                    { id: 3, name: 'Temur', last: 'Kodirov'},
                    { id: 4, name: 'Temur', last: 'Kodirov'},
                    { id: 5, name: 'Temur', last: 'Kodirov'},
                ]
            }
        }
    }
</script>