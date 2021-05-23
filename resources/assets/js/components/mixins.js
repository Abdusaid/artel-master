/**
 * Created by termurkodirov on 9/15/18.
 */

export var firms = {
    data: function(){
        return {
            firms: [

            ],

            loadingFirms: false
        }
    },
    methods: {
        loadFirms: function(){
            var self = this;
            self.loadingFirm = true;
            return axios.get('/api/firms/')
                        .then(function(response){
                            self.firms = response.data.data;
                            self.loadingFirm = false;

                        });
        }

    }
};

export var raws = {
    data: function(){
        return {
            raws: [

            ],

            loadingRaws: false
        }
    },

    methods: {
        loadRaws: function(firm_id, type){
            var self = this;
            self.loadingRaws = true;
            return axios.get('/api/firms/'+firm_id+'/raws', {
                            params: {
                                type: type
                            }
                        }).then(function(response){
                            self.raws = response.data.data;
                            self.loadingRaws = false;
                        });
        },
    }
};

export var types = {
    data: function(){
        return {
            types:{
                1: 'Первичка',
                2: 'Вторичка',
                0: 'Гранула',
            },

            tos: {
                '-1': "На производство",
                '-2': "На гранулятор",
                '-3': "По заводам",
                '-4': "Возврат"
            }
        }
    }
};

export var socket = {
    methods: {
        connectSocket: function(){
            var self = this;
            Echo.channel('import')
                .listen('ImportCreated', (data) => {
                    self.listen(data, 'import', 'c');
                    self.$emit('imported', 1, 'Новый приход!', data);
                })
                .listen('ImportUpdated', (data) => {
                    self.listen(data, 'import', 'u');
                    self.$emit('imported', 1, 'Приход изменен!', data);

                })
                .listen('ImportDeleted', (data) => {
                    self.listen(data, 'import', 'd');
                    self.$emit('imported', 1, 'Приход удален!', data);


                });

            Echo.channel('export')
                .listen('ExportCreated', (data) => {
                    self.listen(data, 'export', 'c');
                    self.$emit('exported', 2, 'Новый расход!');

                })
                .listen('ExportRequested', (data) => {
                    self.listen(data, 'export', 'c');
                    self.$emit('exported', 2, 'Новая заявка!');

                })
                .listen('ExportUpdated', (data) => {
                    self.listen(data, 'export', 'u');
                    self.$emit('exported', 2, 'Расход изменен!');

                })
                .listen('ExportDeleted', (data) => {
                    self.listen(data, 'export', 'd');
                    self.$emit('exported', 2, 'Расход удален!');

                });
        }
    },

    destroyed: function(){
        Echo.leave('import');
        Echo.leave('export');
    }
};


