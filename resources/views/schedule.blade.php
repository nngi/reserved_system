<!DOCTYPE html>
<html>
    <head>
        <title>Test</title>
        <!-- Laravelではpublicがデフォルトディレクトリなのでcssやnode_modulesにないライブラリはそっちで読み込むこと -->
        <!-- 画面幅をデバイスのサイズに合わせる -->
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- bootstrapを利用する -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css" rel="stylesheet">
        <!-- ElementUIのcss -->
        <link rel="stylesheet" type="text/css" href="css/index.css">
        <!-- カスタムスタイルを利用する -->
        <link rel="stylesheet" type="text/css" href="css/custom.css">
        <!-- LaravelのCSRFトークンを設定（よくわかんないけど警告避け） -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body>
        <!-- 画面は完全にVue.jsに任せる -->
        <div id="app"></div>
        <script src="js/app.js"></script>
    </body>
</html>
