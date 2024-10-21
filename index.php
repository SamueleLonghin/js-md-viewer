<?php
$BASE_PATH = '/js-md-viewer//';

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
        $fileName = $config['topics'][$macroargomento]['chapters'][$argomento]; // Prendi il nome del file markdown
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
$route = getRoute();
$markdownContent = '';
$preview = valOrDefault($config,"preview",false);
$language = valOrDefault($config,"language",null);


$macroargomento = valOrDefault($route, 'macroargomento');
if($macroargomento){
    $argomento = valOrDefault($route,'argomento');

    $preview = valOrDefault($config['topics'][$macroargomento],"preview",$preview);
    $language = valOrDefault($config['topics'][$macroargomento],"language",$language);

    if($argomento){
        // Ottieni il percorso del file markdown dal config.json
        $markdownPath = getMarkdownPath($config, $macroargomento, $argomento);
        
        // Carica il contenuto markdown se il percorso è valido
        if ($markdownPath) {
            $markdownContent = fetchMarkdownContent($markdownPath);
        } else {
            $markdownContent = 'Errore: Argomento non trovato nel file di configurazione.';
        }
    }
    else {
        $markdownContent = <<<'EOD'
        ## Seleziona un Argomento per iniziare
    
        EOD;
    }
} else {
    $markdownContent = <<<'EOD'
    ## Seleziona un Super Argomento per iniziare

    EOD;
}
?>

<!DOCTYPE html>
<html lang="it">

    <head>

        <title>
            <?php echo isset($argomento) ? htmlspecialchars($argomento) : 'Argomenti'; ?>
        </title>
        <meta charset="UTF-8">
        <meta name="author" content="Samuele Longhin">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="<?=$BASE_PATH?>public/prism.css">
        <link rel="stylesheet" href="<?=$BASE_PATH?>public/site.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    </head>



    <body>

        <button id="sidebar-toggle"> ☰ </button>
        <div id="sidebar">
            <div class="file-section">
                <h2>Documenti</h2>
                <div id="fileList">
                    <?php
                    if($macroargomento){
                        foreach($config['topics'][$macroargomento]['chapters'] as $label => $link){
                            $link = urlencode($label);
                            echo "<a href='/$macroargomento/$link' class='text-reset file-link'> {$label} </a>";
                        }
                    }
                    else{
                        foreach($config['topics'] as $topic => $val){
                            $label = $val['label'];
                            $link = urlencode($topic);
                            echo "<a href='$link' class='text-reset file-link'> {$label} </a>";
                         }
                    }
                    ?>
                </div>
            </div>
            <div class="chapters-section <?= !$argomento?'d-none':'' ?>" >
                <h3>Capitoli</h3>
                <div id="chapterList"></div>
            </div>
        </div>
        <div id="content">
            <div id="output" class="bordered"></div>

            <footer class="text-center">

                <h2>Credits</h2>
                <p>
                    Creato da Samuele Longhin
                </p>

            </footer>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/marked/14.1.2/marked.min.js"></script>
        <script src="<?=$BASE_PATH?>public/prism.js"></script>

        <script>
            preview =  <?= json_encode( $preview )?>;
            language = <?= json_encode( $language )?>;
            content = <?= json_encode($markdownContent) ?>
        </script>


        <script src="<?=$BASE_PATH?>public/md-render.js"></script>
        <script src="<?=$BASE_PATH?>public/script-php.js"></script>
    </body>

</html>