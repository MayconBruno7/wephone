        <!-- General JS Scripts -->
        <script src="<?= baseUrl() ?>assets/js/app.min.js"></script>
        
        <!-- JS Libraies -->
        <script src="<?= baseUrl() ?>assets/bundles/apexcharts/apexcharts.min.js"></script>

        <!-- Template JS File -->
        <script src="<?= baseUrl() ?>assets/js/scripts.js"></script>
        <!-- Custom JS File -->
        <script src="<?= baseUrl() ?>assets/js/custom.js"></script>

        <!-- Datatables -->
        <script src="<?= baseUrl() ?>assets/bundles/datatables/datatables.min.js"></script>
        <script src="<?= baseUrl() ?>assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <script src="<?= baseUrl() ?>assets/bundles/jquery-ui/jquery-ui.min.js"></script>
        <script src="<?= baseUrl() ?>assets/js/page/datatables.js"></script>

        <!-- verifica a quantidade limite do estoque para alerta disparado por email -->
        <script>
            // Verificar a cada 24 horas (86400000 ms)
            // Verificar a cada 3 minutos (180000)
            // Verificar a cada 1 minuto (60000)
            // Verificar a cada 24 horas (86400000 ms)
            const intervalo = 86400000; // 1 minuto em milissegundos
            const ultimaVerificacao = localStorage.getItem('ultimaVerificacao');

            function verificarEstoque() {
                console.log("Verificação de estoque iniciada às " + new Date().toLocaleTimeString());

                fetch('<?= baseUrl() ?>FaleConosco/verificaEstoque')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Erro na resposta da rede.');
                        }
                        return response.text();
                    })
                    .then(data => {
                        console.log('Verificação de estoque realizada.');
                        // Processar a resposta se necessário
                        // console.log(data);
                        // Salvar a hora da última verificação no localStorage
                        localStorage.setItem('ultimaVerificacao', new Date().getTime());
                        // Atualizar o tempo para a próxima verificação
                        atualizarTempoParaProximaVerificacao();
                    })
                    .catch(error => console.error('Erro:', error));
            }

            function atualizarTempoParaProximaVerificacao() {
                const ultimaVerificacao = localStorage.getItem('ultimaVerificacao');
                const agora = new Date().getTime();

                if (ultimaVerificacao) {
                    const tempoPassado = agora - ultimaVerificacao;
                    const tempoRestante = Math.max(intervalo - tempoPassado, 0); // Garantir que tempoRestante não seja negativo

                    if (tempoRestante > 0) {
                        console.log("Tempo restante para a próxima verificação: " + formatarTempo(tempoRestante));
                    } else {
                        console.log("A próxima verificação será realizada em breve.");
                    }
                }
            }

            function formatarTempo(millis) {
                const horas = Math.floor(millis / 3600000);
                const minutos = Math.floor((millis % 3600000) / 60000);
                const segundos = Math.floor((millis % 60000) / 1000);
                return `${horas}h ${minutos}m ${segundos}s`;
            }

            setInterval(verificarEstoque, intervalo);

            // Verificar imediatamente quando a página carregar, se tiver passado 1 minuto desde a última verificação
            document.addEventListener('DOMContentLoaded', function() {
                const agora = new Date().getTime();

                if (!ultimaVerificacao || (agora - ultimaVerificacao >= intervalo)) {
                    verificarEstoque();
                } else {
                    console.log("Ainda não passou 1 minuto desde a última verificação.");
                    atualizarTempoParaProximaVerificacao();
                }
            });

            function exibirModal(titleModal, menssageModal) {
                
                const ultimaVerificacao = localStorage.getItem('ultimaVerificacao');
                const agora = new Date().getTime();
                const tempoRestanteInicial = Math.max(intervalo - (agora - ultimaVerificacao), 0); // Garantir que tempoRestante não seja negativo

                const tituloModal = document.getElementsByClassName('modal-title')[0];
                tituloModal.innerHTML = titleModal;

                const mensagemModal = document.getElementById('mensagemModal');
                mensagemModal.innerHTML = menssageModal;

                $('#modalGlobal').modal('show');
            }

        </script>

        <style>
            footer {
                background-color: rgb(240, 243, 243);
                padding: 3%;
                text-align: center;
            }

        </style>
        
        <footer class="main-footer mt-4">




            <!-- <button onclick="verificarEstoque()">Abrir  modla</button> -->
            <!-- <a href="<?= baseUrl() ?>HistoricoProduto/getHistoricoProduto/2024-07-25 ">Testar</a>
            <a href="<?= baseUrl() ?>Relatorio/getDados/dia/2024-07-27/default/1 ">relario</a> -->




            <p>Departamento de Informática Rosário da Limeira - MG</p>
            <span>© 2024 Company, Inc</span>

            <?php 
                use App\Library\Session;

                $redirectUrl = '';

                if (Session::get('usuarioNivel') == 1) {
                    $redirectUrl = 'Home/homeAdmin';
                } elseif (Session::get('usuarioNivel') == 11) {
                    $redirectUrl = 'Home/home';
                } 

            ?>
            <div class="container mt-2">
                <?php if (Session::get('usuarioId') != false) : ?>
                    <a class="mt-2" href="<?= baseUrl() . $redirectUrl ?>">Home</a>
                <?php endif; ?>
            </div>
        </footer>
    </body>   
</html>