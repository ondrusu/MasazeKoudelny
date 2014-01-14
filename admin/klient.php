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
<title>Klienti - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php
 function admin_menu()
 {
  require("menu.php");
 }
 function vypis_klientu()
 {
 $vypis_k = dibi::query("SELECT *,DATE_FORMAT(datum_reg,'%e.%c.%Y %H:%i:%S') as datum_reg FROM masaze_uzivatel ORDER BY IdUziv DESC");
 print 'Celkem: '.count($vypis_k).'<table align="center" id="sprava_tabulka">
     <tr>
      <th>jméno a příjmení</th>
      <th>email</th>
      <th>telefon</th>
      <th>variabilní symbol</th>
      <th>datum registrace</th>
      <th>celkem (kč) / kuponu</th>
      <th>nové akce</th>
     </tr>';
 while ($klienti = $vypis_k->fetch())
 {
  print '<tr>
      <td>'.$klienti->jmeno.' '.$klienti->prijmeni.'</td>
      <td>'.$klienti->email.'</td>
      <td>'.$klienti->telefon.'</td>
      <td>'.$klienti->variabilniSymbol.'</td>
      <td>'.$klienti->datum_reg.'</td>
      <td>'.$klienti->celkovaCena.' / '.$klienti->pocetKuponu.'</td>
      <td>'; 
     if($klienti->zaslat_novinky == 1)
     {
      echo "ANO";      
     }
    else {
       echo "NE";  
     }
     print '</td>
     </tr>';   
 }
  print '</table>';
 }
?>    
<div id="header">
 <?php
  require 'header.php';
 ?>       
</div>  
<div id="stred">
 <div id="text"> 
     <h2>Výpis klientů</h2>
 <?php
 if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
 {
     try {
     vypis_klientu();    
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
