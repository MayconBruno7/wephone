<?php 

use App\Library\Session;
use App\Library\Formulario;

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>We Phone - Controle estoque</title>

    <link rel="stylesheet" href="<?= baseUrl() ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="icon" href="<?= baseUrl() ?>assets/img/brasao-pmrl-icon.jpeg" type="image/jpeg">

    <!-- Datatables -->
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/app.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/bundles/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/bundles/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css">

    <!-- Template -->
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/app.min.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/components.css">
    <!-- <link rel="stylesheet" href="<?= baseUrl() ?>assets/css/custom.css"> -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="<?= baseUrl() ?>assets/js/jquery-3.3.1.js"></script>
    <script src="<?= baseUrl() ?>assets/bootstrap/js/bootstrap.min.js"></script>

</head>

<body class="sidebar-gone sidebar-mini">

<?php if (Session::get("exibirModalEstoque")): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            mensagemModal.innerHTML = "Verifique a quantidade dos itens em estoque que foram citados no email enviado para o administrador principal!<br>";

                // Atualizar o tempo restante a cada segundo
                const intervaloAtualizacao = setInterval(function() {
                    const agora = new Date().getTime();
                    const tempoRestante = Math.max(intervalo - (agora - ultimaVerificacao), 0); // Garantir que tempoRestante não seja negativo

                    // Substituir a mensagem de tempo restante
                    mensagemModal.innerHTML = "Verifique a quantidade dos itens em estoque que foram citados no email enviado para o administrador principal!<br>";
                    mensagemModal.innerHTML += "Tempo restante para a próxima notificação de estoque: " + formatarTempo(tempoRestante);

                    // Parar o intervalo quando o tempo restante for 0 ou menor
                    if (tempoRestante <= 0) {
                        clearInterval(intervaloAtualizacao);
                        mensagemModal.innerHTML = "A próxima verificação será realizada em breve!";
                    }
                }, 1000);
            exibirModal("Notificação de estoque", mensagemModal);
        });
    </script>
    <?php Session::destroy("exibirModalEstoque"); // Limpa a variável de sessão após o uso ?>
<?php endif; ?>

<?php if (Session::get("exibeModalNotificacaoEstoque")): ?>
    <script>
        menssageModal = "";
        document.addEventListener("DOMContentLoaded", function() {
            menssageModal = "Sem itens abaixo do limite de alerta em estoque.";

            exibirModal("Notificação de estoque", menssageModal);
        });
    </script>
    <?php Session::destroy("exibeModalNotificacaoEstoque"); // Limpa a variável de sessão após o uso ?>
<?php endif; ?>


