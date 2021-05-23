<template>
    <div class="row">
        <div class="col s4">
            <import-card @created="created" @updated="updated" @canceled="canceled" :is-change="isChange" ref="importCard"></import-card>
        </div>
        <div class="col s8">
            <history :url="url" @edit="edit" title="Приход" @deleted="deleted" ref="history"></history>

            <div class="fixed-action-btn">
                <input v-show="false" type="text" class="datepicker" v-model.lazy="date"/>
                <a v-show="false" :href="downloadUrl" download ref="downloadLink"></a>
                <a class="btn-floating btn-large blue hover-icon" @click="datepicker.open();">
                    <i class="large material-icons">cloud_download</i>
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import History from "./History.vue";
    import ImportCard from "./ImportCard.vue";
    import { socket } from "../mixins.js";

    export default {
        mixins: [ socket ],
        components: {
            'history': History,
            'import-card': ImportCard
        },

        data: function(){
            return {
                url: '/api/import',
                isChange: false,

                datepicker: null,
                date: null,
                downloadUrl: '/api/import/excel'
            }
        },

        watch: {
            date: function(){
                if(this.date != null){
                    this.downloadUrl = '/api/import/excel?date='+this.date;
                    Vue.nextTick( () => {
                        this.$refs.downloadLink.click();
                        this.date = null;
                    });
                }
            }
        },


        methods: {
            created: function(){
                this.$refs.history.loadHistory();
            },

            updated: function(addedImport){
                this.isChange = false;
                this.$refs.history.update(addedImport);
            },

            edit: function(intendedImport){
                this.isChange = true;
                this.$refs.importCard.prepareEdit(intendedImport);

            },

            deleted: function(){

            },

            canceled: function(){
                this.isChange = false;
            },

            listen: function(data, type){
                if(type == 'import')
                    this.updated(data.import);
            }
        },

        mounted: function(){
            this.connectSocket();

            var elems = document.querySelector('.datepicker');
            this.datepicker = M.Datepicker.init(elems, {
                format: 'dd.mm.yyyy'
            });
        }
    }
</script>