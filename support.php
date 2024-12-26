<?php
// Funzione per caricare il file topics.json e restituire i dati
function loadConfig()
{
    $configPath = 'config.json';    // Assumi che il file topics.json si trovi nella stessa cartella di index.php
    if (file_exists($configPath)) {
        $configContent = file_get_contents($configPath);
        return json_decode($configContent, associative: true); // Decodifica il JSON in un array PHP
    } else {
        return null;
    }
}

function loadTopics($resourcesFolder)
{
    $configPath = $resourcesFolder . '/topics.json';
    if (file_exists($configPath)) {
        $topicsContent = file_get_contents($configPath);
        return json_decode($topicsContent, true); // Decodifica il JSON in un array PHP
    } else {
        return [];
    }
}
function loadChapters($macroArgomentoFolder)
{
    $argomentiPath = $macroArgomentoFolder . '/chapters.json';
    if (file_exists($argomentiPath)) {
        $argomentiContent = file_get_contents($argomentiPath);
        return json_decode($argomentiContent, true); // Decodifica il JSON in un array PHP
    } else {
        return [];
    }
}

function getFileFolderAndName($topics, $resourcesFolder, $macroargomento, $argomento)
{
    if (isset($topics[$macroargomento]['chapters'][$argomento])) {
        $fileName = $topics[$macroargomento]['chapters'][$argomento]['file']; // Prendi il nome del file markdown
        $topicFolder = $topics[$macroargomento]['folder']; // Prendi il nome del file markdown
        return [$resourcesFolder . $topicFolder, $fileName]; // Restituisci il percorso completo del file
    }
    return null;
}

// Funzione per caricare il contenuto del file markdown
function getMarkdownContent($path)
{
    if (file_exists($path)) {
        $content = file_get_contents($path); // Ritorna il contenuto del file markdown
        $type = pathinfo($path, PATHINFO_EXTENSION);

        if ($type == "ipynb") {
            require "converters.php";
            $content = convertNotebookToMd($content);
        }
        return $content;
    } else {
        return 'Errore: file non trovato! (' . $path . ")";
    }
}

function addLinks($md, $basePath)
{
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
        return [urldecode($parts[0]), urldecode($parts[1])];
    // Se ce c'è una soltanto è il macroargomento
    else if (count($parts) == 1)
        return [urldecode($parts[0]), null];
    // Se è vuoto niente
    return [null, null];
}

function valOrDefault($dict, $key, $default = null)
{
    return isset($dict[$key]) ? $dict[$key] : $default;
}