<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel de Chamados</title>

    <link rel="icon" type="image/png" href="assets/img/icon-chamados.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/relatorio.css"/>
    <link rel="stylesheet" href="assets/css/index.css"/>
</head>

<body>
    <div id="bloqueio-tela"></div>
    <div class="dashboard-container">
        <!--CABEÇALHO-->
        <header class="dashboard-header">
            <div class="row align-items-center g-3">
                <div class="col-lg">
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            <h1 class="dashboard-title">
                                <i class="bi bi-bar-chart-line me-2"></i>
                                Painel de Chamados
                            </h1>
                            <div class="dashboard-subtitle">
                                Acompanhamento geral dos chamados e desempenho da equipe
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-auto">
                    <div class="d-flex align-items-center gap-3">
                        <div class="status-online">
                            Atualização automática
                        </div>
                        <img src="./assets/img/logo-sicap.png" t="Logo" style="height: 42px; max-width: 180px;">
                    </div>
                </div>
            </div>
        </header>

        <!--FILTROS-->
        <section class="filter-card">
            <div class="row align-items-end g-3">
                <div class="col-12 col-lg-2">
                    <div class="filter-title mb-2">
                        <i class="bi bi-funnel me-1"></i>Filtros
                    </div>
                    <div class="text-muted small">Refine os dados do painel</div>
                </div>
                <div class="col-6 col-lg-2">
                    <label for="ano-select" class="form-label">Ano</label>
                    <select id="ano-select" class="form-select"></select>
                </div>
                <div class="col-6 col-lg-2">
                    <label for="mes-select" class="form-label">Mês</label>
                    <select id="mes-select" ondblclick="setarMesAtual()" class="form-select"></select>
                </div>
                <div class="col-12 col-lg">
                    <label for="titulo-select" class="form-label">Município / Título</label>
                    <select id="titulo-select" class="form-select"></select>
                </div>
                <div class="col-12 col-lg-auto">
                    <label class="form-label d-none d-lg-block">
                        &nbsp;
                    </label>
                    <button type="button" class="btn btn-refresh w-100" onclick="listarChamados(), listarQuantitativos()">
                        <i class="bi bi-arrow-clockwise me-1"></i>Atualizar
                    </button>
                </div>
            </div>
        </section>

        <!--TOTALIZANTES-->
        <section class="row g-3 mb-4">
            <!-- CRIADOS -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card kpi-primary">
                    <div class="kpi-top">
                        <div class="kpi-title">Criados</div>
                        <div class="kpi-icon">
                            <i class="bi bi-ticket-perforated"></i>
                        </div>
                    </div>
                    <div class="kpi-number" id="qtd-criados"></div>
                    <div class="kpi-footer">
                        <span class="kpi-period">
                            Filtrados: <strong id="qtd-criados-filtrados"></strong>
                        </span>
                        <span class="text-primary fw-semibold">acumulados</span>
                    </div>
                </div>
            </div>
            <!-- FINALIZADOS -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card kpi-success">
                    <div class="kpi-top">
                        <div class="kpi-title">Finalizados</div>
                        <div class="kpi-icon">
                            <i class="bi bi-check2-circle"></i>
                        </div>
                    </div>
                    <div class="kpi-number" id="qtd-finalizados"></div>
                    <div class="kpi-footer">
                        <span class="kpi-period">
                            Filtrados: <strong id="qtd-finalizados-filtrados"></strong>
                        </span>
                        <span class="text-success fw-semibold">
                            resolvidos
                        </span>
                    </div>
                </div>
            </div>
            <!-- ABERTOS -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card kpi-warning">
                    <div class="kpi-top">
                        <div class="kpi-title">Em aberto</div>
                        <div class="kpi-icon">
                            <i class="bi bi-exclamation-circle"></i>
                        </div>
                    </div>
                    <div class="kpi-number" id="qtd-abertos"></div>
                    <div class="kpi-footer">
                        <span class="kpi-period">
                            Filtrados: <strong id="qtd-abertos-filtrados"></strong>
                        </span>
                        <span class="text-warning fw-semibold">aguardando</span>
                    </div>
                </div>
            </div>
            <!-- DESENVOLVIMENTO -->
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="kpi-card kpi-info">
                    <div class="kpi-top">
                        <div class="kpi-title">Em desenvolvimento</div>
                        <div class="kpi-icon">
                            <i class="bi bi-code-slash"></i>
                        </div>
                    </div>
                    <div class="kpi-number" id="qtd-desenvolvimento"></div>
                    <div class="kpi-footer">
                        <span class="kpi-period">
                            Filtrados: <strong id="qtd-desenvolvimento-filtrados"></strong>
                        </span>
                        <span class="text-info fw-semibold">andamento</span>
                    </div>
                </div>
            </div>
        </section>
        
        <!--GRÁFICOS-->
        <section class="row g-3 mb-4">
            <div class="col-12 col-xl-8">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-graph-up me-2"></i> Evolução dos chamados
                            </h2>
                            <div class="dashboard-card-subtitle">Acompanhamento mensal de chamados criados e finalizados</div>
                        </div>
                        <span id="ano-grafico-evolucao" class="status-badge badge-info"></span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container">
                            <canvas id="chart-evolucao-mes"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-4">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-pie-chart me-2"></i>Chamados em aberto 
                            </h2>
                            <div class="dashboard-card-subtitle">Distinção dos chamados em aberto</div>
                        </div>
                        <span class="status-badge badge-info">Geral</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container-small">
                            <canvas id="chart-chamados-abertos"></canvas>
                        </div>
                    </div>
                    <div class="text-center mb-2 small text-secondary">
                        <i class="bi bi-plus-circle me-1"></i>
                        <strong id="abertos-qtd-novo"></strong> Implementações &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                         <i class="bi bi-bug me-1"></i>
                        <strong id="abertos-qtd-bug"></strong> Sustentações
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-7">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-bar-chart-steps me-2"></i>Ranking Semanal
                            </h2>
                            <div class="dashboard-card-subtitle">Chamados criados e finalizados nesta semana por programador</div>
                        </div>
                        <span class="status-badge badge-info">Semana</span>
                    </div>
                    <div class="dashboard-card-body" style="padding-bottom: 2px !important">
                        <div class="chart-container-small">
                            <canvas id="chart-do-dia"></canvas>
                        </div>
                    </div>
                    <div class="text-center mb-2 small text-secondary">
                        <i class="bi bi-person-x me-1"></i>
                        <strong id="quantidade-nao-atribuidos"></strong> chamados não atribuídos
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-clock-history me-2"></i>Desenvolvimento
                            </h2>
                            <div class="dashboard-card-subtitle">Listagem de chamados que estão em andamento</div>
                        </div>
                        <span class="status-badge badge-info">Geral</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div style="max-height: 300px; overflow: auto;">
                            <table id="tabela-chamados-em-desenvolvimento" class="table dashboard-table table-hover">
                                <thead>
                                    <tr>
                                        <th class="text-center">Id</th>
                                        <th class="text-center">Criação</th>
                                        <th class="text-center">Título</th>
                                        <th>Participantes</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-12">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-bar-chart-line me-2"></i>Chamados por aba
                            </h2>
                            <div class="dashboard-card-subtitle">Quantitativo dos chamados que ainda não foram arquivados</div>
                        </div>
                        <span class="status-badge badge-info">Geral</span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container-small">
                            <canvas id="chart-abas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-12">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-bar-chart-line me-2"></i>Evolução dos chamados por módulo
                            </h2>
                            <div class="dashboard-card-subtitle">Acompanhamento mensal de chamados criados e finalizados por sistema</div>
                        </div>
                        <span id="ano-grafico-evolucao-modulo" class="status-badge badge-info"></span>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container-small">
                            <canvas id="chart-evolucao-mes-sistema"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--DETALHAMENTO DOS CHAMADOS-->
        <section class="row g-3 mb-4">
            <!-- SUPORTE-->
            <div class="col-12 col-xl-6">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-headset me-2"></i> Suporte Técnico
                            </h2>
                            <div class="dashboard-card-subtitle">Detalhamento por colaborador</div>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table id="tabela-suporte" class="tabela-detalhamento-equipe table dashboard-table table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle border">Colaborador</th>
                                    <th colspan="3" class="text-center border">Acumulados</th>
                                    <th colspan="3" class="text-center border">Filtrados</th>
                                    <th rowspan="2" class="text-center border align-middle">Desenvolvimento
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center border">Total</th>
                                    <th class="text-center border">Finalizados</th>
                                    <th class="text-center border">Abertos</th>
                                    <th class="text-center border">Total</th>
                                    <th class="text-center border">Finalizados</th>
                                    <th class="text-center border">Abertos</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="table-footer">
                                    <td class="text-center">Totais</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <!--PROGRAMAÇÃO-->
            <div class="col-12 col-xl-6">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-code-square me-2"></i> Programação
                            </h2>
                            <div class="dashboard-card-subtitle">Detalhamento por colaborador</div>
                        </div>
                    </div>
                    <div class="table-wrapper">
                        <table id="tabela-desenvolvimento" class="tabela-detalhamento-equipe table dashboard-table table-hover">
                            <thead>
                                <tr>
                                    <th rowspan="2" class="text-center align-middle border">Colaborador</th>
                                    <th colspan="3" class="text-center border">Acumulados</th>
                                    <th colspan="3" class="text-center border">Filtrados</th>
                                    <th rowspan="2" class="text-center border align-middle">Desenvolvimento
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-center border">Total</th>
                                    <th class="text-center border">Finalizados</th>
                                    <th class="text-center border">Abertos</th>
                                    <th class="text-center border">Total</th>
                                    <th class="text-center border">Finalizados</th>
                                    <th class="text-center border">Abertos</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="table-footer">
                                    <td class="text-center">Totais</td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                    <td class="text-center"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </section>
        
        <div class="relatorios-fab">
            <button type="button" class="relatorios-fab__button" data-bs-toggle="modal" data-bs-target="#modal-relatorio" title="Central de Relatórios" onclick="listarRelatorios()">
                <i class="bi bi-bar-chart-line-fill"></i>
            </button>
        </div>
        
        <div class="modal fade" id="modal-relatorio" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content relatorios-modal">
                    <div class="relatorios-header">
                        <div class="relatorios-header__icon">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="relatorios-header__title">Central de Relatórios</div>
                            <div class="relatorios-header__subtitle">Selecione um relatório para gerar</div>
                        </div>
                        <button type="button" class="relatorios-close" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                    <div class="modal-body relatorios-body">
                        <div class="relatorios-container" id="relatoriosContainer">
                            <div class="relatorios-tela">
                                <div class="relatorios-lista">
                                    <div class="relatorios-grid rel-titulos"></div>
                                </div>
                            </div>
                            <div class="relatorios-tela">
                                <div class="row rel-filtros"></div>
                            </div>
                        </div>
                    </div>
                    <div class="relatorios-footer">
                        <div id="alerta-relatorio" class="alert alert-warning py-2 mb-0 d-none" role="alert">
                        </div>
                        <div class="relatorios-footer__acao">
                            <button type="button" class="relatorios-btn-voltar" data-bs-dismiss="modal">Fechar</button>
                            <button type="button" class="relatorios-btn-gerar" onclick="gerarRelatoriosGerais()">
                                Gerar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--RODAPÉ-->
        <footer class="text-center">
            © 2026 Sicap Soluções Sistemas de Gerenciamento em Gestão Educacional
            <span>•</span>
            <span>Atualizado em:</span>
            <span class="ultima-atualizado"></span>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/exceljs/4.4.0/exceljs.min.js" integrity="sha512-dlPw+ytv/6JyepmelABrgeYgHI0O+frEwgfnPdXDTOIZz+eDgfW07QXG02/O8COfivBdGNINy+Vex+lYmJ5rxw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js" integrity="sha512-Qlv6VSKh1gDKGoJbnyA5RMXYcvnpIqhO++MhIM2fStMcGT9i2T//tSwYFlcyoRRDcDZ+TYHpH8azBBCyhpSeqw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="assets/js/main.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/index.js?v=<?php echo time(); ?>"></script>
    <script src="assets/js/relatorio.js?v=<?php echo time(); ?>"></script>
</body>

</html>