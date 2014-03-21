memetah
=======

php tool to replace title, keyword, descriptions of html files


notice 注意書き
=======

※ローカルサーバ上で使用してください。パブリックなWEBサーバ等には置かないでください
Please use in local server. NOT USE PUBLIC WEB SERVER.

※このスクリプトを使ったことで生じたいかなる損害やトラブルの責任は一切負いかねますので予めご了承ください。
AT YOUR OWN RISK. I shall not be responsible for any loss, damages and troubles.



概要
=======

・titleやキーワード等をtsvに出力し、tsvで編集したものを元ファイルに反映します
・一枚一枚HTMLを開いてmetaを入力せずに一枚のtsvで管理できるようになります


導入
=======

・ローカルサーバの適当なドキュメントルートにmetaをディレクトリ毎コピー
例）d:\works\XXX\htdocs\meta\
・PHPの設定は適当に調べてください
・ブラウザで上記にアクセス
例）http://localXXX/meta
・「１」探査したいディレクトリを入力
例）D:\works\xxx\htdocs
・tsvをダウンロードし、編集
・「２」tsvをどこか適当において実行
例）D:\works\xxx\htdocs\hoge.tsv


用途
=======

■全ファイルのtitleやmetaを取得したい
・「１」でできます

■ファイルリストだけでいい
・「１」でメタの出力をするからチェックを外せば可能です
・デフォルトではhtml,php,htmファイルをリストアップするようにしてありますが、xmlやgifなども対象に含めることは可能です
html|htm|php
を
html|htm|php|xml|gif
のように半角の|で拡張子を区切り、「１」の拡張子欄に入力し、実行してください

■tsvで編集したmetaを反映させたい
・「２」でできます


トラブルシューティングなど
=======

■HTMLファイルが軒並み文字化けした
・「１」にも「２」にもHTMLファイルの文字コードを指定するオプションがあります。
・バックアップは必ずとってください

