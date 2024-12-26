<?php
include "support.php";

// Carica il file di configurazione
$config = loadConfig();

if (!$config) {
    die('Errore: Impossibile caricare il file di configurazione.');
}

// Path nella quale è posizionato il servizio (nel caso vengano utilizzati .htaccess per redirect può tornare utile)
$BASE_PATH = valOrDefault($config, "base-path", '/');

// Id google analytics
$ANALYTICS_ID = valOrDefault($config, "analytics-id");

// Path nella quale sono posizionati i file md da visualizzare
$resourcesFolder = valOrDefault($config, "resources-folder", '/risorse');

// Estrai macroargomento e argomento dall'URL
list($macroargomento, $argomento) = getRoute();

$markdownContent = '';
// Enable preview
$preview = valOrDefault($config, "preview", false);

// Default language
$language = valOrDefault($config, "language");

// Dizionario contenente i corsi
// $topics = valOrDefault($config, "topics", []);
$topics = loadTopics($resourcesFolder);

if ($macroargomento && isset($topics[$macroargomento])) {

    $preview = valOrDefault($topics[$macroargomento], "preview", $preview);
    $language = valOrDefault($topics[$macroargomento], "language", $language);

    if (!array_key_exists('chapters', $topics[$macroargomento])) {
        $topicFolder = $resourcesFolder . $topics[$macroargomento]['folder'];
        $topics[$macroargomento]['chapters'] = loadChapters($topicFolder);
    }
    if ($argomento && isset($topics[$macroargomento]['chapters'][$argomento]) && (!isset($topics[$macroargomento]['chapters'][$argomento]['visibility']) || $topics[$macroargomento]['chapters'][$argomento]['visibility'] != 'none')) {
        // Ottieni il percorso del file markdown dal topics.json
        $fileFolderAndName = getFileFolderAndName($topics, $resourcesFolder, $macroargomento, $argomento);

        $preview = valOrDefault($topics[$macroargomento]['chapters'][$argomento], "preview", $preview);
        $language = valOrDefault($topics[$macroargomento]['chapters'][$argomento], "language", $language);

        // Carica il contenuto markdown se il percorso è valido
        if ($fileFolderAndName) {
            $markdownPath = implode("/", $fileFolderAndName);
            $markdownContent = getMarkdownContent($markdownPath);
            $resourcePath = $fileFolderAndName[0];
            // Se il file è posizionato in una cartella (cartella dell'applicazione web) pubblica vado a rimpiazzare i link con i percorsi effettivi
            if (str_starts_with($resourcePath, "./")) {
                $resourcePath = substr($resourcePath, 2);
                $markdownContent = addLinks($markdownContent, $BASE_PATH . $resourcePath);
            }
        } else {
            $markdownContent = 'Errore: Argomento non trovato nel file di configurazione.';
        }
        $title = htmlspecialchars($argomento);
        require 'view.php';
    } else {
        $title = htmlspecialchars($topics[$macroargomento]['label']);
        include 'scelta_argomento.php';
    }
} else {
    $title = htmlspecialchars("Corsi disponibili");
    include "scelta_macro.php";
}
exit;
