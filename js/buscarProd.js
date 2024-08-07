var edit=0;
var nombre;
var producto;
var trid;

mostrarProd();

$("#buscar").click(function (e) {
    e.preventDefault();
    $("#borrar").prop("disabled", false);
    $("#borrar").removeClass("disabled");
    $("#editar").prop("disabled", false);
    $("#editar").removeClass("disabled");
    $("#editar").html('<i class="fas fa-edit"></i> Editar');
    $("#btn_descarga").removeClass("disabled");
    $("#btn_descarga").prop("disabled", false);
    edit=0;
    mostrarProd();
});

$("#editar").click(function (e) {
    $("#borrar").prop("disabled", true);
    $("#borrar").addClass("disabled");
    e.preventDefault();
    if (edit==0) {
        $("#editar").html('<i class="fas fa-save"></i> Guardar');
        editar();
        edit=1;
    } else {
        $("#editar").html('<i class="fas fa-edit"></i> Editar');
        ids=$(".identi");
        for (let i = 0; i < ids.length; i++) {
            auxid=ids[i].getAttribute('id');
            nombre=$("#"+auxid+" .nombre").val().replace("'", '"');
            cantidades=$("#"+auxid+" .cantidad").val();
            presentaciones=$("#"+auxid+" .present").val();
            referencia=$("#"+auxid+" .referencia").val();
            precios=$("#"+auxid+" .precio").val();
            precios_compra=$("#"+auxid+" .precio_compra").val();
            editar2(auxid,nombre,referencia,cantidades,presentaciones,precios,precios_compra);
        }
        setTimeout(() => {
            $("#borrar").prop("disabled", false);
            $("#borrar").removeClass("disabled");
            mostrarProd(); 
        }, 500);
        
        edit=0;
    }

});

$("#agregar").click(function (e) {
    e.preventDefault();
    $("#borrar").prop("disabled", true);
    $("#borrar").addClass("disabled");
    $("#editar").prop("disabled", true);
    $("#editar").addClass("disabled");
    $("#editar").html('<i class="fas fa-edit"></i> Editar');
    $("#btn_descarga").addClass("disabled");
    $("#btn_descarga").prop("disabled", true);
    $(".content").empty();
    $(".content").html($(
            '<br>'+
            '<label for="producto">Nombre:</label>'+
            '<input type="text" class="form-control" placeholder="Nombre" id="nombre">'+
            '<label for="producto">Referencia:</label>'+
            '<input type="text" class="form-control" placeholder="Referencia" id="referencia">'+
            '<label for="referencia">Cantidad:</label>'+
            '<input type="number" class="form-control" placeholder="Cantidad" id="cantidad">'+
            '<label for="producto">Presentación:</label>'+
            '<input type="text" class="form-control" placeholder="Presentación" id="present">'+
            '<br>'+
            '<br>'+
            '<label for="producto">Precio de Venta:</label>'+
            '<input type="number" class="form-control" placeholder="Precio de Venta" id="precio">'+
            '<label for="producto">Precio de Compra:</label>'+
            '<input type="number" class="form-control" placeholder="Precio de Compra" id="precio_compra">'+
            '<div id="mensaje" class="rojo"></div>'+
            '<button type="submit" class="btn" id="guardar"> <i class="fas fa-save"></i> Guardar</button>'
    ));
    $("#guardar").click(function (e) { 
        e.preventDefault();
        if($("#nombre").val() && $("#referencia").val() && $("#cantidad").val() && $("#present").val() && $("#precio").val() && $("#precio_compra").val()){
            nombre=$("#nombre").val().replace("'", '"');
            agregar();
        }
        else{
            $("#mensaje").removeClass("verde");
            $("#mensaje").addClass("rojo");
            $("#mensaje").html("Por Favor Ingrese Todos Los Campos.");
        }
    });
    // agregar("");
});

$("#borrar").click(function (e) {
    e.preventDefault();
    $("#editar").prop("disabled", true);
    $("#editar").addClass("disabled");
    $("#borrar").prop("disabled", true);
    $("#borrar").addClass("disabled");
    borrar();
});

