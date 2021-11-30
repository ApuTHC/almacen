$(document).ready(function () {

    var producto;
    
    $("#buscar").click(function (e) {
        e.preventDefault();
        mostrarProd();
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
});