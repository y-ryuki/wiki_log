# ログ解析システム
コマンドライン上で動作するwikipediaのログ解析システムを作成しました。

## 概要

### 解析するデータについて
wikipediaで公開されている、アクセスログを集計したファイルをダウンロードして使用します。 <br>下記URLからダウンロードできます。 https://dumps.wikimedia.org/other/pageviews/2021/2021-12/

データはスペースで区切られ、以下の4つカラムで構成されています。

- ドメインコード 
- ページタイトル 
- 閲覧回数 
- 総レスポンスサイズ 

データ例

```text
zu.m UYurenasi 1 0 
zu.m Ubuchopho 1 0  
zu.m Ukucela_Imvula 1 0
zu.m User:Praxidicae 1 0
zu.m.b Wikibooks:Izehlakalo_ezimanje 1 0
zu.m.d Ikhasi_Elikhulu 1 0
zu.m.d ihlo 1 0
```

データについての全体の解説は、下記URLにて行われているのでご参照ください。
https://dumps.wikimedia.org/other/pageviews/readme.html

ダウンロードしたデータのテーブル定義は下記URLで解説されているのでご参照ください。
https://wikitech.wikimedia.org/wiki/Analytics/Data_Lake/Traffic/Pageviews


### ご使用方法
初めにファイルをダウンロード、展開し、src/log_fileディレクトリにpageviewsという名前で保存してください。

プログラム実行
`docker compose exec app php exec.php`

実行されると以下のように解析されます。

例

```text
データベースへの接続に成功しました。  
既存のデータがあります。  
データベースをインポートしますか？  
はい , いいえで入力してください。  
いいえ  
最もビュー数の多い記事を表示したい場合は1を入力してください。  
指定したドメインコードを表示したい場合は2を入力してください。  
終了する場合は0を入力してください。  
2  
ドメインコードをスペース区切りで入力してください。  
en de  
en 3496102  
de 283423
```

