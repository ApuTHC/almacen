<?php
$tabla=$_POST['busqueda'];
require __DIR__ . "/vendor/autoload.php";
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$reader = new \PhpOffice\PhpSpreadsheet\Reader\Html();
$spreadsheet = $reader->loadFromString($tabla);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Inventario.xlsx"');
header('Cache-Control: max-age=0');
header("Pragma: no-cache");
header("Expires: 0");
$hoja = $spreadsheet->getActiveSheet();
$columna = $hoja->getHighestColumn();
$fila = $hoja->getHighestRow();
$hoja->setTitle("Inventario");
$hoja->getStyle('A')->getNumberFormat()->setFormatCode('0');
$hoja->getStyle('D')->getNumberFormat()->setFormatCode('0');
$hoja->getStyle('F')->getNumberFormat()->setFormatCode('0');
$hoja->getStyle('A:'. $columna)->getFont()->setName('Arial');
$hoja->getStyle('A1:'. $columna.'1')->getFont()->setBold(true)->setSize(12);
$hoja->getStyle('A1:'. $columna.'1')->getFont()->getColor()->setARGB('00000000');
$hoja->getStyle('A1:'. $columna.'1')->getAlignment()->setWrapText(true);
$hoja->getStyle('A1:'. $columna.'1')->getAlignment()->setHorizontal('center');
$hoja->getStyle('A1:'. $columna.'1')->getAlignment()->setVertical('center');
$hoja->getStyle('A1:'.$columna.$fila)->getBorders()->getAllBorders()->setBorderStyle('thin');
$hoja->getStyle('A1:'. $columna.'1')->getBorders()->getAllBorders()->setBorderStyle('thick');
$hoja->getStyle('A1:'. $columna.'1')->getFill()->setFillType('solid')->getStartColor()->setARGB('0061c4e2');
$hoja->getDefaultRowDimension()->setRowHeight(-1);
$hoja->getColumnDimension('A')->setWidth(5);
$hoja->getColumnDimension('B')->setAutoSize(true);
$hoja->getColumnDimension('C')->setAutoSize(true);
$hoja->getColumnDimension('D')->setAutoSize(true);
$hoja->getColumnDimension('E')->setAutoSize(true);
$hoja->getColumnDimension('F')->setAutoSize(true);

// $styleArray = [
//     'font' => [
//         'bold' => true,
//     ],
//     'alignment' => [
//         'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
//     ],
//     'borders' => [
//         'top' => [
//             'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//         ],
//     ],
//     'fill' => [
//         'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
//         'rotation' => 90,
//         'startColor' => [
//             'argb' => 'FFA0A0A0',
//         ],
//         'endColor' => [
//             'argb' => 'FFFFFFFF',
//         ],
//     ],
// ];

// $hoja->getStyle('A:D')->applyFromArray($styleArray);


$writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
exit;

