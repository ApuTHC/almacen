$(document).ready(function () {
    mostrarFactura();

    $("#volver").click(function (e) {
        e.preventDefault();
        $(location).attr('href', "index.html");
    });
        
    function mostrarFactura() {
        $.ajax({
                method: "POST",
                url: "php/venta.php",
                data: {
                    table:"facturas",
                    text:"",
                    modo: "ver_factura"
                }
            })
            .done(function (response) {
                $(".content").html(response);
                $("#busqueda").val(response);
                SetNumber('.precio');
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
});
