<?php
$productos = $_POST['table'];
// Se conecta al SGBD 
if(!($mysql = mysqli_connect("localhost", "almacen", "almacen"))) 
die("Error: No se pudo conectar");
// Selecciona la base de datos 
if(!mysqli_select_db($mysql,"almacen")) 
die("Error: No existe la base de datos");
// Sentencia SQL: muestra todo el contenido de la tabla "books" 
$sentencia = "SELECT * FROM $productos WHERE cantidad<=5 ORDER BY `$productos`.`cantidad` ASC"; 
// Ejecuta la sentencia SQL 
$resultado = mysqli_query($mysql, $sentencia); 
if(!$resultado) 
die("Error: no se pudo realizar la consulta");

echo '<table class="table table-bordered table-hover">'; 
echo '<thead class="thead"><tr><th>' . 'ID' . '</th><th>' . 'Nombre' . '</th><th>' . 'Referencia' . '</th><th>' . 'Cantidad' . '</th><th>' . 'Presentación' . '</th><th>' . 'Precio de Venta' . '</th><th>' . 'Precio de Compra' . '</th></tr></thead>'; 
$aux=0;
while($fila = mysqli_fetch_array($resultado)) { 
    echo '<tr>'; 
    echo '<td class="id">' . $fila['id'] . '</td><td class="nombre">' . $fila['nombre'] . '</td><td class="referencia">' . $fila['referencia'] . '</td><td class="cantidad">' . $fila['cantidad'] . '</td><td class="present">' . $fila['presentacion'] . '</td><td class="precio">' . $fila['precio'] . '</td><td class="precio_compra">' . $fila['precio_compra'] . '</td>'; 
    echo '</tr>'; 
    $aux++;
} 
if ($aux==0) {
    echo '<td colspan="6">No Hay Resultados para: "'. $producto .'"</td>';    
}
echo '</table>';

// Libera la memoria del resultado
mysqli_free_result($resultado);
// Cierra la conexión con la base de datos 
mysqli_close($mysql); 
?>
