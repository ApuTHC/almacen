<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/fontawesome.min.css">
    <link rel="stylesheet" href="css/main.css">

    <title>Inventario</title>
</head>

<?php

/*
Subir archivo a servidor con PHP
@author parzibyte
 */
# La carpeta en donde guardaremos los archivos, en este caso es "subidas" pero podría ser
# cualqueir otro, incluso podría ser aquí mismo sin subcarpetas
$rutaDeSubidas = __DIR__ . "/DB_subidas";
# Crear si no existe
if (!is_dir($rutaDeSubidas)) {
    mkdir($rutaDeSubidas, 0777, true);
}
# Tomar el archivo. Recordemos que "archivo" es el atributo "name" de nuestro input
$informacionDelArchivo = $_FILES["archivo"];
# La ubicación en donde PHP lo puso
$ubicacionTemporal = $informacionDelArchivo["tmp_name"];
#Nota: aquí tomamos el nombre que trae, pero recomiendo renombrarlo a otra cosa usando, por ejemplo, uniqid
$nombreArchivo = $informacionDelArchivo["name"];
$nuevaUbicacion = $rutaDeSubidas . "/" . $nombreArchivo;
# Mover
$resultado = move_uploaded_file($ubicacionTemporal, $nuevaUbicacion);
if ($resultado === true) {
     ?><h1 style="text-align: center"><?php echo "Archivo subido correctamente" ?></h1><?php ;
} else {
    echo "Error al subir archivo";
}

require __DIR__ . "/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
$rutaArchivo = $nuevaUbicacion;
$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReaderForFile($rutaArchivo);
$reader->setReadDataOnly(true);
$documento = $reader->load($rutaArchivo);
$hojaDeProductos = $documento->getSheet(0);
$numeroMayorDeFila = $hojaDeProductos->getHighestRow(); // Numérico
$letraMayorDeColumna = $hojaDeProductos->getHighestColumn(); // Letra
$numeroMayorDeColumna = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($letraMayorDeColumna);
if(!($mysql = mysqli_connect("localhost", "almacen", "almacen")))
die("Error: No se pudo conectar");
$mysql->set_charset("utf8");
if(!mysqli_select_db($mysql,"almacen")) 
die("Error: No existe la base de datos");
$i=0;
for ($indiceFila = 2; $indiceFila <= $numeroMayorDeFila; $indiceFila++) {
    $id = $hojaDeProductos->getCellByColumnAndRow(1, $indiceFila);
    $nombre = $hojaDeProductos->getCellByColumnAndRow(2, $indiceFila);
    $cantidad = $hojaDeProductos->getCellByColumnAndRow(3, $indiceFila);
    $presentacion = $hojaDeProductos->getCellByColumnAndRow(4, $indiceFila);
    $precio = $hojaDeProductos->getCellByColumnAndRow(5, $indiceFila);
    
    $sentencia = "SELECT * FROM `productos` WHERE `nombre` LIKE'$nombre'"; 
    $resultado = mysqli_query($mysql, $sentencia); 
    if(!$resultado) 
    die("Error: no se pudo realizar la consulta");
    if($fila = mysqli_fetch_array($resultado)) { 
        $sentencia = "UPDATE `productos` SET `id`='$id',`nombre`='$nombre',`cantidad`='$cantidad',`presentacion`='$presentacion',`precio`='$precio' WHERE nombre='$nombre'"; 
    }else{
        $sentencia = "INSERT INTO `productos` (`id`, `nombre`, `cantidad`, `presentacion`, `precio`) VALUES ('$id', '$nombre', '$cantidad', '$presentacion', '$precio')"; 
    }
    mysqli_query($mysql, $sentencia);
    mysqli_free_result($resultado);

}
?><h2 style="text-align: center"><?php echo "Se actualizó correctamente la base de datos"?></h2><?php ;