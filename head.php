<?php
global $argomento;
global $BASE_PATH;
?>
<head>


    <title>
        <?php echo isset($argomento) ? htmlspecialchars($argomento) : 'Argomenti'; ?>
    </title>
    <meta charset="UTF-8">
    <meta name="author" content="Samuele Longhin">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?=$BASE_PATH?>public/prism.css">
    <link rel="stylesheet" href="<?=$BASE_PATH?>public/site.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>