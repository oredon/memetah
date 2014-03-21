<?php
include('util.php');
// CSVヘッダ
header("Cache-Control: public");
header("Pragma: public");
header("Content-Type: text/octet-stream");
header("Content-Disposition: attachment; filename=" . date('Ymd_His') . ".tsv");

//echo mb_convert_encoding("ファイルパス\tタイトル\tキーワード\tデスクリプション", "SJIS-WIN", "UTF-8") . "\n";
echo mb_convert_encoding($_POST["filelist"], "SJIS-WIN", "UTF-8");
