<?php
// Rendi globali le variabili necessarie
global $config, $BASE_PATH, $macroargomento;
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seleziona Argomento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4"><?= $config['topics'][$macroargomento]['label'] ?></h1>
        <div class="row">
            <?php foreach ($config['topics'][$macroargomento]['chapters'] as $argomentoKey => $chapter): ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?= $chapter['cover_image'] ?>" class="card-img-top" alt="<?= $argomentoKey ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $argomentoKey ?></h5>
                            <p class="card-text"><?= $chapter['description'] ?></p>
                            <a href="/<?= urlencode($macroargomento). '/' . urlencode($argomentoKey) ?>" class="btn btn-primary stretched-link">Visualizza Contenuto</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
