<?php
$text = $_POST['text'];
$output = wordwrap($text, 60, "<br>");
echo $output;

// Se conecta al SGBD 
if(!($iden = mysqli_connect("localhost", "almacen", "almacen"))) 
die("Error: No se pudo conectar");

// Selecciona la base de datos 
if(!mysqli_select_db($iden,"almacen")) 
die("Error: No existe la base de datos");

// Sentencia SQL: muestra todo el contenido de la tabla "books" 
$sentencia = "SELECT * FROM $text"; 
// Ejecuta la sentencia SQL 
$resultado = mysqli_query($iden, $sentencia); 
if(!$resultado) 
die("Error: no se pudo realizar la consulta");

echo '<table>'; 
while($fila = mysqli_fetch_array($resultado)) 
{ 
echo '<tr>'; 
echo '<td>' . $fila['nombre'] . '</td><td>' . $fila['cantidad'] . '</td>'; 
echo '</tr>'; 
} 
echo '</table>';

// Libera la memoria del resultado
mysqli_free_result($resultado);

// Cierra la conexión con la base de datos 
mysqli_close($iden); 
?>

<?php 
$a = "Hola Mundo!";
?>
<script type="text/javascript">
    $('.tablaProductos').text("<?php echo $a; ?>");
</script>