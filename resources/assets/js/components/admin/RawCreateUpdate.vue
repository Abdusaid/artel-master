<template>
    <div class="card">
        <div class="card-content" :class=" { 'content-loading':adding } ">
            <span class="title blue lighten-2">Сырье</span>
            <div class="input-field">
                <input placeholder="Пример: ABS 123" id="name" type="text" class="validate" v-model="raw.name">
                <label for="name" class="active">Наименование</label>
            </div>

            <div class="input-field">
                <select v-model="raw.color_id">
                    <option :value="null"></option>
                    <option v-for="color in colors" :value="color.id">{{ color.name }}</option>
                </select>
                <label>Цвет</label>
            </div>

            <div class="input-field">
                <select v-model="raw.parent_id">
                    <option :value="null"></option>
                    <option v-for="parent in rawparents" :value="parent.id"><span v-for="num in depth(parent)">&nbsp;&nbsp;</span>{{ parent.name }}
                    </option>
                </select>
                <label>Родитель</label>
            </div>

            <div class="input-field">
                <select multiple v-model="raw.firms">
                    <option v-for="firm in firms" :value="firm.id">{{ firm.name }}</option>
                </select>
                <label>Фирмы</label>
            </div>

            <p v-if="raw.firms.length > 0" class="raw_type">
                <label>
                    <input type="checkbox" v-model="raw.is_pervichka"/>
                    <span>Первичка</span>
                </label>

                <label>
                    <input type="checkbox" v-model="raw.is_vtorichka"/>
                    <span>Вторичка</span>
                </label>

                <label>
                    <input type="checkbox" v-model="raw.is_granula"/>
                    <span>Гранула</span>
                </label>
            </p>
            <button v-if="!isChange" class="btn waves-effect waves-light blue" @click="store" :class="valid ? '' : 'disabled' ">Добавить</button>
            <button v-if="isChange" class="btn waves-effect waves-light blue" @click="update" :class="valid ? '' : 'disabled' ">Изменить</button>
            <button v-if="isChange" data-target="modal" class="btn modal-trigger waves-effect waves-light red">Удалить</button>
            <button v-if="isChange" class="btn waves-effect waves-light" @click="cancel">Отменить</button>

        </div>


        <div id="modal" class="modal center-align">
            <div class="modal-content">
                <h4>Удалить?</h4>
            </div>
            <div class="modal-footer center-align">
                <div class="row">
                    <div class="col s4 offset-s1">
                        <button class="btn waves-effect waves-light red modal-close" @click="destroy">Да</button>
                    </div>

                    <div class="col s4 offset-s2">
                        <button class="btn waves-effect waves-light modal-close">Нет</button>
                    </div>
                </div>

            </div>
        </div>


    </div>
</template>

<style scoped>
    p.raw_type{
        margin-bottom: 20px;
    }
    .raw_type label{
        margin-right: 20px;
    }
    .modal .modal-footer button{
        width: 100%;
    }


</style>

<script>
    import { firms } from "../mixins.js";

    export default {
        mixins: [firms],

        data: function(){
            return {
                rawparents: [],
                colors: [],
                raw: {
                    name: '',
                    color_id: null,
                    parent_id: null,
                    firms: [],
                    is_pervichka: false,
                    is_vtorichka: false,
                    is_granula: false
                },

                adding: false,
                isChange: false
            }
        },

        computed: {
            valid(){
                return this.raw.name &&
                    (this.raw.firms.length == 0 || this.raw.is_pervichka
                        || this.raw.is_vtorichka || this.raw.is_granula ) &&
                    this.adding==false;
            }
        },

        watch: {
            rawparents(){
                this.$nextTick( () => {
                    this.init();
                });
            }
        },

        methods: {
            depth(rawparent){
                return rawparent.ancestors.match(/,/g).length - 1;
            },

            init(){
                var elems = document.querySelectorAll('select');
                M.FormSelect.init(elems);
            },

            setDefault(){
                this.raw = {
                    name: '',
                    color_id: null,
                    parent_id: null,
                    firms: [],
                    is_pervichka: false,
                    is_vtorichka: false,
                    is_granula: false,
                };

                this.$nextTick( () => {
                    this.init();
                });
            },

            setRaw(raw){
                this.raw = {
                    id: raw.id,
                    name: raw.name,
                    color_id: raw.color_id,
                    parent_id: raw.parent_id,
                    firms: raw.firms,
                    is_pervichka: raw.is_pervichka,
                    is_vtorichka: raw.is_vtorichka,
                    is_granula: raw.is_granula,
                };
                this.isChange = true;
                this.$nextTick( () => {
                    this.init();
                });
            },

            store(){
                this.adding = true;
                axios.post('/api/raw', {
                    'name': this.raw.name,
                    'color_id': this.raw.color_id ? this.raw.color_id: undefined,
                    'parent_id': this.raw.parent_id ? this.raw.parent_id : undefined,
                    'firms': this.raw.firms,
                    'is_pervichka': this.raw.is_pervichka,
                    'is_vtorichka': this.raw.is_vtorichka,
                    'is_granula': this.raw.is_granula,
                })
                    .then((response) => {
                        M.toast({html: 'Сырье успешно добавлено!'});
                        this.$emit('stored', response.data.data);
                    })
                    .catch((error) => {
                        M.toast({html: 'Ошибка при добавлении!', classes: 'error'});
                        console.log(error.response);
                    })
                    .then(() => {
                        this.adding = false;
                    });
            },

            update(){
                this.adding = true;
                axios.put('/api/raw/'+this.raw.id, {
                    'name': this.raw.name,
                    'color_id': this.raw.color_id ? this.raw.color_id : undefined,
                    'parent_id': this.raw.parent_id ? this.raw.parent_id : undefined,
                    'firms': this.raw.firms,
                    'is_pervichka': this.raw.is_pervichka,
                    'is_vtorichka': this.raw.is_vtorichka,
                    'is_granula': this.raw.is_granula,
                })
                    .then((response) => {
                        M.toast({html: 'Сырье успешно изменено!'});
                        this.isChange = false;
                        this.setDefault();
                        this.$emit('updated', response.data.data);
                    })
                    .catch((error) => {
                        M.toast({html: 'Ошибка при изменении!', classes: 'error'});
                        console.log(error.response);
                    })
                    .then(() => {
                        this.adding = false;
                    });
            },

            destroy(){
                this.adding = true;
                axios.delete('/api/raw/'+this.raw.id)
                    .then(() => {
                        M.toast({html: 'Сырье удалено!'});
                        this.isChange = false;
                        this.setDefault();
                        this.$emit('deleted', this.raw);
                    })
                    .catch((error) => {
                        M.toast({html: 'Ошибка при удалении!', classes: 'error'});
                        console.log(error.response);
                    })
                    .then(() => {
                        this.adding = false;
                    });
            },

            cancel(){
                this.isChange = false;
                this.setDefault();
            },

            loadRawparents(){
                axios.get('/api/rawparents')
                    .then((response) => {
                        this.rawparents = response.data.data;
                    });
            },

            loadColors(){
                axios.get('/api/color')
                    .then((response) => {
                        this.colors = response.data.data;
                    });
            }
        },
        created: function(){
            this.loadFirms();
            this.loadColors();
            this.loadRawparents();
        },

        mounted: function(){
            var elems = document.querySelectorAll('.modal');
            var instances = M.Modal.init(elems);
        }
    }
</script>