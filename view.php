<?php
global $macroargomento,$argomento,$config,$BASE_PATH,$preview,$language,$markdownContent;

?>
<!DOCTYPE html>
<html lang="it">

<?php require "head.php";?>


    <body>

        <button id="sidebar-toggle"> ☰ </button>
        <div id="sidebar">
            <div class="file-section">
                <h2>Documenti</h2>
                <div id="fileList">
                <?php
                    if($macroargomento){
                        foreach($config['topics'][$macroargomento]['chapters'] as $label => $data){
                            // $link = $data['file']
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