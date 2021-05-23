<template>
    <div class="row">
        <div class="col s4">
            <export-card :url="url" @created="created" @updated="updated" @canceled="canceled" :is-change="isChange" ref="exportCard"></export-card>
            <request v-for="request in requests" :request="request" @confirmed="confirmed" @deleted="deletedRequest"></request>
        </div>
        <div class="col s8">
            <history :url="url" :status="1" title="Расход" @edit="edit" @deleted="deleted" ref="history"></history>

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
    import ExportCard from "./ExportCard.vue";
    import Request from "./Request.vue";
    import { socket } from "../mixins.js";

    export default {
        mixins: [ socket ],
        components: {
            'history': History,
            'export-card': ExportCard,
            'request': Request
        },
        data: function(){
            return {
                url: '/api/export',
                isChange: false,
                requests: [

                ],

                datepicker: null,
                date: null,
                downloadUrl: '/api/export/excel'
            }
        },

        watch: {
            date: function(){
                if(this.date != null){
                    this.downloadUrl = '/api/export/excel?date='+this.date;
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

            updated: function(addedExport){
                this.isChange = false;
                this.$refs.history.update(addedExport);
            },

            edit: function(intendedExport){
                this.isChange = true;
                this.$refs.exportCard.prepareEdit(intendedExport);

            },

            deleted: function(deletedExport){
                this.deleteRequest(deletedExport);
                this.$refs.exportCard.loadMaxQuantity();
            },

            canceled: function(){
                this.isChange = false;
            },

            confirmed: function(addedExport){
                this.deleteRequest(addedExport);
                this.$refs.history.loadHistory();
            },

            deletedRequest: function(deletedRequest){
                this.deleteRequest(deletedRequest);
                this.$refs.exportCard.loadMaxQuantity();
            },

            deleteRequest: function(exp){
                var index = this.requests.findIndex(item => item.id==exp.id);
                if(index != -1){
                    this.requests.splice(index, 1);
                }
            },

            loadRequests: function(){
                var self = this;
                return axios.get('/api/request')
                            .then(function(response){
                                self.requests = response.data.data;
                            });
            },


            listen: function(data, type, operation){
                this.$refs.exportCard.listen(data, type);

                if(type == 'export'){
                    switch(operation){
                        case 'c': // created
                            this.requests.unshift(data.export);
                            break;

                        case 'u': // updated
                            var index = this.requests.findIndex(item => item.id==data.export.id);
                            if(index != -1){
                                this.requests.splice(index, 1, data.export);
                            }
                            break;

                        case 'd': // deleted
                            var index = this.requests.findIndex(item => item.id==data.export.id);
                            if(index != -1){
                                this.requests.splice(index, 1);
                            }
                            break;
                    }
                }
            }


        },

        mounted: function(){
            this.loadRequests();
            this.connectSocket();

            var elems = document.querySelector('.datepicker');
            this.datepicker = M.Datepicker.init(elems, {
                format: 'dd.mm.yyyy'
            });
        }
    }
</script>