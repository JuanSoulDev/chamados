const urlRelatorio = {
    // padrao: server + "novo_relatorio_controller.php",
    infinity: "https://infinity.sicapsolucoes.com/app-geral/controller/novo_relatorio_controller.php",
    infinity_sec: "https://infinity.sicapsolucoes.com/app/controller/novo_relatorio_controller.php"
};

const tipoFiltro = {
    1: "SELECT",
    2: "SELECT_MULTIPLE",
    3: "SELECT_MULTIPLE_TAGS",
    4: "INPUT_DATE",
    5: "CHECKBOX",
    6: "CHECKBOX_MULTIPLE",
    7: "TEXTAREA"
};

class FiltrosLista {
    static listarTitulos(idRelatorio) {
        const select = $(`[data-filtro=${idRelatorio}] .container-filtros .listarTitulos`);

        select.html($("<option>", { "data-titulo": 0, value: 0, text: "TODOS OS TÍTULOS" })); 
        select.append(Defaults.titulos.map((t) => $("<option>", { "data-titulo": t.id, value: t.id, text: t.title }))); 

        select.select2({ 
            width: '100%', 
            dropdownParent: $('#modal-relatorio')
        });
    }
}

const filtros = {
    titulos: {
        funcao: FiltrosLista.listarTitulos, label: "TÍTULOS", chave: "titulo", tipoFiltro: 1
    },
    dataInicial: {
        funcao: null, label: "DATA INICIAL", chave: "data_inicial", tipoFiltro: 4
    },
    dataFinal: {
        funcao: null, label: "DATA FINAL", chave: "data_final", tipoFiltro: 4
    }
};

const relatorios = [
    {
        id: 1, nome: "Chamados", descricao: "Listagem de Chamados gerais", filtros: [ filtros.dataInicial, filtros.dataFinal, filtros.titulos],
    }
].sort((a1, a2) => a1.nome.localeCompare(a2.nome));

function listarRelatorios() {
    const modal = $("#modal-relatorio");
    const modalTitulos = modal.find(".rel-titulos").empty();
    const modalFiltros = modal.find(".rel-filtros").empty();

    document
        .getElementById('relatoriosContainer')
        .classList.remove('filtros-abertos');

    relatorios.forEach((rel) => {
        modalTitulos.append(/*HTML*/`
            <button data-relatorio="${rel.id}" type="button" class="relatorios-card">
                <div class="relatorios-card__icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="relatorios-card__content">
                    <div class="relatorios-card__title">${rel.nome}</div>
                    <div class="relatorios-card__description">${rel.descricao}</div>
                </div>
                <div class="relatorios-card__arrow">
                    <i class="bi bi-chevron-right"></i>
                </div>
            </button>
        `);

        modalFiltros.append(/*HTML*/`
            <div data-filtro="${rel.id}" class="relatorios-filtros d-none">
                <div class="relatorios-filtros__topo">
                    <button type="button" class="relatorios-voltar">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                    <div class="relatorios-filtros__relatorio">
                        <div class="relatorios-filtros__icone">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div>
                            <div class="relatorios-filtros__titulo">${rel.nome}</div>
                            <div class="relatorios-filtros__subtitulo">${rel.descricao}</div>
                        </div>
                    </div>
                </div>
                <div class="relatorios-filtros__box">
                    <div class="relatorios-filtros__box-titulo">
                        <i class="bi bi-funnel-fill"></i>
                        <span>Filtrar resultados</span>
                    </div>
                    <div class="row container-filtros"></div>
                </div>
            </div>    
        `);
    });

    modal.find("[data-relatorio]").on("click", function() {
        const idRelatorio = parseInt(this.dataset.relatorio);
        const objRel = relatorios.find(fil => fil.id == idRelatorio);

        modalTitulos.find("[data-relatorio]").removeClass("selecionado");
        modalTitulos.find(`[data-relatorio=${idRelatorio}]`).addClass("selecionado");

        modalFiltros.find(".relatorios-filtros").addClass("d-none");
        modalFiltros.find(`[data-filtro=${idRelatorio}]`).removeClass("d-none");

        modalFiltros.find(".container-filtros").empty();

        setTimeout(function () {
            document
                .getElementById('relatoriosContainer')
                .classList.add('filtros-abertos');
        }, 180);

        console.log(
            `%cRelatório: ${idRelatorio}`,
            `color: #${Math.floor(Math.random() * 16777215).toString(16)}; font-weight: 700; font-size: 13px;`
        );

        if (objRel.filtros.length) {
            listarFiltros(idRelatorio, objRel.filtros);
        }
    });

    modalFiltros.find(".relatorios-voltar").on("click", function() {
        document
            .getElementById('relatoriosContainer')
            .classList.remove('filtros-abertos');
    });

    modal.modal("show");
}

