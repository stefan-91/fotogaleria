<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.

http://localhost/Vlastny_web_1/

-->

<html>
    <head>
        <meta charset="utf-8">
        <title>Fotogaléria - Štefan Kubini</title>
        <link rel="stylesheet" type="text/css" href="style.css"> 
    </head>
    <body background="grafika/pozadie.png">        
        <div class="obsah">
            <?php include "hlavicka.php"; ?>                      
            <div  class ="hlavny-obsah">   <!-- Hlavna sekcia stranky -->
                <script src="myScript.js"></script>
                <?php include "main.php"; ?>
            </div> 
            <?php include "paticka.php"; ?>  
        </div>                         
    </body>
</html>