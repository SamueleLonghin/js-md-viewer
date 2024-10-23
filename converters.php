<?php 
function convertNotebookToMd($content)  {

    $notebook = json_decode($content, true);

    if ($notebook === null) {
        echo "Errore nella lettura del file .ipynb!";
        exit;
    }

    $mdOutput = [];

    // Itera attraverso le celle
    foreach ($notebook['cells'] as $cell) {
        if ($cell['cell_type'] === 'markdown') {
            // Celle markdown: aggiungile direttamente
            $mdOutput[] = implode("", $cell['source']);
        } elseif ($cell['cell_type'] === 'code') {
            // Celle di codice: avvolgi in blocchi di codice markdown
            $codeContent = implode("", $cell['source']);
            $mdOutput[] = "```python\n" . $codeContent . "\n```";
            // $mdOutput[] = "\n\n[Prova questo codice su Colab]($colabUrl)";

        }
    }

    // Unisci tutto il risultato in una singola stringa markdown
    return implode("\n\n", $mdOutput);


// Converti il notebook in markdown
}