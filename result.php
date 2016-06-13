<?php

$host=$_REQUEST[host];
$query=$_REQUEST[query];

if (!empty($_REQUEST[doi])) {
    $query_type = "doi";
}
if (!empty($_REQUEST[isbn])) {
    $query_type = "isbn";
}

$num_hosts = count($host);

include('inc/functions.php');

?>

<html>
    <head>
        <title>Resultado da Busca - Framework de Catalogação</title>
        <?php include('inc/meta-header.php'); ?>
        <script src="http://cdn.jsdelivr.net/g/filesaver.js"></script>
        <script>
              function SaveAsFile(t,f,m) {
                    try {
                        var b = new Blob([t],{type:m});
                        saveAs(b, f);
                    } catch (e) {
                        window.open("data:"+m+"," + encodeURIComponent(t), '_blank','');
                    }
                }

        </script>        
    </head>
    <body>
        <?php include('inc/barrausp.php'); ?>
        <div class="ui main container">
            <?php include('inc/header.php'); ?>
            <?php include('inc/navbar.php'); ?>
            <div id="main">
<?php
    
switch ($query_type) {
    case "doi":
        query_doi($_REQUEST[doi]);
        break;
    case "isbn":
        query_isbn($_REQUEST[isbn],$num_hosts,$host);
        break;
}
            
?>
            </div>            
        </div>
        <script>
            $('.ui.dropdown')
              .dropdown()
            ;
        </script>
        <?php include('inc/footer.php'); ?>
    </body>
</html>