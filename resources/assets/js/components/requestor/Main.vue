<template>
    <layout :with-sidebar="withSidebar" @logout="logout" :user="user">
        <div class="row">
            <div class="col s4">
                <export-card v-on:created="created" v-on:updated="updated" v-on:canceled="canceled" :url="urlRequest" :to="to" :is-change="isChange" ref="exportCard"></export-card>
            </div>
            <div class="col s8">
                <history :url="url" v-on:edit="edit" v-on:deleted="deleted" ref="history"></history>
            </div>
        </div>
    </layout>
</template>

<script>
    import Layout from "../layouts/Main.vue";
    import ExportCard from "../warehouseman/ExportCard.vue";
    import History from "../warehouseman/History.vue";
    import { socket } from "../mixins.js";

    export default {
        mixins: [ socket ],
        components: {
            'export-card': ExportCard,
            'history': History,
            'layout': Layout
        },

        props: {
            user: Object
        },
        data: function(){
            return {
                urlRequest: '/api/export/request',
                url: '/api/export',
                withSidebar: false,
                isChange: false,
                to: -1
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

            deleted: function(){
                this.$refs.exportCard.loadMaxQuantity();
            },

            canceled: function(){
                this.isChange = false;
            },

            logout: function(){
                this.$emit('logout');
            },

            listen: function(data, type, operation){
                this.$refs.exportCard.listen(data, type);

                if(type=='export' && operation=='u'){ // if export is updated
                    this.updated(data.export);
                }else if(type=='export' && operation=='d'){ // if export is deleted
                    this.$refs.history.loadHistory();
                }
            }
        },

        mounted: function(){
            var instances = M.Dropdown.init(this.$refs.dropdownTrigger, {
                coverTrigger: false,
                alignment: 'right'
            });
            this.connectSocket();
        }
    }
</script>