<!doctype HTML PUBLIC '-//W3C//DTD HTML 4.01 Transitional//EN' 'http://www.w3.org/TR/html4/loose.dtd'>
<html>
<head>
    <style>body { font-family:Times New Roman;}</style>
    <title>404 Not Found</title>
</head>
<body>
    <h1>Not Found</h1>
    <p>The requested URL <?= $_SERVER[ 'REQUEST_URI' ];  ?> was not found on this server.</p>
    <hr>
    <?= $_SERVER[ 'SERVER_SIGNATURE' ];  ?>
</body>
</html>
