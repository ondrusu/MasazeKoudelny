<?php
session_start();
require 'pripojeni.php';
if(isset($_GET["vysypat"]) and $_GET["vysypat"] == true)
{
 unset($_SESSION["kosik"]);   
}
 if(isset($_GET["odstranit"]))
    {
       unset($_SESSION["kosik"][$_GET["odstranit"]]);   
    }
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="content-language" content="cs" />
<meta name="description" content="Masérské studio Rostislav Koudelný, Brno, Lesná" />
<meta name="keywords" content="masáže, maserské studio, studio, Rostislav Koudelný, Brno, Lesná, Brno Lesná, Brno Střed, Halasovo náměstí Brno" />
<link rel="stylesheet" href="style.css" type="text/css" />
<title>Masérské studio Rostislav Koudelný - košík - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
class kosik
{
 private $platba;
 private $idUzivatel;
 private $jmeno;
 private $prijmeni;
 private $email;
 private $telefon;
 private $variabilniC;
 private $celkemCena;
 private $kosik;
 public function __construct($platba,$celkemCena,$kosik) {
    $this->platba = $platba;
    $this->celkemCena = $celkemCena;
    $this->kosik = $kosik;
    $this->udaje();
 }
 private function udaje()
 {
     try {
    $vypisU = dibi::query("SELECT * FROM masaze_uzivatel WHERE idUziv = %i LIMIT 1",$_SESSION["idMasazeKoudelny"]);
  $uzivatel = $vypisU->fetch();
  $this->idUzivatel = $uzivatel->idUziv;
  $this->jmeno = $uzivatel->jmeno;
  $this->prijmeni = $uzivatel->prijmeni;
  $this->telefon = $uzivatel->telefon;
  $this->email = $uzivatel->email;
  $this->variabilniC = $uzivatel->variabilniSymbol;         
     } catch (Exception $exc) {
         echo $exc->getMessage();
     }
 
 }
 public function vypisUdaju()
 {
  print '
   <h3>Vystavení kupónu na osobu</h3>
   <table border="1">
    <tr>
     <td>jméno a příjmeni: </td>
     <td>'.htmlspecialchars($this->jmeno, ENT_QUOTES).' '.htmlspecialchars($this->prijmeni, ENT_QUOTES).'</td>
    </tr>
    <tr>
     <td>telefon: </td>
     <td>'.htmlspecialchars($this->telefon, ENT_QUOTES).'</td>
    </tr>
    <tr>
     <td>email: </td>
     <td>'.htmlspecialchars($this->email, ENT_QUOTES).'</td>
    </tr>
   </table>
   ';
 }
 public function metodyPlatby()
 {
  print '<h3>Metody platby</h3><form action="kosik.php?proces-objednavky=true" method="post">
   <input type="radio" name="platba" value="prevodem" />převodem na účet<br />
   <input type="radio" name="platba" value="osobne" />osobně<br />
   <input type="submit" name="tlacitko" value="Potvrdit objednávku" />
  </form>';
 }
 public function odeslaniInformaci()
 {
  try {
  $status = dibi::query("SELECT status FROM masaze_admin WHERE idAdmin = 1");
  $cisloStatus = $status->fetchSingle();
  require ("PHPmailer/class.phpmailer.php");
  $mail = new PHPMailer();
  $mail->IsSMTP();
  $mail->IsHTML(true);
  $mail->Host = "ssl://smtp.masazekoudelny.cz";
  $mail->SMTPAuth = true;
  $mail->Port = 465;
  $mail->Username = "automat@masazekoudelny.cz";
  $mail->Password = "rosta1988";
  $mail->From = "automat@masazekoudelny.cz"; 
  $mail->FromName = "maserské studio Koudelný";
  $mail->AddAddress($this->email);
  $mail->AddAddress("maserske-studio@seznam.cz");
  $mail->Subject = "Nová objednávka na masazekoudelny.cz"; 
  $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">
                 Vážený uživateli, Vážená uživatelko, '.htmlspecialchars($this->jmeno, ENT_QUOTES).' '.htmlspecialchars($this->prijmeni, ENT_QUOTES).'<br /> 
                 Děkujeme za objednávku akčního kupónu.<br /><br />
      </div>'; 
  if($cisloStatus == 1)
  {
     $mail->Body .= "<p style='color:red;font-weight:bold'>Jsem dočasně nepřítomen, vaši objednávku vyřídím až se vrátím. Sledujte stránky nebo Facebook. Děkuji za pochopení.</p>";  
  }
  switch ($this->platba)
  {
    case "prevodem":
    {
      $mail->Body .= "Nyní můžete zaslat částku ".htmlspecialchars($this->celkemCena, ENT_QUOTES)." kč na bankovní účet <b>216 689 599 / 0300</b>.<br />
                      Nezapomeňte uvést své variabilní číslo <b>".htmlspecialchars($this->variabilniC, ENT_QUOTES)."</b>. Po přijetí patby dostanete další informace.<br /><br />
                      UPOZORNĚNÍ: platba může trvat i několik dní.";  
      break;  
    }
    case "osobne":
    {
      $mail->Body .= "
          Akční kupón(y) zaplaťte na Halasovém náměstí Brno - Lesná. Pro vystavení dokladu o zaplacení si můžete požádat telefonicky nebo emailem.<br />
          Celková cena kupónu(ů) je <b>".htmlspecialchars($this->celkemCena, ENT_QUOTES)." Kč.</b>"; 
       break;  
    }
  }
  $mail->Body .= '<div style="width:100%;margin-top:20px;font-size:1em">Pozn: Tato upozornění jsou zasílána strojově, neodpovídejte na ně prosím, vaše zpráva nebude doručena.</div>    
  </div>';
  $mail->WordWrap = 50; 
  $mail->CharSet = "UTF-8";
    if(!$mail->Send())
    {  
     echo '<p class="chyba">Chyba odeslání emailu! Opakujte akci! (Chybová hláška: ' .$mail->ErrorInfo.')</p>';
     
    }
    else
    {       
     print '<p class="ok">Objednávka byla přijata. Na váš email byli odeslány další informace.</p>';  
    }  
  } catch (Exception $exc) {
         echo $exc->getMessage();
     } 
 }
 public function zmenaStatistik()
 {
   try {   
     $zmena = dibi::query("UPDATE masaze_uzivatel SET celkovaCena =celkovaCena+%i, pocetKuponu =pocetKuponu+%i,posledniDatumObjednavky = %d WHERE idUziv = %i ",$this->celkemCena,count($this->kosik),date("Y-m-d"),$this->idUzivatel);
     return $zmena;
  } catch (Exception $exc) {
         echo $exc->getMessage();
   }
 }
 public function ulozitObjednavku()
 {
  try {
  $dataObjednavka = array(
   "idUziv" => $this->idUzivatel,
   "cena" => $this->celkemCena,
   "zpusobPlatby" => $this->platba,
   "stav" => 1,
   "datumObjednani" => date("Y-m-d")
  );
  $objednavka = dibi::query("INSERT INTO masaze_objednavky",$dataObjednavka);
  $idDotaz = dibi::getInsertId();
  foreach ($this->kosik as $key => $value) {
    $dataObjednavkaMN = array(
        "idObje"=>$idDotaz,
        "idSl"=>$key,
        "mnozstvi"=>$value,
        "vycerpanoKs" => 0
    );
    $objednavkaMN = dibi::query("INSERT INTO masaze_objednavky_mn",$dataObjednavkaMN);
  }
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
 <?php
 if(!isset($_SESSION["kosik"]))
 {
   die("<p>Košík je prázdný</a>");
 }
 ?>
 <table id="kosik">
  <tr>
    <th>NÁZEV (MNOŽSTVÍ)</th>
    <th>CENA</th>
    <th>ODSTRANIT</th>
  </tr>
 <?php 
   try {
 $celkemCena = 0;
 foreach ($_SESSION["kosik"] as $key => $value) {
    // $value - mnostvi 
    // $key - id
  $polozka = dibi::query("SELECT * FROM masaze_slevy WHERE idSl = %i",$key); 
  while($p = $polozka->fetch())
  {
  
   $cenaZaKupon = $value*$p->cena;
    $celkemCena += $cenaZaKupon;
   print '
   <tr>
    <td>'.htmlspecialchars($p->nazevSl, ENT_QUOTES).' ('.$value.') </td>
    <td>'.htmlspecialchars($cenaZaKupon, ENT_QUOTES).' Kč</td>
    <td><a href="kosik.php?odstranit='.htmlspecialchars($p->idSl, ENT_QUOTES).' " title="odstranit z košíku"><img src="Images/cart_delete.png" alt="odstranit z košíku"></a></td>
   </tr>    
   '; 
   
  }
}
 } catch (Exception $exc) {
         echo $exc->getMessage();
     }
?>   
  <tr id="ovladaiKosiku">
   <td colspan="3">Menu košíku</td>
  </tr>
  <tr>
   <td><p class="kosikAkce">Cena celkem: <strong><?=$celkemCena;?></strong> Kč</p></td>
   <td><p class="kosikAkce"><a href="kosik.php?vysypat=true" title="vysypat košík"><img src="Images/remove_card.png" alt="Vysypat košík se slevami" /></a></p></td>
   <td><p class="kosikAkce"><a href="kosik.php?proces-objednavky=true" title="Proces objednávky">Pokračovat</a></p></td>
  </tr>
 </table>
<?php


if(isset($_GET["proces-objednavky"]) and $_GET["proces-objednavky"] == true and $_SESSION["idMasazeKoudelny"] and count($_SESSION["kosik"]) > 0)
{
  try {
 $kosik = new kosik($_POST["platba"],$celkemCena,$_SESSION["kosik"]);
 $kosik->metodyPlatby();
 if(isset($_POST["tlacitko"]))
 {
  $kosik->vypisUdaju();
  $kosik->odeslaniInformaci();
  $kosik->ulozitObjednavku();
  $kosik->zmenaStatistik();
  unset($_SESSION["kosik"]);
 }
    } catch (Exception $exc) {
         echo $exc->getMessage();
     }
}
?>
</div>       
</div> 

</body>
</html>