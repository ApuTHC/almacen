$(document).ready(function () {

    $(".btn_mostrar").click(function (e) {
        e.preventDefault();
        mostrarProd();
    });

    function mostrarProd() {
        $.ajax({
                method: "POST",
                url: "php/wrap.php",
                data: {
                    text: $("p.unbroken").text()
                }
            })
            .done(function (response) {
                $("p.broken").html(response);
            });
    }
});