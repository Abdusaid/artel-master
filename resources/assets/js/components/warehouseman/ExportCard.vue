<template>
    <my-card :is-success="isSuccess" :is-change="isChange" :error="error" :invalid="invalid" ref="card" @store="store" @edit="edit" @confirm="confirm" @canceled="cancelEdit">
        <div class="my-input-field">
            <p><label>Фирма</label></p>
            <select v-model="addingExport.firm_id" class="browser-default" :class=" {'content-loading' : loadingFirms} ">
                <option v-for="firm in firms" :value="firm.id">
                    {{ firm.name }}
                </option>
            </select>
        </div>

        <div class="my-input-field">
            <p><label>Тип</label></p>
            <select v-model="addingExport.type" class="browser-default">
                <option v-for="(type,key) in types" :value="key" >{{ type }}</option>
            </select>
        </div>

        <div class="my-input-field">
            <p><label>Сырье</label></p>
            <select v-model="addingExport.raw_id" class="browser-default" :class="{ 'content-loading' : loadingRaws }">
                <option v-for="raw in raws" :value="raw.id" >{{ raw.name }}</option>
            </select>
        </div>

        <div class="input-field">
            <input type="number" :class="[ (addingExport.quantity>0 && addingExport.quantity<=maxQuantity + potentialQuantity)? 'valid' : 'invalid']" id="import_quantity" v-model="addingExport.quantity" />
            <label class="active" for="import_quantity">Количество (кг) &mdash; Остаток({{ parseFloat(maxQuantity) + parseFloat(potentialQuantity) - addingExport.quantity }})</label>
        </div>

        <div v-if="!to" class="my-input-field">
            <p><label>Куда</label></p>
            <select v-model="addingExport.to" class="browser-default">
                <option v-for="(to, key) in tos" :value="key">{{ to }}</option>
            </select>
        </div>

        <div class="input-field">
            <input id="export_comment" v-model="addingExport.comment" />
            <label class="active" for="export_comment">Примечание</label>
        </div>

        <table slot="success">

            <tr>
                <th>Сырье</th>
                <td>{{ addedExport.raw_name }}</td>
            </tr>
            <tr>
                <th>Фирма</th>
                <td>{{ addedExport.firm_name }}</td>
            </tr>
            <tr>
                <th>Кол-во (кг)</th>
                <td>{{ addedExport.quantity }}</td>
            </tr>
            <tr>
                <th>Тип</th>
                <td>{{ types[addedExport.type] }}</td>
            </tr>
            <tr>
                <th>Кому</th>
                <td>{{ tos[addedExport.to] }}</td>
            </tr>
            <tr>
                <th>Дата</th>
                <td>{{ addedExport.date }}</td>
            </tr>

            <tr>
                <th>Примечание</th>
                <td>{{ addedExport.comment }}</td>
            </tr>

        </table>
    </my-card>
</template>

