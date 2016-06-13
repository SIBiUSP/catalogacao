<?php    


function query_isbn($isbn,$num_hosts,$host) {
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
            case "lx2.loc.gov:210/LCDB":
                echo '<h3>Library of Congress:';
                break;
            case "marte.biblioteca.upm.es:2200":
                echo '<h3>Universidade de Madrid:';
                break;
            case "sirsi.library.utoronto.ca:2200":
                echo '<h3>University of Toronto:';
                break;                
                
            case "ilsz3950.nlm.nih.gov:7091/VOYAGER":
                echo '<h3>U.S. National Library of Medicine (NLM):';
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
            case "zcat.libraries.psu.edu:2200":
                echo '<h3>Penn State University:';
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
            
            if ($host[$i] == "lx2.loc.gov:210/LCDB") {
                $rec_download = yaz_record($id[$i], $p, "raw");
                
            } else {
                $rec_download = yaz_record($id[$i], $p, "raw");
            }
            
            
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
}

function query_doi($doi) {
    $url = "https://api.crossref.org/v1/works/http://dx.doi.org/$doi";
    $json = file_get_contents($url);
    $data = json_decode($json, TRUE);
    
    echo '<h2>Resultados de busca pelo DOI: ' . htmlspecialchars($data["message"]["DOI"]) . '</h2>';
    
    echo '<b>Título:</b> '.$data["message"]["title"][0].'</br>';
    foreach ($data["message"]["author"] as $authors) {
        echo '<b>Autor:</b> '.$authors["family"].', '.$authors["given"].'</br>';
    }
    
    echo '<b>Periódico:</b> '.$data["message"]["container-title"][0].'</br>';
    echo '<b>ISSN:</b> '.$data["message"]["ISSN"][0].'</br>';
    
    
    
    $author_number = count($data["message"]["author"]);
    

    $record = [];
    $record[] = "000000001 FMT   L BK";
    $record[] = "000000001 LDR   L ^^^^^nam^^22^^^^^Ia^4500";
    $record[] = "000000001 BAS   L \$\$a04";
    $record[] = "000000001 008   L ^^^^^^s^^^^^^^^^^^^^^^^^^^^^^000^0^^^^^d";
    $record[] = '000000001 0247  L \$\$a'.$data["message"]["DOI"].'\$\$2DOI';
    $record[] = "000000001 040   L \$\$aUSP/SIBI";
    $record[] = '000000001 0410  L \$\$a';
    $record[] = '000000001 044   L \$\$a';
    
    if ($author_number > 1) {
        if (!empty($data["message"]["author"][0]["affiliation"])) {
            $record[] = '000000001 1001  L \$\$a'.$data["message"]["author"][0]["family"].', '.$data["message"]["author"][0]["given"].'\$\$8'.$data["message"]["author"][0]["affiliation"].'';
        } else {
            $record[] = '000000001 1001  L \$\$a'.$data["message"]["author"][0]["family"].', '.$data["message"]["author"][0]["given"].'';
        }
        } else {
        for ($i = 1; $i < $author_number; $i++) {
             if (!empty($data["message"]["author"][$i]["affiliation"])) {
                 $record[] = '000000001 7001  L \$\$a'.$data["message"]["author"][$i]["family"].', '.$data["message"]["author"][$i]["given"].'\$\$8'.$data["message"]["author"][$i]["affiliation"].'';
         } else {   
            $record[] = '000000001 7001  L \$\$a'.$data["message"]["author"][$i]["family"].', '.$data["message"]["author"][$i]["given"].'';
        }
    
        $record[] = '000000001 1001  L \$\$a'.$data["message"]["author"][0]["family"].', '.$data["message"]["author"][0]["given"].'';
    }}
    
    if (!empty($data["message"]["container-title"][1])) {
        $record[] = '000000001 7730  L \$\$t'.$data["message"]["container-title"][1].'\$\$x'.$data["message"]["ISSN"][0].'\$\$hv.'.$data["message"]["volume"].', n.'.$data["message"]["issue"].', p.'.$data["message"]["page"].', '.$data["message"]["issued"]["date-parts"][0][0].'';
    } else {
        $record[] = '000000001 7730  L \$\$t'.$data["message"]["container-title"][0].'\$\$x'.$data["message"]["ISSN"][0].'\$\$hv.'.$data["message"]["volume"].', n.'.$data["message"]["issue"].', p.'.$data["message"]["page"].', '.$data["message"]["issued"]["date-parts"][0][0].'';
    }
    
    $record[] = '000000001 24510 L \$\$a'.$data["message"]["title"][0].'';
    $record[] = '000000001 260   L \$\$b'.$data["message"]["publisher"].'\$\$c'.$data["message"]["issued"]["date-parts"][0][0].'';
    $record[] = '000000001 300   L \$\$ap.'.$data["message"]["page"].'';
    $record[] = '000000001 500   L \$\$aDisponível em:<http://dx.doi.org'.$data["message"]["DOI"].'>. Acesso em:';
    
    foreach ($data["message"]["funder"] as $funder) {
        if (!empty($funder["award"])){
          $record[] = '000000001 536   L \$\$a'.$funder["name"].'\$\$f'.$funder["award"][0].'';  
        } else {
            $record[] = '000000001 536   L \$\$a'.$funder["name"].'';
        }    
    }
        
    $record[] = '000000001 6507  L \$\$a';
    $record[] = '000000001 8564  L \$\$zClicar sobre o botão para acesso ao texto completo\$\$uhttp://dx.doi.org'.$data["message"]["DOI"].'\$\$3DOI';
    $record[] = '000000001 945   L \$\$aP\$\$bARTIGO DE PERIODICO\$\$c01\$\$j'.$data["message"]["issued"]["date-parts"][0][0].'\$\$l';
    $record[] = '000000001 946   L \$\$a';
    
    if (!empty($cursor_ris["result"][0]['year'])) {
    $record[] = "PY  - ".$cursor_ris["result"][0]['year']."";
    }
    
    $record_blob = implode("\\n", $record);
    
    echo '<h3>Exportar</h3>';
    echo '<button  class="ui blue label" onclick="SaveAsFile(\''.$record_blob.'\',\'aleph.seq\',\'text/plain;charset=utf-8\')">Baixar ALEPH Sequencial</button>';
    
    echo '<br/><br/><br/>';
    

    echo '<br/><br/><br/>';
    

}

