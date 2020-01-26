<template>
    <div>
        <div class="form-group">
            <router-link to="/" class="btn btn-primary">Voltar</router-link>
        </div>

        <div class="card">
            <div class="card-header">Editar Venda</div>
            <div class="card-body">
                <form class="container" v-on:submit.prevent="saveForm()">
                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="control-label">Data</label>
                            <datepicker v-model="sale.date" name="uniquename" input-class="form-control" placeholder="Selecione" format="dd/MM/yyyy" use-utc="true"></datepicker>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="control-label">Quantidade</label>
                            <input type="text" v-model="sale.quantity" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Produto</label>
                        <select class="form-control" v-model="sale.product_id">
                            <option v-for="product, index in products" v-bind:value="product.id">{{ product.name }}</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <label class="control-label">Valor</label>
                            <input type="text" v-model="sale.amount" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Cliente</label>
                        <select class="form-control" v-model="sale.customer_id">
                            <option v-for="customer, index in customers" v-bind:value="customer.id">{{ customer.name }}</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select class="form-control" v-model="sale.status">
                            <option>VENDIDO</option>
                            <option>CANCELADO</option>
                            <option>DEVOLUÇÃO</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-12 form-group">
                            <button class="btn btn-success">Salvar</button>
                        </div>
                    </div>
                </form>
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
        mounted() {
            let app = this;
            let id = app.$route.params.id;
            app.saleId = id;
            axios.get('/api/v1/sales/' + id)
                .then(function (resp) {
                    app.sale = resp.data;
                })
                .catch(function () {
                    alert("Não foi possivel carregar a venda.")
                });
            axios.get('/api/v1/products')
                .then(function (resp) {
                    app.products = resp.data;
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Não foi possivel listar os produtos.");
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
        data: function () {
            return {
                saleId: null,
                sale: {
                    product_id: '',
                    date: '',
                    amount: '',
                },
                products: [],
                customers: []
            }
        },
        methods: {
            saveForm() {
                var app = this;
                var newSale = app.sale;
                axios.patch('/api/v1/sales/' + app.saleId, newSale)
                    .then(function (resp) {
                        app.$router.replace('/');
                    })
                    .catch(function (resp) {
                        console.log(resp);
                        alert("Não foi possivel atualizar.");
                    });
            }
        }
    }
</script>