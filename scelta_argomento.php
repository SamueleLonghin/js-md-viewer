<?php
// Rendi globali le variabili necessarie
global $config, $BASE_PATH, $macroargomento, $title;

require "head.php";
?>
<body>
    <div class="container mt-5">
        
        <h1 class="text-center mb-4"><?= $config['topics'][$macroargomento]['label'] ?></h1>
        <div class="row">
            <?php foreach ($config['topics'][$macroargomento]['chapters'] as $argomentoKey => $chapter): ?>
                <div class="col-md-3 mb-4">
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
    <!-- Aggiungi il pulsante indietro in cima alla pagina -->
    <a href="/" class="btn btn-outline-secondary back-button mb-4">
        <i class="bi bi-arrow-left"></i> Torna ai Corsi
    </a>
    <?php require "footer.php"?>

</body>
</html>
