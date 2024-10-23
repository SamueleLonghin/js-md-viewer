function previewCode(base) {
    // Creo l'anteprima
    const previewContainer = document.createElement('div');
    previewContainer.classList.add('render-code');

    // Creazione della griglia per visualizzare codice e anteprima
    const flexContainer = document.createElement('div');
    flexContainer.classList.add('code-preview-flex');

    // Sostituisci il blocco originale con il flexContainer
    base.replaceWith(flexContainer)

    // Agiungo entrambi i figli ovvero quello che prima era il blocco e l'anteprima
    flexContainer.appendChild(base)
    flexContainer.appendChild(previewContainer);


    return previewContainer
}


function previewHTML(base, block) {
    // Ottengo il blocco per la preview
    previewContainer = previewCode(base);
    // Inserisco il codice dentro al blocco
    previewContainer.innerHTML = block.textContent;
}
function previewPython(preElement, codeElement) {

    // Crea il pulsante "Esegui"
    const runButton = document.createElement('button');
    runButton.classList.add('run-button');
    runButton.textContent = 'Esegui';


    // Crea un div per mostrare l'output
    const outputDiv = document.createElement('div');
    outputDiv.classList.add('code-output');

    // Funzione per eseguire il codice Python quando il pulsante viene cliccato
    runButton.addEventListener('click', function () {
        const code = codeElement.textContent.trim(); // Recupera il codice dal blocco <code>
        // Costruisci il payload per l'API
        const payload = {
            language: "python",
            version: "3.10.0",
            files: [
                {
                    name: "main.py",
                    content: code
                }
            ]
        };

        // Esegui la richiesta POST alla API di Piston
        fetch('https://emkc.org/api/v2/piston/execute', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload) // Converte il payload in JSON
        })
            .then(response => response.json()) // Converte la risposta in JSON
            .then(data => {
                // Mostra l'output dell'esecuzione
                if (data.run && data.run.output) {
                    outputDiv.textContent = `Output:\n${data.run.output}`;

                } else {
                    outputDiv.textContent = 'Nessun output o errore.';
                }
            })
            .catch(error => {
                // Gestisce eventuali errori
                console.error('Errore:', error);
                outputDiv.textContent = 'Si è verificato un errore durante l\'esecuzione.';
            });

    });

    // Creo il blocco per la preview
    const previewContainer = previewCode(preElement)

    // Inserisce il pulsante e il div dell'output dopo il blocco <pre>
    previewContainer.append(runButton, outputDiv);
}

// Funzione per visualizzare il contenuto Markdown
function displayMarkdownContent(content) {
    const output = document.getElementById('output');
    output.innerHTML = '';
    const htmlContent = marked.parse(content);

    const container = document.createElement('div');
    container.classList.add('markdown');
    container.innerHTML = htmlContent;

    // Genera l'elenco dei capitoli basato sugli header del markdown
    const chapterList = document.getElementById('chapterList');
    chapterList.innerHTML = '';
    container.querySelectorAll('h1, h2, h3').forEach((header, index) => {

        const chapterLink = document.createElement('a');
        chapterLink.classList.add('text-reset', 'chapter-link', 'header-' + header.tagName.toLowerCase());
        chapterLink.textContent = header.textContent;
        header.id = encodeURIComponent(header.textContent)
        chapterLink.href = "#" + header.id;


        chapterList.appendChild(chapterLink);
    });

    // Evidenziazione dei blocchi di codice
    output.appendChild(container);
    container.querySelectorAll('pre code').forEach((block) => {

        // Prendo l'elemento radice            
        base = block.parentElement;
        base.classList.add("language")

        if (preview) {
            if (block.classList.contains('language-html') && !block.innerHTML.includes("style")) {
                previewHTML(base, block);
            }
            if (block.classList.contains('language-python')) {
                previewPython(base, block);
            }
        }
        Prism.highlightElement(block);

    });

    container.querySelectorAll('code').forEach((inlineCode) => {
        if (/^<.*>$/.test(inlineCode.textContent.trim())) {
            inlineCode.classList.add(language); // Aggiungi la classe di linguaggio appropriata
        }
        Prism.highlightElement(inlineCode);
    });
}
