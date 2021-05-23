@extends('layouts.main')

@section('user', 'Заявочник')

@section('content')
    <div class="row">
        <div class="col s4">
            <export-card v-on:created="created" v-on:updated="updated" v-on:canceled="canceled" :url="urlRequest" :to="to" :is-change="isChange" ref="exportCard"></export-card>
        </div>
        <div class="col s8">
            <history :url="url" v-on:edit="edit" v-on:deleted="deleted" ref="history"></history>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var app = new Vue({
            el: "#my-app",
            components: {
                'export-card': ExportCard,
                'history': History
            },

            data: {
                urlRequest: '/api/export/request',
                url: '/api/export',
                withSidebar: false,
                isChange: false,
                to: -1,
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
                }
            },

            mounted: function(){
                var instances = M.Dropdown.init(this.$refs.dropdownTrigger, {
                    coverTrigger: false,
                    alignment: 'right'
                });


                var self = this;
                Echo.channel('import')
                    .listen('ImportCreated', (data) => {
                        self.$refs.exportCard.listen(data, 'import');
                    })
                    .listen('ImportUpdated', (data) => {
                        self.$refs.exportCard.listen(data, 'import');
                    })
                    .listen('ImportDeleted', (data) => {
                        self.$refs.exportCard.listen(data, 'import');
                    });

                Echo.channel('export')
                    .listen('ExportCreated', (data) => {
                        self.$refs.exportCard.listen(data, 'export');
                    })
                    .listen('ExportRequested', (data) => {
                        self.$refs.exportCard.listen(data, 'export');
                    })
                    .listen('ExportUpdated', (data) => {
                        self.$refs.exportCard.listen(data, 'export');
                        self.updated(data.export);
                    })
                    .listen('ExportDeleted', (data) => {
                        self.$refs.exportCard.listen(data, 'export');
                        self.$refs.history.loadHistory();
                    });
            },

            destroyed: function(){
                Echo.leave('import');
                Echo.leave('export');
            }

        });
    </script>
@endsection