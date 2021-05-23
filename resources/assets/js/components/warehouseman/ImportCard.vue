<template>
    <my-card :is-success="isSuccess" :is-change="isChange" :error="error" :invalid="invalid" ref="card" @store="store" @edit="edit" @confirm="confirm" @canceled="cancelEdit">
        <div class="my-input-field">
            <p><label>Фирма</label></p>
            <select v-model="addingImport.firm_id" class="browser-default" :class=" {'content-loading' : loadingFirms} ">
                <option v-for="firm in firms" :value="firm.id">
                    {{ firm.name }}
                </option>
            </select>
        </div>

        <div class="my-input-field">
            <p><label>Тип</label></p>
            <select v-model="addingImport.type" class="browser-default">
                <option v-for="(type,key) in types" :value="key" >{{ type }}</option>
            </select>
        </div>

        <div class="my-input-field">
            <p><label>Сырье</label></p>
            <select v-model="addingImport.raw_id" class="browser-default" :class=" {'content-loading' : loadingRaws } ">
                <option v-for="raw in raws" :value="raw.id" >{{ raw.name }}</option>
            </select>
        </div>

        <div class="input-field">
            <input type="number" :min="0" :max="2147483647" :class="addingImport.quantity>0 && addingImport.quantity<=2147483647? 'valid' : 'invalid'" id="import_quantity" v-model="addingImport.quantity" />
            <label class="active" for="import_quantity">Количество (кг)</label>
        </div>


        <p class="my-checkbox">
            <label>
                <input type="checkbox" v-model="addingImport.isNew"/>
                <span>Новый</span>
            </label>
        </p>

        <div v-if="importNew" class="input-field">
            <input type="text"  :class=" addingImport.seria ? 'valid' : 'invalid'" id="import_seria" v-model="addingImport.seria"/>
            <label for="import_seria" :class="{ active : addingImport.seria!=null}">Номер серии</label>
        </div>

        <div v-if="importNew" class="input-field">
            <input type="text" :class=" addingImport.supplier ? 'valid' : 'invalid'" id="import_supplier" v-model="addingImport.supplier"/>
            <label for="import_supplier" :class="{ active : addingImport.supplier!=null}">Поставщик</label>
        </div>

        <div v-if="importNew" class="input-field">
            <input type="text" :class=" addingImport.container ? 'valid' : 'invalid'" id="import_container" v-model="addingImport.container"/>
            <label for="import_container" :class="{ active : addingImport.container!=null}">Контейнер</label>
        </div>

        <div class="input-field">
            <input type="text" class="valid" v-model="addingImport.comment" id="import_comment"/>
            <label for="import_comment" :class="{ active : addingImport.comment!=null}">Примечение</label>
        </div>


        <table slot="success">

            <tr>
                <th>Сырье</th>
                <td>{{ addedImport.raw_name }}</td>
            </tr>
            <tr>
                <th>Фирма</th>
                <td>{{ addedImport.firm_name }}</td>
            </tr>
            <tr>
                <th>Кол-во (кг)</th>
                <td>{{ addedImport.quantity }}</td>
            </tr>
            <tr>
                <th>Тип</th>
                <td>{{ types[addedImport.type] }}</td>
            </tr>
            <tr>
                <th>Новый</th>
                <td><i class="material-icons">{{ addedImport.isNew ? "check" : "close"}}</i></td>
            </tr>
            <tr v-if="importNew">
                <th>Номер серии</th>
                <td>{{ addedImport.seria }}</td>
            </tr>

            <tr v-if="importNew">
                <th>Поставщик</th>
                <td>{{ addedImport.supplier }}</td>
            </tr>
            <tr v-if="importNew">
                <th>Контейнер</th>
                <td>{{ addedImport.container }}</td>
            </tr>
            <tr v-if="importNew">
                <th>Примечание</th>
                <td>{{ addedImport.comment }}</td>
            </tr>
            <tr>
                <th>Дата</th>
                <td>{{ addedImport.date }}</td>
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
        props: {
            isChange: Boolean
        },
        data: function(){
            return {

                addingImport: {
                    id: 0,
                    firm_id: 0,
                    raw_id: 0,
                    type: 1,
                    quantity: 1,
                    isNew: false,
                    seria: null,
                    supplier: null,
                    container: null,
                    comment: null
                },

                addedImport: {
                    firm_name: '',
                    raw_name: '',
                    type: 1,
                    quantity: 0,
                    isNew: false,
                    seria: null,
                    supplier: null,
                    container: null,
                    comment: null
                },

                selectedImport: 0,
                isSuccess: true,
                isAdding: false,
                error: "Данные неправильно введены. Попробуйте снова."

            }
        },

        methods:{


            store: function(){
                var self = this;
                self.isAdding = true;
                axios.post('/api/import', {
                    firm_id: self.addingImport.firm_id,
                    raw_id: self.addingImport.raw_id,
                    type: self.addingImport.type,
                    quantity: self.addingImport.quantity,
                    is_new: self.addingImport.isNew,
                    serial_number: self.addingImport.seria,
                    supplier: self.addingImport.supplier,
                    container: self.addingImport.container,
                    comment: self.addingImport.comment,

                }).then(function(response){
                    self.addedImport = response.data.data;
                    self.isSuccess = true;
                    self.$refs.card.activate();
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
                axios.put('/api/import/'+self.addingImport.id, {
                    firm_id: self.addingImport.firm_id,
                    raw_id: self.addingImport.raw_id,
                    type: self.addingImport.type,
                    quantity: self.addingImport.quantity,
                    is_new: self.addingImport.isNew,
                    serial_number: self.addingImport.seria,
                    supplier: self.addingImport.supplier,
                    container: self.addingImport.container,
                    comment: self.addingImport.comment,
                }).then(function(response){
                    self.addedImport = response.data.data;
                    self.isSuccess = true;
                    self.$refs.card.activate();
                    self.$emit("updated", self.addedImport);
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

            confirm: function(isSuccess){
                if(isSuccess){
                    if(this.addingImport.firm_id == this.firms[0].id)
                        this.addingImport.raw_id = this.raws[0].id;
                    this.addingImport.firm_id = this.firms[0].id;
                    this.addingImport.type = 1;
                    this.addingImport.quantity = 1;
                    this.addingImport.isNew = false;
                    this.addingImport.seria = null;
                    this.addingImport.supplier = null;
                    this.addingImport.container = null;
                    this.addingImport.comment = null;
                    this.$refs.card.close();
                }else{
                    this.$refs.card.close();
                }
            },

            prepareEdit: function(imp){
                this.addingImport.id = imp.id;
                this.addingImport.firm_id = imp.firm_id;
                this.addingImport.raw_id = imp.raw_id;
                this.addingImport.type = imp.type;
                this.addingImport.quantity = imp.quantity;
                this.addingImport.isNew = imp.isNew==1 ? true : false;
                this.addingImport.seria = imp.seria;
                this.addingImport.supplier = imp.supplier;
                this.addingImport.container = imp.container;
                this.addingImport.comment = imp.comment;

            },

            cancelEdit: function(){
                this.addingImport.firm_id = this.firms[0].id;
                this.addingImport.type = 1;
                this.addingImport.quantity = 1;
                this.addingImport.isNew = false;
                this.addingImport.seria = null;
                this.addingImport.supplier = null;
                this.addingImport.container = null;
                this.addingImport.comment = null;
                this.$emit('canceled');
            },

            fetchRaws: function(){
                var self = this;
                this.loadRaws(this.addingImport.firm_id, this.addingImport.type)
                    .then(function(){
                        var item = self.raws.find(item => item.id==self.addingImport.raw_id);
                        if(!item){
                            self.addingImport.raw_id = self.raws[0].id;
                        }

                    });
            }
        },

        computed: {
            importNew: function(){
                return this.addingImport.type==1 && this.addingImport.isNew;
            },

            invalid: function(){
                return this.loadingFirms || this.loadingRaws || this.addingImport.quantity <= 0  || this.addingImport.quantity > 2147483647
                        || this.isAdding
                        || ( !this.addingImport.container && this.importNew )
                        || ( !this.addingImport.supplier && this.importNew )
                        || ( !this.addingImport.seria && this.importNew );
            }
        },

        watch: {
            "addingImport.firm_id" : function(){
                this.fetchRaws();
            },

            "addingImport.type" : function(){
                this.fetchRaws();
            }
        },

        mounted: function(){
            var self = this;

            this.loadFirms()
                .then(function(){
                    self.addingImport.firm_id = self.firms[0].id;
                });
        }

    }
</script>