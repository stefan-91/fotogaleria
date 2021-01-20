<?php
error_reporting(E_ALL);
ini_set("display_errors","On");

class Editor_galerie {

	 //Zapise subor podla nazvu
	 public function zapisSubor($nazov) {        
		  $nazov = urlencode($nazov);

			//Overi ci dany subor neexistuje (vtedz k nemu pripise " - kopia")
		  $dir    = 'content/';
		  $files = scandir($dir);

		  //Zapise nazvy priecinkov do pola
		  $folders = array();
		  for($i=0; $i<count($files); $i++) {
				array_push($folders, $files[$i]);
		  }

		  //Overi ci nie je v nazve zhoda s nejakym existujucim suborom
		  $zhoda = false;
		  for($i=0; $i<count($folders); $i++) {
				if(strcmp($folders[$i], $nazov) == 0){
					 $zhoda = true;
					 break;
				}            
		  }

		  //Ak je zhoda, tak vytvori unikatny nazov
		  if($zhoda == true) {
				$pocet = count($files) - 2 + 1; //"-2" lebo count() berie do uvahy aj . a .., "+1" aby to oznacovalo poradie existujuceho priecinka
				$nazov = $nazov . '-' . $pocet;
		  }

		  //Vytvori novy priecinok v adresari "content" podla nazvu
		  if (!mkdir('content/'.$nazov, 0777)) {
				echo('Failed to create folder...');
		  }        
		  else echo "Nová galéria s názvom \"$nazov\" bola vytvorená";                              
	 }

	 //Vytvori subor podla zadaneho nazvu
	 public function zobrazFormular() {
		  //Formular na zapisanie nazvu suboru
		  echo 
		  '
		  <form action="administracia.php" method="get">
			 Zadajte názov galérie: 
			 <input type="text" name="nazovGalerie" required>
			 <input type="submit" value="Zapísať novú galériu">
		  </form>';                                                              
	 }

	 public function vratGalerie() {
		  //najdeme vsetky subory v priecinku
		  $dir    = 'content/';
		  $files = scandir($dir);

		  //Zapise nazvy priecinkov do pola "$folders"
		  $folders = array();
		  for($i=0; $i<count($files); $i++) {
				array_push($folders, $files[$i]);
		  }

		  //Vyhodi prec "." a ".."        
		  for($i=0; $i<=count($folders)-1-2; $i++) { //"-2" lebo posuvame pole o dve miesta dopredu
				$folders[$i] = $folders[$i+2];
		  }        
		  $pocetSub = count($folders);
		  unset($folders[$pocetSub-1]);
		  unset($folders[$pocetSub-2]);

		  return $folders;
	 }

	 public function vyberSubor($komentar, $btnKomentar, $premennaURL) {

		  $folders = $this->vratGalerie();
		  //Formular na zapisanie nazvu suboru
		  echo 
		  '
		  <form action="administracia.php" method="get">
				' . $komentar . ' 
				<select name="' . $premennaURL . '">
					 <option value="nic" selected="selected">nie je vybraná galéria</option>
		  ';      

		  for($i=0; $i<count($folders); $i++) {
				echo "<option value=\"$folders[$i]\">$folders[$i]</option>";        
		  }

		  echo '        
				</select>
				<input type="submit" value="'. $btnKomentar .'">
		  </form>
		  ';                                                              
	 }

