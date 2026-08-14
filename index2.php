<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Painel de Chamados</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <!-- Chart.js -->
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

        .kpi-card::after {
            content: "";
            position: absolute;
            width: 100px;
            height: 100px;
            right: -35px;
            top: -35px;
            border-radius: 50%;
            opacity: .08;
        }

        .kpi-primary::after {
            background: var(--primary);
        }

        .kpi-success::after {
            background: var(--success);
        }

        .kpi-warning::after {
            background: var(--warning);
        }

        .kpi-info::after {
            background: var(--info);
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

    </style>
</head>


<body>

<div class="dashboard-container">

    <!-- =========================================================
         HEADER
    ========================================================== -->

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

                    <img
                        src="./assets/files/logo.svg"
                        alt="Logo"
                        style="height: 42px; max-width: 180px;">

                </div>

            </div>

        </div>

    </header>


    <!-- =========================================================
         FILTROS
    ========================================================== -->

    <section class="filter-card">

        <div class="row align-items-end g-3">

            <div class="col-12 col-lg-2">

                <div class="filter-title mb-2">
                    <i class="bi bi-funnel me-1"></i>
                    Filtros
                </div>

                <div class="text-muted small">
                    Refine os dados do painel
                </div>

            </div>


            <div class="col-6 col-lg-2">

                <label for="anoSelect" class="form-label">
                    Ano
                </label>

                <select
                    id="anoSelect"
                    class="form-select"
                    onchange="start();">

                    <option value="2024">2024</option>
                    <option value="2025">2025</option>
                    <option value="2026" selected>2026</option>

                </select>

            </div>


            <div class="col-6 col-lg-2">

                <label for="mesSelect" class="form-label">
                    Período
                </label>

                <select
                    id="mesSelect"
                    class="form-select"
                    onchange="start();">

                    <option value="0">Todos os meses</option>
                    <option value="1">Janeiro</option>
                    <option value="2">Fevereiro</option>
                    <option value="3">Março</option>
                    <option value="4">Abril</option>
                    <option value="5">Maio</option>
                    <option value="6">Junho</option>
                    <option value="7">Julho</option>
                    <option value="8">Agosto</option>
                    <option value="9">Setembro</option>
                    <option value="10">Outubro</option>
                    <option value="11">Novembro</option>
                    <option value="12">Dezembro</option>

                </select>

            </div>


            <div class="col-12 col-lg">

                <label for="municipioSelect" class="form-label">
                    Município / Título
                </label>

                <select
                    id="municipioSelect"
                    class="form-select"
                    onchange="start();">

                    <option selected value="0">
                        Todos os Municípios / Títulos
                    </option>

                    <option value="216">. MOD. PROF. - AUDITORIA</option>
                    <option value="205">. MOD. PROF. - DIÁRIO</option>
                    <option value="206">. MOD. PROF. - PARECERES</option>
                    <option value="208">. MOD. PROF. - RELATÓRIO</option>
                    <option value="207">. MOD. PROF. - TELA DE NOTAS</option>
                    <option value="262">. MOD. RH</option>
                    <option value="153">. MOD. SEC. - AUDITORIA</option>
                    <option value="198">. MOD. SEC. - HISTÓRICO ESCOLAR</option>
                    <option value="202">. MOD. SEC. - ORDEM DE SÉRIE</option>
                    <option value="211">. MOD. SEC. - RELATÓRIO</option>
                    <option value="196">. MOD. SEC. - TELA ESTUDANTE</option>
                    <option value="197">. MOD. SEC. - TELA FUNCIONÁRIO</option>
                    <option value="199">. MOD. SEC. - TELA NOTAS</option>
                    <option value="200">. MOD. SEC. - UNIFICAÇÃO DE CADASTRO</option>
                    <option value="201">. MOD. SEC. - UNIFICAÇÃO DE MATRÍCULA</option>
                    <option value="214">. MODULO AEE</option>
                    <option value="210">. MODULO ESTUDANTE</option>
                    <option value="213">. MODULO MERENDA</option>
                    <option value="204">. MODULO PROFESSOR</option>
                    <option value="215">. MODULO SEC. GERAL</option>
                    <option value="203">. MODULO SECRETARIA</option>
                    <option value="212">. MODULO TRANSPORTE</option>
                    <option value="263">.MOD SICAP DRIVE</option>
                    <option value="308">.PARTICULAR</option>
                    <option value="471">.SAE</option>
                    <option value="608">.SISTEMA CONSULTORIA</option>
                    <option value="183">.SOLICITAÇÃO</option>

                    <option value="47">PE - AGRESTINA</option>
                    <option value="88">PE - ALAGOINHA</option>
                    <option value="92">PE - AMARAJI</option>
                    <option value="514">PE - ARARIPINA</option>
                    <option value="91">PE - ARAÇOIABA</option>
                    <option value="48">PE - BARREIROS</option>
                    <option value="89">PE - BELEM DO SÃO FRANCISCO</option>
                    <option value="74">PE - BODOCÓ</option>
                    <option value="95">PE - BOM CONSELHO</option>
                    <option value="81">PE - BOM JARDIM</option>
                    <option value="632">PE - BREJO DA M. DE DEUS</option>
                    <option value="82">PE - BUENOS AIRES</option>
                    <option value="103">PE - CACHOEIRINHA</option>
                    <option value="470">PE - CALUMBI</option>
                    <option value="94">PE - CAMOCIM DE SÃO FELIZ</option>
                    <option value="83">PE - CARNAUBEIRA DA PENHA</option>
                    <option value="105">PE - CARPINA</option>
                    <option value="110">PE - CATENDE</option>
                    <option value="376">PE - CEDRO</option>
                    <option value="104">PE - CORTÊS</option>
                    <option value="449">PE - DORMENTES</option>
                    <option value="93">PE - EXU</option>
                    <option value="96">PE - FEIRA NOVA</option>
                    <option value="102">PE - GAMELEIRA</option>
                    <option value="573">PE - GLORIA DO GOITA</option>
                    <option value="99">PE - GRANITO</option>
                    <option value="86">PE - IPUBI</option>
                    <option value="84">PE - ITACURUBA</option>
                    <option value="508">PE - ITAMARACA</option>
                    <option value="90">PE - ITAPISSUMA</option>
                    <option value="45">PE - JOÃO ALFREDO</option>
                    <option value="464">PE - LAGOA DE ITAENGA</option>
                    <option value="76">PE - LAGOA DO CARRO</option>
                    <option value="378">PE - MACAPARANA</option>
                    <option value="377">PE - MANARI</option>
                    <option value="106">PE - MIRANDIBA</option>
                    <option value="97">PE - MOREILANDIA</option>
                    <option value="375">PE - NAZARÉ DA MATA</option>
                    <option value="513">PE - OROCÓ</option>
                    <option value="111">PE - OURICURI</option>
                    <option value="113">PE - PARNAMIRIM</option>
                    <option value="78">PE - PASSIRA</option>
                    <option value="79">PE - RIO FORMOSO</option>
                    <option value="476">PE - SALGADINHO</option>
                    <option value="85">PE - SANTA CRUZ</option>
                    <option value="98">PE - SANTA FILOMENA</option>
                    <option value="46">PE - SERRA TALHADA</option>
                    <option value="379">PE - SERRITA</option>
                    <option value="109">PE - SIRINHAÉM</option>
                    <option value="100">PE - SÃO BENEDITO DO SUL</option>
                    <option value="101">PE - SÃO JOSÉ DO BELMONTE</option>
                    <option value="77">PE - SÃO VICENTE FÉRRER</option>
                    <option value="718">PE - TABIRA</option>
                    <option value="107">PE - TAMANDARÉ</option>
                    <option value="80">PE - TAQUARITINGA DO NORTE</option>
                    <option value="108">PE - TERRA NOVA</option>
                    <option value="209">PE - TODOS MUNICÍPIOS</option>
                    <option value="57">PE - TRINDADE</option>
                    <option value="633">PE - TRIUNFO</option>
                    <option value="112">PE - VERDEJANTE</option>
                    <option value="603">PE - VERTENTE DO LERIO</option>
                    <option value="602">PE - VERTENTES</option>
                    <option value="370">PE - VICÊNCIA</option>
                    <option value="87">PE - ÁGUA PRETA</option>
                    <option value="717">PI - ESPERANTINA</option>

                    <option value="714">_BUG</option>
                    <option value="715">_CUSTOMIZAR</option>
                    <option value="750">_INCONSISTENTE</option>
                    <option value="716">_NOVO</option>

                </select>

            </div>


            <div class="col-12 col-lg-auto">

                <label class="form-label d-none d-lg-block">
                    &nbsp;
                </label>

                <button
                    type="button"
                    class="btn btn-refresh w-100"
                    onclick="start();">

                    <i class="bi bi-arrow-clockwise me-1"></i>
                    Atualizar

                </button>

            </div>

        </div>

    </section>


    <!-- =========================================================
         KPI CARDS
    ========================================================== -->

    <section class="row g-3 mb-4">

        <!-- CRIADOS -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-primary">

                <div class="kpi-top">

                    <div class="kpi-title">
                        Chamados criados
                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-ticket-perforated"></i>
                    </div>

                </div>

                <div class="kpi-number" id="qtd_acumulados">
                    6825
                </div>

                <div class="kpi-footer">

                    <span class="kpi-period">
                        No período:
                        <strong id="qtd_acumulados_mes">113</strong>
                    </span>

                    <span class="kpi-positive">
                        <i class="bi bi-arrow-up"></i>
                        período
                    </span>

                </div>

            </div>

        </div>


        <!-- FINALIZADOS -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-success">

                <div class="kpi-top">

                    <div class="kpi-title">
                        Finalizados
                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-check2-circle"></i>
                    </div>

                </div>

                <div class="kpi-number" id="qtd_finalizados">
                    6669
                </div>

                <div class="kpi-footer">

                    <span class="kpi-period">
                        No período:
                        <strong id="qtd_finalizados_mes">97</strong>
                    </span>

                    <span class="text-success fw-semibold">
                        <i class="bi bi-check-lg"></i>
                        resolvidos
                    </span>

                </div>

            </div>

        </div>


        <!-- ABERTOS -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-warning">

                <div class="kpi-top">

                    <div class="kpi-title">
                        Em aberto
                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-exclamation-circle"></i>
                    </div>

                </div>

                <div class="kpi-number" id="qtd_em_aberto">
                    156
                </div>

                <div class="kpi-footer">

                    <span class="kpi-period">
                        No período:
                        <strong id="qtd_em_aberto_mes">16</strong>
                    </span>

                    <span class="text-warning fw-semibold">
                        aguardando
                    </span>

                </div>

            </div>

        </div>


        <!-- DESENVOLVIMENTO -->

        <div class="col-12 col-sm-6 col-xl-3">

            <div class="kpi-card kpi-info">

                <div class="kpi-top">

                    <div class="kpi-title">
                        Em desenvolvimento
                    </div>

                    <div class="kpi-icon">
                        <i class="bi bi-code-slash"></i>
                    </div>

                </div>

                <div class="kpi-number" id="qtd_atendimento">
                    7
                </div>

                <div class="kpi-footer">

                    <span class="kpi-period">
                        No período:
                        <strong id="qtd_atendimento_mes">1</strong>
                    </span>

                    <span class="text-info fw-semibold">
                        em andamento
                    </span>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         GRÁFICOS
    ========================================================== -->

    <section class="row g-3 mb-4">

        <!-- EVOLUÇÃO -->

        <div class="col-12 col-xl-8">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>

                        <h2 class="dashboard-card-title">
                            <i class="bi bi-graph-up me-2"></i>
                            Evolução dos chamados
                        </h2>

                        <div class="dashboard-card-subtitle">
                            Acompanhamento mensal de chamados criados e finalizados
                        </div>

                    </div>

                    <span class="status-badge badge-info">
                        2026
                    </span>

                </div>

                <div class="dashboard-card-body">

                    <div class="chart-container">

                        <canvas id="chartEvolucao"></canvas>

                    </div>

                </div>

            </div>

        </div>


        <!-- SITUAÇÃO -->

        <div class="col-12 col-xl-4">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>

                        <h2 class="dashboard-card-title">
                            <i class="bi bi-pie-chart me-2"></i>
                            Situação atual
                        </h2>

                        <div class="dashboard-card-subtitle">
                            Distribuição dos chamados acumulados
                        </div>

                    </div>

                </div>

                <div class="dashboard-card-body">

                    <div class="chart-container-small">

                        <canvas id="chartSituacao"></canvas>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         SUPORTE X DESENVOLVIMENTO
    ========================================================== -->

    <section class="row g-3 mb-4">

        <!-- SUPORTE -->

        <div class="col-12 col-xl-6">

            <div class="team-card team-support">

                <div class="team-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="team-icon">
                            <i class="bi bi-headset"></i>
                        </div>

                        <div>

                            <h3 class="team-title">
                                Suporte Técnico
                            </h3>

                            <div class="team-subtitle">
                                Atendimento e resolução de chamados
                            </div>

                        </div>

                    </div>

                    <span class="status-badge badge-success">
                        Ativo
                    </span>

                </div>


                <div class="row team-stat">

                    <div class="col-4">

                        <div
                            class="team-stat-number"
                            id="total_acumulado">

                            7054

                        </div>

                        <div class="team-stat-label">
                            Total
                        </div>

                    </div>


                    <div class="col-4">

                        <div
                            class="team-stat-number text-success"
                            id="total_finalizados">

                            6886

                        </div>

                        <div class="team-stat-label">
                            Finalizados
                        </div>

                    </div>


                    <div class="col-4">

                        <div
                            class="team-stat-number text-warning"
                            id="total_abertos">

                            168

                        </div>

                        <div class="team-stat-label">
                            Em aberto
                        </div>

                    </div>

                </div>


                <div class="mt-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="small fw-semibold">
                            Taxa de resolução
                        </span>

                        <span
                            class="small fw-bold"
                            id="taxa_suporte">

                            97,6%

                        </span>

                    </div>

                    <div class="progress">

                        <div
                            id="progress_suporte"
                            class="progress-bar bg-success"
                            style="width: 97.6%;">

                        </div>

                    </div>

                </div>


                <div class="mt-3 text-muted small">

                    <i class="bi bi-calendar3 me-1"></i>

                    No período:
                    <strong id="total_acumulado_mes">
                        132
                    </strong>

                    chamados

                </div>

            </div>

        </div>


        <!-- DESENVOLVIMENTO -->

        <div class="col-12 col-xl-6">

            <div class="team-card team-development">

                <div class="team-header">

                    <div class="d-flex align-items-center gap-3">

                        <div class="team-icon">
                            <i class="bi bi-code-square"></i>
                        </div>

                        <div>

                            <h3 class="team-title">
                                Desenvolvimento
                            </h3>

                            <div class="team-subtitle">
                                Correções, melhorias e novas funcionalidades
                            </div>

                        </div>

                    </div>

                    <span class="status-badge badge-success">
                        Ativo
                    </span>

                </div>


                <div class="row team-stat">

                    <div class="col-4">

                        <div
                            class="team-stat-number"
                            id="total_acumulado_dev">

                            6647

                        </div>

                        <div class="team-stat-label">
                            Total
                        </div>

                    </div>


                    <div class="col-4">

                        <div
                            class="team-stat-number text-success"
                            id="total_finalizados_dev">

                            6526

                        </div>

                        <div class="team-stat-label">
                            Finalizados
                        </div>

                    </div>


                    <div class="col-4">

                        <div
                            class="team-stat-number text-warning"
                            id="total_abertos_dev">

                            121

                        </div>

                        <div class="team-stat-label">
                            Em aberto
                        </div>

                    </div>

                </div>


                <div class="mt-4">

                    <div class="d-flex justify-content-between mb-2">

                        <span class="small fw-semibold">
                            Taxa de resolução
                        </span>

                        <span
                            class="small fw-bold"
                            id="taxa_desenvolvimento">

                            98,2%

                        </span>

                    </div>

                    <div class="progress">

                        <div
                            id="progress_desenvolvimento"
                            class="progress-bar bg-success"
                            style="width: 98.2%;">

                        </div>

                    </div>

                </div>


                <div class="mt-3 text-muted small">

                    <i class="bi bi-calendar3 me-1"></i>

                    No período:
                    <strong id="total_acumulado_mes_dev">
                        120
                    </strong>

                    chamados

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         DESEMPENHO
    ========================================================== -->

    <section class="row g-3 mb-4">

        <div class="col-12">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>

                        <h2 class="dashboard-card-title">
                            <i class="bi bi-trophy me-2"></i>
                            Desempenho da equipe
                        </h2>

                        <div class="dashboard-card-subtitle">
                            Comparativo de chamados por colaborador
                        </div>

                    </div>

                    <button
                        class="btn btn-sm btn-light"
                        type="button"
                        data-bs-toggle="collapse"
                        data-bs-target="#rankingColaboradores">

                        <i class="bi bi-chevron-down"></i>

                    </button>

                </div>


                <div
                    id="rankingColaboradores"
                    class="collapse show">

                    <div class="dashboard-card-body">

                        <div class="row">

                            <div class="col-lg-5 mb-4 mb-lg-0">

                                <div class="fw-semibold mb-3">
                                    Chamados por colaborador
                                </div>

                                <div class="chart-container">

                                    <canvas id="chartColaboradores"></canvas>

                                </div>

                            </div>


                            <div class="col-lg-7">

                                <div class="table-wrapper">

                                    <table class="table dashboard-table table-hover">

                                        <thead>

                                            <tr>

                                                <th>
                                                    Colaborador
                                                </th>

                                                <th class="text-center">
                                                    Total
                                                </th>

                                                <th class="text-center">
                                                    Finalizados
                                                </th>

                                                <th class="text-center">
                                                    Em aberto
                                                </th>

                                                <th class="text-center">
                                                    Desenvolvimento
                                                </th>

                                                <th class="text-center">
                                                    Taxa
                                                </th>

                                            </tr>

                                        </thead>


                                        <tbody id="table_ranking_colaboradores">

                                            <tr>

                                                <td class="fw-semibold">
                                                    Lucas Silva
                                                </td>

                                                <td class="text-center">
                                                    83
                                                </td>

                                                <td class="text-center text-success">
                                                    77
                                                </td>

                                                <td class="text-center text-warning">
                                                    6
                                                </td>

                                                <td class="text-center">
                                                    1
                                                </td>

                                                <td class="text-center">

                                                    <span class="status-badge badge-success">
                                                        92,8%
                                                    </span>

                                                </td>

                                            </tr>

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         TABELAS DETALHADAS
    ========================================================== -->

    <div class="d-flex justify-content-between align-items-center mb-2">

        <h2 class="section-title">
            Detalhamento dos chamados
        </h2>

    </div>


    <section class="row g-3">

        <!-- =====================================================
             SUPORTE
        ====================================================== -->

        <div class="col-12 col-xl-6">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>

                        <h2 class="dashboard-card-title">
                            <i class="bi bi-headset me-2"></i>
                            Suporte Técnico
                        </h2>

                        <div class="dashboard-card-subtitle">
                            Detalhamento por colaborador
                        </div>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="table dashboard-table table-hover">

                        <thead>

                            <tr>

                                <th rowspan="2">
                                    Colaborador
                                </th>

                                <th
                                    colspan="3"
                                    class="text-center">

                                    Acumulados

                                </th>

                                <th
                                    colspan="3"
                                    class="text-center">

                                    Período

                                </th>

                                <th
                                    rowspan="2"
                                    class="text-center">

                                    Desenvolvimento

                                </th>

                            </tr>

                            <tr>

                                <th class="text-center">
                                    Total
                                </th>

                                <th class="text-center">
                                    Finalizados
                                </th>

                                <th class="text-center">
                                    Abertos
                                </th>

                                <th class="text-center">
                                    Total
                                </th>

                                <th class="text-center">
                                    Finalizados
                                </th>

                                <th class="text-center">
                                    Abertos
                                </th>

                            </tr>

                        </thead>


                        <tbody id="table_chamados_suporte">

                            <!-- SEU JS CONTINUA PREENCHENDO AQUI -->

                            <tr>

                                <td>
                                    Lucas Silva 🛖
                                </td>

                                <td class="text-center">
                                    83
                                </td>

                                <td class="text-center">
                                    77
                                </td>

                                <td class="text-center">
                                    6
                                </td>

                                <td class="text-center">
                                    7
                                </td>

                                <td class="text-center">
                                    6
                                </td>

                                <td class="text-center">
                                    0
                                </td>

                                <td class="text-center">
                                    1
                                </td>

                            </tr>

                        </tbody>


                        <tfoot>

                            <tr class="table-footer">

                                <td class="text-center">
                                    Totais
                                </td>

                                <td
                                    id="total_acumulado"
                                    class="text-center">

                                    7054

                                </td>

                                <td
                                    id="total_finalizados"
                                    class="text-center">

                                    6886

                                </td>

                                <td
                                    id="total_abertos"
                                    class="text-center">

                                    168

                                </td>

                                <td
                                    id="total_acumulado_mes"
                                    class="text-center">

                                    132

                                </td>

                                <td
                                    id="total_finalizados_mes"
                                    class="text-center">

                                    116

                                </td>

                                <td
                                    id="total_abertos_mes"
                                    class="text-center">

                                    0

                                </td>

                                <td
                                    id="total_em_desenvolvimento"
                                    class="text-center">

                                    14

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>


        <!-- =====================================================
             DESENVOLVIMENTO
        ====================================================== -->

        <div class="col-12 col-xl-6">

            <div class="dashboard-card">

                <div class="dashboard-card-header">

                    <div>

                        <h2 class="dashboard-card-title">
                            <i class="bi bi-code-square me-2"></i>
                            Desenvolvimento
                        </h2>

                        <div class="dashboard-card-subtitle">
                            Detalhamento por colaborador
                        </div>

                    </div>

                </div>


                <div class="table-wrapper">

                    <table class="table dashboard-table table-hover">

                        <thead>

                            <tr>

                                <th rowspan="2">
                                    Colaborador
                                </th>

                                <th
                                    colspan="3"
                                    class="text-center">

                                    Acumulados

                                </th>

                                <th
                                    colspan="3"
                                    class="text-center">

                                    Período

                                </th>

                                <th
                                    rowspan="2"
                                    class="text-center">

                                    Em Desenvolvimento

                                </th>

                            </tr>

                            <tr>

                                <th class="text-center">
                                    Total
                                </th>

                                <th class="text-center">
                                    Finalizados
                                </th>

                                <th class="text-center">
                                    Abertos
                                </th>

                                <th class="text-center">
                                    Total
                                </th>

                                <th class="text-center">
                                    Finalizados
                                </th>

                                <th class="text-center">
                                    Abertos
                                </th>

                            </tr>

                        </thead>


                        <tbody id="table_chamados_desenvolvimento">

                            <!-- SEU JS CONTINUA PREENCHENDO AQUI -->

                            <tr>

                                <td>
                                    Allyson Rodrigues
                                </td>

                                <td class="text-center">
                                    1
                                </td>

                                <td class="text-center">
                                    0
                                </td>

                                <td class="text-center">
                                    1
                                </td>

                                <td class="text-center">
                                    1
                                </td>

                                <td class="text-center">
                                    0
                                </td>

                                <td class="text-center">
                                    1
                                </td>

                                <td class="text-center">
                                    0
                                </td>

                            </tr>

                        </tbody>


                        <tfoot>

                            <tr class="table-footer">

                                <td class="text-center">
                                    Totais
                                </td>

                                <td
                                    id="total_acumulado_dev"
                                    class="text-center">

                                    6647

                                </td>

                                <td
                                    id="total_finalizados_dev"
                                    class="text-center">

                                    6526

                                </td>

                                <td
                                    id="total_abertos_dev"
                                    class="text-center">

                                    121

                                </td>

                                <td
                                    id="total_acumulado_mes_dev"
                                    class="text-center">

                                    120

                                </td>

                                <td
                                    id="total_finalizados_mes_dev"
                                    class="text-center">

                                    104

                                </td>

                                <td
                                    id="total_abertos_mes_dev"
                                    class="text-center">

                                    16

                                </td>

                                <td
                                    id="total_em_desenvolvimento_dev"
                                    class="text-center">

                                    16

                                </td>

                            </tr>

                        </tfoot>

                    </table>

                </div>

            </div>

        </div>

    </section>


    <!-- =========================================================
         FOOTER
    ========================================================== -->

    <footer class="text-center">

        © 2024 Sicap Soluções Sistemas de Gerenciamento em Gestão Educacional

        <span class="mx-2">•</span>

        <span id="ultimaAtualizacao">
            Atualizado agora
        </span>

    </footer>

</div>


<!-- =============================================================
     BOOTSTRAP
============================================================= -->

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


<!-- =============================================================
     GRÁFICOS
============================================================= -->

<script>

    /*
    ================================================================
    DADOS DOS GRÁFICOS
    ================================================================

    Aqui coloque os dados que vierem do seu PHP/AJAX.

    Por enquanto estou utilizando dados ilustrativos baseados
    nos valores que já estavam no HTML original.
    */


    // ------------------------------------------------------------
    // GRÁFICO DE EVOLUÇÃO
    // ------------------------------------------------------------

    const ctxEvolucao =
        document.getElementById('chartEvolucao');


    const chartEvolucao = new Chart(ctxEvolucao, {

        type: 'line',

        data: {

            labels: [
                'Jan',
                'Fev',
                'Mar',
                'Abr',
                'Mai',
                'Jun',
                'Jul',
                'Ago'
            ],

            datasets: [

                {
                    label: 'Criados',

                    data: [
                        95,
                        108,
                        124,
                        117,
                        130,
                        121,
                        113,
                        113
                    ],

                    borderColor: '#4361ee',

                    backgroundColor: 'rgba(67, 97, 238, .08)',

                    fill: true,

                    tension: .4,

                    pointRadius: 3,

                    pointHoverRadius: 6
                },

                {
                    label: 'Finalizados',

                    data: [
                        88,
                        102,
                        117,
                        111,
                        126,
                        118,
                        106,
                        97
                    ],

                    borderColor: '#20c997',

                    backgroundColor: 'rgba(32, 201, 151, .05)',

                    fill: true,

                    tension: .4,

                    pointRadius: 3,

                    pointHoverRadius: 6
                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            interaction: {
                mode: 'index',
                intersect: false
            },

            plugins: {

                legend: {
                    position: 'bottom'
                }

            },

            scales: {

                y: {

                    beginAtZero: true,

                    grid: {
                        color: '#f0f1f3'
                    }

                },

                x: {

                    grid: {
                        display: false
                    }

                }

            }

        }

    });


    // ------------------------------------------------------------
    // GRÁFICO SITUAÇÃO
    // ------------------------------------------------------------

    const ctxSituacao =
        document.getElementById('chartSituacao');


    const chartSituacao = new Chart(ctxSituacao, {

        type: 'doughnut',

        data: {

            labels: [
                'Finalizados',
                'Em aberto',
                'Em desenvolvimento'
            ],

            datasets: [

                {

                    data: [
                        6669,
                        156,
                        7
                    ],

                    backgroundColor: [
                        '#20c997',
                        '#f59f00',
                        '#0dcaf0'
                    ],

                    borderWidth: 0,

                    hoverOffset: 8

                }

            ]

        },

        options: {

            responsive: true,

            maintainAspectRatio: false,

            cutout: '70%',

            plugins: {

                legend: {
                    position: 'bottom'
                }

            }

        }

    });


    // ------------------------------------------------------------
    // RANKING
    // ------------------------------------------------------------

    const ctxColaboradores =
        document.getElementById('chartColaboradores');


    const chartColaboradores =
        new Chart(ctxColaboradores, {

            type: 'bar',

            data: {

                labels: [
                    'Lucas Silva',
                    'João Silva',
                    'Maria Santos',
                    'Allyson Rodrigues'
                ],

                datasets: [

                    {

                        label: 'Chamados',

                        data: [
                            83,
                            71,
                            64,
                            1
                        ],

                        backgroundColor: '#4361ee',

                        borderRadius: 7,

                        borderSkipped: false

                    }

                ]

            },

            options: {

                indexAxis: 'y',

                responsive: true,

                maintainAspectRatio: false,

                plugins: {

                    legend: {
                        display: false
                    }

                },

                scales: {

                    x: {

                        beginAtZero: true,

                        grid: {
                            color: '#f0f1f3'
                        }

                    },

                    y: {

                        grid: {
                            display: false
                        }

                    }

                }

            }

        });


    // ------------------------------------------------------------
    // ATUALIZAÇÃO DO HORÁRIO
    // ------------------------------------------------------------

    function atualizarHorario() {

        const agora = new Date();

        const horario =
            agora.toLocaleTimeString(
                'pt-BR',
                {
                    hour: '2-digit',
                    minute: '2-digit'
                }
            );

        const elemento =
            document.getElementById('ultimaAtualizacao');

        if (elemento) {

            elemento.innerText =
                'Atualizado às ' + horario;

        }

    }

    atualizarHorario();

</script>

</body>
</html>