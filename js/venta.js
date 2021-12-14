var seleccion = [];
var total = 0;
var descuento =0;

var trid;
var producto;
var filtro;
var celdas = [];
//mostrarProd();

$("#buscar").click(function (e) {
    e.preventDefault();
    mostrarProd();
});

$("#vender").click(function (e) {
    e.preventDefault();
    guardarFactura();
});

$("#agregar").click(function (e) {
    var precio;
    var cantidad = parseInt($("#cantidadVenta").val());
    var control = 0;
    for (let i = 0; i < seleccion.length; i++) {
        if (seleccion[i].id==trid) {
            seleccion[i].cantidad+= parseInt($("#cantidadVenta").val());
            seleccion[i].precioTotal = seleccion[i].cantidad*seleccion[i].numero;
            seleccion[i].precioCorregido = Corregir(seleccion[i].precioTotal);
            control = 1;
            total += parseFloat(seleccion[i].numero)*parseInt($("#cantidadVenta").val());
            graficaPrecio();
            graficaTabla();
        }            
    }
    if (!control) {
        for (let i = 0; i < celdas.length; i++) {
            if(celdas[i].id == trid){
                precio = parseFloat(celdas[i].numero);
            }
        }
        var valor = precio*cantidad;
        var valorCorregido = Corregir(valor);
        total += valor;
        graficaPrecio();
        var select = new Object();
        select.id = trid;
        select.nombre = $("#"+trid+" .nombre").text();
        select.referencia = $("#"+trid+" .referencia").text();
        select.cantidad = cantidad;
        select.cantidadTotal = parseInt($("#"+trid+" .cantidad").text());
        select.presentacion = $("#"+trid+" .present").text();
        select.numero = precio;
        select.numeroCorregido = $("#"+trid+" .precio").text();
        select.precioTotal = valor;
        select.precioCorregido = valorCorregido;
        seleccion.push(select);
        graficaTabla();
    }
});

$('#agregarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    trid = button.data('whatever');
    var modal = $(this); // Extract info from data-* attributes
    modal.find('.modal-title').html('<p><i class="fas fa-shopping-cart"></i> Agregar a la venta</p>');
    modal.find('.modal-body .modal-text').html(
        '<p>Desea Agregar el Siguente producto:<span> '+$("#"+trid+" .nombre").text()+' / '+$("#"+trid+" .referencia").text()+'</span></p>' +
        '<p>Con el Precio :<span> $'+$("#"+trid+" .precio").text()+'</span></p>' +
        '<p>Cantidades Existentes :<span> '+$("#"+trid+" .cantidad").text()+'</span> <span> '+$("#"+trid+" .present").text()+'</span></p>' +
        '<p>Elija la Cantidad a Vender: <input id="cantidadVenta" id type="number" value="1" min="1" max="'+$("#"+trid+" .cantidad").text()+'"> <span> '+$("#"+trid+" .present").text()+'</span></p>'
    );
})

$('#ventaModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    trid = button.data('whatever');
    var modal = $(this); // Extract info from data-* attributes
    modal.find('.modal-title').html('<p><i class="fas fa-exclamation-circle"></i> Confirme la Venta</p>');
    var respuesta;
    if (!seleccion[0]) {
        respuesta = '<p>No se ha agregado ningun producto</p>';
    }else{
        respuesta = '<p>¿Está seguro de realizar este venta?</p>';
    }

    modal.find('.modal-body .modal-text').html(respuesta);
})

