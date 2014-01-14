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
<title>Masérské studio Rostislav Koudelný - zapomenuté heslo - www.masazekoudelny.cz </title>  
</head>
<body>
<?php
class zapomen_heslo
{
 public $chyba;
 private $emailovaAdrea;
 private $variabilniSymbol;
 private $noveHeslo;
 public function __construct($emailovaAdrea,$variabilniSymbol) {
     $this->emailovaAdrea = $emailovaAdrea;
     $this->variabilniSymbol = $variabilniSymbol;
 }
        
         
 function formular_zap_hes()
 {
  print '
  <form action="zapomenute_heslo.php" method="post">
   <table align="center" id="formular">
   <tr>
    <td class="text">EMAIL:</td>
    <td><input type="text" name="email_zap_h" class="input" /></td>
   </tr> 
   <tr>
    <td class="text">VARIABILNÍ SYMBOL:</td>
    <td><input type="text" name="varsym_zap_h" class="input" /></td>
   </tr> 
   <tr>
    <td colspan="2" align="center"><input type="submit" name="odeslat_heslo" value="Změnit heslo" /></td>
   </tr>
   </table>
  </form>';
 }
 function kontrola_email()
 {
  $emailAvarsym = dibi::query("SELECT email,variabilniSymbol FROM masaze_uzivatel WHERE email = %s AND variabilniSymbol=%i",  $this->emailovaAdrea,$this->variabilniSymbol);
  $pocet = $emailAvarsym->fetch();
  if($pocet != 1)
  {
   echo "<p class='chyba'>Tento email a variabilní symbol není v naši databázi!</p>";
  $this->chyba = 1;
  }
 }
 function zmena_heslaDB()
 {
     try {
      $zmena_h = dibi::query("UPDATE masaze_uzivatel SET heslo = %s WHERE email = '".$this->emailovaAdrea."'",sha1("#@%$007MaSaZe".$this->noveHeslo)); 
  return $zmena_h;        
     } catch (Exception $exc) {
         echo $exc->getMessage();
     }
 }
   function odeslani_hesla()
    {
     try {  
     require ("PHPmailer/class.phpmailer.php");
     $mail = new PHPMailer();
     $this->noveHeslo = rand(0,100000000);
     $mail->IsSMTP();
     $mail->IsHTML(true);
     $mail->Host = "ssl://smtp.masazekoudelny.cz";
     $mail->SMTPAuth = true;
     $mail->Port = 465;
     $mail->Username = "automat@masazekoudelny.cz";
     $mail->Password = "rosta1988";
     $mail->From = "automat@masazekoudelny.cz"; 
     $mail->FromName = "maserské studio Koudelny";
     $mail->AddAddress($this->emailovaAdrea);
     $mail->Subject = "Nové heslo - maserske-studio.wz.cz\n"; 
     $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">Dobrý den, zažádali jste o změnu hesla. <br />
                      Vaše nové heslo je: <b>'.$this->noveHeslo.'</b><br />
                      Pokud jste o heslo nezažádali, kontaktujte nás.<br />
                      S pozdravem Rostislav Koudelny.</div> 
      <div style="width:100%;margin-top:20px;font-size:0.8em">Pozn: Tato upozornění jsou zasílána strojově, neodpovídejte na ně prosím, vaše zpráva nebude doručena.</div>  
     </div>';
  $mail->WordWrap = 50; 
  $mail->CharSet = "UTF-8";
   if(!$mail->Send())
   {  
    echo '<p class="chyba">Chyba odeslání emailu! Opakujte akci! (Chybová hláška: '.$mail->ErrorInfo.')</p>';
   }
   else
   {       
     print '<p class="ok">Na zadaný email vám bylo odesláno nové heslo.</p>';     
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
<h2> Zapomnenuté heslo </h2>
<?php
 $zap_heslo = new zapomen_heslo($_POST["email_zap_h"],$_POST["varsym_zap_h"]);
   if(isset($_POST["odeslat_heslo"]))
   {
    $zap_heslo->kontrola_email();  
    if($zap_heslo->chyba == 1)
    {
     $zap_heslo->formular_zap_hes(); 
    }
    else 
    {
     $zap_heslo->odeslani_hesla();
     $zap_heslo->zmena_heslaDB();
     $zap_heslo->formular_zap_hes();  
    }
   }
   else 
   {
    $zap_heslo->formular_zap_hes();   
   }


?>
</div>       
</div> 

</body>
</html>