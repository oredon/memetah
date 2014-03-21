<?php

// PHP include path settings
//set_include_path(realpath(str_replace('\\', '/', dirname(__FILE__)).'/pear/'));

//debug
$system_mem = false;

include('util.php');

//POSTを精査
if( $_POST["code"] == 'sjis' ){
    $code = 'SJIS';
}else{
    $code = 'UTF-8';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-language" content="ja" />
<title>実行 | MEMETAH</title>
<link rel="stylesheet" type="text/css" media="screen,print" href="css/bootstrap.min_custom.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="css/common.css" />
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery1.8.2.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>

<h1 class="mb50">MEMETAH - 実行　<a class="btn" href="index.php">戻る</a></h1>

<div class="alert alert-block alert-info">
<h2>[debug]</h2>
<p>tsv ファイルパス : 
<?php
echo $_POST["tsv"];
?>
</p>

<p>指定したHTMLファイルの文字コード : 
<?php
echo $_POST["code"];
?>
</p>
</div>

<div class="well">

<?php
$point1 = memory_get_usage($system_mem); // メモリ使用量計測

$row = 1; // 行番号

// tsvを読み込んでパース
if (($fp = fopen($_POST["tsv"], "r")) !== FALSE) {
    while (($data = fgetcsv_reg($fp, null, "\t")) !== FALSE) {
        //$num = count($data);
        // tsvの一行目はheaderなので処理スキップ
        if($row != 1){
            put_data($data,$code);
        }
        $row++;
    }
    fclose($fp);
}

$point2 = memory_get_usage($system_mem); // メモリ使用量計測

echo "memory usage: " . $point2 - $point1 . " bytes";//debug

?>

</div>
</body>
</html>