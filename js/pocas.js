$(document).ready(function () {

    mostrarProd();
    
    function mostrarProd() {
        $.ajax({
                method: "POST",
                url: "php/pocas.php",
                data: {
                    table:"productos"
                }
            })
            .done(function (response) {
                $(".content").html(response);
            });
    }
});