<?php
namespace Sigapp\Layers\IO;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use \PhpOffice\PhpSpreadsheet\Style\{ Color, Fill, Border };
use \Sigapp\Layers\LayersEntity;

class Excel extends LayersEntity
{
    public $filter;
    public $extent;
    private $rows;

    public function __construct(int $id, string $filter, array $extent)
    {
        $this->filter = $filter;
        $this->extent = $extent;
        parent::__construct($id);
        $this->prepare();
    }

	private function prepare(): void
	{
		$letter = 'B';
		$columns['A'] = 'APP_DISPLAYFIELD';
		foreach ($this->layer['columns'] as $column) {
			if (!$column['excluded']) {
				$columns[$letter] = $column['alias']; 
				$letter++;
			}
        }
        if ( empty($extent) ) {
            $rows = $this->getProvider()->getDatatable($this->filter);
        }
        else {
            $rows = $this->getProvider()->getDatatableFromExtent($this->extent, $this->filter);
        }
        $this->rows = $rows;
        $this->columns = $columns;
	}

    public function getSettings(): object
    {
        $columncount = count($this->columns);
        $rowcount = count($this->rows) + 1;
        $letter = 'A';
        for($i = 1;  $columncount >= $i; $i++){
            $columns[$i] = $letter;
            $letter++;
        }
        return (object) [
            'rowcount' =>  $rowcount,
            'columncount' =>  $columncount,
            'columns' => $columns,
            'allColumns' => 'A1:' . $columns[$columncount] . '1',
            'allTable' => 'A1:' . $columns[$columncount] .  $rowcount,
            'allRows' => 'A2:' . $columns[$columncount] .  $rowcount,
            'columnSettings' => $this->columns
        ];
    }

    public function createSpreadsheet(): string
    {
        $get = $this->getSettings();
        $spreadsheet = new Spreadsheet();
        $spreadsheet->setActiveSheetIndex(0);
        $sheet = $spreadsheet->getActiveSheet(0);
        $sheet->setTitle($this->layer['title']);
        $sheet->setAutoFilter($get->allColumns);
        $sheet->getStyle($get->allColumns)->applyFromArray([
            'font'  => [
                'bold'  => true,
                'color' => ['rgb' => '1c313a'],
                'size'  => 11,
                'name'  => 'Helvetica'
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'e3f2fd'],
                'endColor' => ['rgb' => 'e3f2fd'],
            ]
        ]);
        $sheet->getStyle($get->allTable)->getAlignment()->setWrapText(true);
        $sheet->getStyle($get->allTable)->getAlignment()->setHorizontal('center');
        $sheet->getStyle($get->allTable)->getAlignment()->setVertical('center');
        $sheet->getStyle($get->allTable)->getAlignment()->setVertical('center');
        $sheet->getStyle($get->allTable)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '9e9e9e'],
                ],
            ],
        ]);
        $sheet->freezePaneByColumnAndRow(1, 2);
        $sheet->getRowDimension('1')->setRowHeight(25);

        foreach($get->columnSettings as $column => $title){
            $sheet->setCellValue($column . "1", $title);
            $sheet->getColumnDimension($column)->setWidth(30);
        }

        $i = 2;
        foreach ($this->rows as $row){
            foreach($get->columnSettings as $column => $title){
                $attribute = $title;
                $sheet->setCellValue($column . $i, $row[$attribute]);
            }
            $i++;
        }
        $sheet->setCellValue('A1', 'ID');
        $writer = new Xlsx($spreadsheet);
        $file = PATH_TO_FILES . $this->layer['title'] . ".xlsx";
        $writer->save($file);
        return $file;
    }
}