function listarFiltros(idRelatorio, filtros) {
    const container = $(`[data-filtro=${idRelatorio}] .container-filtros`);

    for ([i, fil] of filtros.entries()) {
        const onchange = (fil.onchange ? `onchange='FiltrosLista.${fil.onchange.name}(${idRelatorio}, ${JSON.stringify(fil.objConfigOnchange)}, this.value)'` : "");

        switch (fil.tipoFiltro) {
            case 1:
            case 2:
            case 3:
                container.append(`
                    <div class="col-md-6">
                        <div data-tipo-filtro="${fil.tipoFiltro}" class="relatorios-filtro">
                            <label>${fil.label}</label> 
                            <select data-filtro="${fil.chave}" ${fil.tipoFiltro != 1 ? "multiple" : ""} class="input w-full border flex-1 ${fil.funcao.name}" ${onchange}></select>
                        </div>
                    </div>
                `);
                break;
            case 4:
                container.append(`
                    <div class="col-md-6">
                        <div data-tipo-filtro="${fil.tipoFiltro}"  class="relatorios-filtro">
                            <label>${fil.label}</label> 
                            <input data-filtro="${fil.chave}" type="date" class="form-control form-control-sm">
                        </div>
                    </div>
                `);
                break;
            case 5:
                container.append(`
                    <div data-tipo-filtro="${fil.tipoFiltro}"  class="form-group mt-2"> 
                        <label class="flex items-center"> 
                            <input data-filtro="${fil.chave}" type="checkbox" class="input input--switch border mr-2">
                            ${fil.label}
                        </label>
                    </div>
                `);
                break;
            case 6:
                container.append(`
                    <div data-tipo-filtro="${fil.tipoFiltro}"  class="form-group mt-2 ${fil.funcao.name}"> 
                    </div>
                `);
                break;
            case 7:
                container.append(`
                    <div data-tipo-filtro="${fil.tipoFiltro}"  class="form-group mt-2"> 
                        <label>${fil.label}</label>
                        <textarea data-filtro="${fil.chave}" class="p-3 block border w-full"></textarea>
                    </div>
                `);
                break;

        }

        if (!fil.possuiDependencia && fil.funcao) fil.funcao(idRelatorio, fil.objConfig);
    }
}

function gerarRelatoriosGerais() {
    if (!document.querySelector(".rel-titulos [data-relatorio].selecionado")) return;

    const idRelatorio = document.querySelector(".rel-titulos [data-relatorio].selecionado").dataset.relatorio;
    const dadosRelatorio = relatorios.find(rel => rel.id == idRelatorio);
    const parametros = coletarParametrosFiltros(idRelatorio);
    const alerta = (msg) => {
        const container = $("#alerta-relatorio");

        container.removeClass("d-none");
        container.html(`<strong>⚠️ Atenção:</strong> <span>${msg}</span>`);

        setTimeout(() => {
            container.addClass("d-none");
            container.empty();
        }, 3000);
    };

    NProgress.start();
    Tela.desabilitar();

    $.ajax({
        type: "POST",
        url: "app/function/relatorio.php",
        data: Object.assign(
            { s: idRelatorio },
            parametros
        ),
        dataType: "JSON",
        success: function (json) {
            if (json.result) {
                switch (json.type) {
                    case 1: // EXCEL JS
                        gerarExcelJS(dadosRelatorio.descricao, dadosRelatorio.nome, json.p1);
                        break;
                    case 2: // PDF
                        window.open(json.p1);
                        break;
                }
            } else {
                alerta(json.msg);
                console.log(json.msg);
            }

            NProgress.done();
            Tela.habilitar();
        },
        error: function (e) {
            NProgress.done();
            Tela.habilitar();
            alerta(e);
            console.log(e);
        }
    });
}

function coletarParametrosFiltros(idRelatorio) {
    let parametros = {};

    $(`[data-filtro=${idRelatorio}] .container-filtros [data-tipo-filtro]`).each(function () {
        const tipo = Number($(this).attr("data-tipo-filtro"));
        const campo = $(this).find("[data-filtro]");

        switch (tipo) {
            case 1:
                Object.assign(parametros, campo.find("option:selected").data());
                break;
            case 2:
            case 3:
            case 4:
            case 7:
                parametros[campo.attr("data-filtro")] = campo.val();
                break;
            case 5:
                parametros[campo.attr("data-filtro")] = campo.is(":checked");
                break;
            case 6:
                campo.map((i, el) => parametros[$(el).attr("data-filtro")] = $(el).is(":checked"));
                break;
        }
    });

    delete parametros["select2Id"];

    return parametros;
}

function gerarExcelJS(tituloAba, nomeArquivo, dadosFormatadosParaDownload) {
    let workbook = new ExcelJS.Workbook();

    let worksheet = workbook.addWorksheet(tituloAba);

    let columns = Object.keys(dadosFormatadosParaDownload[0]).map(col => ({ name: col, filterButton: true }));
    let rows = dadosFormatadosParaDownload.map(val => (Object.values(val)));

    worksheet.addTable({
        name: 'TabelaExemplo',
        ref: 'A1',
        headerRow: true, 
        style: {
            theme: 'TableStyleMedium2',
            showRowStripes: true, 
        },
        columns: columns,
        rows: rows
    });

    workbook.xlsx.writeBuffer().then((buffer) => {
        let blob = new Blob([buffer], {
            type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
        });
        saveAs(blob, `${nomeArquivo}-${Date.now()}.xlsx`);
    });
}