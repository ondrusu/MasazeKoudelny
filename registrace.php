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
<title>Masérské studio Rostislav Koudelný - registrace - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
class registrace
{
 public $chyba;
 
 private $jmeno;
 private $prijmeni;
 private $email;
 private $telefon;
 private $heslo;
 private $potvrzeniHesla;
 private $kontrolniOtazka;
 private $kontrolniOdpoved;
 

 public $error_jmeno;
 public $error_prijm;
 public $error_email;
 public $error_telef;
 public $error_heslo;
 public $error_heslo2;
 public $error_otazka;
 public $otazka;
 public $odpoved;
 public $error_vse;
  
 public function __construct($jmeno,$prijmeni,$email,$telefon,$heslo,$potvrzeniHesla,$kontrolniOtazka,$kontrolniOdpoved) {
     $this->jmeno = htmlspecialchars($jmeno, ENT_QUOTES);
     $this->prijmeni = htmlspecialchars($prijmeni, ENT_QUOTES);
     $this->email = htmlspecialchars($email, ENT_QUOTES);
     $this->telefon = htmlspecialchars($telefon, ENT_QUOTES);
     $this->heslo = htmlspecialchars($heslo, ENT_QUOTES);
     $this->potvrzeniHesla = htmlspecialchars($potvrzeniHesla, ENT_QUOTES);
     $this->kontrolniOtazka = htmlspecialchars($kontrolniOtazka, ENT_QUOTES);
     $this->kontrolniOdpoved = htmlspecialchars($kontrolniOdpoved, ENT_QUOTES);
     
     $dopln_pole = array(1=>"Na jakém náměstí sídlí Maserské studio koudelný? (1 pád a velké H)","Halasovo",
                   "Kolik stojí 30. minutová klasická masáž záda a šíje (pouze číslo)",220,
                   "Jaké křestní jméno má Koudelný? (velké R)","Rostislav",
                   "Jak dlouho trvá klasická masáž hrudníku, která stojí 80 kč? (pouze číslo)",20
    );
    $max_hodnota = count($dopln_pole);
    $nahodneC = rand(1, $max_hodnota);
    $sudeCislo = $nahodneC%2;
     if($sudeCislo == 0)
     {
     //echo "Sudé <br />";
      $this->otazka = $dopln_pole[$nahodneC-1]; // přísloví     
      $this->odpoved = $dopln_pole[$nahodneC]; // musí se rovnat POST
     }
     else
    {
     //echo "Liché <br />";   
     $this->otazka = $dopln_pole[$nahodneC]; // přísloví     
     $this->odpoved = $dopln_pole[$nahodneC+1]; // musí se rovnat POST 
    }
     
 }
 public function formular_reg()
 {
  print '<form action="registrace.php" method="post"><table align="center" id="formular">
    <tr><td colspan="2"><div class="chyba">'.$this->error_vse.'</div></td></tr>
    <tr>
     <td class="text">JMÉNO:* </td>
     <td><input type="text" name="reg_jmeno" class="input" value="';
       if(isset($this->jmeno))
       {
        echo $this->jmeno;   
       }
     print '" /><div class="chyba">'.$this->error_jmeno.'</div></td>
    </tr>
    <tr>
     <td class="text">PŘÍJMENÍ:*</td>
     <td><input type="text" name="reg_prijm" class="input" value="';
       if(isset($this->prijmeni))
       {
        echo $this->prijmeni;   
       }
     print '" /><div class="chyba">'.$this->error_prijm.'</div></td>
    </tr>
    <tr>
     <td class="text">EMAIL:*</td>
     <td><input type="text" name="reg_email" class="input" value="';
       if(isset($this->email))
       {
        echo $this->email;   
       }
       else
       {
        echo "@";
       }
     print '" /><div class="chyba">'.$this->error_email.'</div></td>
    </tr>
    <tr>
     <td class="text">TELEFON:*</td>
     <td><input type="text" name="reg_telefon" class="input" value="';
       if(isset($this->telefon))
       {
        echo $this->telefon;   
       }
     print '" /><div class="chyba">'.$this->error_telef.'</div></td>
    </tr>
    <tr>
     <td class="text">HESLO:*</td>
     <td><input type="password" name="reg_heslo" class="input" /><div class="chyba">'.$this->error_heslo.'</div></td>
    </tr>
    <tr>
     <td class="text">POTVRZENÍ HESLA:*</td>
     <td><input type="password" name="reg_heslo2" class="input" /><div class="chyba">'.$this->error_heslo2.'</div></td>
    </tr>
    <tr>
     <td colspan="2">'.$this->otazka.'</td>
    </tr>
    <tr>
     <td class="text">Kontrolní otázka:*</td>
     <td><input type="text" name="reg_otazka" class="input" /><div class="chyba">'.$this->error_otazka.'</div></td>
    </tr>
    <tr>
    <tr>
     <td colspan="2" align="center"><input type="submit" name="reg_tlacitko" value="registrovat se" /></td>
    </tr>
    <input type="hidden" name="question" value="How do you love?" />
    <input type="hidden" name="odpoved" value="'.$this->odpoved.'" />
   </table></form>';
  
 }
 function kontrola_udaju()
 {
  $delka_jmeno = strlen($this->jmeno);
  $delka_prijm = strlen($this->prijmeni);
  $delka_email = strlen($this->email);
  $delka_telefon = strlen($this->telefon);
  $existuje_email = dibi::query("SELECT email FROM masaze_uzivatel WHERE email = %s",$this->email);
  if($this->jmeno == "" or $this->prijmeni == "" or $this->email == "" or $this->heslo == "" or $this->potvrzeniHesla == "" or $this->telefon == "" or $this->kontrolniOtazka == "")
  {
   $this->error_vse = "Musíte zadat všechy údaje označené * !";
   $this->chyba = 1;
  }
  if($delka_jmeno > 100)
  {
   $this->error_jmeno = "Jméno je moc dlouhé (max 100 znaků)!";
   $this->chyba = 1;   
  }
  if($delka_prijm > 100)
  {
   $this->error_prijm = "Příjmení je moc dlouhé (max 100 znaků)!";
   $this->chyba = 1;   
  }
  if($delka_email > 200)
  {
   $this->error_email = "Email je moc dlouhý (max 200 znaků)!";
   $this->chyba = 1;   
  }
  if(!((preg_match("/^[\w-\.]+@([\w-]+\\.)+[a-zA-Z]{2,4}$/", $this->email))))
  {
   $this->error_email = "Email je ve špatném tvaru!";
   $this->chyba = 1;      
  }
  if(count($existuje_email) > 0)
  {
   $this->error_email = "Zadaný email již existuje!";
   $this->chyba = 1;   
  }
   if($delka_telefon != 9)
   {
    $this->error_telef = "Telefon musí být na 9 znaků!";
    $this->chyba = 1;   
   }
   if(!is_numeric($this->telefon))
   {
    $this->error_telef = "Telefon musí obsahovat jen číslice!";
    $this->chyba = 1;   
   }      
  if($this->heslo != $this->potvrzeniHesla)
  {
   $this->error_heslo2 = "Hesla se neschodují!";
   $this->chyba = 1;    
  }
  if($this->kontrolniOtazka != $this->kontrolniOdpoved)
  {
   $this->error_otazka = "Kontrolní otázka byla zodpovězena špatně!".$this->odpoved;
   $this->chyba = 1;    
  }
 }
 function potvrzeni_emailu()
 {
     try {
     $kam = $this->email;
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
  $mail->AddAddress($kam);
  $mail->Subject = "potvrzení registrace"; 
  $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">
                 Vážený uživateli, Vážená uživatelko, '.$this->jmeno.' '.$this->prijmeni.'<br /> 
                 zaregistroval/a jste se na www.masazekoudelny.cz.
                 Děkuji za registraci. S pozdravem Rostislav Koudelný.
      </div> 
      <div style="width:100%;margin-top:20px;font-size:0.8em">Pozn: Tato upozornění jsou zasílána strojově, neodpovídejte na ně prosím, vaše zpráva nebude doručena.</div>  
     </div>';
    $mail->WordWrap = 50; 
    $mail->CharSet = "UTF-8";
    if(!$mail->Send())
    {  
     echo '<p class="chyba">Chyba odeslání emailu! Opakujte akci! (Chybová hláška: ' .$mail->ErrorInfo.')</p>';
     
    }
    else
    {       
     print '<p class="ok"></p>';  
    }   
   } catch (Exception $exc) {
         echo $exc->getMessage();
     }  
 }
 function dataDoDb()
 {
  $variabilni = rand(1000000,9999999);
  $varSymp = dibi::query("SELECT variabilniSymbol FROM masaze_uzivatel WHERE variabilniSymbol = %i",$variabilni);
  if(count($varSymp) == 1)
  {
   $variabilni = rand(1000000,9999999);
  }
  else
  {
   $variabilniS = $variabilni;  
  }
  $regData = array(
   "jmeno" => $this->jmeno, 
   "prijmeni" => $this->prijmeni, 
   "telefon" => $this->telefon, 
   "email" => $this->email, 
   "heslo" => sha1("#@%$007MaSaZe".$this->heslo), 
   "variabilniSymbol" => $variabilniS,
   "datum_reg" => date("Y-m-d H:i:s"),
   "celkovaCena" => 0,
   "pocetKuponu" => 0,
   "zaslat_novinky" => 1
  );
  try{
  $insert = dibi::query("INSERT INTO masaze_uzivatel",$regData);
  if($insert)
  {
   echo "<p>Děkujeme za registraci, nyní se můžete přihlásit a objednat si slevové kupóny. </p>";
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
<h2> Registrace nového uživatele </h2>
<?php
$registrace = new registrace($_POST["reg_jmeno"],$_POST["reg_prijm"],$_POST["reg_email"],$_POST["reg_telefon"],$_POST["reg_heslo"],$_POST["reg_heslo2"],$_POST["reg_otazka"],$_POST["odpoved"]);
if(isset($_POST["reg_tlacitko"]))
{
 $registrace->kontrola_udaju();
 if($registrace->chyba == 1)
 {
  $registrace->formular_reg();
 }
 else 
 {
  $registrace->dataDoDb();
 }
}
 else 
{
  $registrace->formular_reg();  
}
?>
</div>       
</div> 

</body>
</html>