function parse_usmarc_string($record){
        $ret = array();
        // there was a case where angle brackets interfered
        $record = str_replace(array("<", ">"), array("",""), $record);
       // $record = utf8_decode($record);
        // split the returned fields at their separation character (newline)
        $record = explode("\n",$record);
        //examine each line for wanted information (see USMARC spec for details)
        foreach($record as $category){
            // subfield indicators are preceded by a $ sign
            $parts = explode("$", $category);
            // remove leading and trailing spaces
            array_walk($parts, "custom_trim");
            // the first value holds the field id,
            // depending on the desired info a certain subfield value is retrieved
            switch(substr($parts[0],0,3)){
                case "008" : $ret["language"] = substr($parts[0],39,3); break;
                case "020" : $ret["isbn"] = get_subfield_value($parts,"a"); break;
                case "022" : $ret["issn"] = get_subfield_value($parts,"a"); break;
                case "100" : $ret["author"] = get_subfield_value($parts,"a"); break;
                case "245" : $ret["title"] = get_subfield_value($parts,"a");
                             $ret["subtitle"] = get_subfield_value($parts,"b"); break;
                case "250" : $ret["edition"] = get_subfield_value($parts,"a"); break;
                case "260" : $ret["pub_date"] = get_subfield_value($parts,"c");
                             $ret["pub_place"] = get_subfield_value($parts,"a");
                             $ret["publisher"] = get_subfield_value($parts,"b"); break;
                case "300" : $ret["extent"] = get_subfield_value($parts,"a");
                             $ext_b = get_subfield_value($parts,"b");
                             $ret["extent"] .= ($ext_b != "") ? (" : " . $ext_b) : "";
                             break;
                case "490" : $ret["series"] = get_subfield_value($parts,"a"); break;
                case "502" : $ret["diss_note"] = get_subfield_value($parts,"a"); break;
                case "700" : $ret["editor"] = get_subfield_value($parts,"a"); break;
            }
        }
        return $ret;
    }
     
    // fetches the value of a certain subfield given its label
    function get_subfield_value($parts, $subfield_label){
        $ret = "";
        foreach ($parts as $subfield)
            if(substr($subfield,0,1) == $subfield_label)
                $ret = substr($subfield,2);
        return $ret;
    }
     
    // wrapper function for trim to pass it to array_walk
    function custom_trim(& $value, & $key){
        $value = trim($value);
    }

?>