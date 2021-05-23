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
        <div class = "my-sidebar green lighten-4">

            <div class = "my-nav green lighten-2">
                <p>Artel</p>
            </div>

            <ul class="my-menu" v-cloak>
                <li v-for="(menu, index) in menus" class="my-menu-item" :class="{ 'green lighten-3' : currentPage==index }" @click="changeMenu(index)"><i class="material-icons">@{{ menu.icon }}</i> @{{ menu.name }}</li>
            </ul>
        </div>

        <div  id="my-body">
            <div class="my-nav green lighten-1">

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

                    <div v-if="currentPage==0" class="my-remainder-content">
                        <div class="row">
                            <div class="col s12 firms-list">
                                <ul class="tabs" ref="firmsTab">
                                    <li class="tab" v-for="(firm, index) in firms" @click="changeFirm(firm.id)" ><a :href="'#firm'+index">@{{ firm.name }}</a></li>
                                </ul>
                            </div>

                        </div>
                        <div v-if="false" v-for="(firm, index) in firms" :id="'firm'+index" class="col s12">

                        </div>
                        <div class="row" :class=" {  'content-loading' : contentLoading }">
                            <my-table :table="pervichka" tittle="Первичка"></my-table>
                            <my-table :table="vtorichka" tittle="Вторичка"></my-table>
                            <my-table :table="granula" tittle="Гранула"></my-table>
                        </div>
                    </div>

                    <div v-if="currentPage==1" class="my-import-content">

                        <div class="row">
                            <div class="col s4">
                                <div class="my-add card">
                                    <div class="card-content">
                                        <div class="my-input-field">
                                            <p><label>Фирма</label></p>
                                            <select v-model="importFirm" class="browser-default">
                                                <option v-for="firm in firms" :value="firm.id">
                                                    @{{ firm.name }}
                                                </option>
                                            </select>
                                        </div>

                                        <div class="my-input-field">
                                            <p><label>Сырье</label></p>
                                            <select v-model="importRaw" class="browser-default" :class="{ 'content-loading' : importRawLoading }">
                                                <option v-for="raw in raws" :value="raw.id" >@{{ raw.name }}</option>
                                            </select>
                                        </div>

                                        <div class="my-input-field">
                                            <p><label>Тип</label></p>
                                            <select v-model="importRawType" class="browser-default">
                                                <option v-for="(type, index) in rawTypes" :value="type.id" >@{{ type.name }}</option>
                                            </select>
                                        </div>

                                        <div class="input-field">
                                            <input type="number" :class="importQuantity>0 ? 'valid' : 'invalid'" id="import_quantity" v-model="importQuantity" />
                                            <label class="active" for="import_quantity">Количество (кг)</label>
                                        </div>


                                        <p class="my-checkbox">
                                            <label>
                                                <input type="checkbox" v-model="importIsNew"/>
                                                <span>Новый</span>
                                            </label>
                                        </p>

                                        <div v-if="importRawType==1 && importIsNew" class="input-field">
                                            <input type="text"  :class=" importSeria ? 'valid' : 'invalid'" id="import_seria" v-model="importSeria"/>
                                            <label for="import_seria" :class="{ active : importSeria!=null}">Номер серии</label>
                                        </div>

                                        <div v-if="importRawType==1 && importIsNew" class="input-field">
                                            <input type="text" :class=" importSupplier ? 'valid' : 'invalid'" id="import_supplier" v-model="importSupplier"/>
                                            <label for="import_supplier" :class="{ active : importSupplier!=null}">Поставщик</label>
                                        </div>

                                        <button class="btn btn-small waves-effect waves-light" :class="{ 'disabled' : importInvalid } " @click="addImport">@{{ importChange ? "Изменить" : "Добавить" }}
                                        </button>

                                        <button v-if="importChange" class="btn btn-small waves-effect waves-light red" type=button @click="cancelEditImport">Отменить
                                        </button>
                                        <span v-show="false" ref="iActivate" class="activator"></span>
                                    </div>
                                    <div class="card-reveal" :class="importSuccess ? 'green lighten-5' : 'red lighten-5'">
                                        <div v-if="importSuccess" class="export-success">
                                            <table>

                                                <tr>
                                                    <th>Сырье</th>
                                                    <td>@{{ importStore.raw_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Фирма</th>
                                                    <td>@{{ importStore.firm_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Кол-во (кг)</th>
                                                    <td>@{{ importStore.quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Тип</th>
                                                    <td>@{{ rawTypes.find(type => type.id == importStore.type).name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Новый</th>
                                                    <td><i class="material-icons">@{{ importStore.isNew ? "check" : "close"}}</i></td>
                                                </tr>
                                                <tr v-if="importStore.type == 1 && importStore.isNew">
                                                    <th>Номер серии</th>
                                                    <td>@{{ importStore.seria }}</td>
                                                </tr>

                                                <tr v-if="importStore.type == 1 && importStore.isNew">
                                                    <th>Поставщик</th>
                                                    <td>@{{ importStore.supplier }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Дата</th>
                                                    <td>@{{ importStore.date }}</td>
                                                </tr>

                                            </table>

                                            <div class="card-action">
                                                <button class="btn btn-small waves-effect waves-light" @click="okImport">OK</button>
                                            </div>
                                        </div>

                                        <div v-else class="export-fail">
                                            <p class="title center-align">Ошибка!</p>
                                            <p>Данные неправильно введены. Попробуйте снова.</p>
                                            <div class="card-action">
                                                <button class="btn btn-small waves-effect waves-light" @click="okImport">OK</button>
                                            </div>
                                        </div>

                                        <span v-show="false" ref="iClose" class="card-title"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col s8">
                                <div class="my-import-history">
                                    <table id="my-import-history-table" class="bordered highlight" :class="{ 'content-loading' : importHistoryLoading }">
                                        <thead>
                                        <tr>
                                            <th>Наименование</th>
                                            <th>Кол-во (кг)</th>
                                            <th>Дата</th>
                                            <th></th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(history, index) in importHistory" @click="chooseImportHistory(index)" :class="{'green lighten-5' : (history.status==1), 'lime lighten-5' : (history.status==2), 'red lighten-5' : (history.status==3)}">
                                                <td>@{{ history.raw_name }} (@{{ rawTypes.find(type => type.id==history.type).name }})</td>
                                                <td>@{{ history.quantity }}</td>
                                                <td>@{{ history.date }}</td>
                                                <td>
                                                    <a v-if="isToday(history.date)" class="waves-effect waves-light blue-text" @click.stop="editImport(index)"><i class="material-icons">edit</i></a>
                                                    <a v-if="isToday(history.date)" class="waves-effect waves-light red-text" @click.stop="deleteImportHistory(index)"><i class="material-icons">delete</i></a>
                                                </td>

                                            </tr>

                                        </tbody>
                                    </table>
                                    <ul class="pagination my-pagination">
                                        <li :class="historyCurrentPage==1 ? 'disabled' : 'waves-effect'" @click="changePage(historyCurrentPage-1)"><a href="#"><i class="material-icons">chevron_left</i></a></li>
                                        <li v-for="page in pages" v-show="pageVisible(page)" :class="historyCurrentPage==page ? 'active' : 'waves-effect'" @click="changePage(page)"><a href="#">@{{ page }}</a></li>
                                        <li :class="historyCurrentPage==pages ? 'disabled' : 'waves-effect'" @click="changePage(historyCurrentPage+1)"><a href="#"><i class="material-icons">chevron_right</i></a></li>
                                    </ul>
                                    <div v-if="importHistory.length>0" id="modal-import-delete" ref="modalImportDelete" class="modal">
                                        <div class="modal-content">
                                            <h4>Удалить?</h4>
                                        </div>
                                        <div class="modal-footer">
                                            <div class="row">
                                                <div class="col s4 offset-s1">
                                                    <button class="btn waves-effect waves-light red" @click="deleteImport">Да</button>
                                                </div>

                                                <div class="col s4 offset-s2">
                                                    <button class="btn waves-effect waves-light modal-close">Нет</button>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <div v-if="importHistory.length>0" id="modal-import-show" ref="modalImportShow" class="modal" >
                                        <div class="modal-content">
                                            <h5 class="center-align">Приход <i class="material-icons right modal-close">close</i> </h5>
                                            <table class="bordered">
                                                <tr>
                                                    <th>Наименование</th>
                                                    <td>@{{ importHistory[chosenIndex].raw_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Фирма</th>
                                                    <td>@{{ importHistory[chosenIndex].firm_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Количество (кг)</th>
                                                    <td>@{{ importHistory[chosenIndex].quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Тип</th>
                                                    <td>@{{ rawTypes.find(type => type.id==importHistory[chosenIndex].type).name }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Новый</th>
                                                    <td><i class="material-icons">@{{ importHistory[chosenIndex].isNew ? "check" : "close"}}</i></td>
                                                </tr>

                                                <tr v-if="importHistory[chosenIndex].type == 1 && importHistory[chosenIndex].isNew">
                                                    <th>Номер серии</th>
                                                    <td>@{{ importHistory[chosenIndex].seria }}</td>
                                                </tr>

                                                <tr v-if="importHistory[chosenIndex].type == 1 && importHistory[chosenIndex].isNew">
                                                    <th>Поставщик</th>
                                                    <td>@{{ importHistory[chosenIndex].supplier }}</td>
                                                </tr>

                                                <tr>
                                                    <th>Дата</th>
                                                    <td>@{{ importHistory[chosenIndex].date }}</td>
                                                </tr>

                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div v-if="currentPage==2" class="my-export-content">
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
                                <div v-for="exp in requestedExports" class="my-add card">
                                    <div class="card-content">

                                        <div class="my-input-field">
                                            <p><label>Фирма</label></p>
                                            <div class="select-style">
                                                <p>@{{exp.firm_name}}</p>
                                            </div>
                                        </div>

                                        <div class="my-input-field">
                                            <p><label>Сырье</label></p>
                                            <div class="select-style">
                                                <p>@{{exp.raw_name}}</p>
                                            </div>
                                        </div>

                                        <div class="my-input-field">
                                            <p><label>Тип</label></p>
                                            <div class="select-style">
                                                <p>@{{ rawTypes.find(type => type.id == exp.type).name }}</p>
                                            </div>
                                        </div>

                                        <div class="my-input-field">
                                            <p><label>Количество(кг)</label></p>
                                            <div class="select-style">
                                                <p>@{{exp.quantity}}</p>
                                            </div>
                                        </div>

                                        <div class="my-input-field">
                                            <p><label>Куда</label></p>
                                            <div class="select-style">
                                                <p>@{{ exportTo.find(to => to.id == exp.to).name }}</p>
                                            </div>
                                        </div>

                                        <button class="btn btn-small waves-effect waves-light green" name="action" @click="confirmExport(exp)">Подтвердить</button>

                                        <button v-if="exportChange" class="btn btn-small waves-effect waves-light red" type=button @click="cancelEditExport">Отменить
                                        </button>
                                        <span v-show="false" class="activator"></span>
                                    </div>
                                    <div class="card-reveal" :class="exportSuccess ? 'green lighten-5' : 'red lighten-5' ">

                                        <div v-if="exportSuccess" class="export-success">

                                            <table>

                                                <tr>
                                                    <th>Сырье</th>
                                                    <td>@{{ exp.raw_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Фирма</th>
                                                    <td>@{{ exp.firm_name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Кол-во (кг)</th>
                                                    <td>@{{ exp.quantity }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Тип</th>
                                                    <td>@{{ rawTypes.find(type => type.id == exp.type).name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Кому</th>
                                                    <td>@{{ exportTo.find(to => to.id == exp.to).name }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Дата</th>
                                                    <td>@{{ exp.date }}</td>
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

                                        <span v-show="false" class="card-title"></span>
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
                                        <tr v-for="(history, index) in exportHistory" @click="chooseExportHistory(index)" >
                                            <td>@{{ history.raw_name }} (@{{ rawTypes.find(type => type.id == history.type).name }})</td>
                                            <td>@{{ history.quantity }}</td>
                                            <td>@{{ history.date }}</td>
                                            <td >
                                                <a v-if="isToday(history.date)" class="waves-effect waves-light blue-text" @click.stop="editExport(index)"><i class="material-icons">edit</i></a>
                                                <a v-if="isToday(history.date)" class="waves-effect waves-light red-text" @click.stop="deleteExportHistory(index)"><i class="material-icons">delete</i></a>
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


        Vue.component('my-table', {
            props: {
                table: Array,
                tittle: String
            },
            data: function(){
                return {
                    searchPervichka: '',
                    orderKey: 0,
                    orderType: true,
                    columns: ['name', 'position', 'quantity']
                }
            },
            computed: {
                filteredTable: function(){
                    var self = this;
                    var orderedTable = _.orderBy(self.table, self.columns[self.orderKey], self.orderType ? 'asc' : 'desc');
                    return orderedTable.filter(function(row){
                       return row.name.toLowerCase().includes(self.searchPervichka.toLowerCase().trim());
                    });
                }
            },
            methods: {
                orderBy: function(key){
                    if(key == this.orderKey)
                        this.orderType = this.orderType ? false : true;
                    else
                        this.orderType = true;

                    this.orderKey = key;
                }
            },
            template:`
                <div class="my-table-div">
                    <div class="my-table-name col s12">
                        <div class="col s8">
                            <p>@{{ tittle }}</p>
                        </div>
                        <div class="input-field col s4">
                            <i class="material-icons prefix">search</i>
                            <input class="search" type="text" placeholder="Поиск" v-model="searchPervichka"/>
                        </div>
                    </div>

                    <table class="stripe row-border">
                        <col width="40%">
                        <col width="25%">
                        <col width="25%">
                        <thead>
                            <tr>
                                <th @click="orderBy(0)">
                                    Наименование
                                    <span class="right my-order-icons">
                                        <span class="arrow-up" :class="orderKey==0 ? orderType==true ? 'order-active' : 'order-inactive' : ''"></span>
                                        <span class="arrow-down" :class="orderKey==0 ? orderType==true ? 'order-inactive' : 'order-active' : ''"></span>
                                    </span>
                                </th>
                                <th @click="orderBy(1)">
                                    Позиция
                                    <span class="right my-order-icons">
                                        <span class="arrow-up" :class="orderKey==1 ? orderType==true ? 'order-active' : 'order-inactive' : ''"></span>
                                        <span class="arrow-down" :class="orderKey==1 ? orderType==true ? 'order-inactive' : 'order-active' : ''"></span>
                                    </span>
                                </th>
                                <th @click="orderBy(2)">
                                    Количество
                                    <span class="right my-order-icons">
                                        <span class="arrow-up" :class="orderKey==2 ? orderType==true ? 'order-active' : 'order-inactive' : ''"></span>
                                        <span class="arrow-down" :class="orderKey==2 ? orderType==true ? 'order-inactive' : 'order-active' : ''"></span>
                                    </span>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="tab in filteredTable">
                                <td>@{{ tab.name }}</td>
                                <td>@{{ tab.position }}</td>
                                <td>@{{ tab.quantity }} @{{ tab.quantity!=tab.valid_quantity ? '('+tab.valid_quantity+')' : '' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            `
        });


        var app = new Vue({
            el: '#my-app',
            data: {
                status: 1,
                contentLoading: false,
                currentPage: 0,

                menus: [
                    { icon: "storage", name: "Остаток"},
                    { icon: "cloud_download", name: "Приход"},
                    { icon: "cloud_upload", name: "Расход"}
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

                importHistory: [
                    { id: 2, raw_id: 2, raw_name: "HIPS", quantity: 1096, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 1, raw_id: 2, raw_name: "Термоэластамер (AP8175W-AB) China", quantity: 96, type: 2, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 3, raw_id: 2, raw_name: "PP", quantity: 34, type: 1, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 4, raw_id: 2, raw_name: "POM", quantity: 154, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 5, raw_id: 2, raw_name: "HIPS", quantity: 1096, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 6, raw_id: 2, raw_name: "ABS", quantity: 96, type: 2, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 7, raw_id: 2, raw_name: "PP", quantity: 34, type: 1, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 8, raw_id: 2, raw_name: "POM", quantity: 154, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 9, raw_id: 2, raw_name: "HIPS", quantity: 1096, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 10, raw_id: 2, raw_name: "ABS", quantity: 96, type: 2, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 15, raw_id: 2, raw_name: "PP", quantity: 34, type: 1, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 14, raw_id: 2, raw_name: "POM", quantity: 154, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 12, raw_id: 2, raw_name: "HIPS", quantity: 1096, type: 0, isNew: true, seriaNumber: "A-1234", supplier: "Samsung", date: "2018-08-01"},
                    { id: 11, raw_id: 2, raw_name: "ABS", quantity: 96, type: 2, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"},
                    { id: 18, raw_id: 2, raw_name: "PP", quantity: 34, type: 1, isNew: false, seriaNumber: null, supplier: null, date: "2018-08-01"}

                ],

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
                importHistoryLoading: false,
                importChosen: undefined,
                importFirm: 0,
                importRaw: 0,
                importQuantity: 1,
                importRawType: 1,
                importIsNew: false,
                importSeria: null,
                importSupplier: null,
                importChange: false,
                importDelete: null,
                importSuccess: true,
                importRawLoading: false,
                importAdding: false,

                exportTo: [ { id: -1, name: 'Производство'}, { id: -2, name: 'Гранулятор'}, { id: -3, name: 'Завод' }],
                exportHistory:[
                    { id: 2, name: "HIPS", quantity: 1096, type: 0, to: -1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 1, name: "Термоэластамер (AP8175W-AB) China", quantity: 96, type: 2, to: 1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 3, name: "PS", quantity: 34, type: 1, to: 2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 4, name: "POM", quantity: 154, type: 0, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 5, name: "HIPS", quantity: 1096, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 6, name: "ABS", quantity: 96, type: 2, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 7, name: "PP", quantity: 34, type: 1, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 8, name: "POM", quantity: 154, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 9, name: "HIPS", quantity: 1096, type: 0, to:0, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 10, name: "ABS", quantity: 96, type: 2, to:0, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 15, name: "PP", quantity: 34, type: 1, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 14, name: "POM", quantity: 154, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 12, name: "HIPS", quantity: 1096, type: 0, to:1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 11, name: "ABS", quantity: 96, type: 2, to:2, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                    { id: 18, name: "PP", quantity: 34, type: 1, to:0, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]}
                ],
                requestedExports:[
                    { id: 2, name: "HIPS", quantity: 1096, type: 0, to: -1, date: "2018-08-01", fifo: [{seria: 'A-1234', quantity: 20}, {seria: 'B-4321', quantity: 10}, {seria: 'A-14', quantity: 4}]},
                ],

                exportStore: {
                    id: 2,
                    raw_id: 1,
                    raw_name: "Термоэластамер (AP8175W-AB) China",
                    firm_id: 1,
                    firm_name: "Стиралка",
                    quantity: 20,
                    type: 1,
                    status: 0,
                    to: -1,
                    date: "21.08.18"
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

                importInvalid: function(){
                    return this.importRawLoading || this.importAdding || this.importQuantity <= 0
                        || ( !this.importSupplier && this.importIsNew && this.importRawType==1)
                        || ( !this.importSeria && this.importIsNew && this.importRawType==1);
                },

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

                importFirm: function(){
                    var self = this;
                    self.importRawLoading = true;
                    axios.get('/api/firms/'+self.importFirm+'/raws')
                        .then(function(response){
                            self.raws = response.data.data;
                            self.importRaw = self.raws.find(raw => raw.id==self.importRaw) ? self.importRaw : self.raws[0].id;
                            self.importRawLoading = false;
                        });
                },

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
                isToday: function(date){
                    return moment().isSame(moment(date, "DD.MM.YY, HH:mm"), 'year');
                },

                pageVisible: function(page){
                    var left = this.historyCurrentPage - (this.historyCurrentPage<this.pages-2 ? 2 : this.historyCurrentPage-this.pages+4);
                    var right = this.historyCurrentPage + (this.historyCurrentPage>2 ? 2 : 5-this.historyCurrentPage);
                    return page>=left && page<=right;
                },

                changeMenu: function(index){

                    this.status = 2;
                    this.currentPage=index;
                    var self = this;
                    if(index == 0){
                        axios.get('/api/firms')
                            .then(function(response){
                                self.firms = response.data.data;
                                return axios.get('/api/firms/'+self.firms[0].id+'/balance');
                            })
                            .then(function(response){
                                self.pervichka = response.data.data.pervichka;
                                self.vtorichka = response.data.data.vtorichka;
                                self.granula = response.data.data.granula;
                                self.status = 1;
                                var instance = M.Tabs.init(self.$refs.firmsTab);
                            })
                            .catch(function(error){
                                self.status = 0;
                            });
                    }else if(index == 1){
                        axios.get('/api/firms')
                            .then(function(response){
                                self.firms = response.data.data;
                                self.importFirm = self.firms[0].id;
                                return axios.get('/api/firms/'+self.firms[0].id+'/raws');
                            }).then(function(response){
                                self.raws = response.data.data;
                                self.importRaw = self.raws[0].id;
                                return axios.get('/api/import?paginate=1');
                            }).then(function(response){
                                self.importHistory = response.data.data;
                                self.historyCurrentPage = response.data.meta.current_page;
                                self.pages = response.data.meta.last_page;
                                self.status = 1;
                            }).catch(function(error){
                                self.status = 0;
                            });


                    }else if(index == 2){
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
                                return axios.get('/api/export?paginate=1&status=1');
                            }).then(function(response){
                                self.exportHistory = response.data.data;
                                self.historyCurrentPage = response.data.meta.current_page;
                                self.pages = response.data.meta.last_page;
                                return axios.get('/api/requestedExport')
                            }).then(function(response){
                                self.requestedExports = response.data.data;
                                self.status = 1;
                            }).catch(function(error){
                                self.status = 0;
                            });
                    }


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
                    axios.get(((self.currentPage == 1) ? '/api/import?paginate=1' : '/api/export?paginate=1') +'&page='+page)
                        .then(function(response){
                            if(self.currentPage == 1)
                                self.importHistory = response.data.data;
                            else
                                self.exportHistory = response.data.data;
                            self.historyCurrentPage = response.data.meta.current_page;
                            self.pages = response.data.meta.last_page;
                            self.exportHistoryLoading = false;
                            self.importHistoryLoading = false;
                        }).catch(function(error){
                            self.status = 0;
                        });
                },

                addImport: function(){
                    this.importAdding = true;
                    var self = this;
                    if(this.importChange){
                        axios.post('/api/import/'+self.importChosen.id, {
                            firm_id: self.importFirm,
                            raw_id: self.importRaw,
                            type: self.importRawType,
                            quantity: self.importQuantity,
                            is_new: self.importIsNew,
                            serial_number: (self.importRawType==1 ? self.importSeria : undefined),
                            supplier: ((self.importRawType==1 && self.importIsNew) ? self.importSupplier : undefined),
                            _method: 'PUT'
                        }).then(function(response){
                            self.importStore = response.data.data;
                            self.importSuccess = true;
                            self.$refs.iActivate.click();
                            var index = self.importHistory.findIndex(importItem => importItem.id==self.importChosen.id);
                            if(index != -1){
                                self.importHistory[index] = self.importStore;
                            }
                            self.importChange = false;
                        }).catch(function(error){

                            if(error.response.status == 400){
                                self.importSuccess = false;
                                self.$refs.iActivate.click();
                                console.log(error.response.data);
                            }
                            console.log(error);
                        }).then(function(){
                            self.importAdding = false;
                        });
                    }else{
                        axios.post('/api/import', {
                            firm_id: self.importFirm,
                            raw_id: self.importRaw,
                            type: self.importRawType,
                            quantity: self.importQuantity,
                            is_new: self.importIsNew,
                            serial_number: (self.importRawType==1 ? self.importSeria : undefined),
                            supplier: (self.importRawType==1 && self.importIsNew ? self.importSupplier : undefined)
                        }).then(function(response){
                            self.importStore = response.data.data;
                            self.importSuccess = true;
                            self.$refs.iActivate.click();

                            return axios.get('/api/import?paginate=1' +'&page='+self.historyCurrentPage);

                        }).then(function(response){
                            self.importHistory = response.data.data;
                            self.pages = response.data.meta.last_page;
                        }).catch(function(error){

                            if(error.response.status == 400){
                                self.importSuccess = false;
                                self.$refs.iActivate.click();
                                console.log(error.response.data);
                            }

                        }).then(function(){
                            self.importAdding = false;
                        });
                    }
                },

                editImport: function(index){
                    this.importChange = true;
                    this.importChosen = this.importHistory[index];
                    this.importFirm = this.importChosen.firm_id;
                    this.importRaw = this.importChosen.raw_id;
                    this.importQuantity = this.importChosen.quantity;
                    this.importRawType = this.importChosen.type;
                    this.importIsNew = this.importChosen.isNew ? true : false;
                    this.importSeria = this.importChosen.seria;
                    this.importSupplier = this.importChosen.supplier;
                },


                cancelEditImport: function(){
                    this.importChange = false;
                    this.importFirm = this.firms[0].id;
                    this.importQuantity = 1;
                    this.importRawType = 1;
                    this.importIsNew = false;
                    this.importSeria = null;
                    this.importSupplier = null;

                },

                deleteImport: function(){
                    var self = this;
                    var modalShow = M.Modal.getInstance(this.$refs.modalImportDelete);
                    modalShow.close();
                    axios.post('/api/import/'+self.importHistory[self.chosenIndex].id, {
                        _method: 'DELETE'
                    }).then(function(response){
                        console.log(response.data);
                        return axios.get('/api/import?paginate=1' +'&page='+((self.exportHistory.length==1) ? self.historyCurrentPage-1 : self.historyCurrentPage));
                    }).then(function(response){
                        self.importHistory = response.data.data;
                        self.historyCurrentPage = response.data.meta.current_page;
                        self.pages = response.data.meta.last_page;
                    }).catch(function(error){
                        console.log(error.response.data);
                    });
                },

                deleteImportHistory: function(index){
                    this.chosenIndex = index;
                    var self = this;
                    var modalShow = M.Modal.init(this.$refs.modalImportDelete, {
                        onCloseEnd: function(){
                            self.chosenIndex = 0;
                        }
                    });
                    modalShow.open();
                },

                chooseImportHistory: function(index){
                    this.chosenIndex = index;
                    var self = this;
                    var modalShow = M.Modal.init(this.$refs.modalImportShow, {
                        onCloseEnd: function(){
                            self.chosenIndex = 0;
                        }
                    });
                    modalShow.open();

                },

                okImport: function(){
                    this.importFirm = this.firms[0].id;
                    this.importRawType = 1;
                    this.importQuantity = 1;
                    this.importIsNew = false;
                    this.importSeria = null;
                    this.importSupplier = null,
                    this.$refs.iClose.click();
                },

                checkSeria: _.debounce(function(){
                    var self = this;
                    if(self.importSeria){
                        axios.get('/api/import/check-seria', {
                            params:{
                                firm_id: self.importFirm,
                                raw_id: self.importRaw,
                                type: self.importRawType,
                                serial_number: self.importSeria
                            }
                        }).then(function(response){
                            self.importSeriaExists = response.data;
                            if(self.importIsNew && self.importSeriaExists){
                                if(self.importChange && self.importChosen.seria==self.importSeria && self.importChosen.isNew){
                                    self.importSeriaError = '';
                                    self.importSeriaValid = true;
                                }else{
                                    self.importSeriaValid = false;
                                    self.importSeriaError = 'Серия уже существует';
                                }
                            }else if(!self.importIsNew && !self.importSeriaExists){
                                self.importSeriaValid = false;
                                self.importSeriaError = 'Серия не совпадают';
                            }else{
                                if(self.importChange && !self.importIsNew && self.importChosen.isNew && self.importChosen.seria==self.importSeria){
                                    self.importSeriaValid = false;
                                    self.importSeriaError = 'Серия не совпадают';
                                }else{
                                    self.importSeriaError = '';
                                    self.importSeriaValid = true;
                                }
                            }


                        });
                    }
                }, 1000),

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

                confirmExport: function(obj){
                    var self = this;
                    axios.post('/api/export/'+obj.id, {
                            firm_id: obj.firm_id,
                            raw_id: obj.raw_id,
                            type: obj.type,
                            quantity: obj.quantity,
                            to: obj.to,
                            status: 1,
                            _method: 'PUT'
                        }).then(function(response){
                            self.exportStore = response.data.data;
                            self.exportSuccess = true;
                            var index = self.exportHistory.length;
                            self.exportHistory.splice(0, 0, self.exportStore);
                            self.exportChange = false;
                            self.requestedExports.splice(obj, 1);
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
                },

                addExport: function(){
                    this.exportAdding = true;
                    var self = this;
                    if(this.exportChange){
                        axios.post('/api/export/'+self.exportChosen.id, {
                            firm_id: self.exportFirm,
                            raw_id: self.exportRaw,
                            type: self.exportRawType,
                            quantity: self.exportQuantity,
                            to: self.exportRawTo,
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
                        axios.post('/api/export', {
                            firm_id: self.exportFirm,
                            raw_id: self.exportRaw,
                            type: self.exportRawType,
                            quantity: self.exportQuantity,
                            to: self.exportRawTo
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
                this.changeMenu(0);

                var elems = document.querySelectorAll('.dropdown-trigger');
                M.Dropdown.init(elems, {
                    coverTrigger: false,
                    alignment: 'right'
                });
            }

        });

    </script>
</html>