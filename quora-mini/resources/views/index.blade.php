<!doctype html>
<html ng-app="quora-mini">
<head>
    <meta charset="UTF-8">
    <title>Quora-mini</title>
    <link rel="stylesheet" href="/node_modules/normalize-css/normalize.css">
    <script rel="stylesheet" href="/css/base.css"></script>
    <script src="/node_modules/jquery/dist/jquery.js"></script>
    <script src="/node_modules/angular/angular.js"></script>
    <script src="/node_modules/angular-ui-router/release/angular-ui-router.js"></script>
    <script src="/js/base.js"></script>
</head>

<body>
<div class="navbar">
    <div class="fl"></div>
    <div class="fr"></div>
</div>

<div class="page">
    <div ui-view></div>
</div>
</body>


<script type="text/ng-template" id="home.tpl">
<div class="home"></div>
</script>

<script type="text/ng-template" id="login.tpl">

</script>

</html>