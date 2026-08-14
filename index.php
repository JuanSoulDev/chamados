<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel de Chamados</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>

        :root {
            --bg-page: #f4f6f9;
            --card-radius: 16px;
            --border-color: #e9ecef;

            --primary: #4361ee;
            --success: #20c997;
            --warning: #f59f00;
            --danger: #ef476f;
            --info: #0dcaf0;
            --dark: #212529;
            --muted: #6c757d;
        }

        body {
            background: var(--bg-page);
            color: #212529;
            font-family:
                Inter,
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                sans-serif;
        }

        /* =========================================================
           CONTAINER
        ========================================================= */

        .dashboard-container {
            max-width: 1800px;
            margin: auto;
            padding: 25px;
        }

        /* =========================================================
           HEADER
        ========================================================= */

        .dashboard-header {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 22px 25px;
            margin-bottom: 20px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, .03);
        }

        .dashboard-title {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0;
        }

        .dashboard-subtitle {
            color: var(--muted);
            font-size: .9rem;
            margin-top: 4px;
        }

        .status-online {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: #e9f9f2;
            color: #198754;
            padding: 7px 12px;
            border-radius: 30px;
            font-size: .8rem;
            font-weight: 600;
        }

        .status-online::before {
            content: "";
            width: 8px;
            height: 8px;
            background: #20c997;
            border-radius: 50%;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(32, 201, 151, .5);
            }

            70% {
                box-shadow: 0 0 0 7px rgba(32, 201, 151, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(32, 201, 151, 0);
            }
        }

        /* =========================================================
           FILTROS
        ========================================================= */

        .filter-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 18px 20px;
            margin-bottom: 20px;
        }

        .filter-title {
            font-size: .85rem;
            font-weight: 700;
            text-transform: uppercase;
            color: #6c757d;
            letter-spacing: .5px;
        }

        .form-label {
            font-size: .78rem;
            font-weight: 600;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .form-select {
            border-radius: 10px;
            border-color: #dee2e6;
            min-height: 42px;
        }

        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 .2rem rgba(67, 97, 238, .1);
        }

        .btn-refresh {
            height: 42px;
            border-radius: 10px;
            padding: 0 17px;
            background: var(--primary);
            border: none;
            color: white;
        }

        .btn-refresh:hover {
            background: #304bd8;
            color: white;
        }

        /* =========================================================
           KPI CARDS
        ========================================================= */

        .kpi-card {
            position: relative;
            overflow: hidden;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 20px;
            height: 100%;
            transition: all .25s ease;
        }

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, .07);
        }

        .kpi-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .kpi-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .kpi-primary .kpi-icon {
            background: #eef1ff;
            color: var(--primary);
        }

        .kpi-success .kpi-icon {
            background: #e8faf4;
            color: var(--success);
        }

        .kpi-warning .kpi-icon {
            background: #fff6df;
            color: var(--warning);
        }

        .kpi-info .kpi-icon {
            background: #e5f9fd;
            color: #0aa2c0;
        }

        .kpi-title {
            color: var(--muted);
            font-size: .78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .4px;
        }

        .kpi-number {
            font-size: 2rem;
            line-height: 1;
            font-weight: 750;
            margin-top: 14px;
        }

        .kpi-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            font-size: .78rem;
        }

        .kpi-period {
            color: var(--muted);
        }

        .kpi-positive {
            color: #198754;
            font-weight: 600;
        }

        /* =========================================================
           CARDS GERAIS
        ========================================================= */

        .dashboard-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            height: 100%;
            overflow: hidden;
        }

        .dashboard-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 20px;
            border-bottom: 1px solid #f0f1f3;
        }

        .dashboard-card-title {
            font-size: .95rem;
            font-weight: 700;
            margin: 0;
        }

        .dashboard-card-subtitle {
            color: var(--muted);
            font-size: .75rem;
            margin-top: 3px;
        }

        .dashboard-card-body {
            padding: 20px;
        }

        /* =========================================================
           CHARTS
        ========================================================= */

        .chart-container {
            position: relative;
            height: 300px;
        }

        .chart-container-small {
            position: relative;
            height: 260px;
        }

        /* =========================================================
           PROGRESS
        ========================================================= */

        .progress {
            height: 9px;
            border-radius: 20px;
            background: #eef0f3;
        }

        .progress-bar {
            border-radius: 20px;
        }

        .performance-item {
            margin-bottom: 18px;
        }

        .performance-item:last-child {
            margin-bottom: 0;
        }

        .performance-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            font-size: .82rem;
        }

        .performance-name {
            font-weight: 600;
        }

        .performance-value {
            color: var(--muted);
        }

        /* =========================================================
           TEAM CARDS
        ========================================================= */

        .team-card {
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--card-radius);
            padding: 20px;
            height: 100%;
        }

        .team-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .team-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .team-support .team-icon {
            background: #eef1ff;
            color: var(--primary);
        }

        .team-development .team-icon {
            background: #e8faf4;
            color: var(--success);
        }

        .team-title {
            font-weight: 700;
            margin: 0;
        }

        .team-subtitle {
            color: var(--muted);
            font-size: .78rem;
        }

        .team-stat {
            margin-top: 22px;
        }

        .team-stat-number {
            font-size: 1.5rem;
            font-weight: 700;
        }

        .team-stat-label {
            font-size: .75rem;
            color: var(--muted);
        }

        /* =========================================================
           TABELA
        ========================================================= */

        .table-wrapper {
            overflow-x: auto;
        }

        .dashboard-table {
            margin: 0;
        }

        .dashboard-table th {
            font-size: .72rem;
            text-transform: uppercase;
            color: #6c757d;
            font-weight: 700;
            white-space: nowrap;
            background: #fafbfc;
            border-bottom: 1px solid #e9ecef;
        }

        .dashboard-table td {
            font-size: .82rem;
            vertical-align: middle;
        }

        .dashboard-table tbody tr {
            transition: background .15s ease;
        }

        .dashboard-table tbody tr:hover {
            background: #f8f9ff;
        }

        .table-footer {
            background: #fafbfc;
            font-weight: 700;
        }

        /* =========================================================
           BADGES
        ========================================================= */

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: .7rem;
            font-weight: 700;
        }

        .badge-success {
            background: #e8faf4;
            color: #198754;
        }

        .badge-warning {
            background: #fff5db;
            color: #a66b00;
        }

        .badge-info {
            background: #e5f9fd;
            color: #087990;
        }

        /* =========================================================
           SECTION
        ========================================================= */

        .section-title {
            font-size: 1rem;
            font-weight: 700;
            margin: 25px 0 12px;
        }

        /* =========================================================
           FOOTER
        ========================================================= */

        footer {
            color: #8a8f98;
            font-size: .75rem;
            padding: 25px 0 5px;
        }

        /* =========================================================
           RESPONSIVO
        ========================================================= */

        @media (max-width: 768px) {

            .dashboard-container {
                padding: 12px;
            }

            .dashboard-title {
                font-size: 1.3rem;
            }

            .dashboard-header {
                padding: 18px;
            }

            .kpi-number {
                font-size: 1.7rem;
            }

            .chart-container {
                height: 250px;
            }

        }

        /*CAVEIRA*/
        <style>
        .table-responsive {
            overflow-x: auto;
            overflow-y: visible !important;
        }

       .caveira, .coroa {
            display: inline-block;
            cursor: pointer;
            transition: transform .2s ease
            position: relative;
        }

        tr:hover .caveira{
            animation: death 0.6s infinite;
            filter: drop-shadow(0 0 6px red);
        }

        tr:hover .coroa{
            animation: demise 0.6s infinite;
            filter: drop-shadow(0 0 6px white);
        }

        @keyframes death {
            0%   { transform: scale(1) rotate(0deg); }
            15%  { transform: scale(1.3) rotate(-12deg); }
            30%  { transform: scale(1.25) rotate(12deg); }
            45%  { transform: scale(1.35) rotate(-10deg); }
            60%  { transform: scale(1.25) rotate(10deg); }
            75%  { transform: scale(1.3) rotate(-8deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        @keyframes demise {
            0%   { transform: scale(1) rotate(0deg); }
            15%  { transform: scale(1.3) rotate(-12deg); }
            30%  { transform: scale(1.25) rotate(12deg); }
            45%  { transform: scale(1.35) rotate(-10deg); }
            60%  { transform: scale(1.25) rotate(10deg); }
            75%  { transform: scale(1.3) rotate(-8deg); }
            100% { transform: scale(1) rotate(0deg); }
        }

        .ai{
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            color: #ff1500;
            font-weight: bold;
            pointer-events: none;
            animation: subirAI .5s ease-out forwards;
        }

        @keyframes subirAI{
            from{
                transform: translate(-50%, 0);
                opacity: 1;
            }
            to{
                transform: translate(-50%, -40px);
                opacity: 0;
            }
        }

        #jumpscare {
            position: fixed;
            inset: 0;

            width: 100vw;
            height: 100vh;

            object-fit: cover; /* cobre toda a tela */
            object-position: center;

            opacity: 0;
            pointer-events: none;

            z-index: 2147483647;
        }

        #jumpscare.mostrar {
            animation: aparecerDesaparecer 2s ease forwards;
        }

        @keyframes aparecerDesaparecer {
            0%   { opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { opacity: 0; }
        }
    </style>
</head>

<body>
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
                        <img src="./assets/files/logo.svg" t="Logo" style="height: 42px; max-width: 180px;">
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
                    <select id="mes-select" class="form-select"></select>
                </div>
                <div class="col-12 col-lg">
                    <label for="titulo-select" class="form-label">Município / Título</label>
                    <select id="titulo-select" class="form-select"></select>
                </div>
                <div class="col-12 col-lg-auto">
                    <label class="form-label d-none d-lg-block">
                        &nbsp;
                    </label>
                    <button type="button" class="btn btn-refresh w-100" onclick="listarChamados()">
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
                        <div class="kpi-title">Chamados criados</div>
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
                    <div class="kpi-number" id="qtd-desenvolvimento">7</div>
                    <div class="kpi-footer">
                        <span class="kpi-period">
                            Filtrados: <strong id="qtd-desenvolvimento-filtrados">1</strong>
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
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container-small">
                            <canvas id="chart-chamados-abertos"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-7">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-bar-chart-steps me-2"></i>Ranking do Dia
                            </h2>
                            <div class="dashboard-card-subtitle">Programadores com mais chamados finalizados do dia</div>
                        </div>
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container-small">
                            <canvas id="chart-do-dia"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-xl-5">
                <div class="dashboard-card">
                    <div class="dashboard-card-header">
                        <div>
                            <h2 class="dashboard-card-title">
                                <i class="bi bi-pie-chart me-2"></i>Desenvolvimento
                            </h2>
                            <div class="dashboard-card-subtitle">Listagem de chamados que estão em desenvolvimento</div>
                        </div>
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
                    </div>
                    <div class="dashboard-card-body">
                        <div class="chart-container-small">
                            <canvas id="chart-abas"></canvas>
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

        <!--RODAPÉ-->
        <footer class="text-center">
            © 2026 Sicap Soluções Sistemas de Gerenciamento em Gestão Educacional
            <span>•</span>
            <span>Atualizado em:</span>
            <span id="ultima-atualizado">Atualizado agora</span>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.js"></script>
    <script src="assets/js/index.js?v=<?php echo time(); ?>"></script>
</body>

</html>