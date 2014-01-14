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
<title>Masérské studio Rostislav Koudelný - úvodní stránka - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
 function vypis_slev()
 {
  $slevy = dibi::query("SELECT * FROM masaze_slevy WHERE zobrazeno = 1");  
  while($s = $slevy->fetch())
  {
   // cena_po_slevě * 100 / puvodni_cena = 100 - sleva 
    $sleva = ($s->cena * 100) / $s->cenaPuvodni;
    $slevaC = 100 - $sleva;
    print '<div id="slevy">
   <p class="nazev">'.htmlspecialchars($s->nazevSl, ENT_QUOTES).'</p>
   <p class="popis">'.htmlspecialchars($s->popis, ENT_QUOTES).'</p>
   <form action="slevy.php" method="get">
   <input type="hidden" name="kupon" value="'.htmlspecialchars($s->idSl, ENT_QUOTES).'">
   <div>množství:<input type="text" name="mnozstvi" value="1"></div>
   <div class="puvodniCena">původní cena: '.htmlspecialchars($s->cenaPuvodni, ENT_QUOTES).' Kč / 1 ks</div>
   <div>vaše cena: <span class="polozka">'.htmlspecialchars($s->cena, ENT_QUOTES).'</span> Kč / 1 ks</div>
   <div>sleva: <span class="polozka">'.htmlspecialchars(round($slevaC), ENT_QUOTES).' %</span></div>   
   <div>';
    if(isset($_SESSION["idMasazeKoudelny"]))
    {
        ?><button type="submit" onclick="alert('Váš kuón byl přidán do košíku.')">OBJEDNAT</button><?php 
        
    }
    else {
     print 'pro koupení musíte být příhlášený/á';  
    }
    
    print '</div></form></div>';    
  }
  return $slevy;
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
<h2> Slevy na masáže </h2>
<?php
try {
   vypis_slev(); 
} catch (Exception $exc) {
    echo $exc->getMessage();
}


?>
</div>       
</div> 

</body>
</html>