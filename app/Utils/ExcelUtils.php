<?php

namespace App\Utils;

use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelUtils
{

    public static function getFile()
    {
        // 保存文件
        $file = request()->file('file');
        $fileName =  uniqid() . '.' . $file->getClientOriginalExtension();
        $filePath = storage_path('import');
        $file->move($filePath, $fileName);
        return $filePath . DIRECTORY_SEPARATOR . $fileName;
    }

    /**
     * 解析文件内容
     *
     * @param $file
     * @param $colArr
     * @param $startLine
     * @param $dateArr
     * @param $checkFirstRow
     * @return array
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public static function getData($file, $colArr = [], $startLine = 2, $dateArr = [], $checkFirstRow = true)
    {
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(TRUE);
        $spreadsheet = $reader->load($file);

        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        $insertData = [];

        for ($row = $startLine; $row <= $highestRow; ++$row) {
            if ($checkFirstRow) {
                $col1 = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
                if (empty($col1)) {
                    continue;
                }
            }

            $rowData['row'] = $row;

            foreach ($colArr as $key => $field) {
                $worksheet->getCellByColumnAndRow($key + 1, $row)->setDataType(DataType::TYPE_STRING);
                $val = trim($worksheet->getCellByColumnAndRow($key + 1, $row)->getValue());
                $rowData[$field] = $val;
            }

            foreach ($rowData as $_key => $item) {
                if (in_array($_key, $dateArr)) {
                    $rowData[$_key] = (isset($rowData[$_key]) && is_numeric($rowData[$_key])) ? ExcelUtils::formatTime($rowData[$_key]) : ($rowData[$_key] ?? '');
                }
            }

            array_push($insertData, $rowData);
        }

        return $insertData;
    }

    public static function export($fileName, $titleArr, $excelData = [])
    {

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
        header('Cache-Control: max-age=0');

        $spreadsheet = new Spreadsheet();

        $sheet = $spreadsheet->getActiveSheet(); //创建sheet
        $row = 1;

        // 设置表头
        foreach ($titleArr as $key => $value) {
            $sheet->setCellValue(self::convert($key + 1) . $row, $value);
        }

        // 插入表格
        foreach ($excelData as $value) {
            $row++;
            foreach ($value as $col => $item) {
                $sheet->setCellValue(self::convert($col + 1) . $row, $item);
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        //删除清空：
        $spreadsheet->disconnectWorksheets();
    }

    public static function convert($n)
    {
        if ($n > 26) {
            return self::convert(($n - ($n % 26)) / 26) . mb_chr(64 + ($n % 26));
        } else return mb_chr(64 + $n) . '';
    }

    /**
     * 时间格式化
     *
     * @param $timeValue
     * @param $format
     * @return false|string|null
     */
    public static function formatTime($timeValue, $format = 'Y-m-d')
    {
        if(strpos($timeValue,"-")) {
            $times = strtotime($timeValue);
        } else if (is_numeric($timeValue)) {
            $times = intval(($timeValue - 25569) * 3600 * 24);
        } else {
            return null;
        }

        return date($format, $times);
    }

    /**
     * @param $value
     * @return int
     */
    public static function getBoolean($value)
    {
        return in_array($value, ["是", "Y", "y", "1"]) ? 1 : 0;
    }


    /**
     * 格式化
     *
     * @param $number
     * @return array|mixed|string|string[]|null
     */
    public static function formatNumber($number)
    {
        if(stripos($number, 'e') === false) {
            //判断是否为科学计数法
            return $number;
        }
        if(!preg_match(
            "/^([\\d.]+)[eE]([\\d\\-\\+]+)$/",
            str_replace(array(" ", ","), "", trim($number)), $matches)
        ) {
            //提取科学计数法中有效的数据，无法处理则直接返回
            return $number;
        }
        //对数字前后的0和点进行处理，防止数据干扰，实际上正确的科学计数法没有这个问题
        $data = preg_replace(array("/^[0]+/"), "", rtrim($matches[1], "0."));
        $length = (int)$matches[2];
        if($data[0] == ".") {
            //由于最前面的0可能被替换掉了，这里是小数要将0补齐
            $data = "0{$data}";
        }
        //这里有一种特殊可能，无需处理
        if($length == 0) {
            return $data;
        }
        //记住当前小数点的位置，用于判断左右移动
        $dot_position = strpos($data, ".");
        if($dot_position === false) {
            $dot_position = strlen($data);
        }
        //正式数据处理中，是不需要点号的，最后输出时会添加上去
        $data = str_replace(".", "", $data);
        if($length > 0) {
            //如果科学计数长度大于0
            //获取要添加0的个数，并在数据后面补充
            $repeat_length = $length - (strlen($data) - $dot_position);
            if($repeat_length > 0) {
                $data .= str_repeat('0', $repeat_length);
            }
            //小数点向后移n位
            $dot_position += $length;
            $data = ltrim(substr($data, 0, $dot_position), "0").".".substr($data, $dot_position);
        } elseif($length < 0) {
            //当前是一个负数
            //获取要重复的0的个数
            $repeat_length = abs($length) - $dot_position;
            if($repeat_length > 0) {
                //这里的值可能是小于0的数，由于小数点过长
                $data = str_repeat('0', $repeat_length).$data;
            }
            $dot_position += $length;//此处length为负数，直接操作
            if($dot_position < 1) {
                //补充数据处理，如果当前位置小于0则表示无需处理，直接补小数点即可
                $data = ".{$data}";
            } else {
                $data = substr($data, 0, $dot_position).".".substr($data, $dot_position);
            }
        }
        if($data[0] == ".") {
            //数据补0
            $data = "0{$data}";
        }
        return trim($data, ".");
    }

}
