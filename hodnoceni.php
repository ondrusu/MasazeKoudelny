<?php
session_start();
require 'pripojeni.php';
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="content-language" content="cs" />
<meta name="description" content="Masérské studio Rostislav Koudelný, Brno, Lesná" />
<meta name="keywords" content="masáže, maserské studio, studio, Rostislav Koudelný, Brno, Lesná, Brno Lesná, Brno Střed, Halasovo náměstí Brno" />
<link rel="stylesheet" href="style.css" type="text/css" />
<script type="text/javascript" src="js/star/js/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="js/star/jquery.rating.js"></script>
<link rel="stylesheet" media="screen" type="text/css" href="js/star/jquery.rating.css" />
<script>
	$(document).ready(function(){
		    $(".rating").rating();
                    
		});
</script>
<title>Masérské studio Rostislav Koudelný - hodnocení - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
function formular()
{
 print '
  <form action="hodnoceni.php" method="post" id="formular">
 <table align="center" id="tabulkaObsah">
  <tr>
   <td class="text">Hodnocení</td>
   <td><select class="rating" name="hodnoceni">
    <option value="1">Nejsem spokojen/a</option>
    <option value="2">2</option>
    <option value="3">3</option>
    <option value="4">4</option>
    <option value="5">Jsem velmi spokojen/a</option>
</select></td>
  </tr>
  <tr>
   <td class="text">Poznámka</td>
   <td><textarea name="poznamka" class="textarea"></textarea></td>
  </tr>
  <tr>
   <td colspan="2" align="center"><input type="submit" name="odeslat" value="hodnotit"></td>
  </tr>
 </table>
</form>   
';  
}
function odeslatHodnoceni($hodnoceni,$poznamka)
{
  $hodnoceniData = array(
    "idKlient" =>$_SESSION["idMasazeKoudelny"],
    "hodnota" => $hodnoceni,
    "poznamka" => $poznamka
  );
 $poslatHodnoceni = dibi::query("INSERT INTO masaze_hodnoceni ",$hodnoceniData); 
 return $poslatHodnoceni;
}
function existenceHodnoceni()
{
 $pocet = dibi::query("SELECT idKlient FROM masaze_hodnoceni WHERE idKlient = %i ",$_SESSION["idMasazeKoudelny"]);
 return count($pocet);
}
function celkoveHodnoceni()
{
 $celkem = dibi::query("SELECT AVG(hodnota) as hodnota FROM masaze_hodnoceni");
 $avg = $celkem->fetchSingle();
 return $avg;
}
function textyHodnoceni()
{
 $text = dibi::query("SELECT poznamka FROM masaze_hodnoceni");
 while($textSql = $text->fetch())
 {
  echo "<p>".$textSql->poznamka."</p>";
 }
}
?>
<div id="hlavni">
 <?php
  require 'hlavicka.php';
 ?>      
<div id="menu">
 <div id="prihlaseni">
  <?php
  require 'prihlaseni.php';
  ?>
 </div>
<?php
 require "menu.php";
?>
</div>
<div id="obsah">
<h2> Hodnocení uživatelů </h2>
<?php
if(!isset($_SESSION["idMasazeKoudelny"]))
{
 ?>
<br />Celkové hodnocení: <br />
<select class="rating" name="hodnoceni" disabled="disable">
    <option value="1" <?php if(celkoveHodnoceni() == 1) echo "selected='select' "; ?>>Nejsem spokojen/a</option>
    <option value="2" <?php if(celkoveHodnoceni() == 2) echo "selected='select' "; ?>>2</option>
    <option value="3" <?php if(celkoveHodnoceni() == 3) echo "selected='select' "; ?>>3</option>
    <option value="4" <?php if(celkoveHodnoceni() == 4) echo "selected='select' "; ?>>4</option>
    <option value="5" <?php if(celkoveHodnoceni() == 5) echo "selected='select' "; ?>>Jsem velmi spokojen/a</option>
</select>
<h3>Textové hodnocení uživatelů</h3>
<?php
     textyHodnoceni();
   die("<p class='chyba'>Hodnotit mohou pouze přihlášení uživatelé.</p>");
}
if(isset($_POST["odeslat"]))
{
     try {
        odeslatHodnoceni($_POST["hodnoceni"], $_POST["poznamka"]); 
     } catch (Exception $exc) {
         echo $exc->getTraceAsString();
     }
 
}
if(existenceHodnoceni() == 0)
{
    try {
      formular();  
    } catch (Exception $exc) {
        echo $exc->getMessage();
    }

    
}
 else {
 echo "Hodnocení bylo odesláno.";
 ?>
<br />Celkové hodnocení: <br />
<select class="rating" name="hodnoceni" disabled="disable">
    <option value="1" <?php if(celkoveHodnoceni() == 1) echo "selected='select' "; ?>>Nejsem spokojen/a</option>
    <option value="2" <?php if(celkoveHodnoceni() == 2) echo "selected='select' "; ?>>2</option>
    <option value="3" <?php if(celkoveHodnoceni() == 3) echo "selected='select' "; ?>>3</option>
    <option value="4" <?php if(celkoveHodnoceni() == 4) echo "selected='select' "; ?>>4</option>
    <option value="5" <?php if(celkoveHodnoceni() == 5) echo "selected='select' "; ?>>Jsem velmi spokojen/a</option>
</select>
<?php
}
?>


</div>       
</div> 

</body>
</html>