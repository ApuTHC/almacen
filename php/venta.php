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
        echo '<td class="id">' . $fila['id'] . '</td><td class="nombre">' . $fila['nombre'] . '</td><td class="referencia">' . $fila['referencia'] . '</td><td class="cantidad">' . $fila['cantidad'] . '</td><td class="present">' . $fila['presentacion'] . '</td><td class="precio">' . $fila['precio'] . '</td>'; 
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
    $totalFactura = $_POST['totalFactura'];
    $descuentoFactura = $_POST['descuentoFactura'];
    $fecha = $_POST['fecha'];
    $numberIds = sizeof(explode(",", $ids));
    for ($i=0; $i < $numberIds; $i++) { 
        $sentencia = "UPDATE `productos` SET `id`='$id',`nombre`='$nombre',`referencia`='$referencia',`cantidad`='$cantidad',`presentacion`='$presentacion',`precio`='$precio' WHERE id='$id'"; 
        // Ejecuta la sentencia SQL 
        $resultado = mysqli_query($mysql, $sentencia); 
    }

    // Sentencia SQL: Agrega una nueva fila
    $sentencia = "INSERT INTO $productos (id, ids, productos, referencias, cantidades, presentaciones, precios, total, descuento, fecha) VALUES (NULL, '$productos1', '$referencias' ,'$cantidades', '$presentaciones', '$precios', '$totalFactura', '$descuentoFactura', '$fecha')"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia);  
    echo 'Se guardó la factura correctamente';
}    

// Cierra la conexión con la base de datos 
mysqli_close($mysql); 
?>
