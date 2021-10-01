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
    // Sentencia SQL: muestra todo el contenido de la tabla "productos" 
    $sentencia = "SELECT * FROM $productos WHERE nombre LIKE'%$producto%' ORDER BY `productos`.`nombre` ASC"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    
    echo '<table class="table table-bordered table-hover">'; 
    echo '<thead class="thead"><tr><th>' . 'Nombre' . '</th><th>' . 'Cantidad' . '</th><th>' . 'Presentación' . '</th><th>' . 'Precio' . '</th></tr></thead>'; 
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<tr id="'.$fila['id'].'">'; 
        echo '<td class="nombre">' . $fila['nombre'] . '</td><td class="cantidad">' . $fila['cantidad'] . '</td><td class="present">' . $fila['presentacion'] . '</td><td class="precio">' . $fila['precio'] . '</td>'; 
        echo '</tr>'; 
        $aux++;
    } 
    if ($aux==0) {
        echo '<td colspan="4" class="nohay">No Hay Resultados para: "'. $producto .'"</td>';  
        ?>
            <script type="text/javascript">
                $("#editar").addClass("disabled");
                $("#editar").prop("disabled", true);
                $("#borrar").addClass("disabled");
                $("#borrar").prop("disabled", true);
            </script>
        <?php
    }
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}
if ($modo == 'borrar') {
    // Sentencia SQL: muestra todo el contenido de la tabla "productos" 
    $sentencia = "SELECT * FROM $productos WHERE nombre LIKE'%$producto%' ORDER BY `productos`.`nombre` ASC"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    
    echo '<table class="table table-bordered table-hover">'; 
    echo '<thead class="thead"><tr><th>' . 'Nombre' . '</th><th>' . 'Cantidad' . '</th><th>' . 'Presentación' . '</th><th>' . 'Precio' . '</th></tr></thead>'; 
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<tr class="borrar" id="'.$fila['id'].'" data-toggle="modal" data-target="#borrarModal" data-whatever="'.$fila['id'].'">'; 
        echo '<td class="nombre">' . $fila['nombre'] . '</td><td class="cantidad">' . $fila['cantidad'] . '</td><td class="present">' . $fila['presentacion'] . '</td><td class="precio">' . $fila['precio'] . '</td>'; 
        echo '</tr>'; 
        $aux++;
    } 
    if ($aux==0) {
        echo '<td colspan="4" class="nohay">No Hay Resultados para: "'. $producto .'"</td>';  
        ?>
            <script type="text/javascript">
                $("#editar").addClass("disabled");
                $("#editar").prop("disabled", true);
                $("#borrar").addClass("disabled");
                $("#borrar").prop("disabled", true);
            </script>
        <?php
    }
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}
if ($modo == 'borrar2') {
    $idborrar = $_POST['idborrar'];
    // Sentencia SQL: Agrega una nueva fila
    $sentencia = "DELETE FROM $productos WHERE id='$idborrar'"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia);           
}
if ($modo == 'agregar') {
    $nombre = $_POST['nombre'];
    $sentencia = "SELECT * FROM $productos WHERE nombre LIKE'$nombre'"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        ?>
            <script type="text/javascript">
                $("#mensaje").removeClass("verde");
                $("#mensaje").addClass("rojo");
            </script>
        <?php
        echo 'Este Producto Ya Existe'; 
        $aux++;
    } 
    mysqli_free_result($resultado);
    if ($aux==0) {
        $cantidad = $_POST['cantidad'];
        $presentacion = $_POST['presentacion'];
        $precio = $_POST['precio'];
        // Sentencia SQL: Agrega una nueva fila
        $sentencia = "INSERT INTO $productos (id, nombre, cantidad, presentacion, precio) VALUES (NULL, '$nombre', '$cantidad', '$presentacion', '$precio')"; 
        // Ejecuta la sentencia SQL 
        $resultado = mysqli_query($mysql, $sentencia);    
        echo 'Se Agregó el Producto Correctamente';
    }    
}
if ($modo == 'editar') {
    // Sentencia SQL: muestra todo el contenido de la tabla "productos" 
    $sentencia = "SELECT * FROM $productos WHERE nombre LIKE'%$producto%' ORDER BY `productos`.`nombre` ASC"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    
    echo '<table class="table table-bordered table-hover">'; 
    echo '<thead class="thead"><tr><th>' . 'Nombre' . '</th><th>' . 'Cantidad' . '</th><th>' . 'Presentación' . '</th><th>' . 'Precio' . '</th></tr></thead>'; 
    $aux=0;
    while($fila = mysqli_fetch_array($resultado)) { 
        echo '<tr class="identi" id="'.$fila['id'].'">'; 
        echo '<td><input class="nombre" type="text" value="'.$fila['nombre'].'"></td> <td><input class="cantidad" type="number" value="'.$fila['cantidad'].'"></td> <td><input class="present" type="text" value="'.$fila['presentacion'].'"></td> <td><input class="precio" type="number" value="'.$fila['precio'].'"></td>'; 
        echo '</tr>'; 
        $aux++;
    } 
    if ($aux==0) {
        echo '<td colspan="4" class="nohay">No Hay Resultados para: "'. $producto .'"</td>';  
        ?>
            <script type="text/javascript">
                $("#editar").addClass("disabled");
                $("#editar").prop("disabled", true);
            </script>
        <?php
    }
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}
if ($modo == 'editar2') {
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $cantidad = $_POST['cantidad'];
    $presentacion = $_POST['presentacion'];
    $precio = $_POST['precio'];
    $sentencia = "UPDATE `productos` SET `nombre`='$nombre',`cantidad`='$cantidad',`presentacion`='$presentacion',`precio`='$precio' WHERE id='$id'"; 
    // Ejecuta la sentencia SQL 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta"); 
}
// Cierra la conexión con la base de datos 
mysqli_close($mysql); 
?>
