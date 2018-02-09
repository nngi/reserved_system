## INSTALL
※XAMPP、npm、composer、Tortoise Gitのインストールが済んでいること 
①XAMPPのApacheとMySQLを起動する 
②Tortoise Gitで任意のディレクトリで下記URLのクローンを作成 
https://github.com/nngi/reserved_system.git 
→下記ディレクトリが作成される 
<任意のディレクトリ>reserved_system 
③コマンドプロンプトを起動する 
④下記コマンドで予約管理システムフォルダに移動 
cd <任意のディレクトリ>\reserved_system 
⑤下記コマンドでpackage.jsonをインストール 
npm install 
⑥下記コマンドを実行（vendorフォルダになんかいろいろインストールされるらしい） 
composer install 
⑦phpMyAdminで「reserved_system」という名称でデータベース作成 
⑧下記コマンドでテーブルと初期レコードを登録 
php artisan migrate 
php artisan db:seed 
⑨下記コマンドでビルド 
npm run dev 
⑩下記コマンドでサービスを開始 
php artisan serve 
⑪下記にブラウザでアクセスする 
http://localhost:8000/ 