<div class="modal fade" id="modalGlobal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="mensagemModal"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- <div class="loader"></div> -->
<div class="settingSidebar" id="settingSidebar">
    <a href="javascript:void(0)" class="settingPanelToggle"> <i class="fa fa-spin fa-cog"></i>
    </a>
    <div class="settingSidebar-body ps-container ps-theme-default">
        <div class=" fade show active">
            <div class="setting-panel-header">Painel de configurações
            </div>
            <!-- <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Selecionar aparência</h6>
                <div class="selectgroup layout-color w-50">
                    <label class="selectgroup-item">
                        <input type="radio" name="value" value="1" class="selectgroup-input-radio select-layout" checked>
                        <span class="selectgroup-button">Branco</span>
                    </label>
                    <label class="selectgroup-item">
                        <input type="radio" name="value" value="2" class="selectgroup-input-radio select-layout">
                        <span class="selectgroup-button">Escuro</span>
                    </label>
                </div>
            </div>
            <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Cor da barra lateral</h6>
                <div class="selectgroup selectgroup-pills sidebar-color">
                    <label class="selectgroup-item">
                        <input type="radio" name="icon-input" value="1" class="selectgroup-input select-sidebar">
                        <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                              data-original-title="Light Sidebar"><i class="fas fa-sun"></i></span>
                    </label>
                    <label class="selectgroup-item">
                        <input type="radio" name="icon-input" value="2" class="selectgroup-input select-sidebar" checked>
                        <span class="selectgroup-button selectgroup-button-icon" data-toggle="tooltip"
                              data-original-title="Dark Sidebar"><i class="fas fa-moon"></i></span>
                    </label>
                </div>
            </div> -->

            <!-- <div class="p-15 border-bottom">
                <h6 class="font-medium m-b-10">Tema cores</h6>
                <div class="theme-setting-options">
                    <ul class="choose-theme list-unstyled mb-0">
                        <li title="white" class="active">
                            <div class="white"></div>
                        </li>
                        <li title="cyan">
                            <div class="cyan"></div>
                        </li>
                        <li title="black">
                            <div class="black"></div>
                        </li>
                        <li title="purple">
                            <div class="purple"></div>
                        </li>
                        <li title="orange">
                            <div class="orange"></div>
                        </li>
                        <li title="green">
                            <div class="green"></div>
                        </li>
                        <li title="red">
                            <div class="red"></div>
                        </li>
                    </ul>
                </div>
            </div> -->
            
            <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                    <label class="m-b-0">
                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                               id="mini_sidebar_setting">
                        <span class="custom-switch-indicator"></span>
                        <span class="control-label p-l-10">Mini barra lateral</span>
                    </label>
                </div>
            </div>
            <!-- <div class="p-15 border-bottom">
                <div class="theme-setting-options">
                    <label class="m-b-0">
                        <input type="checkbox" name="custom-switch-checkbox" class="custom-switch-input"
                               id="sticky_header_setting">
                        <span class="custom-switch-indicator"></span>
                        <span class="control-label p-l-10">Cabeçalho adesivo</span>
                    </label>
                </div>
            </div> -->
            <div class="mt-4 mb-4 p-3 align-center rt-sidebar-last-ele">
                <a href="#" class="btn btn-icon icon-left btn-primary btn-restore-theme">
                    <i class="fas fa-undo"></i> Restaurar padrão
                </a>
            </div>
        </div>
    </div>
</div>

