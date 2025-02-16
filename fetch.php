<?php
include "support.php";

// Carica il file di configurazione
$config = loadConfig();

if (!$config) {
    die('Errore: Impossibile caricare il file di configurazione.');
}

// Path nella quale sono posizionati i file md da visualizzare
$resourcesFolder = valOrDefault($config, "resources-folder", '/risorse');






// Inserisci la chiave segreta che hai usato su GitHub
$secret = valOrDefault($config, "webhook-secret", 'secret');

// Legge i dati ricevuti dal webhook
$payload = file_get_contents('php://input');
$headers = getallheaders();

// Verifica che la richiesta provenga effettivamente da GitHub
if (!isset($headers['X-Hub-Signature-256'])) {
    http_response_code(400);
    die('Signature missing');
}

// Calcola l'hash della richiesta con la chiave segreta
$signature = 'sha256=' . hash_hmac('sha256', $payload, $secret);

// Confronta l'hash ricevuto con quello calcolato
if (!hash_equals($signature, $headers['X-Hub-Signature-256'])) {
    http_response_code(403);
    die('Invalid signature');
}

// Decodifica il payload JSON
$data = json_decode($payload, true);

// Controlla che sia un evento push
if ($data['ref'] === 'refs/heads/main') {
    // Esegui il pull della repository
    $output = shell_exec("cd $resourceFolder && git pull origin main 2>&1");
    
    // Salva l'output nel log
    file_put_contents('/var/log/github-webhook.log', date('Y-m-d H:i:s') . " - " . $output . "\n", FILE_APPEND);
    
    echo "Git pull eseguito con successo.";
} else {
    echo "Non è un push sulla branch principale.";
}
?>
