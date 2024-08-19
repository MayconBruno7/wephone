<?php
    use App\Library\Formulario;

    $page = $this->getOutrosParametros(1);

    $tituloPage = isset($page) && $page == 'relatorioMovimentacoes' ? 'Relatorio de Movimentações' : 'Relatório de itens por fornecedor';
?>

<main class="container" style="margin-top: 100px;">
    <div class="row">
        <div class="col-12">
            <?= Formulario::exibeMsgError() ?>
        </div>

        <div class="col-12 mt-3">
            <?= Formulario::exibeMsgSucesso() ?>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-center"><?= $tituloPage ?></div>
        <div class="card-body">

            <?php if ($page == 'relatorioItensPorFornecedor') : ?>
                <div class="mt-3">
                <label for="fornecedor_id" class="form-label">Fornecedor</label>
                <select name="fornecedor_id" id="fornecedor_id" class="form-control" required <?= $this->getAcao() == 'view' || $this->getAcao() == 'delete' ? 'disabled' : '' ?>>
                    <option value="">...</option>
                    <?php foreach($dados as $fornecedor) : ?>
                        <option value="<?= $fornecedor['id'] ?>" <?= setValor('id_fornecedor') == $fornecedor['id'] ? 'selected' : '' ?>>
                            <?= $fornecedor['nome'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif ; ?>

            <form id="relatorioForm" class="mt-2">
                <div class="form-group">
                    <label for="tipoRelatorio">Tipo de Relatório</label>
                    <select id="tipoRelatorio" class="form-control mb-2">
                        <option value="dia">Diário</option>
                        <option value="semana">Semanal</option>
                        <option value="mes">Mensal</option>
                        <option value="ano">Anual</option>
                    </select>
                </div>
                <!-- Div para exibir calendários dinamicamente -->
                <div id="calendarios" class="form-group"></div>

                <div class="container">
                    <div class="row">
                        <div class="col d-flex align-items-center">
                            <button type="button" id="gerarRelatorio" class="btn btn-primary mt-3">Gerar Relatório</button>
                            <button type="button" id="imprimirRelatorio" class="btn btn-secondary mt-3 ms-1">Imprimir Relatório</button>

                            <div class="dropdown mt-3 ms-1">
                                <a class="btn btn-secondary dropdown-toggle" id="downloads" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" disabled>
                                    Downloads
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" id="downloadCSV" href="javascript:void(0);">CSV</a></li>
                                    <li><a class="dropdown-item" id="downloadExcel" href="javascript:void(0);">Excel</a></li>
                                    <li><a class="dropdown-item" id="downloadPDF" href="javascript:void(0);">PDF</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="container">            
                <canvas id="graficoRelatorio" class="mt-4"></canvas>
                <div id="relatorioHtml" class="mt-4"></div> 
            </div>
        </div>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- parte do pdf -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.3.1/jspdf.umd.min.js"></script>

<!-- parte do excel -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<!-- parte do csv -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js"></script>


<script>
    // Função para mostrar/esconder calendários baseado no tipo de relatório selecionado
    function toggleCalendarios(tipo) {
        var calendariosDiv = document.getElementById('calendarios');
        calendariosDiv.innerHTML = ''; // Limpa conteúdo anterior

        var gerarRelatorioBtn = document.getElementById('gerarRelatorio');
        var imprimirRelatorioBtn = document.getElementById('imprimirRelatorio');
        var downloadRelatorioBtn = document.getElementById('downloads');

        gerarRelatorioBtn.disabled = true; // Desabilita por padrão
        imprimirRelatorioBtn.disabled = true; // Desabilita por padrão
        downloadRelatorioBtn.disabled = true;

        if (tipo === 'dia') {
            // Mostrar calendário para selecionar dia e mês
            calendariosDiv.innerHTML = `
                <label for="calendarioDia">Selecione o Dia, Mês e Ano:</label>
                <input type="date" id="calendarioDia" class="form-control" required>
            `;

            // Evento ao mudar a data do calendário de dia
            document.getElementById('calendarioDia').addEventListener('change', function() {
                checkDataPreenchida();
            });

        } else if (tipo === 'semana') {
            // Mostrar dois calendários para selecionar intervalo semanal
            calendariosDiv.innerHTML = `
                <label for="calendarioInicio">Data Início:</label>
                <input type="date" id="calendarioInicio" class="form-control" required>
                <label for="calendarioFim" class="mt-2">Data Fim:</label>
                <input type="date" id="calendarioFim" class="form-control" required>
            `;

            // Evento ao mudar a data de início da semana
            document.getElementById('calendarioInicio').addEventListener('change', function() {
                checkDataPreenchida();
            });

            // Evento ao mudar a data de fim da semana
            document.getElementById('calendarioFim').addEventListener('change', function() {
                checkDataPreenchida();
            });

        } else if (tipo === 'mes') {
            // Mostrar calendário para selecionar mês e ano
            calendariosDiv.innerHTML = `
                <label for="calendarioMesAno">Selecione o Mês e Ano:</label>
                <input type="month" id="calendarioMesAno" class="form-control" required>
            `;

            // Evento ao mudar o mês e ano
            document.getElementById('calendarioMesAno').addEventListener('change', function() {
                checkDataPreenchida();
            });

        } else if (tipo === 'ano') {
            // Mostrar calendário para selecionar apenas o ano
            calendariosDiv.innerHTML = `
                <label for="calendarioAno">Selecione o Ano:</label>
                <input type="number" id="calendarioAno" class="form-control" min="1900" max="2024" value="<?= date('Y') ?>" required>
            `;

            // Evento ao mudar o ano
            document.getElementById('calendarioAno').addEventListener('change', function() {
                checkDataPreenchida();
            });
        }

        // Função para verificar se a data está preenchida e habilitar/desabilitar os botões
        function checkDataPreenchida() {
            var dataInicio;

            switch (tipo) {
                case 'dia':
                    dataInicio = document.getElementById('calendarioDia').value;
                    break;
                case 'semana':
                    dataInicio = document.getElementById('calendarioInicio').value;
                    var fim = document.getElementById('calendarioFim').value;
                    if (dataInicio && fim) {
                        gerarRelatorioBtn.disabled = false;
                        imprimirRelatorioBtn.disabled = false;
                        downloadRelatorioBtn.disabled = false;
                    } else {
                        gerarRelatorioBtn.disabled = true;
                        imprimirRelatorioBtn.disabled = true;
                        downloadRelatorioBtn.disabled = true;
                    }
                    break;
                case 'mes':
                    dataInicio = document.getElementById('calendarioMesAno').value;
                    break;
                case 'ano':
                    dataInicio = document.getElementById('calendarioAno').value;
                    break;
                default:
                    break;
            }

            if (dataInicio) {
                gerarRelatorioBtn.disabled = false;
                imprimirRelatorioBtn.disabled = false;
                downloadRelatorioBtn.disabled = false;
            } else {
                gerarRelatorioBtn.disabled = true;
                imprimirRelatorioBtn.disabled = true;
                downloadRelatorioBtn.disabled = true;
            }
        }

        // Inicializa a verificação quando a função é chamada
        checkDataPreenchida();
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Inicializa com o tipo de relatório padrão
        toggleCalendarios(document.getElementById('tipoRelatorio').value);

        // Evento ao mudar o tipo de relatório
        document.getElementById('tipoRelatorio').addEventListener('change', function() {
            toggleCalendarios(this.value);
        });

        // Variável para armazenar a instância do gráfico
        let chart;
        let fetchedData; // Variável para armazenar os dados do fetch

        // Evento para gerar relatório
        document.getElementById('gerarRelatorio').addEventListener('click', function() {
            fetchedData = null; // Limpar os dados anteriores

            var tipoRelatorio = document.getElementById('tipoRelatorio').value;
            var dataInicio;
            var fim;

            // Obter os dados conforme o tipo de relatório selecionado
            switch (tipoRelatorio) {
                case 'dia':
                    dataInicio = document.getElementById('calendarioDia').value;
                    break;
                case 'semana':
                    dataInicio = document.getElementById('calendarioInicio').value;
                    fim = document.getElementById('calendarioFim').value;
                    break;
                case 'mes':
                    dataInicio = document.getElementById('calendarioMesAno').value;
                    break;
                case 'ano':
                    dataInicio = document.getElementById('calendarioAno').value;
                    break;
                default:
                    break;
            }

            var id_fornecedor = document.getElementById('fornecedor_id')?.value;

            // Montar URL com os parâmetros
            var url = '<?= baseUrl() ?>Relatorio/getDados/' + tipoRelatorio + '/' + dataInicio;
            url += '/' + (fim || dataInicio); // Substitua 'default_value' pelo valor desejado
            url += '/' + id_fornecedor;

            fetch(url)
                .then(response => response.json())
                .then(data => {

                    console.log(data)
                    // Transformação para um array de objetos
                    fetchedData = [];

                    for (let i = 0; i < data.labels.length; i++) {
                        fetchedData.push({
                            Data: data.labels[i],
                            Descricao: data.descricoes[i],
                            Valor: data.valores[i],
                            Entrada: data.entradas[i],
                            Saida: data.saidas[i]
                        });
                    }

                    console.log(fetchedData);

                    // Função para baixar CSV
                    document.getElementById('downloadCSV').addEventListener('click', function() {
                        if (fetchedData) {
                            let csv = Papa.unparse(fetchedData);
                            let blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                            let url = URL.createObjectURL(blob);
                            let link = document.createElement('a');
                            link.setAttribute('href', url);
                            link.setAttribute('download', 'relatorio.csv');
                            link.style.visibility = 'hidden';
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    });
                    
                    // Função para baixar Excel
                    document.getElementById('downloadExcel').addEventListener('click', function() {
                        if (fetchedData) {
                            let ws = XLSX.utils.json_to_sheet(fetchedData); // Certifique-se fetchedData é um array de objetos
                            let wb = XLSX.utils.book_new();
                            XLSX.utils.book_append_sheet(wb, ws, "Relatorio");
                            XLSX.writeFile(wb, "relatorio.xlsx");
                        }
                    });


                    // Evento para baixar PDF
                    document.getElementById('downloadPDF').addEventListener('click', function() {
                        if (fetchedData) {
                            const { jsPDF } = window.jspdf;
                            const doc = new jsPDF();

                            // Adicionar imagem
                            const imgData = '<?= baseUrl() ?>assets/img/brasao-pmrl.png'; // Substitua com o seu próprio dado de imagem
                            const imgWidth = 25; // Largura da imagem
                            const imgHeight = 20; // Altura da imagem
                            const marginX = (doc.internal.pageSize.width - imgWidth) / 2; // Centraliza horizontalmente
                            const marginY = 10; // Distância a partir do topo
                            doc.addImage(imgData, 'JPEG', marginX, marginY, imgWidth, imgHeight);

                            // Cabeçalho do PDF abaixo da imagem
                            const headerText = 'Relatório de Movimentações';
                            const headerFontSize = 14;
                            const headerTextWidth = doc.getStringUnitWidth(headerText) * headerFontSize / doc.internal.scaleFactor;
                            const headerX = (doc.internal.pageSize.width - headerTextWidth) / 2;
                            const headerY = marginY + imgHeight + 10; // Posiciona abaixo da imagem
                            doc.setFontSize(headerFontSize);
                            doc.text(headerText, headerX, headerY);

                            // Construir a tabela no PDF
                            let posY = headerY + headerFontSize + 10; // Posição inicial Y da tabela
                            let margins = { top: 20, left: 10, bottom: 10 };

                            // Cabeçalho da tabela
                            doc.setFontSize(12);
                            doc.setTextColor(0, 0, 0); // Cor do texto
                            doc.setFillColor(240, 240, 240); // Cor de fundo do cabeçalho
                            doc.rect(margins.left, posY, 190, 10, 'F');
                            doc.text('Data', margins.left + 5, posY + 8);
                            doc.text('Produto', margins.left + 45, posY + 8);
                            doc.text('Valor', margins.left + 85, posY + 8);
                            doc.text('Entradas', margins.left + 115, posY + 8);
                            doc.text('Saídas', margins.left + 145, posY + 8);
                            posY += 10;

                            // Conteúdo da tabela
                            doc.setFontSize(10);
                            doc.setTextColor(0, 0, 0); // Cor do texto

                            fetchedData.forEach(item => {
                                doc.rect(margins.left, posY, 190, 10);
                                doc.text(String(item.Data), margins.left + 5, posY + 8); // Converter para string
                                doc.text(String(item.Descricao), margins.left + 45, posY + 8); // Converter para string
                                doc.text(String(item.Valor), margins.left + 85, posY + 8); // Converter para string
                                doc.text(String(item.Entrada), margins.left + 115, posY + 8); // Converter para string
                                doc.text(String(item.Saida), margins.left + 145, posY + 8); // Converter para string
                                posY += 10;

                                // Adicionar nova página se necessário
                                if (posY > 280) { // 280 é o limite da página para evitar overflow
                                    doc.addPage();
                                    posY = margins.top;
                                }
                            });

                            // Salvar o PDF
                            doc.save('relatorio.pdf');
                        }
                    });

                    var ctx = document.getElementById('graficoRelatorio').getContext('2d');

                    // Se existir um gráfico, destruí-lo antes de criar um novo
                    if (chart) {
                        chart.destroy();
                    }

                    chart = new Chart(ctx, {
                        type: 'bar', // ou 'line', 'pie', etc.
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Entradas',
                                data: data.entradas,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Saídas',
                                data: data.saidas,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1
                            }]
                        },
options: {
    responsive: true,
    scales: {
        y: {
            beginAtZero: true
        }
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: function(tooltipItem) {
                    let index = tooltipItem.dataIndex;
                    let entrada = data.entradas[index];
                    let saida = data.saidas[index];
                    let descricao = data.descricoes[index];
                    let valor = data.valores[index];
                    return `Data: ${tooltipItem.label}\nProduto: ${descricao}\nValor: ${valor}\nEntradas: ${entrada}\nSaídas: ${saida}`;
                }

            },
            displayColors: false, // Oculta a exibição das cores
            backgroundColor: 'rgba(0, 0, 0, 0.8)', // Cor de fundo do tooltip
            bodyFontColor: '#fff', // Cor do texto dentro do tooltip
            titleFontColor: '#fff', // Cor do título do tooltip
            bodyFontSize: 14, // Tamanho da fonte do texto dentro do tooltip
            bodySpacing: 6, // Espaçamento entre linhas dentro do tooltip
            cornerRadius: 8, // Raio do canto do tooltip
            caretPadding: 10, // Espaçamento entre a borda do tooltip e a "caret"
            borderWidth: 1, // Largura da borda do tooltip
            borderColor: '#ccc' // Cor da borda do tooltip
        }
    }
}
                    });
                    renderRelatorioHtml(data); // Renderiza os dados em formato HTML
                });
        });

        // Função para renderizar os dados em formato HTML
        function renderRelatorioHtml(data) {
            var html = '<h3>Relatório de Movimentações</h3>';
            html += '<table class="table table-striped">';
            html += '<thead><tr><th>Data</th><th>Produto</th><th>Valor</th><th>Entradas</th><th>Saídas</th></tr></thead>';
            html += '<tbody>';
            for (var i = 0; i < data.labels.length; i++) {
                html += '<tr>';
                html += `<td>${data.labels[i]}</td>`;
                html += `<td>${data.descricoes[i]}</td>`;
                html += `<td>${data.valores[i]}</td>`;
                html += `<td>${data.entradas[i]}</td>`;
                html += `<td>${data.saidas[i]}</td>`;
                html += '</tr>';
            }
            html += '</tbody>';
            html += '</table>';

            document.getElementById('relatorioHtml').innerHTML = html;
        }

        // Evento para imprimir relatório
        document.getElementById('imprimirRelatorio').addEventListener('click', function() {
            window.print(); // Imprime a página
        });
    });

</script>

<style>
    @media print {
        .navbar {
            display: none;
        }
        #graficoRelatorio {
            position: absolute;
            left: -9999px; /* Mova o elemento para fora do espaço visível */
            opacity: 0; /* Garanta que o elemento não seja visível */
        }
        #relatorioHtml {
            display: block;
        }
        footer {
            display: none;
        }
    }
</style>