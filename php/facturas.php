<?php
$producto = $_POST['text'];
$productos = $_POST['table'];
$modo = $_POST['modo'];
// Se conecta al SGBD 
if(!($mysql = mysqli_connect("localhost", "almacen", "almacen"))) 
die("Error: No se pudo conectar");
// Selecciona la base de datos 
if(!mysqli_select_db($mysql,"almacen")) 
die("Error: No existe la base de datos");

if ($modo == 'facturas') {
    $aux = explode("-", $_POST['filtro']);
    $filtro = $aux[0];
    if($filtro == 'id'){
        $sentencia = "SELECT * FROM $productos WHERE $filtro LIKE'%$producto%' ORDER BY `$productos`.`$filtro` ASC"; 
    }
    if($filtro == 'fechaUnica'){
        if($producto==''){
            $producto='01/01/2020';
        }
        $aux1 = $producto;
        $sentencia = "SELECT * FROM `facturas` WHERE CAST(`fecha` as date) = '$aux1'  ORDER BY `$productos`.`fecha` ASC";
        $producto='Facturas de la fecha: '.$aux1; 
    }
    if($filtro == 'fecha'){
        if($producto==''){
            $producto='01/01/2020';
        }
        $aux1 = $producto;
        if($aux[1]=='1'){
            $sentencia = "SELECT * FROM `facturas` WHERE CAST(`fecha` as date) >= '$aux1' ORDER BY `$productos`.`$filtro` ASC";
            $producto='Facturas a partir de la fecha: '.$aux1; 
        }else{
            $sentencia = "SELECT * FROM `facturas` WHERE CAST(`fecha` as date) <= '$aux1' ORDER BY `$productos`.`$filtro` ASC";
            $producto='Facturas anteriores a la fecha: '.$aux1; 
        }
    }
    if($filtro=='total'){
        if($producto==''){
            $producto='0';
        }
        $aux1 = intval($producto);
        if($aux[1]=='1'){
            $sentencia = "SELECT * FROM $productos WHERE $filtro >= $aux1 ORDER BY `$productos`.`$filtro` ASC"; 
            $producto='Facturas con totales Mayores a '.$aux1; 
        }else{
            $sentencia = "SELECT * FROM $productos WHERE $filtro <= $aux1 ORDER BY `$productos`.`$filtro` ASC";
            $producto='Facturas con totales Menores a '.$aux1; 
        }
    } 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    
    echo '<table class="table table-bordered table-hover">'; 
    echo '<thead class="thead"><tr><th>' . 'ID' . '</th><th>' . 'Productos' . '</th><th>' . 'Total' . '</th><th>' . 'Fecha' . '</th></tr></thead>'; 
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<tr class="agregar-venta" id="'.$fila['id'].'" data-toggle="modal" data-target="#facturaModal" data-whatever="'.$fila['id'].'">'; 
        echo '<td class="id">' . $fila['id'] . '</td><td class="productos">' . $fila['productos'] . '</td><td class="total">' . $fila['total'] . '</td><td class="fecha">' . $fila['fecha'] . '</td>'; 
        echo '</tr>';
        $aux++;
    }  
    if ($aux==0) {
        echo '<td colspan="4" class="nohay">No Hay Resultados para: "'. $producto .'"</td>';  
    }
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}

if ($modo == 'contabilidad') {
    $desde = $_POST['text'];
    $hasta = $_POST['filtro'];

    $sentencia = "SELECT * FROM `facturas` WHERE CAST(`fecha` as date) >= '$desde' && CAST(`fecha` as date) <= '$hasta' ORDER BY `$productos`.`fecha` ASC";
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    
    echo '<table class="table table-bordered table-hover">'; 
    echo '<thead class="thead"><tr><th>' . 'ID' . '</th><th>' . 'Productos' . '</th><th>' . 'Fecha' . '</th><th>' . 'Total de Venta' . '</th><th>' . 'Valor de Compra' . '</th></tr></thead>'; 
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<tr class="agregar-venta" id="'.$fila['id'].'" data-toggle="modal" data-target="#facturaModal" data-whatever="'.$fila['id'].'">'; 
        echo '<td class="id">' . $fila['id'] . '</td><td class="productos">' . $fila['productos'] . '</td><td class="fecha">' . $fila['fecha'] . '</td><td class="total">' . $fila['total'] . '</td><td class="compra_total">' . $fila['compra_total'] . '</td>'; 
        echo '</tr>';
        $aux++;
    }  
    if ($aux==0) {
        echo '<td colspan="5" class="nohay">No Hay Facturas Desde: '. $desde .', Hasta: '. $hasta.'</td>';  
    }
    echo '<td colspan="3" class="thead">Total</td><td id="t_total" class="t_total">Total</td><td id="t_compra_total" class="t_compra_total">Total</td>';  
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}

if ($modo == 'ver_factura') {

    // Sentencia SQL: Agrega una nueva fila
    $sentencia = "SELECT * FROM $productos WHERE id = $producto"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia);  
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");

    echo '<table class="table table-bordered table-hover">'; 
    
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<thead class="thead">'; 
        echo '<tr><th class="id_factura">' . $fila['id'] . '</th><th colspan="3">' . 'Ferreteria Toño' . '</th><th>' . 'Fecha' . '</th><th>' . $fila['fecha'] . '</th>'; 
        echo '<tr><th>' . 'Nombre' . '</th><th>' . 'Referencia' . '</th><th>' . 'Cantidad' . '</th><th>' . 'Presentación' . '</th><th>' . 'Precio' . '</th><th>' . 'Total' . '</th></tr>'; 
        echo '</thead>'; 
        $nombres = explode(",", $fila['productos']);
        $referencias = explode(",", $fila['referencias']);
        $cantidades = explode(",", $fila['cantidades']);
        $presentaciones = explode(",", $fila['presentaciones']);
        $precios = explode(",", $fila['precios']);
        for ($i=0; $i < sizeof($nombres); $i++) {           
            echo '<tr>'; 
            echo '<td>' . $nombres[$i] . '</td><td>' . $referencias[$i] . '</td><td>' . $cantidades[$i] . '</td><td>' . $presentaciones[$i] . '</td><td class="precio">' . $precios[$i] . '</td><td class="precio">' . $precios[$i]*$cantidades[$i] . '</td>'; 
            echo '</tr>'; 
        }
        echo '<tr><td colspan="5">Descuento</td><td class="precio">'. $fila['descuento'] .'</td></tr>';    
        echo '<tr><td colspan="5">Total</td><td class="precio">'. $fila['total'] .'</td></tr>';    
    } 
       
    echo '</table>';
}    


mysqli_close($mysql); 
?>
