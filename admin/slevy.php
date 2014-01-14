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
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="../js/javascript.js" type="text/javascript"></script>
<title>Slevy - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php

class slevy
{
 function __construct() {

 }
 function admin_menu()
 {
  require("menu.php");
 }
 function vlozeni_do_db($nazev,$popis,$puvodniCena,$cenaPoSleve)
 {
   try {
   $data_do_db = array(
    "nazevSl" =>$nazev,
    "popis" => $popis,
    "cenaPuvodni" => $puvodniCena,
    "cena" => $cenaPoSleve,
    "zobrazeno" => 1
   );
  $data = dibi::query("INSERT INTO masaze_slevy ",$data_do_db);
  return $data;
  } catch (Exception $exc) {
          echo $exc->getMessage();
      }
 }
  function uprava_db($nazev,$popis,$puvodniCena,$cenaPoSleve,$id)
 {
  try {
   $data_do_db = array(
    "nazevSl" =>$nazev,
    "popis" => $popis,
    "cenaPuvodni" => $puvodniCena,
    "cena" => $cenaPoSleve
   );
  $data = dibi::query("UPDATE masaze_slevy SET ",$data_do_db, "WHERE idSl = %i",$id);
  return $data;
 } catch (Exception $exc) {
          echo $exc->getMessage();
      }
 }
 function vypis_slev()
 {
 $dotaz = dibi::query("SELECT * FROM masaze_slevy ORDER BY idSl DESC");   
 print '<table align="center" id="sprava_tabulka"><form action="slevy.php" method="post">
     <tr>
      <th>Poslat</th>
      <th>ID</th>
      <th>název</th>
      <th>popis</th>
      <th>původní cena</th>
      <th>cena po slevě</th>
      <th>zobrazeno</th>
      <th>akce</th>
     </tr>';
 while ($slevy = $dotaz->fetch())
 {
  print '<tr id="radek_'.$slevy->idSl.'">
      <td><input type="checkbox" name="poslatEmailem[]" value="'.$slevy->idSl.'" class="poslatEmailem"></td>
      <td>'.$slevy->idSl.'</td>
      <td>'.$slevy->nazevSl.'</td>
      <td>'.$slevy->popis.'</td>
      <td>'.$slevy->cenaPuvodni.' Kč</td>
      <td>'.$slevy->cena.' Kč</td>
      <td>';
      switch ($slevy->zobrazeno) {
          case 1:
          ?>
    <select name="zobrazeno" onchange="zmenitZobrazeno(<?=$slevy->idSl;?>,0);">
        <option value="0" selected="select">ANO</option>
        <option value="1">NE</option>
    </select>    
          <?php
          break;
         case 0:
          ?>
    <select name="zobrazeno" onchange="zmenitZobrazeno(<?=$slevy->idSl;?>,1);">
        <option value="1">ANO</option>
        <option value="0" selected="select">NE</option>
    </select>    
          <?php

          break;
  }
      print '</td>    
          <td>';?><a href="" onclick="return smazatAkci(<?php echo $slevy->idSl;?>);" title="smazat tuto akci">SMAZAT</a>&nbsp;|&nbsp;<a href="slevy.php?upravit=<?php echo $slevy->idSl;?>"  title="upravit tuto akci">UPRAVIT</a>&nbsp; <?php print '</td>
      </tr>';
 }
   print '<tr><td colspan="2"><input type="submit" name="novinkyEmailem" value="Poslat novinky" ></td></tr></form></table>';
 }
 function odeslaniEmailem($kupony)
 {
  try {
  require ("../PHPmailer/class.phpmailer.php");
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->IsHTML(true);
  $mail->Host = "ssl://smtp.masazekoudelny.cz";
  $mail->SMTPAuth = true;
  $mail->Port = 465;
  $mail->Username = "automat@masazekoudelny.cz";
  $mail->Password = "rosta1988";
  $mail->From = "automat@masazekoudelny.cz"; 
  $mail->FromName = "Maserské studio Koudelný";
  $emaily = dibi::query("SELECT email FROM masaze_uzivatel WHERE zaslat_novinky = 1");
  while ($e = $emaily->fetch())
  {
   $mail->AddAddress($e->email);   
  }
  $mail->Subject = "Nové slevové kupóny"; 
  
  $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">
        Vážený uživateli, Vážená uživatelko,<br /> 
                 Upozorňuji na nové slevové kupóny.';
  foreach ($kupony as $key => $value) {
    $slevy = dibi::query("SELECT * FROM masaze_slevy WHERE idSl = %i LIMIT 1",$value);  
    $s = $slevy->fetch();
    $mail->Body .= "<div style='margin-top:5px'>
        <strong style='font-size: 1.1em'>".$s->nazevSl."</strong>
        <p style='font-size: 0.9em'>".$s->popis."</p>
        <span>CENA: <i>".$s->cena."</i>,-</span><br />
         </div> 
         <div style='width:100%;margin-top:20px;font-size:0.8em'>Pozn: Tato upozornění jsou zasílána strojově, neodpovídejte na ně prosím, vaše zpráva nebude doručena.</div>  
      </div>"; 
  }
    $mail->Body .= "<br />S pozdravem Rostislav Koudelný.<br />
     <span style='font-size:0.8em'>(Nechcete-li dostávat tyto novinky, přihlašte se a v nastavení (jiné nastavení) odškrtněte políčko se  zasíláním novinek)</span>";
    $mail->WordWrap = 50; 
    $mail->CharSet = "UTF-8";
    if(!$mail->Send())
    {  
     echo '<p class="chyba">Chyba odeslání emailu! Opakujte akci! (Chybová hláška: ' .$mail->ErrorInfo.')</p>';
     
    }
    else
    {       
     print '<p class="ok">Slevy byly odeslány!</p>';  
    }   
  } catch (Exception $exc) {
          echo $exc->getMessage();
      }   
 }
 function zmenitZobrazeni($id,$hodnota)
 {
  try {
  $zmenitZobrazeni = dibi::query("UPDATE masaze_slevy SET zobrazeno = %i WHERE idSl = %i",$hodnota,$id);   
  return $zmenitZobrazeni;
 } catch (Exception $exc) {
          echo $exc->getMessage();
      }
 }
 function smazatAkciZdb($id)
{
 try {
 $smazat = dibi::query("DELETE FROM masaze_slevy WHERE idSl = %i ",$id);
 if($smazat)
 {
  echo "Položka se smazala.";
 }
 return $smazat;   
  } catch (Exception $exc) {
          echo $exc->getMessage();
      }
}
  function sprava_menu()
  {
   print '
    <ul class="obsah_menu">
     <li><button type="button" id="pridatSlevu" class="obsah_menu">Přidat akci</button></li>
    </ul>';    
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
     <h2>Výpis slev</h2>
 <?php
$slevy = new slevy();
 if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
 {    
  if(isset($_GET["IdZobrazeno"]) and isset($_GET["hodnota"]))
  {
    $slevy->zmenitZobrazeni($_GET["IdZobrazeno"],$_GET["hodnota"]);
  }
  if(isset($_GET["smazat"]))
  {
   $slevy->smazatAkciZdb($_GET["smazat"]);
  }
         
  if(isset($_GET["nazev"]) && isset($_GET["popis"]) && isset($_GET["puvodniCena"]) && isset($_GET["cenaPoSleve"]))
   {
     $slevy->vlozeni_do_db($_GET["nazev"], $_GET["popis"], $_GET["puvodniCena"], $_GET["cenaPoSleve"]);  
   }
   if(isset($_GET["upravitNazev"]) && isset($_GET["upravitPopis"]) && isset($_GET["upravitPuvodniCena"]) && isset($_GET["upravitCenaPoSleve"]))
   {
    $slevy->uprava_db($_GET["upravitNazev"], $_GET["upravitPopis"], $_GET["upravitPuvodniCena"], $_GET["upravitCenaPoSleve"], $_GET["upravit"]);
   }
   
   ?><div id="dialog" title="Vytvoření akčního kupónu">
<form action="slevy.php" method="post" name="formular">
      <table align="center" id="sprava_tabulka">
      <tr>
       <td>Název*</td>
       <td><input type="text" name="nazev" class="input_sprava" /></td>
      </tr> 
       <tr>
       <td>Popis*</td>
       <td><textarea name="popis" class="input_sprava"></textarea></td>
      </tr>
      <tr>
       <td>Původní cena*</td>
       <td><input type="text" name="puvodniCena" class="input_sprava" /></td>
      </tr> 
      <tr>
       <td>Cena po slevě*</td>
       <td><input type="text" name="cena" class="input_sprava"  /></td>
      </tr> 
      </table></form>    
     </div>
     
         <?php
    
  
  $slevy->sprava_menu();
  $slevy->vypis_slev(); 

  if(isset($_POST["novinkyEmailem"]))
  {
   $slevy->odeslaniEmailem($_POST["poslatEmailem"]);
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
   $slevy->admin_menu();  
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