<script>

    import MyCard from "./AddCard";
    import { firms, raws, types } from "../mixins";

    export default {
        mixins: [
            firms, raws, types
        ],
        components: {
            MyCard
        },
        props:{
            isChange: Boolean,
            to: Number,
            data: Object,
            url: {
                type: String,
                default: '/api/export'
            }
        },

        data: function(){
            return {
                addingExport: {
                    firm_id: 0,
                    raw_id: 0,
                    type: 1,
                    quantity: 0,
                    to: -1,
                    comment: ''
                },

                addedExport: {
                    firm_name: '',
                    raw_name: '',
                    type: 1,
                    quantity: 0,
                    to: -1,
                    comment: ''
                },
                selectedExport: null,
                maxQuantity: 0,
                loadingQuantity: false,
                isSuccess: true,
                isAdding: false,
                error: "Что-то пошло не так. Попробуйте снова."
            }
        },

        methods: {

            store: function(){
                var self = this;
                self.isAdding = true;
                axios.interceptors.request.use(request => {
                    console.log('Starting Request', request)
                    return request
                });
                axios.interceptors.response.use(response => {
                    console.log('Response:', response)
                    return response
                });
                axios.post(self.url, {
                    firm_id: self.addingExport.firm_id,
                    raw_id: self.addingExport.raw_id,
                    type: self.addingExport.type,
                    quantity: self.addingExport.quantity,
                    to: self.to ? self.to : self.addingExport.to,
                    comment: self.addingExport.comment
                }).then(function(response){
                    self.addedExport = response.data.data;
                    self.isSuccess = true;
                    self.$refs.card.activate();
                    self.maxQuantity -= self.addedExport.quantity;
                    self.$emit("created");
                }).catch(function(error){
                    self.isSuccess = false;
                    if(error.response.status == 400){
                        self.error = error.response.data.message;
                        console.log(error.response.data);
                    }else{
                        self.error = "Что-то пошло не так. Попробуйте снова."
                    }
                    self.$refs.card.activate();
                    console.log(error);
                }).then(function(){
                    self.isAdding = false;
                });
            },

            edit: function(){
                var self = this;
                self.isAdding = true;
                axios.put(self.url+'/'+self.selectedExport.id, {
                    firm_id: self.addingExport.firm_id,
                    raw_id: self.addingExport.raw_id,
                    type: self.addingExport.type,
                    quantity: self.addingExport.quantity,
                    to: self.to ? self.to : self.addingExport.to,
                    comment: self.addingExport.comment
                }).then(function(response){
                    self.addedExport = response.data.data;
                    self.isSuccess = true;
                    self.$refs.card.activate();
                    self.maxQuantity += self.potentialQuantity - self.addedExport.quantity;
                    self.isChange = false;
                    self.$emit("updated", self.addedExport);
                }).catch(function(error){
                    self.isSuccess = false;
                    if(error.response.status == 400){
                        self.error = error.response.data.message;
                        console.log(error.response.data);
                    }else{
                        self.error = "Что-то пошло не так. Попробуйте снова."
                    }
                    self.$refs.card.activate();
                    console.log(error);
                }).then(function(){
                    self.isAdding = false;
                });
            },


            loadMaxQuantity: function(){
                var self = this;
                self.loadingQuantity = true;
                axios.get('/api/export/quantity', {
                    params:{
                        firm_id: self.addingExport.firm_id,
                        raw_id: self.addingExport.raw_id,
                        type: self.addingExport.type
                    }
                }).then(function(response){
                    self.maxQuantity = parseFloat(response.data.data);
                    self.loadingQuantity = false;
                }).catch(function(error){
                    self.maxQuantity = 0;
                });
            },

            confirm: function(isSuccess){
                if(isSuccess){
                    if(this.addingExport.firm_id == this.firms[0].id)
                        this.addingExport.raw_id = this.raws[0].id;
                    this.addingExport.firm_id = this.firms[0].id;
                    this.addingExport.type = 1;
                    this.addingExport.quantity = 0;
                    this.addingExport.to = -1;
                    this.$refs.card.close();
                }else{
                    this.$refs.card.close();
                }
            },

            prepareEdit: function(imp){
                this.selectedExport = imp;
                this.addingExport.id = imp.id;
                this.addingExport.firm_id = imp.firm_id;
                this.addingExport.raw_id = imp.raw_id;
                this.addingExport.type = imp.type;
                this.addingExport.quantity = imp.quantity;
                this.addingExport.to = imp.to;
                this.addingExport.comment = imp.comment;
            },

            cancelEdit: function(){
                this.addingExport.firm_id = this.firms[0].id;
                this.addingExport.type = 1;
                this.addingExport.quantity = 0;
                this.addingExport.to = -1;
                this.addingExport.comment = '';
                this.$emit('canceled');
            },

            listen: function(data, type){
                if(data[type].firm_id == this.addingExport.firm_id && data[type].raw_id==this.addingExport.raw_id
                    && data[type].type==this.addingExport.type){
                    this.maxQuantity = data.rawFirm.valid_quantity;
                }
            },

            fetchRaws: function(){
                var self = this;

                if(!this.isChange)
                    self.addingExport.raw_id = 0;
                this.loadRaws(this.addingExport.firm_id, this.addingExport.type)
                    .then(function(){
                        var item = self.raws.find(item => item.id==self.addingExport.raw_id);
                        if(!item){
                            self.addingExport.raw_id = self.raws[0].id;
                        }else{
                            self.loadMaxQuantity();
                        }
                    });
            }
        },

        computed: {
            invalid: function(){
                return this.loadingFirms || this.loadingRaws || this.isAdding
                    || this.addingExport.quantity <= 0 || this.addingExport.quantity > (this.maxQuantity + this.potentialQuantity)
                    || this.loadingQuantity;
            },

            potentialQuantity: function(){
                if(this.isChange && this.selectedExport.firm_id==this.addingExport.firm_id
                    && this.selectedExport.raw_id==this.addingExport.raw_id && this.selectedExport.type==this.addingExport.type){
                    return this.selectedExport.quantity;
                }

                return 0;
            }

        },

        watch: {
            "addingExport.firm_id" : function(){
                this.fetchRaws();
            },

            "addingExport.raw_id": function(){
                if(this.addingExport.raw_id != 0)
                    this.loadMaxQuantity();
            },

            "addingExport.type": function(){
                this.fetchRaws();
            },

            data: function(){
                this.addingExport = this.data;
            }
        },

        mounted: function(){
            var self = this;

            this.loadFirms()
                .then(function(){
                    self.addingExport.firm_id = self.firms[0].id;
                });

        }

    }
</script>