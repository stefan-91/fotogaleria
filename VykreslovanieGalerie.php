<?php

class VykreslovanieGalerie {
    //private $width, $height;
    
    private function vratObrazky($galeriaDir) { //Vrati pole vsetkych obrazkov v galerii        
        $poleObr = array();
        $files = scandir($galeriaDir);
        
        for($i=2; $i<count($files); $i++) {
            array_push($poleObr, $files[$i]);
        }        

        return $poleObr;
    }
    
    public function vratnahodnyObrazok($galeria) {
        $galeria = 'content/' . $galeria;        
        $poleObr = $this->vratObrazky($galeria);
        
        if(count($poleObr) == 0) return null; //ak v galerii neexistuju subory
        
        $index = rand(0, count($poleObr)-1);
        
        return $poleObr[$index];
    }

    
    //Vykresli obrazky z jednej galerie
    function vypisGaleriu($galeria) { //Vypisalo galeriu 
               
        //Prepise nazov z premennej lebo v URL sa z medzery vytvori znak '%20
        $galeria = str_replace('%20', ' ', $galeria);
        echo "<h1>$galeria</h1>";
        echo '<br>';
        
        //Ziska vsetky nazvy suborov v galerii
        $images = glob('content/' . $galeria . '/' ."*.{jpg,JPG,png,PNG,gif,GIF}", GLOB_BRACE);
        $id = 1;
        
        foreach ($images as $filename) {
            $imid = "id" . $id;
            echo"\t\t\t<img id=\"$imid\" src=\"$filename\" height=\"150\" onclick=\"ZvacsitObrazok(this);\"/> \n";
            $id++;            
        }        
    }      
    
    //Vykresli galerie na uvodnu stranku
    function vypisGalerie() {
        //echo "<br>";
        //Ulozi do pola vsetky galerie
        require('Editor_galerie.php');
        $myEditor = new Editor_galerie(); 
        $galerieArr = $myEditor->vratGalerie();        
        $galerieArrOrig = $galerieArr;
        
        //Obmedzi nazvy galerie aby neboli prilis dlhe
        for($i=0; $i<count($galerieArr); $i++) {
            if(strlen($galerieArr[$i]) > 16) {
                $galerieArr[$i] = substr($galerieArr[$i], 0, 16);              
                $galerieArr[$i] = $galerieArr[$i] . "...";
            }            
        }         
        
        for($i=0; $i<count($galerieArr); $i++) { 
            $obrazok = $this->vratnahodnyObrazok($galerieArrOrig[$i]);  //"$galerieArrOrig" je zoznam galerii s nezmenenymi nazvami
            
            echo '<div class="galeria_nahlad">';
            if($obrazok == null) {
                echo "$galerieArr[$i]";
                echo "<br><br>";
                echo "<b>Galéria neobsahuje obrázky</b>";
            }
            else {
                echo '<a href="index.php?galeria=' . $galerieArrOrig[$i] . '"> ' . $galerieArr[$i]. '</a>';
                echo "<br><br>";                
                echo '<a href="index.php?galeria=' . $galerieArrOrig[$i] . '"> ' . '<img src="' . 'content/'. $galerieArrOrig[$i] . '/' . $obrazok . '">'. '</a>';
            }
            echo '</div>';
        }


    }
    
    /*
    function __construct($width, $height) {
        $this->width = $width;
        $this->height = $height; 

    }    
            
    private function prepisanieDoPriecinka($src,$dst) { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) { 
            if (( $file != '.' ) && ( $file != '..' )) { 
                if ( is_dir($src . '/' . $file) ) { 
                    $this->prepisanieDoPriecinka($src . '/' . $file,$dst . '/' . $file); 
                } 
                else {
                    //kontrola ci subor uz nebol kopirovany lebo funkcia "copy" subor s rovnakym nazvom vzdy prepise
                    //if(file_exists($dst . '/' . $file) == false) {
                        copy($src . '/' . $file,$dst . '/' . $file); //prekopiruje subor
                        
                        if(!is_dir($dst . '/' . $file)) { //ak je to subor a nie adresar, tak to zmensi                            
                            $rozmeryArr = getimagesize($dst . '/' . $file); //Indexy 0 - sirka, 1 - vyska
                            $malaSirka = ($rozmeryArr[0] * $this->height) / $rozmeryArr[1]; //Podla vzorca o pomeroch povodneho obrazka a zmenseniny
                            $this->resize_image($dst . '/' . $file, $malaSirka, $this->height);
                        }
                    //}
                } 
            } 
        } 
        closedir($dir); 
    }    
    
    public function urobMiniatury() {
        //Prekopiruje subory z adresara "content" do "miniatury"
        $this->prepisanieDoPriecinka('content','miniatury');             
                
        //Obrazky pozmensuje a oreze
        
        
    }
    
    public function zmensiObrazky() {
        
    }
    
    private function resize_image($file, $newwidth, $newheight) {
        list($width, $height) = getimagesize($file);       
        
        $src = imagecreatefromjpeg($file);
        $dst = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);

        echo "Ciel: $dst <br>";
        return $dst;
    }    
    */
}
