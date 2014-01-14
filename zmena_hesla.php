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
<title>Masérské studio Rostislav Koudelný - změna hesla - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
class zmenaHesla
{
 public $chyba;
 private $stareHeslo;
 private $noveHeslo;
 private $potvrzeniHesla;
 private $uzivatel;
 public function __construct($stareHeslo,$noveHeslo,$potvrzeniHesla,$uzivatel) {
     $this->stareHeslo = $stareHeslo;
     $this->noveHeslo = $noveHeslo;
     $this->potvrzeniHesla = $potvrzeniHesla;
     $this->uzivatel = $uzivatel;
 }
 function formular_zmeny()
 {
  print '
  <form action="zmena_hesla.php" method="post">
  <table id="formular" align="center">
   <tr>
    <td class="text">STARÉ HESLO:*</td>
    <td><input type="password" name="heslo_stare" class="input" /></td>
   </tr>
   <tr>
    <td class="text">NOVÉ HESLO:*</td>
    <td><input type="password" name="heslo_nove" class="input" /></td>
   </tr>
   <tr>
    <td class="text">POTVRZENÍ NOVÉHO HESLA:*</td>
    <td><input type="password" name="heslo_nove2" class="input" /></td>
   </tr>
   <tr>
    <td colspan="2" align="center"><input type="submit" name="heslo_tlacitko" value="Změnit heslo"></td>
   </tr>
  </table>
  </form>';
 }
 function kontrola_zmeny()
 {
    $zmena_h = dibi::query("SELECT heslo,idUziv FROM masaze_uzivatel WHERE idUziv = %i LIMIT 1",$this->uzivatel);
   $heslo_a = $zmena_h->fetch();
   $heslo_hash = sha1("#@%$007MaSaZe".$this->stareHeslo);
   if($heslo_a->heslo != $heslo_hash)
   {
     echo "<div class='chyba'>Staré heslo není správné!</div>";
     $this->chyba = 1;
   }
   if($this->noveHeslo != $this->potvrzeniHesla)
   {
     echo "<div class='chyba'>Hesla se neshodují!</div>";
     $this->chyba = 1;    
   }
   return $zmena_h;    
 }
 function vlozeni_nove_heslo()
 {
  try {
  $vloz_h = dibi::query("UPDATE masaze_uzivatel SET heslo = %s WHERE idUziv = %i",sha1("#@%$007MaSaZe".$this->noveHeslo),$this->uzivatel); 
  return $vloz_h;
 } catch (Exception $exc) {
         echo $exc->getMessage();
     }
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
<h2>Změna hesla </h2>
<?php
$zmenaH = new zmenaHesla($_POST["heslo_stare"],$_POST["heslo_nove"],$_POST["heslo_nove2"],$_SESSION["idMasazeKoudelny"]);
if(isset($_SESSION["idMasazeKoudelny"]))
{
 if(isset($_POST["heslo_tlacitko"]))
 {
  $zmenaH->kontrola_zmeny();
  if($zmenaH->chyba == 1)
  {
   $zmenaH->formular_zmeny();
  }
  else 
  {
   $zmenaH->vlozeni_nove_heslo();
   echo "Heslo změněno";
   $zmenaH->formular_zmeny();
  }
 }
 else
 {
  $zmenaH->formular_zmeny();   
 }   
}
else
{
 echo "Ke stránce nemáte přístup. Musíte se přihlásit, přihlašovací formulář se nachází na levé straně.";  
}

?>
</div>       
</div> 

</body>
</html>