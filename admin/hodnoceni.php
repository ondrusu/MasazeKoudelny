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
<title>Hodnocení od uživatelů - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php
 function admin_menu()
 {
  require("menu.php");
 }
  function vypis_hodnoceni()
  {
   print '
   <table id="sprava_tabulka" align="center"><tr><th>EMAIL</th><th>HVĚZDIČKY</th><th>POZNÁMKA</th></tr>';
   $vypis_hodnoceni = dibi::query("SELECT * FROM masaze_hodnoceni JOIN masaze_uzivatel ON masaze_hodnoceni.idKlient = masaze_uzivatel.IdUziv ");
   while ($h = $vypis_hodnoceni->fetch())
   {
    print '<tr>
        <td>'.$h->email.'</td>
        <td>'.$h->hodnota.'</td>
        <td>'.$h->poznamka.'</td>
       </tr>';  
   }
   print '</table>';
  }
  function sprava_menu()
  {
   print '
    <h2>Hodnocení od uživatelů</h2>';
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
 if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
 {
     try {
  sprava_menu(); 
  vypis_hodnoceni();    
    } catch (Exception $exc) {
         echo $exc->getMessage();
     }
 } 
 else 
 {
   echo "<p id='NeniPristup'>Nemáte přístup ke stránce, musíte se nejprve přihlásit!</p>";
 }
 ?>   
 </div> 
 <div id="menu">
  <?php
  if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   admin_menu();  
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