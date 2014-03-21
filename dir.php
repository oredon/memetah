<?php

// PHP include path settings
//set_include_path(realpath(str_replace('\\', '/', dirname(__FILE__)).'/pear/'));

//debug
$system_mem = false;

include('util.php');

//POSTを精査
if( !empty( $_POST["dir_path"] ) ){
    $path = $_POST["dir_path"];
}else{
    $path = $_SERVER['DOCUMENT_ROOT'];
}

if( !empty( $_POST["meta"] ) ){
    $metaFlag = true;
}else{
    $metaFlag = false;
}

if( !empty( $_POST["ext"] ) ){
    $ext = '/(\.)(' . $_POST["ext"] . ')$/i';
}else{
    $ext = '/(\.)(html|htm|php)$/i';
}

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
<title>ファイルリスト作成 | MEMETAH</title>
<link rel="stylesheet" type="text/css" media="screen,print" href="css/bootstrap.min_custom.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="css/common.css" />
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery1.8.2.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>

<h1 class="mb50">MEMETAH - ファイルリスト作成　<a class="btn" href="index.php">戻る</a></h1>

<form id="genform" name="gentsv" method="post" target="_blank" action="gen.php">
<p><input type="submit" value="TSVでダウンロード" class="btn btn-info" /></p>

<div class="alert alert-block alert-info">
<h2 class="alert-heading">仕様</h2>
<p>コピペする場合、文字コードはshift-jisにして保存してください</p>
<p>tsvはエクセルで編集可能です。もちろんテキストエディタでも可能です</p>
<p>何言ってるかよく分からなかったらとりあえずTSVでダウンロードのボタンを押してください</p>
</div>

<div class="alert alert-block alert-info">
<h2 class="alert-heading">[debug]</h2>
<p>ディレクトリpath : 
<?php
echo $path;
?>
</p>
<p>探査対象拡張子 : 
<?php
echo $ext;
?>
</p>
<p>指定したHTMLファイルの文字コード : 
<?php
echo $code;
?>
</p>
<p>meta取得 : 
<?php
echo $metaFlag;
?>
</p>
<!-- alert --></div>


<div class="well">
<?php

//指定ディレクトリ以下を再帰的にファイルパス探査
$dirlist = array_dirlist($path,$ext);
$fullpath = array_fullpath($path, $dirlist);
?>

<textarea id="filelistresult" name="filelist">ファイルパス	タイトル	キーワード	デスクリプション	og:locale	og:type	og:site_name	og:title	og:description	og:image	og:url
<?php
$point1 = memory_get_usage($system_mem); // メモリ使用量計測

//ファイルパス・メタの出力
foreach ($fullpath as $path){
    if($metaFlag){
        show_filedata( $path, $code );
    }else{
        echo $path."\n";
    }
}

$point2 = memory_get_usage($system_mem); // メモリ使用量計測

?>
</textarea>

<!-- well --></div>
</form>


<?php
echo "memory usage: " . $point2 - $point1 . " bytes";//debug
?>


</body>
</html>