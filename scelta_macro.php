<?php
// Rendi globali le variabili necessarie
global $config, $BASE_PATH;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scegli un Modulo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Seleziona un Modulo</h1>
        <div class="row">
            <?php foreach ($config['topics'] as $key => $topic): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= $topic['cover_image'] ?>" class="card-img-top" alt="<?= $topic['label'] ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $topic['label'] ?></h5>
                            <p class="card-text"><?= $topic['description'] ?></p>
                            <a href="/<?=  urlencode($key) ?>" class="btn btn-primary stretched-link">Visualizza Argomenti</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
