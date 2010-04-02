<!doctype HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
    <style>body { font-family:Times New Roman;}</style>
    <title>403 Forbidden</title>
</head>
<body>
    <h1>Forbidden</h1>
    <p>You don't have permission to access <?= $_SERVER[ 'REQUEST_URI' ];  ?> on this server.</p>
    <hr>
    <?= $_SERVER[ 'SERVER_SIGNATURE' ];  ?>
</body>
</html>
