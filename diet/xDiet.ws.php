<?php

    // DATABASE

    include "./includes/dbConnect.inc.php";
    include "./includes/functions.inc.php";

    // SANITAZE INPUT 

    $clean = array();
    foreach(array_keys($_REQUEST) as $key)
    {
        $clean[$key] = mysqli_real_escape_string($db, $_REQUEST[$key]);
    }
    
    // SEND RESPONSE

    if (isset($clean['action'])) {
        
        header('HTTP/1.1 200 '.getStatusCodeMessage(200));
        header('Content-Type: application/json; charset=utf8');

        $response = "";
        if (empty($clean['action'])) $response .= "{}";

        switch ($clean['action'])
        {
            case "getHelp":

                $sql ="select item, description"
                     ." from diet_help"
                     ." where IDhelp = ? and lang = ?";
                $stmt =$db->prepare($sql);
                $stmt->bind_param("is", $clean['help'], $clean['lang']);
                $stmt->execute();
                $stmt->bind_result($db_item, $db_desc);
                $c = 0;
                $response = '{ "help": [ ';
                while ($stmt->fetch())
                {
                    if ($c) $response .=',';
                    $response .= '{ "item":"'.$db_item.'",'.
                                    ' "text":"'.$db_desc.'"'.
                                        ' }';
                    $c += 1;
                }
                $stmt->close();
                if ($c == 0) $response = '{ "response":"void" }';
                else $response .= " ] } ";

                break;

            case "getProd":
    
                $sql ="select IDproduct, unit, grams, energy, fat, saturates, carbohydrate, sugar, fibre, protein, salt, alcohol"
                     ." from diet_product_data"
                     ." where IDproduct = ?";
                $stmt =$db->prepare($sql);
                $stmt->bind_param("i", $clean['prod']);
                $stmt->execute();
                $stmt->bind_result($db_prod, $db_unit, $db_grams, $db_energy, $db_fat, $db_saturates, $db_carbo, $db_sugar, $db_fibre, $db_protein, $db_salt, $db_alcohol);
                $c = 0;
                $response = '{ "product": [ ';
                while ($stmt->fetch())
                {
                    if ($c) $response .=',';
                    $response .= '{ "grams":"'.$db_grams.'",'.
                                    ' "energia":"'.$db_energy.'",'.
                                        ' "greix":"'.$db_fat.'",'.
                                            ' "saturat":"'.$db_saturates.'",'.
                                                ' "carboh.":"'.$db_carbo.'",'.
                                                    ' "sucre":"'.$db_sugar.'",'.
                                                        ' "fibra":"'.$db_fibre.'",'.
                                                            ' "proteina":"'.$db_protein.'",'.
                                                                ' "sal":"'.$db_salt.'",'.
                                                                    ' "alcohol":"'.$db_alcohol.'"'.
                                                                        ' }';
                    $c += 1;
                }
                $stmt->close();
                if ($c == 0) $response = '{ "response":"void" }';
                else $response .= " ] } ";
                
                break;

            case "getIngredients":

                    $sql ="select ingredients"
                         ." from diet_products"
                         ." where IDproduct = ?";
                    $stmt =$db->prepare($sql);
                    $stmt->bind_param("i", $clean['prod']);
                    $stmt->execute();
                    $stmt->bind_result($db_text);
                    if ($stmt->fetch()) {

                        $response = '{ "list":"'.$db_text.'" }';
                    }
                    else {

                        $response = '{ "response":"void" }';
                    }
                    $stmt->close();
                    
                    break;
            }        
        echo $response;
    }
    mysqli_close($db);
    
?>
