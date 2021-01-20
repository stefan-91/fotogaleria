<?php  
session_start();
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Prihlásenie sa</title>
        <link rel="stylesheet" type="text/css" href="style.css"> 
        <script src="myScript.js"></script>
    </head> 
    <body class="login">
        <h1>Prihláste sa</h1>
        <form action="administracia.php" method="post">
          Meno:<br>
          <input type="text" name="meno" required>
          <br>
          Heslo:<br>
          <input type="password" name="heslo" required>
          <br><br>
          <input type="submit" value="Prihlásiť sa">
        </form> 

        <p>
        <b>Pomôcka</b> <br>
        meno: admin <br>
        heslo: 123 <br>
        </p>

    </body>
</html>




