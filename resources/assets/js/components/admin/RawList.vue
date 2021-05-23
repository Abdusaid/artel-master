<template>
    <div class="card">
        <span class="title blue lighten-2">Список сырья</span>
        <div class="card-content">
            <input class="browser-default search-input" type="text" placeholder="Поиск..." v-model="search"/>

            <table class="highlight">
                <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Родитель</th>
                    <th>Фирмы</th>
                    <th></th>
                </tr>
                </thead>

                <tbody>
                <tr v-for="raw in filteredRaws" @click="choose(raw)">
                    <td>{{ raw.name }} {{ raw.color_id ? `(${raw.color_name})` : ``  }}</td>
                    <td>{{ raw.parent_name }}</td>
                    <td>
                        <span v-for="(firm,index) in raw.firm_names">{{ (index!=0 ? ', ' : '') + firm }}</span>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<style scoped>
    .card, .card-content{
        height: 100%;
    }
    .card-content{
        padding: 10px;
        overflow-x: scroll;

    }
    .card{
    }

    .search-input{
        width: 40%;
        float: right;
        padding: 5px;
    }
</style>
<script>

    export default{
        data(){
            return {
                raws: [],
                search: ''
            }
        },

        computed: {
            filteredRaws(){
                var orderedRaws = _.orderBy(this.raws,[raw => raw.name.toLowerCase() ], 'name');
                return orderedRaws.filter((row) => {
                    return row['name'].toLowerCase().includes(this.search.toLowerCase().trim());
                });
            }
        },

        methods: {

            choose(raw){
                this.$emit('choose', raw);
            },

            add(raw){
                this.raws.push(raw);
            },

            deleteRaw(raw){
                var index = this.raws.indexOf(raw);
                this.raws.splice(index, 1);
            },

            loadRaws(){
                axios.get('/api/raw')
                    .then((response) => {
                        this.raws =  response.data.data;
                    });
            }
        },

        created(){
            this.loadRaws();
        }


    }
</script>