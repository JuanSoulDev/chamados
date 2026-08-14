let isRequestInProgress = false; 

const data_atual = new Date();
const mes_atual = data_atual.getMonth() + 1;
const ano_atual = data_atual.getFullYear();

$('#anoSelect').val(ano_atual);
$('#mesSelect').val(mes_atual);

async function start(mes, ano) {
    if (isRequestInProgress) {
        console.log("Requisição ainda está em andamento. Aguardando...");
        return;
    }

    isRequestInProgress = true;

    try {
        const chamados = JSON.parse(await lista_chamados(mes, ano));

        if(chamados){
            const tabela_suporte         = $('#table_chamados_suporte');
            const tabela_desenvolvimento = $('#table_chamados_desenvolvimento');
            
            const kpi_abertos            = $('#qtd_acumulados');
            const kpi_finalizados        = $('#qtd_finalizados');
            const kpi_em_aberto          = $('#qtd_em_aberto');
            const kpi_atendimento        = $('#qtd_atendimento');
            
            const kpi_abertos_mes        = $('#qtd_acumulados_mes');
            const kpi_finalizados_mes    = $('#qtd_finalizados_mes');
            const kpi_em_aberto_mes      = $('#qtd_em_aberto_mes');
            const kpi_atendimento_mes    = $('#qtd_atendimento_mes');

            tabela_suporte.html('');
            tabela_desenvolvimento.html('');
            
            kpi_abertos.html(chamados[0]['ACUMULADOS']);
            kpi_finalizados.html(chamados[0]['FINALIZADOS']);
            kpi_em_aberto.html(chamados[0]['ABERTOS']);
            kpi_atendimento.html(chamados[0]['EM_DESENVOLVIMENTO']);

            kpi_abertos_mes.html(chamados[0]['ACUMULADOS_MES']);
            kpi_finalizados_mes.html(chamados[0]['FINALIZADOS_MES']);
            kpi_em_aberto_mes.html(chamados[0]['ABERTOS_MES']);
            kpi_atendimento_mes.html(chamados[0]['EM_DESENVOLVIMENTO_MES']);

            const dados_suporte = chamados[0];
            const dados_sup = JSON.parse(dados_suporte.SUPORTE);
            
            const dados_desenvolvimento = chamados[0];
            const dados_dev = JSON.parse(dados_desenvolvimento.DESENVOLVIMENTO);
        
            let total_acumulado = 0;
            let total_finalizados = 0;
            let total_abertos = 0;
            let total_acumulado_mes = 0;
            let total_finalizados_mes = 0;
            let total_abertos_mes = 0;
            let total_em_desenvolvimento = 0;

            dados_sup.forEach((chamado, index) => {
                tabela_suporte.append(
                    `
                        <tr class="${chamado.ativo ? "" : "table-danger border"}">
                            <td>
                                ${index == 0 ? "<span class='cursor-pointer coroa'>👑</span>" : ""} ${chamado.ativo ? "" : "<span class='cursor-pointer caveira'>💀</span>"} ${capitalizeName(chamado.operador)}
                            </td>
                            <td class="text-center">${chamado.acumulado}</td>
                            <td class="text-center">${chamado.finalizados}</td>
                            <td class="text-center">${chamado.abertos}</td>

                            <td class="text-center">${chamado.acumulado_mes}</td>
                            <td class="text-center">${chamado.finalizados_mes}</td>
                            <td class="text-center">${chamado.abertos_mes}</td>

                            <td class="text-center">${chamado.em_atendimento}</td>
                        </tr>
                    `
                )

                total_acumulado += parseFloat(chamado.acumulado) || 0;
                total_finalizados += parseFloat(chamado.finalizados) || 0;
                total_abertos += parseFloat(chamado.abertos) || 0;
                total_acumulado_mes += parseFloat(chamado.acumulado_mes) || 0;
                total_finalizados_mes += parseFloat(chamado.finalizados_mes) || 0;
                total_abertos_mes += parseFloat(chamado.abertos_mes) || 0;
                total_em_desenvolvimento += parseFloat(chamado.em_atendimento) || 0;
            });
            
            $('#total_acumulado').text(total_acumulado);
            $('#total_finalizados').text(total_finalizados);
            $('#total_abertos').text(total_abertos);
            $('#total_acumulado_mes').text(total_acumulado_mes);
            $('#total_finalizados_mes').text(total_finalizados_mes);
            $('#total_abertos_mes').text(total_abertos_mes);
            $('#total_em_desenvolvimento').text(total_em_desenvolvimento);


            let total_acumulado_dev = 0;
            let total_finalizados_dev = 0;
            let total_abertos_dev = 0;
            let total_acumulado_mes_dev = 0;
            let total_finalizados_mes_dev = 0;
            let total_abertos_mes_dev = 0;
            let total_em_desenvolvimento_dev = 0;

            dados_dev.forEach((chamado, index) => {
                tabela_desenvolvimento.append(
                    `
                        <tr class="${chamado.ativo ? "" : "table-danger border"}">
                            <td>
                                ${index == 0 ? "<span class='cursor-pointer coroa'>👑</span>" : ""} ${chamado.ativo ? "" : "<span class='cursor-pointer caveira'>💀</span>"} ${capitalizeName(chamado.operador)}
                            </td>
                            <td class="text-center">${chamado.acumulado}</td>
                            <td class="text-center">${chamado.finalizados}</td>
                            <td class="text-center">${chamado.abertos}</td>

                            <td class="text-center">${chamado.acumulado_mes}</td>
                            <td class="text-center">${chamado.finalizados_mes}</td>
                            <td class="text-center">${chamado.abertos_mes}</td>

                            <td class="text-center">${chamado.em_atendimento}</td>
                        </tr>
                    `
                )

                total_acumulado_dev += parseFloat(chamado.acumulado) || 0;
                total_finalizados_dev += parseFloat(chamado.finalizados) || 0;
                total_abertos_dev += parseFloat(chamado.abertos) || 0;
                total_acumulado_mes_dev += parseFloat(chamado.acumulado_mes) || 0;
                total_finalizados_mes_dev += parseFloat(chamado.finalizados_mes) || 0;
                total_abertos_mes_dev += parseFloat(chamado.abertos_mes) || 0;
                total_em_desenvolvimento_dev += parseFloat(chamado.em_atendimento) || 0;
            });
            
            $('#total_acumulado_dev').text(total_acumulado_dev);
            $('#total_finalizados_dev').text(total_finalizados_dev);
            $('#total_abertos_dev').text(total_abertos_dev);
            $('#total_acumulado_mes_dev').text(total_acumulado_mes_dev);
            $('#total_finalizados_mes_dev').text(total_finalizados_mes_dev);
            $('#total_abertos_mes_dev').text(total_abertos_mes_dev);
            $('#total_em_desenvolvimento_dev').text(total_em_desenvolvimento_dev);
        }
    } catch (error) {
        console.error("Erro na requisição:", error);
    } finally {
        isRequestInProgress = false;
    }
}

