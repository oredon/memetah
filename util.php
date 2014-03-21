<?php
//ディレクトリとファイルの配列を生成
function array_dirlist($path, $pattern, $level=100) {
    $dirlist = array();
    if ($level) {
        $dh = opendir($path);
        while (($filename = readdir($dh))) {
            if ($filename == '.' || $filename == '..' )
                continue;
            else {
                $realpath = $path.'/'.$filename;
             if (is_link($realpath))
                    continue;
             else if (is_file($realpath) && preg_match( $pattern, $filename ) > 0 )
                    $dirlist[] = $filename;
             else if (is_dir($realpath))
                    $dirlist[$filename] = array_dirlist($realpath, $pattern, $level-1);
        }    }
        closedir($dh);
    }
    return $dirlist;
}
//array_dirlistからフルパスを生成
function array_fullpath($path, $dirlist) {
    $fullpath = array();
    foreach ($dirlist as $id=>$filename) {
        if (is_array($filename))
            $fullpath = array_merge($fullpath, array_fullpath($path.'/'.$id, $filename));
        else
            $fullpath[] = $path.'/'.$filename;
    }
    return $fullpath;
}
// memory cleaning
function flush_buffers(){ 
    ob_end_flush(); 
    ob_flush(); 
    flush(); 
}

//alternate fgetcsv
function fgetcsv_reg (&$handle, $length = null, $d = ',', $e = '"') {
   $d = preg_quote($d);
   $e = preg_quote($e);
   $_line = "";
   while ($eof != true) {
      $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
      $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
      if ($itemcnt % 2 == 0) $eof = true;
  }
  $_csv_line = preg_replace('/(?:\\r\\n|[\\r\\n])?$/', $d, trim($_line));
  $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
  preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
  $_csv_data = $_csv_matches[1];
  for($_csv_i=0; $_csv_i<count($_csv_data); $_csv_i++) {
     $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s','$1',$_csv_data[$_csv_i]);
      $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
  }
  return empty($_line) ? false : $_csv_data;
}

// ------------------------------------------------------------ 