$("#borrar2").click(function (e) {
    borrar2(trid);
    setTimeout(() => {
        borrar();
    }, 500);
});

$("#cargar").click(function (e) {
    $(location).attr('href', "carga-inventario.html");
});

$('#borrarModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    trid = button.data('whatever') // Extract info from data-* attributes
    var modal = $(this)
    modal.find('.modal-title').html('<p>Espere un momento    <i class="fas fa-exclamation-triangle"></i></p>')
    modal.find('.modal-body .modal-text').text('Seguro que desea borrar el producto: '+$("#"+trid+" .nombre").text())
})

function mostrarProd() {
    producto=$("#producto").val();
    filtro = $("#lista").val();
    $.ajax({
            method: "POST",
            url: "php/inventario.php",
            data: {
                text: producto,
                table: "productos",
                filtro: filtro,
                modo: "inventario"
            }
        })
        .done(function (response) {
            $(".content").html(response);
            $("#busqueda").val(response);
            SetNumber();
        });
        
    
}

function SetNumber(){
    var numeros = $('.precio');
    for (let i = 0; i < numeros.length; i++) {
        numero=numeros[i].innerHTML;
        var corregido = parseFloat(numero.replace(/,/g, ""))
                .toFixed(2)
                .toString()
                .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        corregido.replace(/,/g, "");
        numeros[i].innerHTML = corregido;      
    }
    var numeros = $('.precio_compra');
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

function borrar() {
    producto=$("#producto").val();
    filtro = $("#lista").val();
    $("#busqueda").val(producto+'-'+filtro);
    $.ajax({
            method: "POST",
            url: "php/inventario.php",
            data: {
                text: producto,
                table: "productos",
                filtro: filtro,
                modo: "borrar"
            }
        })
        .done(function (response) {
            $(".content").html(response);
            $("#busqueda").val(response);
            SetNumber();
        });
}

function borrar2(idborrar) {
    producto=$("#producto").val();
    $.ajax({
            method: "POST",
            url: "php/inventario.php",
            data: {
                text: producto,
                table: "productos",
                modo: "borrar2",
                idborrar: idborrar
            }
        })
        .done(function (response) {
            $(".content").html(response);
            SetNumber();
        });
}

function agregar() {
    $.ajax({
            method: "POST",
            url: "php/inventario.php",
            data: {
                text: "",
                table:"productos",
                modo: "agregar",
                nombre: nombre,
                referencia: $("#referencia").val(),
                cantidad: $("#cantidad").val(),
                presentacion: $("#present").val(),
                precio:$("#precio").val(),
                precio_compra:$("#precio_compra").val()
            }
        })
        .done(function (response) {
            $("#mensaje").removeClass("rojo");
            $("#mensaje").addClass("verde");
            $("#mensaje").html(response);
            setTimeout(() => {
                $("#mensaje").html("");
            }, 3000);
            SetNumber();
        });
}

function editar() {
    producto=$("#producto").val();
    filtro = $("#lista").val();
    $("#busqueda").val(producto+'-'+filtro);
    $.ajax({
            method: "POST",
            url: "php/inventario.php",
            data: {
                text: producto,
                table: "productos",
                filtro: filtro,
                modo: "editar"
            }
        })
        .done(function (response) {
            $(".content").html(response);
            $("#busqueda").val(response);
            SetNumber();
        });
}

function editar2(id,nom,ref,cant,pres,prec,prec_com) {
    $.ajax({
            method: "POST",
            url: "php/inventario.php",
            data: {
                text: producto,
                table:"productos",
                modo: "editar2",
                id: id,
                nombre: nom,
                referencia: ref,
                cantidad: cant,
                presentacion: pres,
                precio:prec,
                precio_compra:prec_com
            }
        });
}

function busquedaCambio(){
    $("#btn_descarga").removeClass("disabled");
    $("#btn_descarga").prop("disabled", false);
    $("#borrar").prop("disabled", false);
    $("#borrar").removeClass("disabled");
    $("#editar").prop("disabled", false);
    $("#editar").removeClass("disabled");
    $("#editar").html('<i class="fas fa-edit"></i> Editar');
    edit=0;
    mostrarProd();
}

