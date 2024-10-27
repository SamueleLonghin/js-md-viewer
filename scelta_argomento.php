<?php
// Rendi globali le variabili necessarie
global $config, $BASE_PATH, $macroargomento, $title;

require "head.php";
?>
<body>
    
    <header class="sticky-top bg-white pb-1 pt-2 mb-2">
        <a href="/corsi" class=" btn btn-outline-secondary position-absolute mx-2 ">
            <i class="bi bi-arrow-left"></i>
            <!-- <span class="d-none d-md-inline">Torna ai Corsi</span> -->
        </a>
        <h1 class="text-center title-corso"><?= $config['topics'][$macroargomento]['label'] ?></h1>
    </header>


    <div class="container">
        <div class="row">
            <?php foreach ($config['topics'][$macroargomento]['chapters'] as $argomentoKey => $chapter): ?>
                <div class="col-6 col-sm-4 col-lg-3 mb-4">
                    <div class="card h-100">
                        <img src="<?= $chapter['cover_image'] ?>" class="card-img-top" alt="<?= $argomentoKey ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= $argomentoKey ?></h5>
                            <p class="card-text"><?= $chapter['description'] ?></p>
                            <a href="/<?= urlencode($macroargomento). '/' . urlencode($argomentoKey) ?>" class="stretched-link"></a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <!-- <a href="/" class="btn btn-outline-secondary back-button">
        <i class="bi bi-arrow-left"></i> Torna ai Corsi
    </a> -->
    <?php require "footer.php"?>

</body>
</html>
