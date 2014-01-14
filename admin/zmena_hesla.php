<?php
 session_start();
 require("../pripojeni.php");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
<head>
<link rel="shortcut icon" type="image/x-icon" href="" />
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="Content-language" content="cs" /> 
<meta name="description" content="" />
<meta name="keywords" content="" />
<link rel="stylesheet" href="AdminStlye.css" type="text/css" />
<title> Změna hesla - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php
class zmena_hesla
{
  public $chyba; 
  private $stareHeslo;
  private $noveHeslo;
  private $noveHeslo2;
  private $uzivatel;
  function __construct($stareHeslo,$noveHeslo,$noveHeslo2,$uzivatel) {
      $this->stareHeslo = $stareHeslo;
      $this->noveHeslo = $noveHeslo;
      $this->noveHeslo2 = $noveHeslo2;
      $this->uzivatel = $uzivatel;
  }
  function admin_menu()
  {
   require("menu.php");
  }    
  function formular_zmena()
  {
   print '<form action="zmena_hesla.php" method="post">
   <table align="center" id="sprava_tabulka">
    <tr>
     <td>Staré heslo:*</td>
     <td><input type="password" name="stare_heslo" class="input_sprava" /></td>
    </tr>
    <tr>
     <td>Nové heslo:*</td>
     <td><input type="password" name="nove_heslo" class="input_sprava" /></td>
    </tr>
    <tr>
     <td>Potvrďte nové heslo:*</td>
     <td><input type="password" name="nove_heslo2" class="input_sprava" /></td>
    </tr>
    <tr>
     <td colspan="2" align="right"><input type="submit" name="nove_odeslat" class="potvrzeni_btn" value="Změnit heslo" /></td>
    </tr>
   </table></form>';
  }
  function kontrola_zmeny()
  {
   $zmena_h = dibi::query("SELECT heslo,idAdmin FROM masaze_admin WHERE idAdmin = %i LIMIT 1",$this->uzivatel);
   $heslo_a = $zmena_h->fetch();
   $heslo_hash = sha1("#@%$007MaSaZe".$this->stareHeslo);
   if($heslo_a->heslo != $heslo_hash)
   {
     echo "<div class='chyba'>Staré heslo není správné!</div>";
     $this->chyba = 1;
   }
   if($this->noveHeslo != $this->noveHeslo2)
   {
     echo "<div class='chyba'>Hesla se neshodují!</div>";
     $this->chyba = 1;    
   }
   return $zmena_h;
  }
  function vlozeni_nove_heslo()
  {
   $vloz_h = dibi::query("UPDATE masaze_admin SET heslo = %s WHERE idAdmin = %i",sha1("#@%$007MaSaZe".$this->noveHeslo),$this->uzivatel); 
   return $vloz_h;
  }
  
 }
$zmena_hesla = new zmena_hesla($_POST["stare_heslo"],$_POST["nove_heslo2"],$_POST["nove_heslo"],$_SESSION["idAdminMaserskeStudioKoudelny"]);
?>
<div id="header">
 <?php
  require 'header.php';
 ?>       
</div>  
<div id="stred">
 <div id="text"> 
  <h2>Změnit heslo</h2>      
<?php
if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
{
 if(isset($_POST["nove_odeslat"]))
 {
  $zmena_hesla->kontrola_zmeny();
  if($zmena_hesla->chyba == 1)
  {
   $zmena_hesla->formular_zmena();  
  }
  else 
  {
   echo "<p class='je_ok'>Vložení do databáze proběhlo.</p>";  
   $zmena_hesla->vlozeni_nove_heslo();
   $zmena_hesla->formular_zmena(); 
  }
 }
 else 
 {
  $zmena_hesla->formular_zmena();      
 }   
}
else
{
 echo "<p id='NeniPristup'>Nemáte přístup ke stránce. Prosím přihlašte se <a href='index.php' title='Přihlašovací stránka'>ZDE</a></p>";
}
 ?>   
 </div> 
 <div id="menu">
  <?php
  if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   $zmena_hesla->admin_menu();  
  }    
 ?>    
 </div>     
</div>
<div id="menu_uzivatel">
 <?php
  require 'uziv_menu.php';
 ?>   
</div>    
</body>
</html>
