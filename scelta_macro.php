<?php
// Rendi globali le variabili necessarie
global $config, $BASE_PATH, $title;

require "head.php";
?>

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
    <?php require "footer.php"?>

</body>
</html>
