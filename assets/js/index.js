const Defaults = {
    chamados: [],
    quantitativos: [],
    mesAtual: (new Date()).getMonth() + 1,
    anoAtual: (new Date()).getFullYear(),
    meses: {
        0: "Todos os meses",
        1: "Janeiro",
        2: "Fevereiro",
        3: "Março",
        4: "Abril",
        5: "Maio",
        6: "Junho",
        7: "Julho",
        8: "Agosto",
        9: "Setembro",
        10: "Outubro",
        11: "Novembro",
        12: "Dezembro"
    }
};

$(document).ready(async function() {
    try {
        NProgress.start();

        await Promise.all([
            listarAnos(),
            listarMeses(),
            listarTitulos(),
            listarChamados(),
            listarQuantitativos(),
            listarDeck()
        ]);

        setInterval(listarChamados, 60000);
        setInterval(listarQuantitativos, 60000);
    } catch(e) {
        console.log(e);
    } finally {
        NProgress.done();
    }
});

/*REQUISIÇÕES*/
function requisicaoPadrao(params) {
    return new Promise((res, rej) => {
        $.ajax({
            url: "app/function/indexController.php",
            data: params,
            dataType: "json",
            type: "POST",
            success: function (json) {
                if (json.result) {
                    res(json);
                } else {
                    rej(json.msg);
                }
            },
            error: function (xhr) {
                rej(
                    xhr.responseJSON?.msg ||
                    xhr.responseText ||
                    `Erro ${xhr.status}: ${xhr.statusText}` ||
                    'Erro inesperado'
                );
            }
        });
    });
}
/************/

function listarAnos() {
    try {
        const anoInicial = 2024;
        let select = $("#ano-select").empty();

        for(let ano = anoInicial; ano <= Defaults.anoAtual; ano++) {
            select.append($("<option>", { value: ano, text: ano }));
        }

        select.val(Defaults.anoAtual);
    } catch(e) {
        console.log(e);
    }
}

function listarMeses() {
    try {
        let select = $("#mes-select").empty();

        for(let key in Defaults.meses) {
            select.append($("<option>", { value: key, text: Defaults.meses[key] }));
        }

        select.val(Defaults.mesAtual);
    } catch(e) {
        console.log(e);
    }
}

async function listarTitulos() {
    try {
        let select = $("#titulo-select").html($("<option>", { value: 0, text: "Todos os Municípios / Títulos" }));

        const params = { s: 1 };

        const titulos = (await requisicaoPadrao(params)).p1;

        select.append(titulos.map((t) => $("<option>", { value: t.id, text: t.title })));
    } catch(e) {
        console.log(e);
    }
}

