$(document).ready(function () {

    $("#buscar").click(function (e) {
        e.preventDefault();
        mostrarProd();
    });

    function mostrarProd() {
        $.ajax({
                method: "POST",
                url: "php/buscarProd.php",
                data: {
                    text: $("#producto").val(),
                    table:"productos"
                }
            })
            .done(function (response) {
                $(".content").html(response);
            });
    }
});