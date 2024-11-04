<?php
// Rendi globali le variabili necessarie
global $topics, $BASE_PATH, $macroargomento, $title;

require "head.php";
?>

<body>

    <header class="sticky-top bg-white  mb-2 p-3 ">
        <a href="/corsi" class=" btn btn-outline-secondary position-absolute ">
            <i class="bi bi-arrow-left"></i>
        </a>
        <h1 class="text-center title-corso"><?= $topics[$macroargomento]['label'] ?></h1>
    </header>


    <div class="container">
        <div class="row">
            <?php foreach ($topics[$macroargomento]['chapters'] as $argomentoKey => $chapter) {
                if (!isset($chapter['visibility']) || $chapter['visibility'] == 'visible') {
                    ?>
                    <div class=" col-12 col-sm-6 col-md-4 col-lg-3 mb-4">
                        <div class="card h-100">
                            <img src="<?= $chapter['cover_image'] ?>" class="card-img-top" alt="<?= $argomentoKey ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?= $argomentoKey ?></h5>
                                <p class="card-text"><?= $chapter['description'] ?></p>
                                <a href="/<?= urlencode($macroargomento) . '/' . urlencode($argomentoKey) ?>"
                                    class="stretched-link"></a>
                            </div>
                        </div>
                    </div>
                <?php }
            } ?>
        </div>
    </div>
    <!-- <a href="/" class="btn btn-outline-secondary back-button">
        <i class="bi bi-arrow-left"></i> Torna ai Corsi
    </a> -->
    <?php require "footer.php" ?>

</body>

</html>