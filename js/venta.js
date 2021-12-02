$(document).ready(function () {

    var producto;
    var filtro;
    var trid;
    var aux =0;
    mostrarProd();
    
    $("#buscar").click(function (e) {
        e.preventDefault();
        mostrarProd();
    });

    $("#agregar").click(function (e) {
        if(aux==0){
            $('.table-content').html('');
            aux++
        }
        var tabla = $('.table-content').html();
        tabla += '<tr class="venta-agregado borrar" onclick="borrar("a' + trid + '")" id="a' + trid + '">'+ 
                 '<td class="anombre">' +$("#"+trid+" .nombre").text()+ '</td><td class="areferencia">' +$("#"+trid+" .referencia").text()+ '</td><td class="acantidad"> <input min="1" max="'+$("#"+trid+" .cantidad").text()+'" type="number" value="' +$("#cantidadVenta").val()+ '"></td><td class="apresent">' +$("#"+trid+" .present").text()+ '</td><td class="aprecio">' +$("#"+trid+" .precio").text()+ '</td>'+
                 '</tr>';
        $('.table-content').html(tabla);
    });

    function SetNumber(){
        var numeros = $('.precio');
        for (let i = 0; i < numeros.length; i++) {
            numero=numeros[i].innerHTML;
            // var corregido = numero.toString();
            // var pattern = /(-?\d+)(\d{3})/;
            // while (pattern.test(corregido)){
            //     corregido = corregido.replace(pattern, "$1.$2");
            // }
            var corregido = parseFloat(numero.replace(/,/g, ""))
                    .toFixed(2)
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            corregido.replace(/,/g, "");
            numeros[i].innerHTML = corregido;      
        }
    }

    function mostrarProd() {
        producto=$("#producto").val();
        filtro = $("#lista").val();
        $.ajax({
                method: "POST",
                url: "php/venta.php",
                data: {
                    text: producto,
                    table: "productos",
                    filtro: filtro,
                    modo: "inventario"
                }
            })
            .done(function (response) {
                $(".content").html(response);
                SetNumber();
            });
    }

    $('#agregarModal').on('show.bs.modal', function (event) {
        var button = $(event.relatedTarget) // Button that triggered the modal
        trid = button.data('whatever') // Extract info from data-* attributes
        var modal = $(this)
        modal.find('.modal-title').html('<p><i class="fas fa-shopping-cart"></i> Agregar a la venta</p>');
        modal.find('.modal-body .modal-text').html(
            '<p>Desea Agregar el Siguente producto:<span> '+$("#"+trid+" .nombre").text()+' / '+$("#"+trid+" .referencia").text()+'</span></p>' +
            '<p>Con el Precio :<span> $'+$("#"+trid+" .precio").text()+'</span></p>' +
            '<p>Cantidades Existentes :<span> '+$("#"+trid+" .cantidad").text()+'</span> <span> '+$("#"+trid+" .present").text()+'</span></p>' +
            '<p>Elija la Cantidad a Vender: <input id="cantidadVenta" id type="number" value="0" min="0" max="'+$("#"+trid+" .cantidad").text()+'"> <span> '+$("#"+trid+" .present").text()+'</span></p>'
        );
    })
});