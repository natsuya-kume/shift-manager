<?php
//エクセルからシフト情報を取得してメッセージを作成する

include('./vendor/autoload.php');
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as XlsxReader;

date_default_timezone_set('Asia/Tokyo'); //フォーマット
$today= date("Y/n/d"); //現在の日付の管理
$lastRow =31;  //最後の列の番号
$text_message="本日の予定はありません"; //予定がない時に表示するメッセージ

$reader = new XlsxReader();
$spreadsheet = $reader->load('./shift.xlsx'); // ファイル名を指定
$sheet = $spreadsheet->getSheetByName('シート1'); // 読み込むシートを指定

$data = $sheet->rangeToArray('A1:E31'); // 配列で取得したい範囲を指定


for($i=2;$i<=$lastRow;$i++){
    // エクセルの日付が現在日時と一致した時にメッセージを更新
    if($today==$sheet->getCell("A$i")->getFormattedValue()&&
    $sheet->getCell("B$i")->getFormattedValue()!=""
    ){
        $text_message="本日のシフトは"."\n". //改行
        "\n". //改行
        "-----------------\n". 
        $sheet->getCell("B1")->getFormattedValue().
        "：".
        $sheet->getCell("B$i")->getFormattedValue()."\n".

        $sheet->getCell("C1")->getFormattedValue().
        "：".
        $sheet->getCell("C$i")->getFormattedValue()."\n".

        $sheet->getCell("D1")->getFormattedValue().
        "：".
        $sheet->getCell("D$i")->getFormattedValue()."\n".

        $sheet->getCell("E1")->getFormattedValue().
        "：".
        $sheet->getCell("E$i")->getFormattedValue()."\n".
        "-----------------\n".
        "\n".
        "となっております。"."\n".
        "\n".
        "本日も頑張っていきましょう！";
    }
}
return $text_message;