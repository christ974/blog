<!DOCTYPE html>
<html lang="fr" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= htmlentities(($title) ?? 'Blog') ?></title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body class="d-flex flex-column h-100" >
    <nav class="navbar navbar-expand-lg navbar-dark bg-info">
        <a href="/" class="navbar-brand">Mon blog</a>
        <a href="/admin" class="navbar-brand">Administration</a>
        
    </nav>

    <div class="container mt-4">

    <?= $content ?>
    
    </div>

    <footer class="bg-ligth py-4 footer mt-auto">
        <div class="container">
            <?php if(defined('DEBUG_TIME')):  ?>
            Page générée en <?= round (1000 * (microtime(true)- DEBUG_TIME))  ?>  millisecondes 
            <?php endif ?>
        </div>
    </footer>
</body>
</html>