/**
* フルパスからファイル情報を取得
* 
* @param string $path 対象ファイルのフルパス
* @param integer $code HTMLファイルの文字コード
* @return なし
*/
function show_filedata( $path, $code="UTF-8" ){
    $metaArr = array(
        "title" => "",
        "keywords" => "",
        "description" => "",
        "og:locale" => "",
        "og:type" => "",
        "og:site_name" => "",
        "og:title" => "",
        "og:description" => "",
        "og:image" => "",
        "og:url" => ""
    );
    
    //file_get_contents版
    $buffer = file_get_contents( $path );
    if( preg_match( '/<title>(.*)<\/title>/i', $buffer, $matches ) > 0 ){
        $metaArr["title"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta name="keywords".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["keywords"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta name="description".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["description"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }

    if( preg_match( '/<meta property="og:locale".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:locale"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta property="og:type".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:type"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta property="og:site_name".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:site_name"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta property="og:title".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:title"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta property="og:description".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:description"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta property="og:image".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:image"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    if( preg_match( '/<meta property="og:url".*content="(.*)"/i', $buffer, $matches ) > 0 ){
        $metaArr["og:url"] = htmlspecialchars(mb_convert_encoding($matches[1], "UTF-8", $code),ENT_QUOTES);
    }
    flush_buffers();
    
    echo $path . "\t" . $metaArr["title"] . "\t" . $metaArr["keywords"] . "\t" . $metaArr["description"] . "\t" . $metaArr["og:locale"] . "\t" . $metaArr["og:type"] . "\t" . $metaArr["og:site_name"] . "\t" . $metaArr["og:title"] . "\t" . $metaArr["og:description"] . "\t" . $metaArr["og:image"] . "\t" . $metaArr["og:url"] . "\n";
    
    //mem clr
    unset($buffer);
    unset($path);
    unset($metaArr);
    flush_buffers();
}

// ------------------------------------------------------------ 

/**
* HTMLファイルを上書き更新
* 
* @param array $data 対象ファイルの情報
* @param integer $code HTMLファイルの文字コード
* @return なし
*/
function put_data( $data, $code="UTF-8" ){
    /* $data[]
     * [0] filepath
     * [1] title
     * [2] keyword
     * [3] description
     * [4] og_locale
     * [5] og_type
     * [6] og_site_name
     * [7] og_title
     * [8] og_description
     * [9] og_image
     * [10] og_url
     */
    // 空行無視
    if(array_key_exists(0, $data)){
        // 書き込み権限、およびファイル実体の有無
        if( is_writable($data[0]) ){
            //echo "ひらける";
            $filepath = $data[0];
            
            //file_get_contents版
            $buffer = mb_convert_encoding(file_get_contents( $filepath ) , "UTF-8", $code);
            
            //log
            $log = array();
            
            //更新箇所の有無
            $modFlag = false;
            
            // title ---------------------------------------------------------------------
            if(array_key_exists(1, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvtitle = mb_convert_encoding($data[1], "UTF-8", "SJIS-WIN");
                
                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<title>(.*)<\/title>/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];
                    
                    if($match != $tsvtitle){
                        //更新処理
                        $buffer = preg_replace('/(<title>)(.*)(<\/title>)/i', '$1'.$tsvtitle.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['title'] = "[title]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvtitle;
                    }
                }
            }
            
            // keywords ---------------------------------------------------------------------
            if(array_key_exists(2, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvkeyword = mb_convert_encoding($data[2], "UTF-8", "SJIS-WIN");
                
                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta name="keywords".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];
                    
                    if($match != $tsvkeyword){
                        //更新処理
                        $buffer = preg_replace('/(<meta name="keywords".*content=")(.*)(")/i', '$1'.$tsvkeyword.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['keywords'] = "[keyword]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvkeyword;
                    }
                }
            }
            
            // description ---------------------------------------------------------------------
            if(array_key_exists(3, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvdescription = mb_convert_encoding($data[3], "UTF-8", "SJIS-WIN");
                
                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta name="description".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];
                    
                    if($match != $tsvdescription){
                        //更新処理
                        $buffer = preg_replace('/(<meta name="description".*content=")(.*)(")/i', '$1'.$tsvdescription.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['description'] = "[description]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvdescription;
                    }
                }
            }
            
            // og_locale ---------------------------------------------------------------------
            if(array_key_exists(4, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_locale = mb_convert_encoding($data[4], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:locale".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_locale){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:locale".*content=")(.*)(")/i', '$1'.$tsvog_locale.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_locale'] = "[og_locale]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_locale;
                    }
                }
            }

            // og_type ---------------------------------------------------------------------
            if(array_key_exists(5, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_type = mb_convert_encoding($data[5], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:type".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_type){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:type".*content=")(.*)(")/i', '$1'.$tsvog_type.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_type'] = "[og_type]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_type;
                    }
                }
            }

            // og_site_name ---------------------------------------------------------------------
            if(array_key_exists(6, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_site_name = mb_convert_encoding($data[6], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:site_name".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_site_name){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:site_name".*content=")(.*)(")/i', '$1'.$tsvog_site_name.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_site_name'] = "[og_site_name]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_site_name;
                    }
                }
            }

            // og_title ---------------------------------------------------------------------
            if(array_key_exists(7, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_title = mb_convert_encoding($data[7], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:title".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_title){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:title".*content=")(.*)(")/i', '$1'.$tsvog_title.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_title'] = "[og_title]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_title;
                    }
                }
            }

            // og_description ---------------------------------------------------------------------
            if(array_key_exists(8, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_description = mb_convert_encoding($data[8], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:description".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_description){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:description".*content=")(.*)(")/i', '$1'.$tsvog_description.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_description'] = "[og_description]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_description;
                    }
                }
            }

            // og_image ---------------------------------------------------------------------
            if(array_key_exists(9, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_image = mb_convert_encoding($data[9], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:image".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_image){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:image".*content=")(.*)(")/i', '$1'.$tsvog_image.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_image'] = "[og_image]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_image;
                    }
                }
            }

            // og_url ---------------------------------------------------------------------
            if(array_key_exists(10, $data)){
                //tsv内のsjis-win文字列を作業用UTF8に変換
                $tsvog_url = mb_convert_encoding($data[10], "UTF-8", "SJIS-WIN");

                //buffer(ターゲットとなるHTMLファイル)内を探査
                if( preg_match( '/<meta property="og:url".*content="(.*)"/i', $buffer, $matches ) > 0 ){
                    $match = $matches[1];

                    if($match != $tsvog_url){
                        //更新処理
                        $buffer = preg_replace('/(<meta property="og:url".*content=")(.*)(")/i', '$1'.$tsvog_url.'$3', $buffer);
                        $modFlag = true;
                        //ログを残す
                        $log[$filepath]['og_url'] = "[og_url]" . "\n" . "変更前:" . $match . "\n" . "変更後:" . $tsvog_url;
                    }
                }
            }

            //ログ出力・ファイルへ書き込み
            if($modFlag){
                // log出力処理
                foreach( $log as $key=>$val ){
                    echo $key."<br />";
                    foreach($val as $a=>$v){
                        echo nl2br($v)."<br />";
                    }
                }
                // 書き込み処理
                if( file_put_contents( $filepath, mb_convert_encoding( $buffer, $code, "UTF-8" ) ) ){
                    echo("ファイルを上書きしました")."<br />"; 
                }
                echo "<br /><hr /><br />";
            }else{
                // 更新箇所なし
                echo $filepath . "　に変更箇所はありません";
                echo "<br /><hr /><br />";
            }
            
            //mem clr
            unset($buffer);
            
            flush_buffers();
            
        }else{
        // ファイルがない、または書き込みできない
            //echo "ひらけません";
            echo $data[0] . "　は書き込みモードで開けませんでした";
            echo "<br /><hr /><br />";
            
        }
    }
}

// ------------------------------------------------------------ 

?>