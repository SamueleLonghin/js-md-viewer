/*
 * Gestione del consenso ai cookie e inizializzazione di Google Analytics
 * in ambiente totalmente client-side.
 */

(function () {
    const COOKIE_NAME = "cookie_consent";

    function setCookieConsent() {
        const expires = new Date();
        expires.setFullYear(expires.getFullYear() + 1);
        document.cookie = `${COOKIE_NAME}=accepted; expires=${expires.toUTCString()}; path=/`;
        document.dispatchEvent(new CustomEvent("cookie-consent-granted"));
    }

    function hasCookieConsent() {
        return document.cookie.split(";").some((cookie) => cookie.trim().startsWith(`${COOKIE_NAME}=accepted`));
    }

    function showBanner() {
        const banner = document.getElementById("cookie-consent-banner");
        if (!banner) {
            return;
        }

        banner.classList.remove("d-none");
        const button = banner.querySelector("#accept-cookies");
        if (button) {
            button.addEventListener(
                "click",
                () => {
                    setCookieConsent();
                    banner.remove();
                },
                { once: true }
            );
        }
    }

    function enableAnalytics(measurementId) {
        if (!measurementId) {
            return;
        }

        const script = document.createElement("script");
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${measurementId}`;
        document.head.appendChild(script);

        script.onload = function () {
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }

            window.gtag = gtag;
            gtag("js", new Date());
            gtag("config", measurementId, { anonymize_ip: true });
        };
    }

    function initAnalytics(measurementId) {
        if (!measurementId) {
            console.warn("Google Analytics ID non configurato.");
            return;
        }

        const startAnalytics = () => enableAnalytics(measurementId);

        if (hasCookieConsent()) {
            startAnalytics();
        } else {
            showBanner();
            document.addEventListener("cookie-consent-granted", startAnalytics, { once: true });
        }
    }

    window.Analytics = {
        init: initAnalytics,
        hasConsent: hasCookieConsent,
        setConsent: setCookieConsent,
        showBanner,
    };
})();