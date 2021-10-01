$(document).ready(function () {
    var edit=0;
    var nombre;
    var producto;
    mostrarProd();

    $("#buscar").click(function (e) {
        e.preventDefault();
        $("#borrar").prop("disabled", false);
        $("#borrar").removeClass("disabled");
        $("#editar").prop("disabled", false);
        $("#editar").removeClass("disabled");
        $("#editar").html('<i class="fas fa-edit"></i> Editar');
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
                precios=$("#"+auxid+" .precio").val();
                editar2(auxid,nombre,cantidades,presentaciones,precios);
            }
            setTimeout(() => {
               mostrarProd(); 
            }, 300);
            
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
        $(".content").empty();
        $(".content").html($(
             '<label for="producto">Nombre:</label>'+
             '<input type="text" class="form-control" placeholder="Nombre" id="nombre">'+
             '<label for="producto">Cantidad:</label>'+
             '<input type="number" class="form-control" placeholder="Cantidad" id="cantidad">'+
             '<label for="producto">Presentación:</label>'+
             '<input type="text" class="form-control" placeholder="Presentación" id="present">'+
             '<label for="producto">Precio:</label>'+
             '<input type="number" class="form-control" placeholder="Precio" id="precio">'+
             '<div id="mensaje" class="rojo"></div>'+
             '<button type="submit" class="btn" id="guardar"> <i class="fas fa-save"></i> Guardar</button>'
        ));
        $("#guardar").click(function (e) { 
            e.preventDefault();
            if($("#nombre").val() && $("#cantidad").val() && $("#present").val() && $("#precio").val()){
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

    function mostrarProd() {
        producto=$("#producto").val();
        $.ajax({
                method: "POST",
                url: "php/inventario.php",
                data: {
                    text: producto,
                    table: "productos",
                    modo: "inventario"
                }
            })
            .done(function (response) {
                $(".content").html(response);
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
                    cantidad: $("#cantidad").val(),
                    presentacion: $("#present").val(),
                    precio:$("#precio").val()
                }
            })
            .done(function (response) {
                $("#mensaje").removeClass("rojo");
                $("#mensaje").addClass("verde");
                $("#mensaje").html(response);
                setTimeout(() => {
                    $("#mensaje").html("");
                }, 3000);
            });
    }

    function editar() {
        $.ajax({
                method: "POST",
                url: "php/inventario.php",
                data: {
                    text: producto,
                    table: "productos",
                    modo: "editar"
                }
            })
            .done(function (response) {
                $(".content").html(response);
            });
    }

    function editar2(id,nom,cant,pres,prec) {
        $.ajax({
                method: "POST",
                url: "php/inventario.php",
                data: {
                    text: producto,
                    table:"productos",
                    modo: "editar2",
                    id: id,
                    nombre: nom,
                    cantidad: cant,
                    presentacion: pres,
                    precio:prec
                }
            });
    }
});