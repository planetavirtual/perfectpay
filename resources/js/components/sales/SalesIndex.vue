<template>
    <div>
    	<div class="form-group">
    		<router-link :to="{name: 'createSale'}" class="btn btn-success">Incluir Venda</router-link>
    	</div>
        
        <div class="card">
            <div class="card-body">        
                <form class="container" v-on:submit.prevent="applyFilter()">
                    <div class="row">
                        <div class="col-3 form-group">
                            <select class="form-control" v-model="filters.customer_id">
                                <option v-bind:value="0">Cliente</option>
                                <option v-for="customer, index in customers" v-bind:value="customer.id">{{ customer.name }}</option>
                            </select>
                        </div>
                        <div class="col-3 form-group">
                            <datepicker v-model="filters.period_start" name="uniquename" input-class="form-control" placeholder="Data inicial" format="dd/MM/yyyy" use-utc="true"></datepicker>
                        </div>
                        <div class="col-3 form-group">
                            <datepicker v-model="filters.period_end" name="uniquename" input-class="form-control" placeholder="Data final" format="dd/MM/yyyy" use-utc="true"></datepicker>
                        </div>
                        <div class="col-3 form-group">
                            <button class="btn btn-primary">Filtrar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <br/>

    	<div class="card">
    		<div class="card-header">Lista de Vendas</div>
    		<div class="card-body">
    			<table class="table table-bordered table-striped">
    			<thead>
    				<tr>
    				  <th>Data</th>
                      <th>Quantidade</th>
    				  <th>Produto</th>
    				  <th>Valor</th>
    				  <th>Cliente</th>
                      <th>Status</th>
    				  <th width="170">Ações</th>
    				</tr>
    			</thead>
    			<tbody>
    				<tr v-for="sale, index in sales">
                        <td>{{ sale.date | toDate }}</td>
                        <td>{{ sale.quantity }}</td>
                        <td>{{ sale.product_name }}</td>
                        <td>R$ {{ sale.amount | toCurrency }}</td>
                        <td>{{ sale.customer_name }}</td>
                        <td>{{ sale.status }}</td>
                        <td>
                            <router-link :to="{name: 'editSale', params: {id: sale.id}}" class="btn btn-xs btn-primary">
                                Editar
                            </router-link>
                            <a href="#" class="btn btn-xs btn-danger" v-on:click="deleteEntry(sale.id, index)">
                                Delete
                            </a>
                        </td>
                    </tr>
    			</tbody>
    			</table>
    		</div>
    	</div>
        <br/>
        <div class="card">
            <div class="card-header">Resultados das Vendas</div>
            <div class="card-body">
                <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                      <th>Status</th>
                      <th>Quantidade</th>
                      <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="report, index in reports">
                        <td>{{ report.status }}</td>
                        <td>{{ report.total_sales }}</td>
                        <td>R$ {{ report.total_amount | toCurrency }}</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>
    import Datepicker from 'vuejs-datepicker';

    export default {
        components: {
            Datepicker
        },
        data: function () {
            return {
                filters: {
                    'customer_id': 0,
                    'period_start': '',
                    'period_end': ''
                },
                sales: [],
                reports: [],
                customers: []
            }
        },
        mounted() {
            var app = this;
            axios.get('/api/v1/sales')
                .then(function (resp) {
                    app.sales = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Não foi possivel listar as vendas.");
                });
            axios.get('/api/v1/sales/report')
                .then(function (resp) {
                    app.reports = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Não foi possivel listar os relatórios.");
                });
            axios.get('/api/v1/customers')
                .then(function (resp) {
                    app.customers = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Não foi possivel listar os clientes.");
                });
        },
        methods: {
            deleteEntry(id, index) {
                if (confirm("Tem certeza que deseja excluir?")) {
                    var app = this;
                    axios.delete('/api/v1/sales/' + id)
                        .then(function (resp) {
                            app.sales.splice(index, 1);
                        })
                        .catch(function (resp) {
                            alert("Não foi possivel excluir a venda.");
                        });
                }
            },
            applyFilter() {
                var app = this;
                axios.get('/api/v1/sales', {
                    params: app.filters
                })
                    .then(function (resp) {
                        app.sales = resp.data;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Não foi possivel listar as vendas.");
                    });
                axios.get('/api/v1/sales/report', {
                    params: app.filters
                })
                    .then(function (resp) {
                        app.reports = resp.data;
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Não foi possivel listar os relatórios.");
                    });
            }
        }
    }
</script>