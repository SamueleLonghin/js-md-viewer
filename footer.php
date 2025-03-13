<?php
global $ANALYTICS_ID, $BASE_PATH, $cookieConsent;

if ($ANALYTICS_ID && !$cookieConsent) {
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

        <!-- 
        <script>
            window.GA_MEASUREMENT_ID = <?= json_encode($ANALYTICS_ID) ?>
        </script>
        <script src='<?= $BASE_PATH ?>public/analytics.js'></script> -->
    <?php }
    // Se il consenso non è dato, mostro il banner per accettare i cookies
    else { ?>
        <div class="cookie-consent-container" id='cookie-consent-banner'>
            Questo sito utilizza cookie per raccogliere dati statistici.
            <button id='accept-cookies'>Accetta</button>
            <a href="<?= $BASE_PATH ?>public/privacy-policy.html">Privacy Policy</a>
        </div>
    <?php }
}

?>
<footer class="text-center sticky-bottom bg-white pt-1">
    <h2>Credits</h2>
    <p>Creato da Samuele Longhin</p>
</footer>