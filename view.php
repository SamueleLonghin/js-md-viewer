<?php
global $macroargomento,$argomento,$config,$BASE_PATH,$preview,$language,$markdownContent;

    require "head.php";
?>


    <body>
        <div class="view-container">

            <button id="sidebar-toggle"> ☰ </button>
            <div id="sidebar">
                <div class="file-section">
                    <h2>Documenti</h2>
                    <div id="fileList">
                    <?php
                        if($macroargomento){
                            $macroArgomentoLink = urlencode($macroargomento);

                            foreach($config['topics'][$macroargomento]['chapters'] as $label => $data){
                                $link = urlencode($label);
                                echo "<a href='/{$macroArgomentoLink}/$link' class='text-reset file-link'> {$label} </a>";
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

                <?php require "footer.php"?>
            </div>
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