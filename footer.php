<?php
global $ANALYTICS_ID, $BASE_PATH, $cookieConsent;

if ($ANALYTICS_ID && !$cookieConsent) { ?>
    <div class="cookie-consent-container" id='cookie-consent-banner'>
        Questo sito utilizza cookie per raccogliere dati statistici.
        <button id='accept-cookies'>Accetta</button>
        <a href="<?= $BASE_PATH ?>public/privacy-policy.html">Privacy Policy</a>
    </div>

    <script>
        window.GA_MEASUREMENT_ID = <?= json_encode($ANALYTICS_ID) ?>
    </script>
    <script src='<?= $BASE_PATH ?>public/analytics.js'></script>
<?php }

?>
<footer class="text-center sticky-bottom bg-white pt-1">
    <h2>Credits</h2>
    <p>Creato da Samuele Longhin</p>
</footer>