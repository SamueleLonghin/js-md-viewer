<?php

// Funzione per caricare il file config.json e restituire i dati
function loadConfig() {
    $configPath = 'public/topics.json'; // Assumi che il file config.json si trovi nella stessa cartella di index.php
    if (file_exists($configPath)) {
        $configContent = file_get_contents($configPath);
        return json_decode($configContent, true); // Decodifica il JSON in un array PHP
    } else {
        return null;
    }
}


// Funzione per ottenere il file markdown dal config.json
function getMarkdownPath($config, $macroargomento, $argomento) {
    if (isset($config['topics'][$macroargomento]) && isset($config['topics'][$macroargomento]['chapters'][$argomento])) {
        $fileName = $config['topics'][$macroargomento]['chapters'][$argomento]['file']; // Prendi il nome del file markdown
        $topicFolder = $config['topics'][$macroargomento]['folder']; // Prendi il nome del file markdown
        $resourcesFolder = $config['resources-folder']; // Prendi la cartella delle risorse
        return '.'. $resourcesFolder . $topicFolder . '/' . $fileName; // Restituisci il percorso completo del file
    }
    return null;
}



// Funzione per caricare il contenuto del file markdown
function fetchMarkdownContent($path) {
    if (file_exists($path)) {
        return file_get_contents($path); // Ritorna il contenuto del file markdown
    } else {
        return 'Errore: file non trovato! ('.$path.")";
    }
}


// Funzione per analizzare l'URL e estrarre macroargomento e argomento
function getRoute() {
    // Ottieni la parte dell'URL dopo il dominio
    $requestUri = $_SERVER['REQUEST_URI'];

    // Rimuovi eventuali query string (esempio: ?key=value)
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Divide l'URL in segmenti usando lo slash come separatore
    $parts = explode('/', trim($path, '/'));

    // Se ci sono almeno due parti, il primo è il macroargomento e il secondo è l'argomento
    if (count($parts) >= 2) {
        return [
            'macroargomento' => urldecode($parts[0]),  // Prima parte dell'URL
            'argomento' => urldecode($parts[1])        // Seconda parte dell'URL
        ];
    }else {
        if(count($parts) == 1){
            return ['macroargomento' => urldecode($parts[0])];
        }
    }

    // Se l'URL non è completo, restituisci null
    return null;
}
function valOrDefault($dict, $key, $default = null){
    return isset($dict[$key]) ? $dict[$key] : $default;
}



// Carica il file di configurazione
$config = loadConfig();

if (!$config) {
    die('Errore: Impossibile caricare il file di configurazione.');
}

// Estrai macroargomento e argomento dall'URL

$BASE_PATH = valOrDefault($config,"base-path",'/') ;

$route = getRoute();
$markdownContent = '';
$preview = valOrDefault($config,"preview",false);
$language = valOrDefault($config,"language",null);

$macroargomento = valOrDefault($route, 'macroargomento');


if($macroargomento && isset($config['topics'][$macroargomento])){
    $argomento = valOrDefault($route,'argomento');
    
    $preview = valOrDefault($config['topics'][$macroargomento],"preview",$preview);
    $language = valOrDefault($config['topics'][$macroargomento],"language",$language);
    
    if($argomento && isset($config['topics'][$macroargomento]['chapters'][$argomento])){
        // Ottieni il percorso del file markdown dal config.json
        $markdownPath = getMarkdownPath($config, $macroargomento, $argomento);
        
        // Carica il contenuto markdown se il percorso è valido
        if ($markdownPath) {
            $markdownContent = fetchMarkdownContent($markdownPath);
        } else {
            $markdownContent = 'Errore: Argomento non trovato nel file di configurazione.';
        }
        require 'view.php';

        exit;
    }
    else {
        include 'scelta_argomento.php';
        exit;
    }
} else {
    include "scelta_macro.php";
    exit;
}


?>