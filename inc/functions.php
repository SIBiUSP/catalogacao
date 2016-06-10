<?php    

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