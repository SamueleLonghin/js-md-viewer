<?php
global $BASE_PATH, $cookieConsent;

if (!$cookieConsent && !$ignoreCookies) {
    ?>
        <script src='<?= $BASE_PATH ?>public/analytics.js'></script>
    <div class="cookie-consent-container" id='cookie-consent-banner'>
        Questo sito utilizza cookie per raccogliere dati statistici.
        <button id='accept-cookies' onclick="setCookieConsent()">Accetta</button>
        <a href="<?= $BASE_PATH ?>public/privacy-policy.html">Privacy Policy</a>
    </div>
<?php }


?>
<footer class="text-center sticky-bottom bg-white pt-1">
    <h2>Credits</h2>
    <p>Creato da Samuele Longhin</p>
</footer>