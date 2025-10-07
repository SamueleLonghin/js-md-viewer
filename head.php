<?php
global $argomento, $title, $BASE_PATH;
?>
<!DOCTYPE html>
<html lang="it">

    <head>
        <title>
            <?php echo $title ?>
        </title>
        <meta charset="UTF-8">
        <meta name="author" content="Samuele Longhin">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?= $BASE_PATH ?>public/prism.css">
        <link rel="stylesheet" href="<?= $BASE_PATH ?>public/app.css">

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
            integrity="sha512-dPXYcDub/aeb08c63jRq/k6GaKccl256JQy/AnOq7CAnEZ9FzSL9wSbcZkMp4R26vBsMLFYH4kQ67/bbV8XaCQ=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />
        <?php
        global $ANALYTICS_ID, $BASE_PATH, $cookieConsent;

        // if ($ANALYTICS_ID) {
        // Se il consenso è dato, includo il codice di Google Analytics
        if ($cookieConsent) { ?>

            <!-- Google tag (gtag.js) -->
            <script async src="https://www.googletagmanager.com/gtag/js?id=G-M7VB17TKBW"></script>
            <script>
                window.dataLayer = window.dataLayer || [];
                function gtag() { dataLayer.push(arguments); }
                gtag('js', new Date());

                gtag('config', <?= json_encode($ANALYTICS_ID) ?>);
            </script>
        <?php } else {
            // echo "no cookie consent";
        }
        // }else{
        //     echo "no analytics id";
        // }
        
        ?>
    </head>