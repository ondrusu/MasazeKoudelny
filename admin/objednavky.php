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
 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css" />
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="../js/javascript.js" type="text/javascript"></script>
<link rel="stylesheet" href="AdminStlye.css" type="text/css" />
<title>Objednávky - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php
class objednavky
{
 private $jmenoPrijmUzivatele;
 private $emailUzivatele;
 function admin_menu()
 {
  require("menu.php");
 }
 function vypisObjednavek($parametr)
 {
     switch ($parametr) {
         case "vycerpano":
  $vypis = dibi::query("SELECT *,DATE_FORMAT(datumObjednani,'%e.%c.%Y') as datumObjednani FROM masaze_objednavky,masaze_uzivatel,masaze_objednavky_mn 
      WHERE masaze_uzivatel.IdUziv = masaze_objednavky.IdUziv 
      AND     masaze_objednavky_mn.idObje = masaze_objednavky.idObje
      AND   masaze_objednavky_mn.mnozstvi = masaze_objednavky_mn.vycerpanoKs
      ORDER BY masaze_objednavky.datumObjednani DESC");

             break;
         default:
  $vypis = dibi::query("SELECT *,DATE_FORMAT(datumObjednani,'%e.%c.%Y') as datumObjednani FROM masaze_objednavky,masaze_uzivatel 
      WHERE masaze_uzivatel.IdUziv = masaze_objednavky.IdUziv 
      ORDER BY masaze_objednavky.datumObjednani DESC");
             break;
     }  

  print 'Celkem: '.count($vypis).'<table id="sprava_tabulka">
   <tr>
    <th>ID</th>
    <th>uživatel</th>
    <th>symbol</th>
    <th>cena</th>
    <th>stav</th>
    <th>zpusob platby</th> 
    <th>datum objednani</th> 
    <th>kupóny</th>
   </tr>'; 
  while ($objednavky = $vypis->fetch())
  {
   $this->jmenoPrijmUzivatele = $objednavky->jmeno.' '.$objednavky->prijmeni;
   $this->emailUzivatele = $objednavky->email;
  print '
   <tr id="radek_'.$objednavky->idObje.'">
    <td>'.$objednavky->idObje.'</td>
    <td>'.$this->jmenoPrijmUzivatele.'</td>
    <td>'.$objednavky->variabilniSymbol.'</td>
    <td>'.$objednavky->cena.' Kč</td>
    <td>';
  ?>
    <select name="stavPlatby" onchange="zmenaStavuPlatby(<?=$objednavky->idObje;?>,'<?=$this->emailUzivatele;?>','<?=$objednavky->zpusobPlatby;?>')"><?php
  print ' <option value="zaplaceno"';  if($objednavky->stav == 0) echo"selected='select' disabled "; print'>Zaplaceno</option>
          <option value="nezaplaceno"'; if($objednavky->stav == 1) echo"selected='select' "; print'>Nezaplaceno</option>
          '; ?>
    </select> <?php print '</td>
    <td >'.$objednavky->zpusobPlatby.'</td>
    <td >'.$objednavky->datumObjednani.'</td>
    <td align="right">';
      $vypisMn = dibi::query("SELECT * FROM masaze_objednavky_mn,masaze_slevy 
          WHERE masaze_objednavky_mn.IdObje = %i
          AND masaze_slevy.IdSl = masaze_objednavky_mn.idSl",$objednavky->idObje);
      while ($objednavkyMn = $vypisMn->fetch())
      {
       print "<div title='".$objednavkyMn->nazevSl."'>".$objednavkyMn->nazevSl." - ";
       ?><form action="objednavky.php" method="post" name="Fvycerpano"><select name="vycerpano" style="width:50px" onchange="zmenaVycerpano(<?=$objednavkyMn->idObje;?>,<?=$objednavkyMn->idSl;?>,this);">
           <?php
     
           for($i = 0;$i<=$objednavkyMn->mnozstvi;$i++)
       {
        ?><option value="<?=$i;?>" <?php echo ($objednavkyMn->vycerpanoKs == $i ? "selected='select'" : "");?> ><?=$i;?></option><?php
       }
       ?></select></form>><?php 
       print ' / '.$objednavkyMn->mnozstvi.'x </div>';  
      }
     print '</td>
   </tr>';     
  }
  print '</table>';
 }
  function zmenVycerpano($idObjednavky,$idSlevy,$hodnota)
  {
      try {
      $vycerpano = dibi::query("UPDATE masaze_objednavky_mn SET vycerpanoKs = %i WHERE idObje = %i AND idSl = %i",$hodnota,$idObjednavky,$idSlevy);
    return $vycerpano;
   } catch (Exception $exc) {
          echo $exc->getMessage();
      }
  }
   function ulozitKupon($email)
 {
   try {
   include("../pdf/mpdf.php");
$vypis = dibi::query("
         SELECT *,DATE_FORMAT(masaze_objednavky.datumObjednani,'%d.%m.%Y') as datumObjednani
         FROM masaze_slevy,masaze_objednavky 
         INNER JOIN masaze_objednavky_mn 
         ON masaze_objednavky_mn.idObje = masaze_objednavky.idObje 
         WHERE masaze_objednavky.IdUziv = (SELECT idUziv FROM masaze_uzivatel WHERE email = %s) 
         AND masaze_slevy.idSl = masaze_objednavky_mn.idSl
         AND masaze_objednavky.stav = 1
 ",$email);

$mpdf=new mPDF('UTF-8',array(297,209));
if(count($vypis) == 1)
{
 $slKupony = " slevového kupónu, ";
 $spojka = "který";
 $spojka2 = "Tento kupón ";
}
 else {
  $slKupony = " slevových kupónů, ";  
  $spojka = "které";
  $spojka2 = "Tyto kupóny ";
}
$mpdf->WriteHTML("Děkuji za zakoupení".$slKupony.$spojka."si můžete prohlédnout na následujících stránkách. ".$spojka2." vytiskněte.");
while ($v = $vypis->fetch())
{
 $html = '
  <div id="kupon"> 
   <p id="nadpis">Maserské studio Koudelný</p>
   <p id="nazev">'.$v->nazevSl.'</p>
   <p id="popis">'.$v->popis.'</p>
   <p id="zakoupeno">Zakoupeno: '.$v->datumObjednani.'
   <br /><span style="font-size:0.9em">Platnost: 6 měsíců od zakoupení</span></p>
   <p id="pocet">Počet: '.$v->mnozstvi.'x</p>  
   <p id="adresa">Halasovo náměstí 1<br /> Brno, Lesná<br />638 00</p>   
   <p id="patro">Maserské studio se nachází <br /> v poliklinice první patro</p> 
   <p id="objednani">Objednání na:
    <br />Tel: <i>728 047 545</i>
    <br />Email: <i>maserske-studio@seznam.cz</i>
    <br />Web: <i>www.masazekoudelny.cz</i>
   </p> 
   
  
   <p id="slogan">Dejte svému tělíčku bezbolestný pohyb.</p>
  </div>
';   
$stylesheet = file_get_contents('../pdf/mpdf.css');
$mpdf->WriteHTML($stylesheet,1);
$mpdf->AddPage();
$mpdf->WriteHTML($html,2);

}

$mpdf->Output("../soubory/slevovyKupon.pdf","F"); 
 } catch (Exception $exc) {
          echo $exc->getMessage();
      }
 }
 function zmenaStavPlatby($id)
{
 try {
 $objednavky = dibi::query("DELETE FROM masaze_objednavky WHERE idObje = %i",$id);
 $objednavkyMN = dibi::query("DELETE FROM masaze_objednavky_mn WHERE idObje = %i",$id);
 if(!$objednavky && !$objednavkyMN)
 {
  echo "Vymazání se nezdařilo.";
 }
  } catch (Exception $exc) {
          echo $exc->getMessage();
      }
}
function odeslatKupon()
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
  $mail->FromName = "maserksé studio Koudelný";
  $mail->AddAddress($_GET["email"]);
  $mail->Subject = "Potvrzení platby a kupón - masazekoudelny.cz"; 
  switch ($_GET["platba"]) {
      case "osobne":
      {
       $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">Vážený uživateli, Vážená uživatelko,<br /> 
                 Děkujeme za objednávku akčního kupónu.<br /><br />
                 Váš kuón byl označen za zaplacený. Děkuji a přiji hezký den. Rostislav Koudelný.</div> 
      <div style="width:100%;margin-top:20px;font-size:0.8em">Pozn: Tato upozornění jsou zasílána strojově, neodpovídejte na ně prosím, vaše zpráva nebude doručena.</div>  
     </div>';
      break;   
      }
      case "prevodem":
      {
       $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">Vážený uživateli, Vážená uživatelko,<br /> 
                 Děkujeme za objednávku akčního kupónu.<br /><br />
                 V příloze máte kupóny, které si vytiskněte a uplatněte je na Halasovo Náměstí 1, 63800 Brno.</div> 
      <div style="width:100%;margin-top:20px;font-size:0.8em">Pozn: Tato upozornění jsou zasílána strojově, neodpovídejte na ně prosím, vaše zpráva nebude doručena.</div>  
     </div>';
       $mail->AddAttachment("../soubory/slevovyKupon.pdf", "slevovyKupon.pdf");           
       break;   
      }

  }

  $mail->WordWrap = 50; 
  $mail->CharSet = "UTF-8";
    if(!$mail->Send())
    {  
     echo 'Chyba odeslání emailu! Opakujte akci! (Chybová hláška: ' .$mail->ErrorInfo.')</p>';
     
    }
    else
    {       
     print 'Email s kuóny byl odeslán.';  
    }  
   } catch (Exception $exc) {
          echo $exc->getMessage();
      } 
 }
  function sprava_menu()
  {
   print '
    <ul class="obsah_menu">
     <li><a href="objednavky.php" class="obsah_menu">Objednávky</a></li>
     <li><a href="objednavky.php?menu=castecne" class="obsah_menu">Částečně vyčerpáno</a></li>
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
 <?php
$objednavky = new objednavky();
$objednavky->sprava_menu();
if(isset($_GET["idObje"]) && isset($_GET["idSlevy"]) && isset($_GET["hodnota"]))
{
 $objednavky->zmenVycerpano($_GET["idObje"], $_GET["idSlevy"], $_GET["hodnota"]);   
}
if(isset($_GET["stavPlatby"]))
{
  $objednavky->ulozitKupon($_GET["email"]);
  $objednavky->zmenaStavPlatby($_GET["stavPlatby"]);
  $objednavky->odeslatKupon();
  
}  
switch ($_GET["menu"]) {
    case "castecne":
    $objednavky->vypisObjednavek("vycerpano");

        break;
    default: $objednavky->vypisObjednavek("");   
        break;
}




 ?>   
 </div> 
 <div id="menu">
  <?php
  if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   $objednavky->admin_menu();  
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