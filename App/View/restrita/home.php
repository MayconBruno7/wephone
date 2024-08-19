<?php

use App\Library\Formulario;
use App\Library\Session;

?>

<div class="container" style="margin-top: 80px;">
    <main>
        <div class="jumbotron text-center my-5">
            <h1 class="display-4">Bem-vindo ao Sistema de Controle de Estoque</h1>
            <p class="lead">Gerencie seu estoque de forma eficiente e fácil</p>
            <hr class="my-4">
        </div>

        <div class="row text-center">
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-boxes fa-3x mb-3"></i>
                        <h5 class="card-title">Gerenciar Produtos</h5>
                        <p class="card-text">Adicione, edite e visualize os produtos do seu estoque.</p>
                        <a href="<?= baseUrl() ?>Produto" class="btn btn-primary">Acessar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-chart-line fa-3x mb-3"></i>
                        <h5 class="card-title">Movimentações</h5>
                        <p class="card-text">Adicione movimentações de entrada e saida ao seu estoque.</p>
                        <a href="<?= baseUrl() ?>Movimentacao" class="btn btn-primary">Acessar</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <i class="fas fa-cogs fa-3x mb-3"></i>
                        <h5 class="card-title">Suporte técnico</h5>
                        <p class="card-text">Envie suas dúvidas e sugestões ao suporte técnico.</p>
                        <a href="<?= baseUrl() ?>FaleConosco/formularioEmail" class="btn btn-primary">Acessar</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
<style>

    .jumbotron {
        background-color: #f8f9fa;
        padding: 2rem;
    }

</style>
