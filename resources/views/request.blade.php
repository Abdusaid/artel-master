<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Kitchen</title>

    <!--Import Google Icon Font-->
    <link href="{{asset('css/icon.css')}}" rel="stylesheet">

    <!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="{{asset('css/materialize.min.css')}}">

    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

    <!-- My own css -->
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

</head>

<body>
    <div id="my-app">
        <div  id="my-body">
            <div class="my-nav green lighten-1">
                <p class="left my-brand">
                    <a href="/request" class="brand-logo">Artel</a>
                </p>
                <!-- Dropdown Structure -->
                <ul id="dropdownUser" class="dropdown-content">
                    <li><a href="#!">Выйти</a></li>
                </ul>
    
                <p class="right my-username">
                    <a class="dropdown-trigger" href="#!" data-target="dropdownUser">Складовщик <i class="material-icons">arrow_drop_down</i></a>
                </p>

            </div>
            <div id="my-content">
                <div v-if="status==2" class="my-loading-content">
                    <div class="preloader-wrapper big active">
                        <div class="spinner-layer spinner-blue">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>

                        <div class="spinner-layer spinner-red">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>

                        <div class="spinner-layer spinner-yellow">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>

                        <div class="spinner-layer spinner-green">
                            <div class="circle-clipper left">
                                <div class="circle"></div>
                            </div><div class="gap-patch">
                                <div class="circle"></div>
                            </div><div class="circle-clipper right">
                                <div class="circle"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="status==0" class="my-error-content">
                    <div>
                        <img src="{{ asset('images/noconnect.png') }}" width="100px" height="100px" alt="Internet connection is slow" />
                    </div>
                    <h5 class="text-center">Slow internet connection!</h5>
                </div>
                <div v-show="status==1" class="my-ready-content">

                        <div v-if="currentPage==1" class="my-export-content">
                                <div class="row">
                                    <div class="col s4">
                                        <div class="my-add card">
                                            <div class="card-content">
        
                                                <div class="my-input-field">
                                                    <p><label>Фирма</label></p>
                                                    <select v-model="exportFirm" class="browser-default">
                                                        <option v-for="firm in firms" :value="firm.id">
                                                            @{{ firm.name }}
                                                        </option>
                                                    </select>
                                                </div>
        
                                                <div class="my-input-field">
                                                    <p><label>Сырье</label></p>
                                                    <select v-model="exportRaw" class="browser-default" :class="{ 'content-loading' : exportRawLoading }">
                                                        <option v-for="raw in raws" :value="raw.id" >@{{ raw.name }}</option>
                                                    </select>
                                                </div>
        
                                                <div class="my-input-field">
                                                    <p><label>Тип</label></p>
                                                    <select v-model="exportRawType" class="browser-default">
                                                        <option v-for="type in rawTypes" :value="type.id">@{{ type.name }}</option>
                                                    </select>
                                                </div>
                                                <div class="input-field">
                                                    <input type="number" :class="[(exportQuantity>0 && exportQuantity<=exportQuantityMax) ? 'valid' : 'invalid']" id="export_quantity" v-model="exportQuantity" />
                                                    <label class="active" for="export_quantity">Количество(кг) &mdash; Остаток(@{{ exportQuantityMax }})</label>
                                                </div>
        
                                                <div class="my-input-field">
                                                    <p><label>Куда</label></p>
                                                    <select v-model="exportRawTo" class="browser-default">
                                                        <option v-for="to in exportTo" :value="to.id">@{{ to.name }}</option>
                                                    </select>
                                                </div>
        
                                                <button class="btn btn-small waves-effect waves-light" :class="{ 'disabled' : exportInvalid } " name="action" @click="addExport">@{{ exportChange ? "Изменить" : "Добавить" }}
                                                </button>
        
                                                <button v-if="exportChange" class="btn btn-small waves-effect waves-light red" type=button @click="cancelEditExport">Отменить
                                                </button>
                                                <span v-show="false" ref="activate" class="activator"></span>
                                            </div>
                                            <div class="card-reveal" :class="exportSuccess ? 'green lighten-5' : 'red lighten-5' ">
        
                                                <div v-if="exportSuccess" class="export-success">
        
                                                    <table>
        
                                                        <tr>
                                                            <th>Сырье</th>
                                                            <td>@{{ exportStore.raw_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Фирма</th>
                                                            <td>@{{ exportStore.firm_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Кол-во (кг)</th>
                                                            <td>@{{ exportStore.quantity }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Тип</th>
                                                            <td>@{{ rawTypes.find(type => type.id == exportStore.type).name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Кому</th>
                                                            <td>@{{ exportTo.find(to => to.id == exportStore.to).name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Дата</th>
                                                            <td>@{{ exportStore.date }}</td>
                                                        </tr>
        
                                                        {{--<tr>
                                                            <th>FIFO</th>
                                                            <td>
                                                                <div v-for="fifo in exportStore.fifos" class="my-fifos">
                                                                    <span class = "my-fifo-badge green" >@{{ fifo.seria }} (@{{ fifo.quantity }} кг)</span>
                                                                </div>
        
                                                            </td>
                                                        </tr>--}}
                                                    </table>
        
                                                    <div class="card-action">
                                                        <button class="btn btn-small waves-effect waves-light" @click="okExport">OK</button>
                                                    </div>
        
                                                </div>
        
        
                                                <div v-else class="export-fail">
                                                    <p class="title center-align">Ошибка!</p>
                                                    <p>Данные неправильно введены. Попробуйте снова.</p>
                                                    <div class="card-action">
                                                        <button class="btn btn-small waves-effect waves-light" @click="okExport">OK</button>
                                                    </div>
                                                </div>
        
                                                <span v-show="false" ref="close" class="card-title"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col s8">
                                        <div class="my-export-history">
                                            <table id="my-export-history-table" class="bordered highlight" :class="{ 'content-loading' : exportHistoryLoading }">
                                                <thead>
                                                    <tr>
                                                        <th>Наименование</th>
                                                        <th>Кол-во (кг)</th>
                                                        <th>Дата</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
        
                                                <tbody>
                                                <tr v-for="(history, index) in exportHistory" @click="chooseExportHistory(index)" :class="{'green lighten-5' : (history.status==1), 'red lighten-5' : (history.status==0)}">
                                                    <td>@{{ history.raw_name }} (@{{ rawTypes.find(type => type.id == history.type).name }})</td>
                                                    <td>@{{ history.quantity }}</td>
                                                    <td>@{{ history.date }}</td>
                                                    <td >
                                                        <a v-if="history.status==0" class="waves-effect waves-light blue-text" @click.stop="editExport(index)"><i class="material-icons">edit</i></a>
                                                        <a v-if="history.status==0" class="waves-effect waves-light red-text" @click.stop="deleteExportHistory(index)"><i class="material-icons">delete</i></a>
                                                    </td>
                                                </tr>
        
                                                </tbody>
                                            </table>
                                            <ul class="pagination my-pagination">
                                                <li :class="historyCurrentPage==1 ? 'disabled' : 'waves-effect'" @click="changePage(historyCurrentPage-1)"><a href="#"><i class="material-icons">chevron_left</i></a></li>
                                                <li v-for="page in pages" v-show="pageVisible(page)" :class="historyCurrentPage==page ? 'active' : 'waves-effect'" @click="changePage(page)"><a href="#">@{{ page }}</a></li>
                                                <li :class="historyCurrentPage==pages ? 'disabled' : 'waves-effect'" @click="changePage(historyCurrentPage+1)"><a href="#"><i class="material-icons">chevron_right</i></a></li>
                                            </ul>
        
                                            <div v-if="exportHistory.length>0" id="modal-export-delete" ref="modalExportDelete" class="modal">
                                                <div class="modal-content">
                                                    <h4>Удалить?</h4>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="row">
                                                        <div class="col s4 offset-s1">
                                                            <button class="btn waves-effect waves-light red" @click="deleteExport">Да</button>
                                                        </div>
        
                                                        <div class="col s4 offset-s2">
                                                            <button class="btn waves-effect waves-light modal-close">Нет</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
        
                                            <div v-if="exportHistory.length>0" id="modal-export-show" ref="modalExportShow" class="modal">
                                                <div class="modal-content">
                                                    <h5 class="center-align">Расход <i class="material-icons right modal-close">close</i> </h5>
                                                    <table>
                                                        <tr>
                                                            <th>Наименование</th>
                                                            <td>@{{ exportHistory[chosenIndex].raw_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Фирма</th>
                                                            <td>@{{ exportHistory[chosenIndex].firm_name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Количество (кг)</th>
                                                            <td>@{{ exportHistory[chosenIndex].quantity }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Тип</th>
                                                            <td>@{{ rawTypes.find(type => type.id == exportHistory[chosenIndex].type).name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Кому</th>
                                                            <td>@{{ exportTo.find(to => to.id == exportHistory[chosenIndex].to).name }}</td>
                                                        </tr>
                                                        <tr>
                                                            <th>Дата</th>
                                                            <td>@{{ exportHistory[chosenIndex].date }}</td>
                                                        </tr>
        
                                                        {{--<tr>
                                                            <th>FIFO</th>
                                                            <td>
                                                                <span class = "my-fifo-badge green" v-for="fifo in exportHistory[chosenIndex].fifos">@{{ fifo.seria }} (@{{ fifo.quantity }} кг)</span>
                                                            </td>
                                                        </tr>--}}
                                                    </table>
                                                </div>
                                            </div>
        
        
                                        </div>
                                    </div>
                                </div>
                            </div>

                </div>
            </div>

        </div>
    </div>




</body>

    <!-- Compiled and minified JavaScript -->
    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/vue.js')}}"></script>
    <script src="{{asset('js/materialize.min.js')}}"></script>
    <script src="{{asset('js/axios.min.js')}}"></script>
    <script src="{{asset('js/lodash.min.js')}}"></script>
    <script src="{{asset('js/moment.js')}}"></script>

    <script>

        var app = new Vue({
            el: '#my-app',
            data: {

                status: 1,
                contentLoading: false,
                currentPage: 1,

                menus: [
                    { icon: "cloud_download", name: "Заявка"}
                ],

                firms: [
                    {id: 1, name: 'Стиралка'},
                    {id: 2, name: 'Холодильник'},
                    {id: 3, name: 'Газ плита'},
                    {id: 4, name: 'Экструдер'}],
                currentFirm: 1,
                pervichka: [
                    { name: "Hips", position: "C/C12/R3", quantity: 446},
                    { name: "POM DPO-02W", position: "C/C22/R5", quantity: 1976},
                    { name: "ABS(красный)", position: "B/C12/R3", quantity: 1026},
                    { name: "PS", position: "C/C22/R1", quantity: 89},
                    { name: "Hips", position: "C/C12/R3", quantity: 446},
                    { name: "POM DPO-02W", position: "C/C22/R5", quantity: 1976},
                    { name: "ABS(красный)", position: "B/C12/R3", quantity: 1026},
                    { name: "PS", position: "C/C22/R1", quantity: 89}
                ],

                vtorichka: [
                    { name: "Hips", position: "C/C12/R3", quantity: "446"},
                    { name: "POM DPO-02W", position: "C/C22/R5", quantity: "1976"},
                    { name: "ABS(красный)", position: "B/C12/R3", quantity: "1026"},
                    { name: "PS", position: "C/C22/R1", quantity: "89"}
                ],

                granula: [
                    { name: "Hips", position: "C/C12/R3", quantity: "446"},
                    { name: "POM DPO-02W", position: "C/C22/R5", quantity: "1976"},
                    { name: "ABS(красный)", position: "B/C12/R3", quantity: "1026"},
                    { name: "PS", position: "C/C22/R1", quantity: "89"}
                ],

                raws: [
                    { id: 1, name: 'ABS' },
                    { id: 2, name: 'HIPS' },
                    { id: 3, name: 'POM' },
                    { id: 4, name: 'PP' },
                    { id: 5, name: 'PS' }
                ],
                rawTypes: [{ id: 1, name: "Первичка"}, { id: 2, name: "Вторичка" }, {id: 0, name: "Гранула" }],



                importStore: {
                    id: 2,
                    raw_id: 1,
                    raw_name: "Термоэластамер (AP8175W-AB) China",
                    firm_id: 1,
                    firm_name: "Стиралка",
                    quantity: 20,
                    type: 1,
                    isNew: true,
                    seria: 'A-1235',
                    supplier: 'Samsung',
                    date: "21.08.18"
                },
                pages: 1,
                historyCurrentPage: 1,

                exportTo: [ { id: -1, name: 'Производство'}, { id: -2, name: 'Гранулятор'}, { id: -3, name: 'Завод' }],
                exportHistory:[
                    { id: 2, status:0, name: "HIPS", quantity: 1096, type: 0, to: -1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 1, status:1, name: "Термоэластамер (AP8175W-AB) China", quantity: 96, type: 2, to: 1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 3, status:1, name: "PS", quantity: 34, type: 1, to: 2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 4, status:1, name: "POM", quantity: 154, type: 0, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 5, status:1, name: "HIPS", quantity: 1096, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 6, status:1, name: "ABS", quantity: 96, type: 2, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 7, status:1, name: "PP", quantity: 34, type: 1, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 8, status:1, name: "POM", quantity: 154, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 9,  status:0, name: "HIPS", quantity: 1096, type: 0, to:0, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 10, status:0,  name: "ABS", quantity: 96, type: 2, to:0, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 15, status:0,  name: "PP", quantity: 34, type: 1, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 14, status:0,  name: "POM", quantity: 154, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 12, status:0,  name: "HIPS", quantity: 1096, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 11, status:0,  name: "ABS", quantity: 96, type: 2, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 18, status:0,  name: "PP", quantity: 34, type: 1, to:0, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]}
                ],

                exportStore: {
                    id: 2,
                    raw_id: 1,
                    raw_name: "Термоэластамер (AP8175W-AB) China",
                    firm_id: 1,
                    firm_name: "Стиралка",
                    quantity: 20,
                    type: 1,
                    to: -1,
                    date: "21.08.18",
                    status: 1
                },

                exportHistoryLoading: false,
                exportChosen: undefined,
                exportFirm: 0,
                exportRaw: 0,
                exportQuantity: 1,
                exportQuantityMax: 0,
                exportRawType: 1,
                exportRawTo: -1,
                exportChange: false,
                exportSuccess: true,
                exportDelete: 0,
                chosenIndex: 0,
                exportRawLoading: false,
                exportMaxQuantityLoading: false,
                exportAdding: false
            },
            computed:{

                exportInvalid: function(){
                    return this.exportRawLoading || this.exportAdding || this.exportMaxQuantityLoading || this.exportQuantity<=0 || this.exportQuantity>this.exportQuantityMax;
                },

                exportPotentialQuantity: function(){
                    if(this.exportChange && this.exportChosen && this.exportChosen.firm_id==this.exportFirm
                        && this.exportChosen.raw_id==this.exportRaw && this.exportChosen.type==this.exportRawType){
                        return this.exportChosen.quantity;
                    }

                    return 0;
                }
            },
            watch: {

                exportFirm: function(){
                    var self = this;
                    self.exportRawLoading = true;
                    axios.get('/api/firms/'+self.exportFirm+'/raws')
                        .then(function(response){
                            self.raws = response.data.data;
                            self.exportRaw = self.raws.find(raw => raw.id==self.exportRaw) ? self.exportRaw : self.raws[0].id;
                            self.exportRawLoading = false;
                        });
                },

                exportRaw: function(){
                    this.getExportQuantityMax();
                },

                exportRawType: function(){
                    this.getExportQuantityMax();
                }
            },
            methods: {

                pageVisible: function(page){
                    var left = this.historyCurrentPage - (this.historyCurrentPage<this.pages-2 ? 2 : this.historyCurrentPage-this.pages+4);
                    var right = this.historyCurrentPage + (this.historyCurrentPage>2 ? 2 : 5-this.historyCurrentPage);
                    return page>=left && page<=right;
                },

                changeFirm: function(id){
                    var self = this;
                    this.contentLoading = true;
                    axios.get('/api/firms/'+id+'/balance')
                        .then(function(response){
                            self.pervichka = response.data.data.pervichka;
                            self.vtorichka = response.data.data.vtorichka;
                            self.granula = response.data.data.granula;
                            self.contentLoading = false;
                        });

                },

                changePage: function(page){
                    var self = this;
                    self.exportHistoryLoading = true;
                    self.importHistoryLoading = true;
                    axios.get(('/api/export?paginate=1') +'&page='+page)
                        .then(function(response){
                            self.exportHistory = response.data.data;
                            self.historyCurrentPage = response.data.meta.current_page;
                            self.pages = response.data.meta.last_page;
                            self.exportHistoryLoading = false;
                            self.importHistoryLoading = false;
                        }).catch(function(error){
                            self.status = 0;
                        });
                },

                getExportQuantityMax: function(){
                    this.exportMaxQuantityLoading = true;
                    var self = this;
                    axios.get('/api/export/quantity', {
                        params:{
                            firm_id: self.exportFirm,
                            raw_id: self.exportRaw,
                            type: self.exportRawType
                        }
                    }).then(function(response){
                        self.exportQuantityMax = parseFloat(response.data.data)+parseFloat(self.exportPotentialQuantity);
                        self.exportMaxQuantityLoading = false;
                    }).catch(function(error){
                        self.exportQuantityMax = 0;
                    });
                },

                editExport: function(index){
                    this.exportChange = true;
                    this.exportChosen = this.exportHistory[index];
                    this.exportFirm = this.exportChosen.firm_id;
                    this.exportRaw = this.exportChosen.raw_id;
                    this.exportRawType = this.exportChosen.type;
                    this.exportQuantity = this.exportChosen.quantity;
                    this.exportRawTo = this.exportChosen.to;
                    this.getExportQuantityMax();
                },

                cancelEditExport: function(){
                    this.exportChange = false;
                    this.exportFirm = this.firms[0].id;
                    this.exportQuantity = 1;
                    this.exportRawType = 1;
                    this.exportRawTo = -1;
                    this.getExportQuantityMax();
                },


                deleteExport: function(){
                    var self = this;
                    var modalShow = M.Modal.getInstance(this.$refs.modalExportDelete);
                    modalShow.close();
                    axios.post('api/export/'+self.exportHistory[self.chosenIndex].id, {
                        _method: 'DELETE'
                    }).then(function(response){
                        console.log(response.data);
                        self.getExportQuantityMax();
                        return axios.get('/api/export?paginate=1' +'&page='+((self.exportHistory.length==1) ? self.historyCurrentPage-1 : self.historyCurrentPage));
                    }).then(function(response){
                        self.exportHistory = response.data.data;
                        self.historyCurrentPage = response.data.meta.current_page;
                        self.pages = response.data.meta.last_page;
                    }).catch(function(){

                    });
                },

                deleteExportHistory: function(index){
                    this.chosenIndex = index;
                    var self = this;
                    var modalShow = M.Modal.init(this.$refs.modalExportDelete, {
                        onCloseEnd: function(){
                            self.chosenIndex = 0;
                        }
                    });
                    modalShow.open();
                },


                addExport: function(){
                    this.exportAdding = true;
                    var self = this;
                    if(this.exportChange){
                        axios.post('/api/export/request'+self.exportChosen.id, {
                            firm_id: self.exportFirm,
                            raw_id: self.exportRaw,
                            type: self.exportRawType,
                            quantity: self.exportQuantity,
                            to: self.exportRawTo,
                            status: 0,
                            _method: 'PUT'
                        }).then(function(response){
                            console.log(response.data);
                            self.exportStore = response.data.data;
                            self.exportSuccess = true;
                            self.$refs.activate.click();
                            self.exportQuantityMax -= self.exportStore.quantity;
                            var index = self.exportHistory.findIndex(exportItem => exportItem.id==self.exportChosen.id);
                            if(index != -1){
                                self.exportHistory[index] = self.exportStore;
                            }
                            self.exportChange = false;
                        }).catch(function(error){

                            if(error.response.status == 400){
                                self.exportSuccess = false;
                                self.$refs.activate.click();
                                console.log(error.response.data);
                            }
                            console.log(error);
                        }).then(function(){
                            self.exportAdding = false;
                        });
                    }else{
                        axios.post('/api/export/request', {
                            firm_id: self.exportFirm,
                            raw_id: self.exportRaw,
                            type: self.exportRawType,
                            quantity: self.exportQuantity,
                            to: self.exportRawTo,
                            status: 0
                        }).then(function(response){
                            self.exportStore = response.data.data;
                            self.exportSuccess = true;
                            self.$refs.activate.click();
                            self.exportQuantityMax -= self.exportStore.quantity;
                            return axios.get('/api/export?paginate=1' +'&page='+self.historyCurrentPage);

                        }).then(function(response){
                            self.exportHistory = response.data.data;
                            self.pages = response.data.meta.last_page;
                        }).catch(function(error){

                            if(error.response.status == 400){
                                self.exportSuccess = false;
                                self.$refs.activate.click();
                                console.log(error.response.data);
                            }
                            console.log(error);
                        }).then(function(){
                            self.exportAdding = false;
                        });
                    }
                },

                okExport: function(){
                    this.exportFirm = this.firms[0].id;
                    this.exportRawType = 1;
                    this.exportRawTo = -1;
                    this.exportQuantity = 1;
                    this.$refs.close.click();
                },


                chooseExportHistory: function(index){
                    this.chosenIndex = index;
                    var self = this;
                    var modalShow = M.Modal.init(this.$refs.modalExportShow, {
                        onCloseEnd: function(){
                            self.chosenIndex = 0;
                        }
                    });
                    modalShow.open();

                }

            },

            mounted: function(){
                //M.AutoInit();
                var self = this;
                axios.get('/api/firms')
                    .then(function(response){
                        self.firms = response.data.data;
                        self.exportFirm = self.firms[0].id;
                        return axios.get('/api/firms/'+self.firms[0].id+'/raws');
                    }).then(function(response){
                        self.raws = response.data.data;
                        if(self.exportRaw == self.raws[0].id)
                            self.getExportQuantityMax();
                        else
                            self.exportRaw = self.raws[0].id;
                        return axios.get('/api/export?paginate=1');
                    }).then(function(response){
                        self.exportHistory = response.data.data;
                        self.historyCurrentPage = response.data.meta.current_page;
                        self.pages = response.data.meta.last_page;
                        self.status = 1;
                    }).catch(function(error){
                        self.status = 0;
                    });
            }

        });

    </script>
</html>