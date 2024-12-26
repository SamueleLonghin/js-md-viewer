document.addEventListener("DOMContentLoaded", function () {
    // Recupera l'ID di tracciamento dalle variabili di ambiente PHP
    const GA_MEASUREMENT_ID = window.GA_MEASUREMENT_ID || null;

    // Se l'ID non è presente, esci
    if (!GA_MEASUREMENT_ID) {
        console.warn("Google Analytics ID non configurato.");
        return;
    }

    // Funzione per verificare se il consenso ai cookie è stato dato
    function hasCookieConsent() {
        return document.cookie.split(";").some((cookie) => {
            return cookie.trim().startsWith("cookie_consent=accepted");
        });
    }

    // Funzione per impostare il consenso ai cookie
    function setCookieConsent() {
        const expires = new Date();
        expires.setFullYear(expires.getFullYear() + 1); // 1 anno di validità
        document.cookie = `cookie_consent=accepted; expires=${expires.toUTCString()}; path=/`;
    }

    // Funzione per mostrare il banner di consenso
    function showConsentBanner() {
        const banner = document.createElement("div");
        banner.id = "cookie-consent-banner";
        banner.style.position = "fixed";
        banner.style.bottom = "0";
        banner.style.width = "100%";
        banner.style.backgroundColor = "#000";
        banner.style.color = "#fff";
        banner.style.padding = "10px";
        banner.style.textAlign = "center";
        banner.style.zIndex = "1000";
        banner.innerHTML = `
            Questo sito utilizza cookie per raccogliere dati statistici. 
            <button id="accept-cookies" style="margin-left: 10px; padding: 5px 10px; cursor: pointer;">
                Accetta
            </button>
        `;
        document.body.appendChild(banner);

        document.getElementById("accept-cookies").addEventListener("click", function () {
            setCookieConsent();
            document.body.removeChild(banner);
            enableAnalytics();
        });
    }

    // Funzione per abilitare Google Analytics
    function enableAnalytics() {
        // Carica il file gtag.js
        const script = document.createElement("script");
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${GA_MEASUREMENT_ID}`;
        document.head.appendChild(script);

        // Configura Google Analytics
        script.onload = function () {
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            window.gtag = gtag;
            gtag("js", new Date());
            gtag("config", GA_MEASUREMENT_ID, { anonymize_ip: true });
        };
    }

    // Controlla se il consenso è già stato dato
    if (hasCookieConsent()) {
        enableAnalytics();
    } else {
        showConsentBanner();
    }
});
