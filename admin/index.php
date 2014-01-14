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
<script src="../ckeditor/ckeditor.js" type="text/javascript"></script>
<link rel="stylesheet" href="AdminStlye.css" type="text/css" />
<title>Úvodní stránka - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php
class administratorska_sekce
{
 public $chyba;
 function admin_menu()
 {
  require("menu.php");
 }
 function admin_form_prihlas()
 {
  print '<form action="index.php" method="POST">
  <table class="admin_prihlaseni" align="center">
   <tr>
    <td> Login</td>
    <td><input type="text" name="login_admin" class="input_sprava" /> </td>
   </tr>
   <tr>
    <td> Heslo </td>
    <td><input type="password" name="heslo_admin" class="input_sprava" /> </td>
   </tr>
   <tr>
    <td colspan="2" align="right"><input type="submit" class="potvrzeni_btn" name="adminprihlas" value="Přihlásit se"> </td>
   </tr>
   </table>
   </form>';
   }
   function kontrola_prihlas($email,$heslo)
   {
    if(!((preg_match("/^[\w-\.]+@([\w-]+\\.)+[a-zA-Z]{2,4}$/", $email))))
    {
     echo "<div class='chyba'>Email není ve správném tvaru!</div>"; 
     $this->chyba = 1;
    }        
    $heslo_sifra = sha1("#@%$007MaSaZe".$heslo);
    $SelectLogin = dibi::query("SELECT * FROM masaze_admin  WHERE email=%s AND heslo=%s",$email,$heslo_sifra);
    $radek = count($SelectLogin);
    $log_adm = $SelectLogin->fetch();
    if($radek == 1)
    {   
      $_SESSION["idAdminMaserskeStudioKoudelny"] = htmlspecialchars($log_adm->idAdmin, ENT_QUOTES);
    }
    else 
    {
     echo "<p id='NeniPristup'>Přihlašovací údaje jsou chybné!</p>";
     $this->chyba = 1;    
    }
   return $SelectLogin;
  } 
  function vypis_uvod()
  {
   // masaze_clanek 
   $vypis_uvodu = dibi::query("SELECT * FROM masaze_clanek");
   $uvod = $vypis_uvodu->fetch();
   print '
     <form action="index.php" method="post">
      <div id="clanek_text">
        <textarea name="text_clanku" class="ckeditor">'.$uvod->obsah.'</textarea>
        <br /><input type="submit" name="clanek_ulozit" value="Uložit" />
      </div>
     </form>';
  }
  function cisteniUzivatele()
  {
      try {
       $aktualniDatum = date("Y-m-d");
       $uzivatele = dibi::query("SELECT * FROM masaze_uzivatel WHERE posledniDatumObjednavky < %d",$aktualniDatum);
       echo "počet:".count($uzivatele);
          
      } catch (Exception $exc) {
          echo $exc->getTraceAsString();
      }
    }
   function cisteniZpravy()
  {
   try {
     $cisteni = dibi::test("TRUNCATE TABLE mysql54521.masaze_zpravy");    
   } catch (Exception $ex) {
      echo "Tabulka masaze_zpravy nebyla vyprázdněna";
   }
   return $cisteni;
  }
  function sprava_menu()
  {
   print '
    <h2>Úvodní stránka</h2>';
  }
  function ulozeni_clanku()
  {
   $ulozit = dibi::query("UPDATE masaze_clanek SET obsah = %s WHERE idClanek = 1 ",$_POST["text_clanku"]);
   return $ulozit;
  }
}
?>    
<div id="header">
 <?php
  require 'header.php';
 ?>       
</div>  
<div id="stred">
 <div id="text">    
 <?php
$administrator = new administratorska_sekce();
if(isset($_POST["clanek_ulozit"]))
{
 $administrator->ulozeni_clanku();   
}
if(isset($_GET["odhlaseni"]))
{
  unset($_SESSION["idAdminMaserskeStudioKoudelny"]);
}
if(isset($_POST["adminprihlas"]))
{
 $administrator->kontrola_prihlas($_POST["login_admin"],$_POST["heslo_admin"]);
 if($administrator->chyba == 1)
 {
  $administrator->admin_form_prihlas();   
 }
 else 
 {
  $administrator->sprava_menu();
  $administrator->vypis_uvod();
 }
}
else
{
 if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
 {
  $administrator->sprava_menu(); 
  switch ($_GET["adminMenu"]) {
      case "cisteni":
      print '<ul class="obsah_menu">
              <li><a href="index.php?adminMenu=nastenka">Vyčistit nástěnku</a></li>
              <li><a href="index.php?adminMenu=uzivatele">Vyčistit uživatelů</a></li></ul>';

          break;
       case "nastenka":
             print '<ul class="obsah_menu">
              <li><a href="index.php?adminMenu=nastenka">Vyčistit nástěnku</a></li>
              <li><a href="index.php?adminMenu=uzivatele">Vyčistit uživatelů</a></li></ul>';
      $administrator->cisteniZpravy();
          break;
      case "uzivatele":
              print '<ul class="obsah_menu">
              <li><a href="index.php?adminMenu=nastenka">Vyčistit nástěnku</a></li>
              <li><a href="index.php?adminMenu=uzivatele">Vyčistit uživatelů</a></li></ul>';
       $administrator->cisteniUzivatele();
          break;

      default: $administrator->vypis_uvod();
          break;
  }
  
 } 
 else 
 {
   $administrator->admin_form_prihlas();    
 }
}
 ?>   
 </div> 
 <div id="menu">
  <?php
  if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   $administrator->admin_menu();  
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