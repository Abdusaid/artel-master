<template>

    <tr class="wrapper-row">
        <td class="wrapper-cell" colspan="100%">
            <table>
                <col v-for="(col, index) in columns" :width="index==0 ? '25%' : 75/(columns.length-1)+'%'">
                <tr class="super-row" @click="choose(balance)">
                    <td class="super-cell" colspan="1" v-for="(column, index) in columns" :style="{ paddingLeft: index==0 ? level*15 + 'px' : '8px' }">

                        <i v-if="index==0 && balance.children.length>0" class="material-icons">{{ withChildren ? 'remove' : 'add' }}</i>

                        {{ balance[column.name] }}
                    </td>
                </tr>
                <my-tr v-if="withChildren" v-for="child in balance.children" :balance="child" :columns="columns" :level="level+1" @choose="choose"></my-tr>
            </table>
        </td>
    </tr>

</template>
<style scoped>
    /*.noroot tr{*/
        /*border: none;*/
    /*}*/

    td{
        padding: 0;
        border-radius: 0;
    }

    td.super-cell{
        padding: 5px 8px;
        box-sizing: border-box;
        vertical-align: middle;
    }

    td.super-cell > i{
        font-size: 14px;
        border-radius: 2px;
        background-color: black;
        color: white;
        margin-right: 5px;
    }

    tr.wrapper-row{
        border-bottom: none;
    }

    tr.super-row:hover{
        background-color: rgba(242, 242, 242, 0.5);
    }
</style>
<script>
    export default {
        name: 'my-tr',
        props: {
            balance: Object,
            columns: Array,
            level: Number
        },
        data: function(){
            return {
                withChildren: false,

                children: [
                    { raw_name: 'ABS', balance_before: 23, import_new: 12, import_tpa: 12, export_tpa: 32, export_gran: 0, export_factory: 12, balance: 20},
                    { raw_name: 'ABS', balance_before: 23, import_new: 12, import_tpa: 12, export_tpa: 32, export_gran: 0, export_factory: 12, balance: 20},
                    { raw_name: 'ABS', balance_before: 23, import_new: 12, import_tpa: 12, export_tpa: 32, export_gran: 0, export_factory: 12, balance: 20},
                    { raw_name: 'ABS', balance_before: 23, import_new: 12, import_tpa: 12, export_tpa: 32, export_gran: 0, export_factory: 12, balance: 20},
                    { raw_name: 'ABS', balance_before: 23, import_new: 12, import_tpa: 12, export_tpa: 32, export_gran: 0, export_factory: 12, balance: 20},
                ]
            }
        },


        methods: {
            choose: function(balance){
                if(balance.hasChild == 0){
                    this.$emit("choose", balance);
                }else{
                    this.withChildren = !this.withChildren;
                }
            }
        }
    }
</script>