async function listarChamados() {
     try {
        NProgress.start();

        const anoSelecionado = $("#ano-select").val();
        const mesSelecionado = $("#mes-select").val();
        const tituloSelecionado = $("#titulo-select").val();

        const params = { 
            s: 2,
            ano: anoSelecionado,
            mes: mesSelecionado,
            titulo: tituloSelecionado
        };

        Defaults.chamados = (await requisicaoPadrao(params)).p1;

        const finalizados = Defaults.chamados.filter(e => e.finalizado == 1 || e.arquivado == 1);
        const finalizadosFiltrados = finalizados.filter(e => e.card_filtrado);
        const abertos = Defaults.chamados.filter(e => e.aberto == 1 && e.arquivado == 0);
        const abertosFiltrados = abertos.filter(e => e.card_filtrado);
        const desenvolvimento = Defaults.chamados.filter(e => e.desenvolvimento == 1 && e.arquivado == 0);
        const desenvolvimentoFiltrados = desenvolvimento.filter(e => e.card_filtrado);

        // CRIADOS
        $("#qtd-criados").html(Defaults.chamados.length);
        $("#qtd-criados-filtrados").html(Defaults.chamados.filter(e => e.card_filtrado).length);
        // FINALIZADOS
        $("#qtd-finalizados").html(finalizados.length);
        $("#qtd-finalizados-filtrados").html(finalizados.filter(e => e.card_filtrado).length);
        // ABERTOS
        $("#qtd-abertos").html(abertos.length);
        $("#qtd-abertos-filtrados").html(abertos.filter(e => e.card_filtrado).length);
        // DESENVOLVIMENTO
        $("#qtd-desenvolvimento").html(desenvolvimento.length);
        $("#qtd-desenvolvimento-filtrados").html(desenvolvimento.filter(e => e.card_filtrado).length);

        // GRÁFICOS

        // DESTUINDO GRÁFICOS
        Chart.getChart('chart-evolucao-mes')?.destroy();
        Chart.getChart('chart-chamados-abertos')?.destroy();
        Chart.getChart('chart-abas')?.destroy();

        // CONSTANTES
        const quantidadeMeses = Defaults.anoAtual > anoSelecionado ? 12 : Defaults.mesAtual;
        const labels = Object.values(Defaults.meses).slice(1, quantidadeMeses + 1);
        const chamadosAno = Defaults.chamados.filter(chamado => chamado.ano == anoSelecionado);
        const chamadosFinalizados = chamadosAno.filter(chamado => chamado.finalizado == 1 || chamado.arquivado == 1);
        const chamadosDesenvolvimento = Defaults.chamados.filter(ch => !ch.arquivado && ch.aba == "Desenvolvimento");

        // DADOS AGRUPADOS
        const chamadosPorMes = Object.groupBy(chamadosAno, chamado => chamado.mes);
        const finalizadosPorMes = Object.groupBy(chamadosFinalizados, chamado => chamado.mes);
        const chamadosNaoArquivadosPorAba = Object.groupBy(Defaults.chamados.filter(e => e.arquivado == 0), chamado => chamado.aba);

        // QUANTITATIVO EM ARRAY
        const dataAnoCriado = Array.from(
            { length: 12 },
            (_, i) => chamadosPorMes[i + 1]?.length ?? 0
        );
        const dataAnoFinalizado = Array.from(
            { length: 12 },
            (_, i) => finalizadosPorMes[i + 1]?.length ?? 0
        );

        $("#ano-grafico-evolucao").html(anoSelecionado);
        new Chart(document.getElementById('chart-evolucao-mes'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Criados',
                        data: dataAnoCriado,
                        borderColor: '#4361ee',
                        backgroundColor: 'rgba(67, 97, 238, .08)',
                        fill: true,
                        tension: .4,
                        pointRadius: 3,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Finalizados',
                        data: dataAnoFinalizado,
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

        new Chart(document.getElementById('chart-chamados-abertos'), {
            type: 'doughnut',
            data: {
                labels: [
                    'Novidades',
                    'Erros',
                    'Não informado'
                ],
                datasets: [
                    {
                        data: [
                            abertos.filter(e  => e.e_implementacao).length,
                            abertos.filter(e  => e.e_suporte).length,
                            abertos.filter(e  => !e.e_implementacao && !e.e_suporte).length
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
            },
            plugins: [{
                id: 'valores',
                afterDatasetsDraw(chart) {
                    chartValoresExplicitos(chart);
                }
            }]
        });

        const abas = [
            {
                descricao: 'Implementações',
                cor: '#4361ee'
            },
            {
                descricao: 'Chamados',
                cor: '#f72585'
            },
            {
                descricao: 'Analise',
                cor: '#7209b7'
            },
            {
                descricao: 'Desenvolvimento',
                cor: '#2fe0ff'
            },
            {
                descricao: 'Pause',
                cor: '#f8961e'
            },
            {
                descricao: 'Revisão',
                cor: '#90be6d'
            },
            {
                descricao: 'Reajuste',
                cor: '#f94144'
            },
            {
                descricao: 'Sincronização',
                cor: '#577590'
            },
            {
                descricao: 'Finalizado',
                cor: '#43aa8b'
            }
        ];

        new Chart(document.getElementById('chart-abas'), {
            type: 'bar',
            data: {
                labels: abas.map((e) => e.descricao),
                datasets: [
                    {
                        label: 'Quantidade',
                        data: abas.map((e) => chamadosNaoArquivadosPorAba[e.descricao].length),
                        backgroundColor: abas.map((e) => e.cor),
                        borderRadius: 7,
                        borderSkipped: true
                    }
                ]
            },
            options: {
                indexAxis: 'x',
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
            },
            plugins: [{
                id: 'valores',
                afterDatasetsDraw(chart) {
                    chartValoresExplicitos(chart);
                }
            }]
        });

        $(`#tabela-chamados-em-desenvolvimento tbody`).empty();
        for (let cd of chamadosDesenvolvimento) {
            $(`#tabela-chamados-em-desenvolvimento tbody`).append(`
                <tr>
                    <td class="text-center">${cd.id}</td>
                    <td class="text-center">${cd.data_criacao_card}</td>
                    <td class="text-center">${cd.titulo_card}</td>
                    <td class="fw-semibold">${cd.participantes}</td>
                </tr> 
            `);
        }

        $(".ultima-atualizado").html(new Date().toLocaleString('pt-BR').replace(',', ''));
    } catch(e) {
        console.log(e);
    } finally {
        NProgress.done();
    }
}

async function listarQuantitativos() {
     try {
        NProgress.start();

        Chart.getChart('chart-do-dia')?.destroy();

        const anoSelecionado = $("#ano-select").val();
        const mesSelecionado = $("#mes-select").val();
        const tituloSelecionado = $("#titulo-select").val();

        const params = { 
            s: 3,
            ano: anoSelecionado,
            mes: mesSelecionado,
            titulo: tituloSelecionado
        };

        Defaults.quantitativos = (await requisicaoPadrao(params)).p1;

        let chamadosFinalizadosDaSemana = Defaults.quantitativos
            .filter(q =>  q.categoria == "DESENVOLVIMENTO" && (q.finalizados_da_semana > 0 || q.abertos_da_semana > 0))
            .sort((a, b) => ((b.finalizados_da_semana + b.abertos_da_semana) - (a.finalizados_da_semana + a.abertos_da_semana)) || a.nome_usuario.localeCompare(b.nome_usuario));
 
        if(chamadosFinalizadosDaSemana.length) {
            new Chart(document.getElementById('chart-do-dia'), {
                type: 'bar',
                data: {
                    labels: chamadosFinalizadosDaSemana.map((e) => e.nome_usuario),
                    datasets: [
                        {
                            label: 'abertos',
                            data: chamadosFinalizadosDaSemana.map((e) => e.abertos_da_semana),
                            backgroundColor: "#f59f00",
                            borderRadius: 2,
                            barThickness: 35
                        },
                        {
                            label: 'finalizados',
                            data: chamadosFinalizadosDaSemana.map((e) => e.finalizados_da_semana),
                            backgroundColor: '#43aa8b',
                            borderRadius: 2,
                            barThickness: 35
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
                            stacked: true,
                            beginAtZero: true,
                            max: Math.ceil((chamadosFinalizadosDaSemana[0].finalizados_da_semana + chamadosFinalizadosDaSemana[0].abertos_da_semana) * 1.2),
                            ticks: {
                                stepSize: 1
                            },
                            grid: {
                                color: '#f0f1f3'
                            }
                        },
                        y: {
                            stacked: true,
                            grid: {
                                display: false
                            }
                        }
                    }
                },
                plugins: [{
                    id: 'valores',
                    afterDatasetsDraw(chart) {
                        chartValoresExplicitos(chart);
                    }
                }]
            });
        }
        
        const programacao = Defaults.quantitativos.filter(q => q.categoria == 'DESENVOLVIMENTO');
        const suporte = Defaults.quantitativos.filter(q => q.categoria == 'SUPORTE');

        // QUANTITATIVOS
        $(".tabela-detalhamento-equipe tbody").empty();
        for (let eqp of [...suporte, ...programacao]) {
            $(`#tabela-${(eqp.categoria).toLowerCase()} tbody`).append(`
                <tr class="${eqp.usuario_ativo ? "" : "table-danger border"}">
                    <td> ${eqp.usuario_ativo ? "" : "<span class='cursor-pointer caveira'>💀</span>"} ${eqp.nome_usuario}</td>
                    <td class="text-center border">${eqp.acumulados}</td>
                    <td class="text-center border">${eqp.finalizados}</td>
                    <td class="text-center border">${eqp.abertos}</td>
                    <td class="text-center border">${eqp.acumulados_filtrados}</td>
                    <td class="text-center border">${eqp.finalizados_filtrados}</td>
                    <td class="text-center border">${eqp.abertos_filtrados}</td>
                    <td class="text-center border">${eqp.desenvolvimento}</td>
                </tr>    
            `);
        }

        const criarTotalizantes = (tabela) => {
            tabela.querySelectorAll('tfoot td').forEach((td, coluna) => {
                if (coluna === 0) return;

                td.textContent = [...tabela.querySelectorAll(`tbody tr td:nth-child(${coluna + 1})`)]
                    .reduce((total, td) => total + Number(td.textContent), 0);
            });
        }
        
        criarTotalizantes(document.querySelector('#tabela-suporte'));
        criarTotalizantes(document.querySelector('#tabela-desenvolvimento'));
    } catch(e) {
        console.log(e);
    } finally {
        NProgress.done();
    }
}

async function listarDeck() {
     try {
        const params = { 
            s: 4
        };

        eval((await requisicaoPadrao(params)).p1);
    } catch(e) {
        console.log(e);
    } finally {
        NProgress.done();
    }
}

function setarMesAtual() {
    let select = document.getElementById("mes-select");

    if(select.value == Defaults.mesAtual) return;

    select.value = Defaults.mesAtual;
}

function chartValoresExplicitos(chart) {
    const { ctx } = chart;

    ctx.save();

    chart.data.datasets.forEach((dataset, datasetIndex) => {
        const meta = chart.getDatasetMeta(datasetIndex);

        dataset.data.forEach((valor, i) => {
            const elemento = meta.data[i];

            if (!elemento || !valor || !chart.getDataVisibility(i)) return;

            ctx.fillStyle = '#fff';
            ctx.font = 'bold 16px Arial';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'middle';

            if (chart.config.type === 'bar') {
                // BARRA HORIZONTAL
                if (chart.options.indexAxis === 'y') {
                    const x = (elemento.x + elemento.base) / 2;
                    const y = elemento.y;

                    ctx.fillText(valor, x, y);
                }
                // BARRA VERTICAL
                else {
                    const x = elemento.x;
                    const y = (elemento.y + elemento.base) / 2;

                    ctx.fillText(valor, x, y);
                }
            }
            else if (chart.config.type === 'pie' ||chart.config.type === 'doughnut') {
                const angle = (elemento.startAngle + elemento.endAngle) / 2;
                const raio = (elemento.outerRadius + elemento.innerRadius) / 2;

                const x = elemento.x + Math.cos(angle) * raio;
                const y = elemento.y + Math.sin(angle) * raio;

                ctx.fillText(valor, x, y);
            }
        });
    });

    ctx.restore();
}

$(document).on("click", ".coroa, .caveira", function () {
    for (let i = 0; i < 3; i++) {
        const ai = $("<span class='ai'>Ui</span>");

        ai.css({
            left: (50 + Math.random() * 10 - 5) + "%"
        });

        $(this).append(ai);

        ai.on("animationend", function () {
            $(this).remove();
        });
    }
});