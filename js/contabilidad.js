$(document).ready(function () {
    // mostrarFacturas();
    var desde;
    var hasta;
    
    $("#volver").click(function (e) {
        e.preventDefault();
        $(location).attr('href', "index.html");
    });

    $('#facturaModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        trid = button.data('whatever');
        var modal = $(this); // Extract info from data-* attributes
        modal.find('.modal-title').html('<p><i class="fas fa-file-invoice"></i> Factura '+ trid +'</p>');
        $.ajax({
            method: "POST",
            url: "php/facturas.php",
            data: {
                text: trid,
                filtro: 'id',
                table:"facturas",
                modo: "ver_factura"
            }
        })
        .done(function (response) {
            $(".content_modal").html(response);
            $("#busqueda").val(response);
            SetNumber('.content_modal .precio');
        });
    })

    $("#buscar").click(function (e) {
        e.preventDefault();
        mostrarFacturas();
    });

});     

function mostrarFacturas() {
    desde=$("#date_desde").val();
    hasta = $("#date_hasta").val();
    $.ajax({
            method: "POST",
            url: "php/facturas.php",
            data: {
                text: desde,
                filtro: hasta,
                table:"facturas",
                modo: "contabilidad"
            }
        })
        .done(function (response) {
            $(".content").html(response);
            Sumar();
            SetNumber('.total');
            SetNumber('.compra_total');
            SetNumber('.t_total');
            SetNumber('.t_compra_total');
        });
}
function Sumar() {
    var n_total = $(".total");
    var n_compra_total = $(".compra_total");
    var t_total = 0;
    var t_compra_total = 0;
    for (let i = 0; i < n_total.length; i++) {
        t_total += parseFloat(n_total[i].innerHTML);     
        t_compra_total += parseFloat(n_compra_total[i].innerHTML);
    }
    $("#t_total").text(t_total+'');
    $("#t_compra_total").text(t_compra_total+'');
}
function SetNumber(num){
    $("#id_fact").val($(".id_factura").text());
    var numeros = $(num);
    for (let i = 0; i < numeros.length; i++) {
        numero=numeros[i].innerHTML;
        var corregido = parseFloat(numero.replace(/,/g, ""))
                .toFixed(2)
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        corregido.replace(/,/g, "");
        numeros[i].innerHTML = corregido;      
    }
}

function busquedaCambio(){
    mostrarFacturas();
}