function guardarFactura(){
    var ids;
    var productos;
    var referencias;
    var cantidades;
    var nuevasCantidades;
    var presentaciones;
    var precios;
    var totalFactura;
    var descuentoFactura;
    var fecha = dateFormat(new Date(), 'm/d/Y ; H:i:s');
    if (seleccion[0]) {
        for (let i = 0; i < seleccion.length; i++) {
            if(i==0){
                ids = seleccion[i].id+'';
                productos = seleccion[i].nombre+'';
                referencias = seleccion[i].referencia+'';
                cantidades = seleccion[i].cantidad+'';
                nuevasCantidades = (parseInt(seleccion[i].cantidadTotal)-parseInt(seleccion[i].cantidad))+'';
                presentaciones = seleccion[i].presentacion+'';
                precios = seleccion[i].numero+'';
            }else{
                ids += ','+seleccion[i].id;
                productos += ','+seleccion[i].nombre;
                referencias += ','+seleccion[i].referencia;
                cantidades += ','+seleccion[i].cantidad;
                nuevasCantidades += ','+(parseInt(seleccion[i].cantidadTotal)-parseInt(seleccion[i].cantidad));
                presentaciones += ','+seleccion[i].presentacion;
                precios += ','+seleccion[i].numero;
            }
        }
        totalFactura = total;
        descuentoFactura = descuento;
        $.ajax({
            method: "POST",
            url: "php/venta.php",
                data: {
                    text: '',
                    table: "facturas",
                    ids: ids,
                    productos: productos,
                    referencias: referencias,
                    cantidades: cantidades,
                    nuevasCantidades: nuevasCantidades,
                    presentaciones: presentaciones,
                    precios: precios,
                    totalFactura: totalFactura,
                    descuentoFactura: descuentoFactura,
                    fecha: fecha,
                    modo: "factura"
                }
            })
            .done(function (response) {
                $(".content").html(response);
                SetNumber();
            });

    }
}

function SetNumber(){
    var numeros = $('.precio');
    var cantidades = $('.cantidad');
    var ids = $('.id')
    for (let i = 0; i < numeros.length; i++) {
        var numero = numeros[i].innerHTML;
        var cantidad = cantidades[i].innerHTML;
        var id = ids[i].innerHTML;
        var celda = new Object();
        celda.numero=numero;
        celda.cantidad=cantidad;
        celda.id=id;
        celdas.push(celda);
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

function descont(){
    if (descuento !== 0) {
        total += descuento;
    }        
    descuento = parseFloat($("#descuento").val());
    total -= descuento;
    graficaPrecio();
}

function borrar(i) {
    total -= seleccion[i].numero*seleccion[i].cantidad;
    graficaPrecio();
    seleccion.splice(i,1);
    graficaTabla();
}

function cambiar(i){
    var cambio = parseInt($("#in"+i).val()) - seleccion[i].cantidad;
    seleccion[i].cantidad = parseInt($("#in"+i).val());
    seleccion[i].precioTotal = seleccion[i].cantidad*seleccion[i].numero;
    seleccion[i].precioCorregido = Corregir(seleccion[i].precioTotal);
    total += seleccion[i].numero * cambio;
    $("#a"+seleccion[i].id+" .aprecioTotal").text(seleccion[i].precioCorregido);
    graficaPrecio();
}

function graficaPrecio(){
    var corregido = parseFloat(total+''.replace(/,/g, ""))
                    .toFixed(2)
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    corregido.replace(/,/g, "");
    $('#precioTotal').text(corregido); 
}

function Corregir(number){
    var corregido = parseFloat(number+''.replace(/,/g, ""))
                    .toFixed(2)
                    .toString()
                    .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    corregido.replace(/,/g, "");
    return corregido;
}

function graficaTabla(){
    var tabla;
    if(!seleccion[0]){
        tabla= '<tr>'+
                  '<td colspan="7" class="nohay">No Se Han Agregado Productos</td>'+
               '</tr>';
        $('.table-content').html(tabla);   
    }
    for (let i = 0; i < seleccion.length; i++) {
        tabla += '<tr class="venta-agregado" id="a' + seleccion[i].id + '">'+ 
                    '<td class="thborrar borrar"  onclick="borrar('+i+')"><i class="fas fa-times"></i></td>' +
                    '<td class="anombre">' +seleccion[i].nombre+ '</td>'+
                    '<td class="areferencia">' +seleccion[i].referencia+ '</td>'+
                    '<td class="acantidad"> <input id="in'+i+'" onchange="cambiar('+i+')" min="1" max="'+seleccion[i].cantidadTotal+'" type="number" value="' +seleccion[i].cantidad+ '"></td>'+
                    '<td class="apresent">' +seleccion[i].presentacion+ '</td>'+
                    '<td class="aprecio">' +seleccion[i].numeroCorregido+ '</td>'+
                    '<td class="aprecioTotal">' +seleccion[i].precioCorregido+ '</td>'+
                 '</tr>';
        $('.table-content').html(tabla);            
    }  
}

function busquedaCambio(){
    mostrarProd();
}


