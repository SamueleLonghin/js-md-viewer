<?php

// Funzione per caricare il file topics.json e restituire i dati
function loadConfig()
{
    $configPath = 'risorse/topics.json'; // Assumi che il file topics.json si trovi nella stessa cartella di index.php
    if (file_exists($configPath)) {
        $configContent = file_get_contents($configPath);
        return json_decode($configContent, true); // Decodifica il JSON in un array PHP
    } else {
        return null;
    }
}

function getFileFolderAndName($config, $macroargomento, $argomento){
    if (isset($config['topics'][$macroargomento]['chapters'][$argomento])) {
        $fileName = $config['topics'][$macroargomento]['chapters'][$argomento]['file']; // Prendi il nome del file markdown
        $topicFolder = $config['topics'][$macroargomento]['folder']; // Prendi il nome del file markdown
        $resourcesFolder = $config['resources-folder']; // Prendi la cartella delle risorse
        return ['.' . $resourcesFolder . $topicFolder, $fileName]; // Restituisci il percorso completo del file
    }
    return null;
}

// Funzione per caricare il contenuto del file markdown
function getMarkdownContent($path)
{
    if (file_exists($path)) {
        $content = file_get_contents($path); // Ritorna il contenuto del file markdown
        $type = pathinfo($path, PATHINFO_EXTENSION);

        if($type == "ipynb"){
            require "converters.php";
            $content = convertNotebookToMd($content);
        }
        return $content;
    } else {
        return 'Errore: file non trovato! (' . $path . ")";
    }
}

function addLinks($md, $basePath){
    return str_replace("%%BASE_FOLDER%%", $basePath, $md);
}


// Funzione per analizzare l'URL e estrarre macroargomento e argomento
function getRoute()
{
    // Ottieni la parte dell'URL dopo il dominio
    $requestUri = $_SERVER['REQUEST_URI'];

    // Rimuovi eventuali query string (esempio: ?key=value)
    $path = parse_url($requestUri, PHP_URL_PATH);

    // Divide l'URL in segmenti usando lo slash come separatore
    $parts = explode('/', trim($path, '/'));

    // Se ci sono almeno due parti, il primo è il macroargomento e il secondo è l'argomento
    if (count($parts) >= 2) 
        return [urldecode($parts[0]),urldecode($parts[1])];
    // Se ce c'è una soltanto è il macroargomento
    else if(count($parts) == 1)
        return [urldecode($parts[0]),null];
    // Se è vuoto niente
    return [null,null];
}

function valOrDefault($dict, $key, $default = null)
{
    return isset($dict[$key]) ? $dict[$key] : $default;
}


// Carica il file di configurazione
$config = loadConfig();

if (!$config) {
    die('Errore: Impossibile caricare il file di configurazione.');
}

// Path nella quale è posizionato il servizio (nel caso vengano utilizzati .htaccess per redirect può tornare utile)
$BASE_PATH = valOrDefault($config, "base-path", '/');

// Estrai macroargomento e argomento dall'URL
list($macroargomento, $argomento) = getRoute();

$markdownContent = '';
// Enable preview
$preview = valOrDefault($config, "preview", false);
// Default language
$language = valOrDefault($config, "language");


if ($macroargomento && isset($config['topics'][$macroargomento])) {

    $preview = valOrDefault($config['topics'][$macroargomento], "preview", $preview);
    $language = valOrDefault($config['topics'][$macroargomento], "language", $language);

    if ($argomento && isset($config['topics'][$macroargomento]['chapters'][$argomento])) {
        // Ottieni il percorso del file markdown dal topics.json
        $fileFolderAndName = getFileFolderAndName($config, $macroargomento, $argomento);

        // Carica il contenuto markdown se il percorso è valido
        if ($fileFolderAndName) {
            $markdownPath = implode("/", $fileFolderAndName);
            $markdownContent = getMarkdownContent($markdownPath);
            $markdownContent = addLinks($markdownContent,$BASE_PATH.$fileFolderAndName[0]);
        } else {
            $markdownContent = 'Errore: Argomento non trovato nel file di configurazione.';
        }
        $title = htmlspecialchars($argomento);
        require 'view.php';
    } else {
         
        $title = htmlspecialchars($config['topics'][$macroargomento]['label']);
        include 'scelta_argomento.php';
    }
} else {
    $title = htmlspecialchars("Corsi disponibili");
    include "scelta_macro.php";
}
exit;
