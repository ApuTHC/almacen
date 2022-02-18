$(document).ready(function () {
    mostrarFacturas();
    
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

    $("#lista").on('change', function() {
        var lista = $("#lista").val();
        if(lista == "fechaUnica" || lista == "fecha-1" || lista == "fecha-2"){
            $("#producto").attr('type','date');
        }
        else{
            $("#producto").attr('type','search');
        }
        mostrarFacturas();
    });
});     

function mostrarFacturas() {
    producto=$("#producto").val()+'';
    filtro = $("#lista").val();
    $.ajax({
            method: "POST",
            url: "php/facturas.php",
            data: {
                text: producto,
                filtro: filtro,
                table:"facturas",
                modo: "facturas"
            }
        })
        .done(function (response) {
            $(".content").html(response);
            SetNumber('.total');
        });
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
    

