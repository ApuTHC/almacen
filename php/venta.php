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
                
            </script>
        <?php
    }
    echo '</table>';
    // Libera la memoria del resultado
    mysqli_free_result($resultado);
}
// Cierra la conexión con la base de datos 
mysqli_close($mysql); 
?>
