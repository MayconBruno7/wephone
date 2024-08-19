<?php

    use App\Library\Formulario;
    use App\Library\Session;
    
?>
      <!-- Main Content -->
      <div class="main-content">
      <div class="row mb-2">
          <div class="col-12">
              <?= Formulario::exibeMsgError() ?>
          </div>
          <div class="col-12">
              <?= Formulario::exibeMsgSucesso() ?>
          </div>
      </div>
        <section class="section">
          <div class="row ">
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Movimentações de hoje</h5>
                          <h2 class="mb-3 font-18">
                            <?php 
                                $totalQuantidadeDia = 0;

                                foreach ($aDados['aRelatorioDia'] as $item) {
                                    // Acessando e somando a quantidadeDia de cada item do array
                                    $totalQuantidadeDia = intval($item['quantidadeDia']);
                                }
                            ?>
                        
                          <?= $totalQuantidadeDia ?>
                          Produtos
                          </h2>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle col-green"><circle cx="12" cy="12" r="10"></circle><polyline points="16 12 12 8 8 12"></polyline><line x1="12" y1="16" x2="12" y2="8"></line></svg>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/1.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Movimentações semana</h5>
                          <h2 class="mb-3 font-18">
                          <?php 
                                $totalQuantidadeSemana = 0;

                                foreach ($aDados['aRelatorioSemana'] as $item) {
                                    // Acessando e somando a quantidadeSemanda de cada item do array
                                    $totalQuantidadeSemana = intval($item['quantidadeSemana']);
                                }
                            ?>
                          <?= $totalQuantidadeSemana ?>
                            Produtos
                          </h2>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle col-green"><circle cx="12" cy="12" r="10"></circle><polyline points="16 12 12 8 8 12"></polyline><line x1="12" y1="16" x2="12" y2="8"></line></svg>

                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/2.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Movimentações do mês</h5>
                          <h2 class="mb-3 font-18">
                          <?php 
                                $totalQuantidadeMes = 0;

                                foreach ($aDados['aRelatorioMes'] as $item) {
                                    // Acessando e somando a quantidadeMes de cada item do array
                                    $totalQuantidadeMes = intval($item['quantidadeMes']);
                                }
                            ?>
                          <?= $totalQuantidadeMes ?>
                            Produtos
                          </h2>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle col-green"><circle cx="12" cy="12" r="10"></circle><polyline points="16 12 12 8 8 12"></polyline><line x1="12" y1="16" x2="12" y2="8"></line></svg>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/3.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
              <div class="card">
                <div class="card-statistic-4">
                  <div class="align-items-center justify-content-between">
                    <div class="row ">
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                        <div class="card-content">
                          <h5 class="font-15">Movimentações do ano</h5>
                          <h2 class="mb-3 font-18">
                          <?php 
                                $totalQuantidadeAno = 0;

                                foreach ($aDados['aRelatorioAno'] as $item) {
                                    // Acessando e somando a quantidadeAno de cada item do array
                                    $totalQuantidadeAno = intval($item['quantidadeAno']);
                                }
                            ?>
                            <?= $totalQuantidadeAno ?>
                            Produtos
                          </h2>
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-up-circle col-green"><circle cx="12" cy="12" r="10"></circle><polyline points="16 12 12 8 8 12"></polyline><line x1="12" y1="16" x2="12" y2="8"></line></svg>
                        </div>
                      </div>
                      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                        <div class="banner-img">
                          <img src="assets/img/banner/4.png" alt="">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-sm-12 col-lg-12">
              <div class="card ">
                <div class="card-header">
                  <h4>Grafico de entradas e saidas hoje</h4>
                  <div class="card-header-action">
                    <div class="container">            
                      <!-- <canvas id="graficoRelatorio" class="mt-4"></canvas>
                      <div id="relatorioHtml" class="mt-4">
                    </div>   -->
                </div>
                  </div>
                </div>
                <div class="card-body">
                  <canvas id="graficoRelatorio" width="400" height="130"></canvas>
                  <div id="relatorioHtml" class="mt-4"></div> 
                </div>
              </div>
            </div>
          </div>
      </div>
    </div>
  </div>


  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obter a data atual
            var today = new Date();

            // Formatar a data como 'YYYY-MM-DD'
            var day = today.getDate().toString().padStart(2, '0');  // Adiciona zero à esquerda se necessário
            var month = (today.getMonth() + 1).toString().padStart(2, '0');  // Adiciona zero à esquerda se necessário
            var year = today.getFullYear();

            var formattedDate = year + '-' + month + '-' + day;

            var url = '<?= baseUrl() ?>Relatorio/getDados/dia/' + formattedDate;
            // Adiciona um valor padrão para 'fim' se necessário
            var fim = 'default_value';
            url += '/' + fim;

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    // console.log(data);
                    // Transformação para um array de objetos
                    let fetchedData = [];

                    for (let i = 0; i < data.labels.length; i++) {
                        fetchedData.push({
                            Data: data.labels[i],
                            Descricao: data.descricoes[i],
                            Valor: data.valores[i],
                            Entrada: data.entradas[i],
                            Saida: data.saidas[i],
                            movimentacao: data.id_movimentacao[i]
                        });
                    }

                    // console.log(fetchedData);

                    var ctx = document.getElementById('graficoRelatorio').getContext('2d');

                    // Se existir um gráfico, destruí-lo antes de criar um novo
                    if (window.chart) {
                        window.chart.destroy();
                    }

                    window.chart = new Chart(ctx, {
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
                                x: {
                                    ticks: {
                                        font: {
                                            size: 10, // Tamanho da fonte dos rótulos do eixo x
                                        },
                                        align: 'center', // Alinhar centralizado
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        font: {
                                            size: 10, // Tamanho da fonte dos rótulos do eixo y
                                        },
                                        align: 'center', // Alinhar centralizado
                                    }
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
                                          return `Data: ${tooltipItem.label}\n
                                          Produto: ${descricao}\n
                                          Valor: ${valor}\n
                                          Entradas: ${entrada}\n
                                          Saídas: ${saida}`;
                                      }

                                    },
                                    displayColors: false, // Oculta a exibição das cores
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)', // Cor de fundo do tooltip
                                    bodyFontColor: '#fff', // Cor do texto dentro do tooltip
                                    titleFontColor: '#fff', // Cor do título do tooltip
                                    bodyFontSize: 12, // Tamanho da fonte do texto dentro do tooltip
                                    bodySpacing: 8, // Espaçamento entre linhas dentro do tooltip
                                    cornerRadius: 8, // Raio do canto do tooltip
                                    caretPadding: 10, // Espaçamento entre a borda do tooltip e a "caret"
                                    borderWidth: 1, // Largura da borda do tooltip
                                    borderColor: '#ccc' // Cor da borda do tooltip
                                }
                            }
                        }
                    });
                    renderRelatorioHtml(data);
                });
        });

        function renderRelatorioHtml(data) {
          // Incorporando a tabela na página
          var html = '<div class="row">';
          html += '<div class="col-12">';
          html += '<div class="card">';
          html += '<div class="card-header">';
          html += '<h4>Tabela de movimentações</h4>';
          html += '<div class="card-header-form">';
          html += '<form>';
          html += '</form>';
          html += '</div>';
          html += '</div>';
          html += '<div class="card-body p-0">';
          html += '<div class="table-responsive">';
          html += '<table class="table table-striped">';
          html += '<thead>';
          html += '<tr>';
          html += '<th class="text-center">';
          html += '</th>';
          html += '<th>Data</th>';
          html += '<th>Produto</th>';
          html += '<th>Valor</th>';
          html += '<th>Entradas</th>';
          html += '<th>Saídas</th>';
          html += '<th>Ação</th>';
          html += '</tr>';
          html += '</thead>';
          html += '<tbody>';

          for (var i = 0; i < data.labels.length; i++) {
              html += '<tr>';
              html += '<td class="p-0 text-center">';
              html += '</td>';
              html += `<td>${data.labels[i]}</td>`;
              html += `<td>${data.descricoes[i]}</td>`;
              html += `<td>${data.valores[i]}</td>`;
              html += `<td>${data.entradas[i]}</td>`;
              html += `<td>${data.saidas[i]}</td>`;
              html += `<td><a href="<?= baseUrl(); ?>Movimentacao/form/view/${data.id_movimentacao[i]}/home" class="btn btn-outline-primary">Detalhes da movimentação</a></td>`;
              html += '</tr>';
          }

          html += '</tbody>';
          html += '</table>';
          html += '</div>';
          html += '</div>';
          html += '</div>';
          html += '</div>';
          html += '</div>';
          html += '</div>';

          document.getElementById('relatorioHtml').innerHTML = html;
      }

    </script>
  


