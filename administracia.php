<?php  
session_start();

	 if (isset($_POST['meno']) && $_POST['heslo']) {
		  $meno = $_POST['meno'];
		  $heslo = $_POST['heslo'];

		  if(strcmp($meno, "admin") == 0 && strcmp($heslo, "123") == 0) {
				//echo "prihlasenie bolo uspesne"; 
				//session_start();
				$_SESSION["loggedIn"] = true;
				//$_SESSION["username"] = $meno;
				//header("Location: administracia.php");  //Ak prihlasenie uspeje, tak to pouzivatela presmeruje do administracie 
				//echo '<script type="text/javascript"> redirect(index.php); </script>';
		  }
		  else {
				echo "Prihlásenie nebolo úspešné.";        
		  }    
	 } 
?>

<?php
error_reporting(E_ALL);
ini_set("display_errors","On");

//session_start();
if($_SESSION["loggedIn"] != true) {
	 echo("<h1>Access denied!</h1>");
	 exit();
}
//echo("Enter my lord!");
?>

<!DOCTYPE html>
<html>
	 <head>
		  <meta charset="utf-8">
		  <title>Administrácia</title>
		  <link rel="stylesheet" type="text/css" href="style.css">         
	 </head> 
	 <body>
		  <script src="myScript.js"></script>
		  <div>
				<span class="admin_nadpis">Administrácia - prihlásený používateľ</span>                       

				<span class="admin_odkaz">
					 <a href="index.php" target="_blank"> <font color="black">Pozri stránku &#8599;<font></a>
				</span>  

		  </div>

		  <div class="admin_menu">
				<a href="administracia.php?akcia=pridanie_galerie">Pridať galériu</a>
				<br>
				<a href="administracia.php?akcia=pridanie_obrazka">Pridať obrázok</a>
				<br>
				<a href="administracia.php?akcia=odobranie_obrazka">Odstrániť obrázok</a>
				<br>
				<a href="administracia.php?akcia=odobranie_galerie">Odstrániť galériu</a>
				<br>
				<a href="administracia.php?akcia=premenovanie_galerie">Premenovať galérie</a>            
				<br>                                  
				<a href="administracia.php?akcia=odhlasenie">Odhlásiť sa</a>                    
		  </div>
		  <div class="admin_main"> <?php editor_galerie(); ?> </div>        
	 </body>
</html>

<?php
	 error_reporting(E_ALL);
	 ini_set("display_errors","On");

	 function editor_galerie() {

		  require('Editor_galerie.php');
		  $myEditor = new Editor_galerie();        

		  //======= Pridanie galerie ==========
		  if(isset($_GET['akcia']) == true) {
				if($_GET['akcia'] == 'pridanie_galerie') {             
					 if(!isset($_GET['nazovGalerie'])) {
						  $myEditor->zobrazFormular();     
					 }
				}   
		  }

		  if(isset($_GET['nazovGalerie'])) {
				$nazovGalerie = $_GET['nazovGalerie'];
				$myEditor->zapisSubor($nazovGalerie); 
		  }

		  //======== Pridanie obrazka =========  
		  if(isset($_GET['akcia']) == true) {
				if($_GET['akcia'] == 'pridanie_obrazka') {            
					$myEditor->pridajObrazkyForm1();            
				}      
		  }

		  if(isset($_GET['vyber_galeriu'])) {  
			  $galeria = $_GET['vyber_galeriu'];
			  $myEditor->pridajObrazkyForm2($galeria);           
		  }          

		  if(isset($_POST["submit"])) { 
				$galeria = $_POST['vyber_galeriu']; //Musi byt "post" lebo uploadujeme v rovnakom formulari aj obrazky (vid. trieda Editor_galerie)
				$obrazok = $myEditor->nahrajObrazok($galeria);
				if($obrazok != null) {
					 require('aktualizaciaRSS.php');
					 $myRSS = new aktualizaciaRSS(); 
					 $myRSS->pridajRSS($obrazok, $galeria);
				}
		  }                 

		  //======== Odobratie obrazka =======
		  if(isset($_GET['akcia']) == true) {
				if($_GET['akcia'] == 'odobranie_obrazka') {
					 $myEditor->odoberObrazkyForm1(); //vyberie galeriu
				}  
		  }

		  if(isset($_GET['galeria_odstranovanie_obr'])) {  
			  $galeria = $_GET['galeria_odstranovanie_obr'];
			  $myEditor->odoberObrazkyForm2($galeria); //Vyberie obrazky        
		  }         

		  //Ziskanie riadku premennych a odstranenie vybranych suborov
		  if(isset($_GET['btnUloz'])) {
				$nazovGalerie = $_GET['galeria'];           
				$poleObr = $myEditor->vratPoleObrazkov($_GET);            
				$myEditor->odstranObrazky($nazovGalerie, $poleObr); 
		  }          

		  //======= Odobratie galerie ========
		  if(isset($_GET['akcia']) == true) {
				if($_GET['akcia'] == 'odobranie_galerie') {
					  $myEditor->vyberSubor('Vyberte galériu, ktorú chcete odstrániť:', 'Odstrániť vybranú galériu', 'odstrani_galeriu');
				}      
		  }
		  if(isset($_GET['odstrani_galeriu'])) {
				$nazovGalerie = $_GET['odstrani_galeriu'];
				$myEditor->odstranGaleriu('content/' . $nazovGalerie); 
		  }        

		  //====== Premenovanie galerie =======
		  if(isset($_GET['akcia']) == true) {
				if($_GET['akcia'] == 'premenovanie_galerie') {
					 $myEditor->premenujGaleriuForm1();
				}
		  }                                

		  if(isset($_GET['premenuj_galeriu'])) {
				$nazovGalerie = $_GET['premenuj_galeriu'];
				$myEditor->premenujGaleriuForm2($nazovGalerie);
		  }         


		  if(isset($_GET['novy_nazov_galerie']) && isset($_GET['nazov_galerie'])) {
				$starynazov = $_GET['nazov_galerie'];
				$novynazov = $_GET['novy_nazov_galerie'];
				$myEditor->premenujGaleriu($starynazov, $novynazov);
		  }

		  //====== Odhlasenie sa ==========
		  if(isset($_GET['akcia']) == true) {
				if($_GET['akcia'] == 'odhlasenie') {
					//session_start();
					$_SESSION["loggedIn"] = false; //takto sa odhlasime: zrusime Session
					echo "Boli ste odhlásení.";
					//header("Location: index.php"); //presmerujeme sa na hlavnu stranku
					//echo 'redirect(index.php);';
				}
		  }
	 }
?>