	 private function odstranSubor($dirPath) {    
		  if (! is_dir($dirPath)) {
				throw new InvalidArgumentException("$dirPath must be a directory");
		  }
		  if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
				$dirPath .= '/';
		  }
		  $files = glob($dirPath . '*', GLOB_MARK);
		  foreach ($files as $file) {
				if (is_dir($file)) {
					 $this->odstranSubor($file);
				} else {
					 unlink($file);
				}
		  }
		  if(rmdir($dirPath) == true) {
				$nazov = basename ($dirPath);
				echo "Galéria \"$nazov\" bola úspešne odstránená <br>";      
		  }
		  else echo "Odstránenie galérie \"$nazov\" zlyhalo <br>";        
	 }        

	 public function odstranGaleriu($subor) {        
		  $nazov = basename ($subor);
		  if(strcmp('nic', $nazov) == 0) {
				$this->vyberSubor('Vyberte galériu, ktorú chcete odstrániť:', 'Odstrániť vybranú galériu', 'odstrani_galeriu');
				echo "CHYBA: Nevybrali ste galériu na odstránenie. <br>";
		  }
		  else $this->odstranSubor($subor);        
	 }

	public function pridajObrazkyForm1() {

		//1. Kontrola ci galeria existuje
		$dir    = 'content/';
		$files = scandir($dir);

		//Zapise nazvy priecinkov do pola
		$folders = array();
		for($i=0; $i<count($files); $i++) {
			  array_push($folders, $files[$i]);
		}

		if(count($folders) == 2) {
			echo "Na nahranie obrázkov musíte najprv vytvoriť galériu! Pokračujte voľbou \"Pridať galériu\". Potom sa vráťte k tomuto kroku <br>";
			exit();            
		}

		 $this->vyberSubor('Vyberte galériu, do ktorej chcete pridať obrázok:', 'Vybrať galériu', 'vyber_galeriu');                        
	}

	 public function nahrajObrazok($galeria) {
		$target_dir = "content/" . $galeria . '/';
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		echo $target_file ;
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
				$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
				if($check !== false) {
					 echo "File is an image - " . $check["mime"] . ".";
					 $uploadOk = 1;
				} else {
					 echo "File is not an image.";
					 $uploadOk = 0;
				}
		}
		// Check if file already exists
		if (file_exists($target_file)) {
				echo "Sorry, file already exists.";
				$uploadOk = 0;
		}
		/*
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 500000) {
				echo "Sorry, your file is too large.";
				$uploadOk = 0;
		}
		*/

		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" && $imageFileType != "GIF") {
				echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed. Format bol $imageFileType";
				$uploadOk = 0;
		}

		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
				echo "Sorry, your file was not uploaded.";
		// if everything is ok, try to upload file
		} else {
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
					 echo "Obrázok ". basename( $_FILES["fileToUpload"]["name"]). " bol úspešne nahraný.";
					 return basename( $_FILES["fileToUpload"]["name"]);
				} else {
					 echo "Sorry, there was an error uploading your file.";
					 return null;
				}
		  }                     
	}

	public function pridajObrazkyForm2($galeria) {
		//Testuje ci pouzivatel vybral galeriu
		if(strcmp('nic', $galeria) == 0) {
				echo "Nevybrali ste galériu! <br>";
				echo "V prípade, že chcete vytvoriť novú galériu pre obrázok, najprv vykonajte krok \"Pridať galériu\" <br>";
				$this->pridajObrazkyForm1();
				exit();
		}        

		$galeria = $_GET['vyber_galeriu'];

		echo 
		'<form action="administracia.php" method="post" enctype="multipart/form-data">
				Vyberte obrázok, ktorý chcete do galérie nahrať:
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="hidden" name="vyber_galeriu" value="' . $galeria . '" />
				<input type="submit" value="Nahraj obrázok" name="submit">

		</form>'
		;   
	}

	//Formular na vyber galerie
	public function odoberObrazkyForm1() {
		$this->vyberSubor('Vyberte galériu, v ktorej chcete odstrániť obrázok:', 'Vybrať galériu', 'galeria_odstranovanie_obr');                
	}

	public function odoberObrazkyForm2($galeria) {
		//1. Testuje ci pouzivatel vybral galeriu
		if(strcmp('nic', $galeria) == 0) {
				echo "Nevybrali ste galériu z ktorej chcete odstrániť obrázky! <br>";
				$this->odoberObrazkyForm1();
				exit();
		}         

		//2. Nacita obrazky s checkboxami        
		echo '<form name ="odstran" method ="get" action ="administracia.php"> <br>'; 
		echo '<input type="hidden" name="galeria" value="' . $galeria . '" />';

		$dirname = "content/" . $galeria . "/";

		$images = glob($dirname."*");
		$imagesNames = scandir($dirname);

		for($j=0; $j<count($imagesNames)-2; $j++) { //"-2" Aby sa nam nezobrazovali tie bodky
				echo '<input type="Checkbox" name="'.$imagesNames[$j+2].'" value ="odstranit" style="width:20px;height:20px;" >'
				. "&nbsp;"
				. '<img src="'.$images[$j].'" height="100" />';   
				//$pocitadlo++;
		}           

		echo '<hr> <br> <INPUT TYPE = "Submit" Name = "btnUloz" VALUE = "Odstráň zaškrtnuté obrázky"> <br> <br>';
		echo '</form>';  
	}

	//Od konca prve podtrzitko zmeni na bodku
	private function podtrzitkonaBodku($nazov) {
		  for($i=strlen($nazov)-1; $i>=0; $i--) {
				if($nazov[$i] == '_') {
					 $nazov[$i] = '.';
					 break; //aby sme nesli v slove dalej ako po prve podtrzitko
				}            
		}
		return $nazov;
	}

	//Z pola premennych "$_GET" ziska mena premennych, ktore oznacuju obrazky (lisia sa tym, ze maju hodnotu "odstranit")
	public function vratPoleObrazkov($poleGET) {
		$poleObr = array();

		$nazvy = array();
		$nazvy = $poleGET;

		foreach ($nazvy as $index=>&$value) { 
				if(strcmp($value, 'odstranit') == 0) { //ak ma premenna hodnotu "odstranit"

					$index = $this->podtrzitkonaBodku($index); //pri zapise do URL sa z bodky urobili podtrzitka, preto sa to musi prepisat naspat

					array_push($poleObr, $index);
				}
		}
		return $poleObr;
	}

	//Odstrani obrazky zo zadanej galerie, ktorych nazvy su ulozene v poli
	public function odstranObrazky($nazovGalerie, $poleObr) {
		$dir = 'content/' . $nazovGalerie . '/';

		$chyba = false;
		for($i=0; $i<count($poleObr); $i++) {
			$path = $dir . $poleObr[$i];
			if(unlink($path) == false) {
					$chyba = true;
					echo "Chyba pri odstraňovaní súboru $poleObr[$i] <br>";
			}	
		}
		if($chyba == true) {
			  echo "Odstraňovanie obrázkov prebehlo s chybami.";
		} else echo "Odstránenie obrázkov prebehlo úspešne!";		
	}
	
	public function premenujGaleriuForm1() {
		  $this->vyberSubor('Vyberte galériu, ktorú chcete premenovať:', 'Vybrať galériu', 'premenuj_galeriu');        
	}

	public function premenujGaleriuForm2($galeria) {
		//Testuje ci pouzivatel vybral galeriu
		if(strcmp('nic', $galeria) == 0) {
				echo "Nevybrali ste galériu! <br>"; 
				$this->premenujGaleriuForm1();
				exit();
		}

		echo 
		'<form action="administracia.php" method="get">
				Prepíšte názov galérie:
				<input type="text" name="novy_nazov_galerie" value="' . $galeria . '" />
				<input type="hidden" name="nazov_galerie" value="' . $galeria . '">     
				<input type="submit" name="submit" value="Zapísať nový názov">            
		  </form>'
		  ;         

	}

	 public function premenujGaleriu($starynazov, $novynazov) {
		$novynazov = urlencode($novynazov);
		$starynazov = 'content/' . $starynazov;
		$novynazov = 'content/' . $novynazov;

		if(rename($starynazov, $novynazov)) {
				echo "Názov galérie bol zmenený";
		}
		else echo "Chyba v premenovan9 galérie";
		//Napisat ci premenovanie galerie prebehlo v poriadku
	}

}
