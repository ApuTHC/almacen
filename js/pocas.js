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
                SetNumber('.precio');
                SetNumber('.precio_compra');
            });
    }

    function SetNumber(num){
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