async function lista_chamados() {
    return new Promise((resolve, reject) => {
        $.ajax({
            type: 'POST',
            url: "app/function/function.php",
            data: {
                s: 1,
                mes: $('#mesSelect').val(),
                ano: $('#anoSelect').val(),
                label: $("#municipioSelect").val()
            },
            success: function(response) {
                resolve(response);
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição: ', status, error);
                reject(error);
            }
        });
    });
}

function lista_municipios() {
    $("#municipioSelect").html($("<option>", { selected: true, value: 0, text: "Todos os Municípos/Títulos" }));

    $.ajax({
        type: 'POST',
        url: "app/function/function.php",
        data: {
            s: 2
        },
        success: function(json) {
            for(m of JSON.parse(json)) {
                $("#municipioSelect").append($("<option>", { value: m.id, text: m.title }));
            }            
        },
        error: function(xhr, status, error) {
            console.error('Erro na requisição: ', status, error);
        }
    });
}

function capitalizeName(name) {
    return name
    .toLowerCase()
    .replace(/(?:^|\s)\S/g, char => char.toUpperCase()); 
}

document.addEventListener("DOMContentLoaded", function () {
    lista_municipios();
    start();
    setInterval(start, 60000);
});

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

let etapa = 0; document.addEventListener("keydown", (e) => { const key = e.key.toLowerCase(); if (etapa === 0 && key === "control") { etapa = 1;} else if (etapa === 1 && key === "r") {    etapa = 2;} else if (etapa === 2 && key === "m") {  $("body").prepend(`<img src="assets/msc/m.jpeg" id="jumpscare"><audio id="fah"> <source src="assets/msc/fahhh_KcgAXfs.mp3" type="audio/mpeg"></audio>`); document.querySelector("audio#fah").play();     document.getElementById("jumpscare").classList.add("mostrar");    setTimeout(() => {   document.getElementById("jumpscare").classList.remove("mostrar"); }, 125); setTimeout(() => { $("#jumpscare").eq(0).remove(); $("#fah").eq(0).remove(); }, 2000);   etapa = 0;} else {    etapa = 0;}});