<div id="app">
    <?php if (Session::get('usuarioId') != false): ?>
    <div class="main-wrapper main-wrapper-1">
        <div class="navbar-bg"></div>
        <nav class="navbar navbar-expand-lg main-navbar sticky">
            <div class="form-inline mr-auto">
                <ul class="navbar-nav mr-3">
                    <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg
									collapse-btn"> <i data-feather="align-justify"></i></a></li>
                    </li>
                </ul>
            </div>

            <ul class="navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <?php if ((Session::get('id_funcionario')) && (Session::get('usuarioImagem'))) : ?>
                            <img alt="image" class="rounded-circle" src="<?= baseUrl() . 'uploads/funcionarios/' . Session::get('usuarioImagem') ?>" width="40px" height="40px">
                        <?php else : ?>
                            <img alt="image" class="rounded-circle" src="<?= baseUrl() . 'assets/img/users/person.svg' ?>" width="40px" height="40px">
                        <?php endif; ?>
                        <span class="d-sm-none d-lg-inline-block"></span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right pullDown">
                        <div class="dropdown-title">Olá, <?= $_SESSION["usuarioLogin"] ?></div>
                        
                        <?php if(Session::get('id_funcionario') != false) : ?>
                        <a href="<?= baseUrl() ?>Usuario/profile/view/<?= Session::get('usuarioId') ?>" class="dropdown-item has-icon"><i class="fas fa-id-badge"></i>
                            Perfil
                        </a> 
                        <?php endif; ?>
                        <a href="settingSidebar" class="dropdown-item has-icon"> <i class="fas fa-cog"></i>
                            Configurações
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="<?= baseUrl() ?>Login/signOut" class="dropdown-item has-icon text-danger"><i class="fas fa-sign-out-alt"></i>
                            Sair
                        </a>
                    </div>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand mb-1">
                <a href="<?= Session::get('usuarioId') ? baseUrl() . Formulario::retornaHomeAdminOuHome() : '#' ?>"> <img alt="imagem" src="<?= baseUrl() ?>assets/img/wephonelogo.jpg" width="65" height="80"  /> 
                </a>
            </div>
            <?php if (Session::get('usuarioId') != false): ?>
            <ul class="sidebar-menu">
                <li class="menu-header">Principal</li>
                <li class="dropdown active">
                    <a href="<?= Session::get('usuarioId') ? baseUrl() . Formulario::retornaHomeAdminOuHome() : '#' ?>" class="nav-link"><i data-feather="monitor"></i><span>Painel</span></a>
                </li>
                <?php if (Session::get('usuarioNivel') == 1): ?>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown">
                        <i data-feather="briefcase"></i>
                        <span>Administrador</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= baseUrl() ?>Usuario">Lista de usuários</a></li>
                        <li><a class="nav-link" href="<?= baseUrl() ?>Funcionario">Lista de funcionários</a></li>
                        <li><a class="nav-link" href="<?= baseUrl() ?>Cargo">Lista de cargos</a></li>

                        <li><hr class="dropdown-divider"></li>
                        <li class="menu-header">Relatórios</li>
                        <li class="dropdown">
                            <li><a href="<?= baseUrl() ?>Relatorio/relatorioMovimentacoes" class="nav-link">Movimentações</a></li>
                            <li><a href="<?= baseUrl() ?>Relatorio/relatorioItensPorFornecedor" class="nav-link">Por fornecedor</a></li>
                        </li>

                        <li><hr class="dropdown-divider"></li>
                        <li class="menu-header">Logs</li>
                        <li class="dropdown">
                            <li><a href="<?= baseUrl() ?>Log" class="nav-link">Logs do sistema</a></li>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="menu-header">Páginas</li>
                <li class="dropdown">
                    <a href="#" class="menu-toggle nav-link has-dropdown"><i data-feather="copy"></i><span>Páginas</span></a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= baseUrl() ?>Produto">Estoque</a></li>
                        <li><a class="nav-link" href="<?= baseUrl() ?>Fornecedor">Fornecedores</a></li>
                        <li><a class="nav-link" href="<?= baseUrl() ?>Movimentacao">Movimentações</a></li>
                        <li><a class="nav-link" href="<?= baseUrl() ?>OrdemServico">Ordem de Servico</a></li>
                        <li><a class="nav-link" href="<?= baseUrl() ?>FaleConosco/formularioEmail">Suporte técnico</a></li>
                    </ul>
                </li>
            </ul>
            <?php endif; ?>
        </aside>
    </div>

    <script>
        $(document).ready(function() {

            // abre a barra de configurações do aplicativo
            var settingSidebar = document.getElementById('settingSidebar');
            var settingSidebarLink = document.querySelector('a[href="settingSidebar"]');
            
            if (settingSidebarLink) {
                settingSidebarLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    settingSidebar.classList.toggle('showSettingPanel'); // Toggle a classe para mostrar/ocultar a barra lateral
                });
            }

            // Verificar o tema salvo no localStorage
            if (localStorage.getItem('theme') === 'dark') {
                $("body").addClass("dark dark-sidebar theme-black");
                $(".selectgroup-input[value='2']").prop("checked", true);
            } else {
                $("body").addClass("light light-sidebar theme-white");
                $(".selectgroup-input[value='1']").prop("checked", true);
            }

            // Alterar tema e salvar a preferência no localStorage
            $(".layout-color input:radio").change(function () {
                if ($(this).val() == "1") {
                    $("body").removeClass().addClass("light light-sidebar theme-white");
                    localStorage.setItem('theme', 'light');
                } else {
                    $("body").removeClass().addClass("dark dark-sidebar theme-black");
                    localStorage.setItem('theme', 'dark');
                }
            });
        });
    </script>