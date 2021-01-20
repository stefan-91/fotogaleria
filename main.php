<?php 
error_reporting(E_ALL);
ini_set("display_errors","On");
    
if(isset($_GET['stranka'])) { 
    if(strcmp($_GET['stranka'], 'kontakt') == 0) {
        echo '<img src="kontakt.png">';
    }         
    
} else {
    require_once('VykreslovanieGalerie.php');
    $myVykreslovanie= new VykreslovanieGalerie();     
    if(isset($_GET['galeria'])) { 
        $galeria = $galeria = $_GET['galeria'];       
        $myVykreslovanie->vypisGaleriu($galeria);
        //ShowPictures($galeria);
    }
    else $myVykreslovanie->vypisGalerie();             
}

?>     



