<?php

// PHP include path settings
//set_include_path(realpath(str_replace('\\', '/', dirname(__FILE__)).'/pear/'));

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ja" xml:lang="ja">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta http-equiv="content-language" content="ja" />
<title>MEMETAH</title>
<link rel="stylesheet" type="text/css" media="screen,print" href="css/bootstrap.min_custom.css" />
<link rel="stylesheet" type="text/css" media="screen,print" href="css/common.css" />
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery1.8.2.js"></script>
<script type="text/javascript" src="js/common.js"></script>
</head>
<body>




<h1 class="mb50">MEMETAH</h1>

<!-- 
form:

form.form-horizontal
  div.control-group
    label.control-label
    div.controls
      input ... etc...

-->
<h2>１．ファイルリスト作成</h2>
<p>トップディレクトリ以下を再帰的に探査してファイルリストを作ります。</p>

<div class="well">
<form method="post" action="dir.php" id="form1" name="filelist-form" class="form-horizontal">

<div class="control-group">
<label class="control-label" for="dir_path">トップディレクトリ</label>
<div class="controls"><input type="text" name="dir_path" value="<?php echo dirname(__FILE__).'/test'; ?>" style="width:80%;" /><br />例）D:\works\xxx\htdocs</div>
<!-- control-group --></div>

<div class="control-group">
<label class="control-label" for="meta">拡張子</label>
<div class="controls"><input type="text" name="ext" value="html|htm|php" style="width:80%;" /><br />例）html|htm|php　|区切り 全てのファイルを対象にリストを作りたい場合は「.*」を入力</div>
<!-- control-group --></div>

<div class="control-group">
<label class="control-label" for="meta">メタの出力</label>
<div class="controls"><input type="checkbox" name="meta" value="true" checked="checked" id="dir_meta_id" /><label for="dir_meta_id" class="labelname">メタを出力する</label></div>
<!-- control-group --></div>

<div class="control-group">
<label class="control-label" for="code">HTMLの文字コード</label>
<div class="controls">
<input type="radio" name="code" value="utf8" checked="checked" id="dir_utf8_id" /><label for="dir_utf8_id" class="labelname">UTF8</label><br />
<input type="radio" name="code" value="sjis" id="dir_sjis_id" /><label for="dir_sjis_id" class="labelname">shift-jis</label><br />
※HTMLファイルの文字コードを合わせないと取得結果が文字化けします
</div>
<!-- control-group --></div>

<div class="controls">
<p><input type="submit" value="ファイルリスト作成" class="btn btn-info" /></p>
</div>

</form>

<ul>
<li>指定したディレクトリ以下を再帰的にファイル検索し、リスト化します。</li>
<li>リスト化対象にする拡張子を限定することができます。半角の|で複数指定可能です。</li>
<li>取得したファイルからtitle,meta:keyword,meta:descriptionを抜き出して一覧化できます。</li>
<li>ファイルリストはtsv形式で出力されます。tsvはExcelやテキストエディタで編集閲覧できます。</li>
<li>HTMLファイルの文字コードを指定する必要があり、文字コードが違う場合は取得結果が文字化けします（メタ出力時のみ）</li>
<li>tsvはExcelで編集することを想定してSHIFT-JISで出力されます。</li>
<li>「１」で元のHTMLファイルが文字化けすることはありません（読み込みしかしてないため）</li>
</ul>
<!-- well --></div>


<h2>２．ファイルへ適用</h2>
<p>tsvを元にtitleやmetaを更新します。</p>

<div class="well">
<form method="post" action="do.php" id="form2" name="do-form" class="form-horizontal">

<div class="control-group">
<label class="control-label" for="tsv">tsvファイルのパス</label>
<div class="controls">
<input type="text" name="tsv" value="<?php echo dirname(__FILE__).'/test.tsv'; ?>" style="width:80%;" /><br />例）D:\works\xxx\tsv\20121101.tsv<br />※tsvの文字コードはshift-jisにしてください。
</div>
<!-- control-group --></div>

<div class="control-group">
<label class="control-label" for="code">HTMLの文字コード</label>
<div class="controls">
<input type="radio" name="code" value="utf8" checked="checked" id="file_utf8_id" /><label for="file_utf8_id" class="labelname">UTF8</label><br />
<input type="radio" name="code" value="sjis" id="file_sjis_id" /><label for="file_sjis_id" class="labelname">shift-jis</label><br />
※HTMLファイルの文字コードを合わせないと全ファイルのメタが文字化けします
</div>
<!-- control-group --></div>

<div class="controls">
<p><input type="submit" value="実行" class="btn btn-info" /></p>
</div>

</form>

<ul>
<li>「１．ファイルリスト作成」 で作ったtsvを編集し、どっか適当な領域に置いてください。</li>
<li><strong>tsvの形式は「１．ファイルリスト作成」 で出力されたものと同様</strong>にしてください</li>
<li><strong>tsvの文字コードはSHIFT-JIS</strong>にします。</li>
<li><strong>HTMLの文字コードは</strong>必ずHTMLファイルの文字コードを指定してください。<strong>間違うとファイルごと文字化け</strong>します。</li>
<li>元のHTMLファイルは上書きされます。バックアップは必ず取っておいてください。</li>
<li>「置換」です。「挿入」ではありません。<strong>元のHTMLにtitleタグやmetaがそもそも無いとtsvに何を書いても意味がありません</strong>。</li>
<li>linuxやmacの場合、<strong>apacheがファイルを書き込める権限</strong>がないと動作しません。</li>
<li>ざっくりした処理の流れは、tsvの1行目はスキップし、二行目から処理開始。1列目のファイルパスから対象のファイルを取得し、2列目以降のデータを置換します。このとき、tsvのデータと元ファイルのデータが同一の場合は置換処理は行いません。また、1つのファイルを通してまったく置換作業を行わなかった場合はファイルの上書き処理は走りません（おそらくファイルの更新日時も変わらないはずです）</li>
</ul>
<!-- well --></div>



<div class="alert alert-block">
<h2 class="alert-heading">注意</h2>
<p>・何が起きても責任持てません。バックアップは必ずしてください。</p>
</div>


<div class="alert alert-block alert-error">
<h2 class="alert-heading">既知の問題</h2>
<p>　</p>
</div>

<div class="alert alert-block alert-info">
<h2 class="alert-heading">仕様</h2>
<p>　</p>
</div>



<div class="alert alert-block alert-success">
<h2 class="alert-heading">アイデア・リトライしたい事項</h2>
<p>・<span class="done">UTF8以外にもSJIS対応したい</span> できた</p>
</div>

</body>
</html>