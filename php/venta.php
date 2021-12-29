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
if ($modo == 'inventario') {
    $aux = explode("-", $_POST['filtro']);
    $filtro = $aux[0];
    if($filtro == 'id' || $filtro == 'nombre' || $filtro == 'presentacion' || $filtro == 'referencia'){
        $sentencia = "SELECT * FROM $productos WHERE $filtro LIKE'%$producto%' ORDER BY `$productos`.`$filtro` ASC"; 
    }else{
        if($producto==''){
            $producto='0';
        }
        $aux1 = intval($producto);

        if($filtro=='cantidad'){
            if($aux[1]=='1'){
                $sentencia = "SELECT * FROM $productos WHERE $filtro >= $aux1 ORDER BY `$productos`.`$filtro` ASC";
                $producto='Cantidades Mayores a '.$aux1; 
            }else{
                $sentencia = "SELECT * FROM $productos WHERE $filtro <= $aux1 ORDER BY `$productos`.`$filtro` ASC";
                $producto='Cantidades Menores a '.$aux1; 
            }
        }else{
            if($aux[1]=='1'){
                $sentencia = "SELECT * FROM $productos WHERE $filtro >= $aux1 ORDER BY `$productos`.`$filtro` ASC"; 
                $producto='Precios Mayores a '.$aux1; 
            }else{
                $sentencia = "SELECT * FROM $productos WHERE $filtro <= $aux1 ORDER BY `$productos`.`$filtro` ASC";
                $producto='Precios Menores a '.$aux1; 
            }
        }

    } 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    
    echo '<table class="table table-bordered table-hover">'; 
    echo '<thead class="thead"><tr><th>' . 'ID' . '</th><th>' . 'Nombre' . '</th><th>' . 'Referencia' . '</th><th>' . 'Cantidad' . '</th><th>' . 'Presentación' . '</th><th>' . 'Precio' . '</th></tr></thead>'; 
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<tr class="agregar-venta" id="'.$fila['id'].'" data-toggle="modal" data-target="#agregarModal" data-whatever="'.$fila['id'].'">'; 
        echo '<td class="id">' . $fila['id'] . '</td><td class="nombre">' . $fila['nombre'] . '</td><td class="referencia">' . $fila['referencia'] . '</td><td class="cantidad">' . $fila['cantidad'] . '</td><td class="present">' . $fila['presentacion'] . '</td><td class="precio">' . $fila['precio'] . '</td><td class="precio_compra d-none">' . $fila['precio_compra'] . '</td>'; 
        echo '</tr>';
        $aux++;
    }  
    if ($aux==0) {
        echo '<td colspan="5" class="nohay">No Hay Resultados para: "'. $producto .'"</td>';  
    }
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}

if ($modo == 'factura') {
    $ids = $_POST['ids'];
    $productos1 = $_POST['productos'];
    $referencias = $_POST['referencias'];
    $cantidades = $_POST['cantidades'];
    $presentaciones = $_POST['presentaciones'];
    $precios = $_POST['precios'];
    $preciosCompra = $_POST['preciosCompra'];
    $totalFactura = $_POST['totalFactura'];
    $descuentoFactura = $_POST['descuentoFactura'];
    $fecha = $_POST['fecha'];
    $numberIds = explode(",", $ids);
    $numberCantidades = explode(",", $cantidades);
    for ($i=0; $i < sizeof($numberIds); $i++) {
        $id= $numberIds[$i];
        $cantidad=$numberCantidades[$i];
        $sentencia = "UPDATE `productos` SET `cantidad`=`cantidad`-'$cantidad' WHERE id='$id'"; 
        // Ejecuta la sentencia SQL 
        $resultado = mysqli_query($mysql, $sentencia); 
    }

    // Sentencia SQL: Agrega una nueva fila
    $sentencia = "INSERT INTO $productos (id, productos, referencias, cantidades, presentaciones, precios, total, descuento, fecha, ids, precio_compra) VALUES (NULL, '$productos1', '$referencias' ,'$cantidades', '$presentaciones', '$precios', '$totalFactura', '$descuentoFactura', '$fecha', '$ids', '$preciosCompra')"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia);  
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    echo 'Se guardó la factura correctamente';
} 

if ($modo == 'ver_factura') {

    // Sentencia SQL: Agrega una nueva fila
    $sentencia = "SELECT * FROM $productos WHERE id=(SELECT max(id) FROM `facturas`)"; 
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


