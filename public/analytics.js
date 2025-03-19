// Recupera l'ID di tracciamento dalle variabili di ambiente PHP
const GA_MEASUREMENT_ID = window.GA_MEASUREMENT_ID || null;

// Funzione per impostare il consenso ai cookie
function setCookieConsent() {
    const expires = new Date();
    expires.setFullYear(expires.getFullYear() + 1); // 1 anno di validità
    document.cookie = `cookie_consent=accepted; expires=${expires.toUTCString()}; path=/`;

    console.log("Cookie consent impostato.");
    location.reload();
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

    console.log("Cookie consent impostato.");
    location.reload();
}

// Funzione per mostrare il banner di consenso
function showConsentBanner() {
    const banner = document.getElementById("cookie-consent-banner");
    banner.style.display = "block";


    document.getElementById("accept-cookies").addEventListener("click", function () {
        setCookieConsent();
        location.reload();
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


// Se l'ID non è presente, esci
if (!GA_MEASUREMENT_ID) {
    console.warn("Google Analytics ID non configurato.");
} else {
    console.log("Google Analytics ID: " + GA_MEASUREMENT_ID);

    // Controlla se il consenso è già stato dato
    if (hasCookieConsent()) {
        enableAnalytics();
    } else {
        console.log("Cookie consent non ancora dato.");
        console.log(this.cookie);
        showConsentBanner();
    }

}