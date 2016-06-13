<?php
    $host=$_REQUEST[host];
    $query=$_REQUEST[query];
    $isbn=$_REQUEST[isbn];
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
if (count($host) == 0) {
    echo 'Nenhuma busca';
} elseif (!empty($isbn)) {
    echo '<h2>Resultados de busca pelo ISBN: ' . htmlspecialchars($isbn) . '</h2>';
    $isbn_query='@attr 1=7 '.$isbn.'';
    for ($i = 0; $i < $num_hosts; $i++) {
        $id[] = yaz_connect($host[$i]);
        yaz_syntax($id[$i], "usmarc");
        yaz_range($id[$i], 1, 10);
        yaz_search($id[$i], "rpn", $isbn_query);
    }
    yaz_wait();
    for ($i = 0; $i < $num_hosts; $i++) {
        echo '<div class="ui divider"></div>';
        switch ($host[$i]) {
            case "dedalus.usp.br:9991/usp01":
                echo '<h3>USP - DEDALUS:';
                break;
            case "z3950.loc.gov:7090/voyager":
                echo '<h3>Library of Congress:';
                break;
            case "168.176.5.96:9991/SNB01":
                echo '<h3>UNAL - Universidade Nacional de Colombia:';
                break;
            case "athena.biblioteca.unesp.br:9992/uep01":
                echo '<h3>UNESP - Athena:';
                break;
            case "library.ox.ac.uk:210/aleph":
                echo '<h3>University of Oxford:';
                break;
            case "ringding.law.yale.edu:210/INNOPAC":
                echo '<h3>Yale Law School:';
                break;
            case "newton.lib.cam.ac.uk:7090/VOYAGER":
                echo '<h3>University of Cambridge:';
                break;
        }
        
        
        

        $error = yaz_error($id[$i]);
        if (!empty($error)) {
            echo "Error: $error";
        } else {
            $hits = yaz_hits($id[$i]);
            echo " $hits resultado(s) </h3>";
        }

        for ($p = 1; $p <= 10; $p++) {
            $rec_download = yaz_record($id[$i], $p, "raw");
            $rec = yaz_record($id[$i], $p, "string");
            if (empty($rec)) continue;

            $result_record = parse_usmarc_string($rec);

            $rec_id= $i.$p;

            echo '<div><div class="ui top attached tabular menu menu'.$rec_id.'">
<a class="item active" data-tab="first'.$rec_id.'">Resumo</a>
<a class="item" data-tab="second'.$rec_id.'">Registro completo</a>
</div>
<div class="ui bottom attached tab segment active" data-tab="first'.$rec_id.'">
<table class="ui celled table">
<thead>
<tr>
<th>ISBN</th>
<th>Título</th>
<th>Autor</th>
<th>Editora</th>
<th>Local</th>
<th>Ano</th>
<th>Edição</th>
<th>Descrição física</th>
<th>Download</th>
</tr>
</thead>
<tbody>
<tr>
<td>'.$result_record[isbn].'</td>
<td>'.$result_record[title].'</td>
<td>'.$result_record[author].'</td>
<td>'.$result_record[publisher].'</td>
<td>'.$result_record[pub_place].'</td>
<td>'.$result_record[pub_date].'</td>
<td>'.$result_record[edition].'</td>
<td>'.$result_record[extent].'</td>
<td><button  class="ui blue label" onclick="SaveAsFile(\''.addslashes($rec_download).'\',\'record.mrc\',\'text/plain;charset=utf-8\')">Baixar MARC</button></td>
</tr>
</tbody>
</table>
</div>
<div class="ui bottom attached tab segment" data-tab="second'.$rec_id.'">
<b>'.$p.'</b>
'.nl2br($rec).'

</div></div><br/><br/>';
            echo '<script>
                        $(\'.menu'.$rec_id.' .item\')
                        .tab();
                  </script>';

        }
    }
    
} else {
    echo 'You searched for ' . htmlspecialchars($query) . '<br />';
    for ($i = 0; $i < $num_hosts; $i++) {
        $id[] = yaz_connect($host[$i]);
        yaz_syntax($id[$i], "usmarc");
        yaz_range($id[$i], 1, 10);
        yaz_search($id[$i], "rpn", $query);
    }
    yaz_wait();
    for ($i = 0; $i < $num_hosts; $i++) {
        echo '<hr />' . $host[$i] . ':';
        $error = yaz_error($id[$i]);
        if (!empty($error)) {
            echo "Error: $error";
        } else {
            $hits = yaz_hits($id[$i]);
            echo "Result Count $hits";
        }
        echo '<dl>';
        for ($p = 1; $p <= 10; $p++) {
            $rec = yaz_record($id[$i], $p, "string");
        if (empty($rec)) continue;
            echo "<dt><b>$p</b></dt><dd>";
            echo nl2br($rec);
            echo "</dd>";

            parse_usmarc_string($rec);
        }
        echo '</dl>';
    }
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