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
<script src="http://code.jquery.com/jquery-2.0.1.min.js" type="text/javascript"></script>
<script src="js/javascript.js" type="text/javascript"></script>
<title>Masérské studio Rostislav Koudelný - nastavení údajů - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
class nastaveni
{
 public $chyba;
 private $idKlienta; 
 private $jmeno; 
 private $prijmeni; 
 private $telefon; 
 private $email; 
 private $variabilni; 
 private $datumReg; 
 private $noviky;
 private $datumPosledniObjednavky;
 private $celkemKC;
 private $celkemKuponu;

         function __construct($idKlienta) {
     $this->idKlienta = $idKlienta;
     $vypis_o_udaju = dibi::query("SELECT *,
             DATE_FORMAT(datum_reg,'%e.%c.%Y') as datum,
             DATE_FORMAT(posledniDatumObjednavky,'%e.%c.%Y') as datumPosObj
             FROM masaze_uzivatel WHERE masaze_uzivatel.idUziv = %i LIMIT 1",$this->idKlienta); 
     $udaje = $vypis_o_udaju->fetch();
     $this->jmeno = htmlspecialchars($udaje->jmeno, ENT_QUOTES);
     $this->prijmeni = htmlspecialchars($udaje->prijmeni, ENT_QUOTES);
     $this->telefon = htmlspecialchars($udaje->telefon, ENT_QUOTES);
     $this->email = htmlspecialchars($udaje->email, ENT_QUOTES);
     $this->variabilni = htmlspecialchars($udaje->variabilniSymbol, ENT_QUOTES);
     $this->datumReg = htmlspecialchars($udaje->datum, ENT_QUOTES);   
     $this->noviky = htmlspecialchars($udaje->zaslat_novinky, ENT_QUOTES);
     $this->datumPosledniObjednavky = htmlspecialchars($udaje->datumPosObj, ENT_QUOTES);
     $this->celkemKC = htmlspecialchars($udaje->celkovaCena, ENT_QUOTES);
     $this->celkemKuponu = htmlspecialchars($udaje->pocetKuponu, ENT_QUOTES);
 }
 function vypis_osobnich_udaju()
 {
  print '
    <h3>Výpis osobních údajů</h3>  
    <table id="tabulkaObsah" align="center">
    <tr>
     <td class="text">JMÉNO A PŘÍJMENÍ: </td>
     <td>'.$this->jmeno.' '.$this->prijmeni.'</td>
    </tr>
    <tr>
     <td class="text">TELEFON: </td>
     <td>'.$this->telefon.'</td>
    </tr>
    <tr>
     <td class="text">EMAIL: </td>
     <td>'.$this->email.'</td>
    </tr>
    <tr>
     <td class="text">VARIABILNÍ SYMBOL: </td>
     <td>'.$this->variabilni.'</td>
    </tr>
    <tr>
     <td class="text">DATUM REGISTRACE: </td>
     <td>'.$this->datumReg.'</td>
    </tr>
    <tr>
     <td class="text">DATUM PPOSLEDNÍ OBJEDNÁVKY: </td>
     <td>'.$this->datumPosledniObjednavky.'</td>
    </tr>
    <tr>
     <td class="text">CELKEM OBJEDNÁNO: </td>
     <td>'.$this->celkemKuponu." kuponů / ".$this->celkemKC.' KČ</td>
    </tr>
  </table>';

  
 }
 function upravit_osobni_udaje()
 {
  print '<h3>Upravit osobní údaje</h3><form action="nastaveni.php?menu='.intval($_GET["menu"]).'" method="post"><table id="formular" align="center">
    <tr>
     <td class="text">JMÉNO:</td>
     <td><input type="text" name="upravit_jmeno" value="'.(isset($_POST["upravit_jmeno"]) ? $_POST["upravit_jmeno"] : $this->jmeno).'" class="input" /></td>
    </tr>
    <tr>
     <td class="text">PŘÍJMENÍ:</td>
     <td><input type="text" name="upravit_prijmeni" value="'.(isset($_POST["upravit_prijmeni"]) ? $_POST["upravit_prijmeni"] : $this->prijmeni).'" class="input" /></td>
    </tr>
    <tr>
     <td class="text">TELEFON:</td>
     <td><input type="text" name="upravit_telefon" value="'.(isset($_POST["upravit_telefon"]) ? $_POST["upravit_telefon"] : $this->telefon).'" class="input" /></td>
    </tr>
    <tr>
     <td class="text">EMAIL:</td>
     <td><input type="text" name="upravit_email" value="'.(isset($_POST["upravit_email"]) ? $_POST["upravit_email"] : $this->email).'" class="input" /></td>
     <td><span title="PAMATUJTE! že pokud změníte email, změníte tím i své přihlašovací údaje!!!"><img src="Images/napoveda.jpg" alt="napověda" /></span></b>
    </tr>
    <tr>
     <td class="zarovat_right" colspan="2"><input type="submit" name="upravit_tlacitko" value="Potvrdit" /></td>
    </tr>
  </table></form>';
  
 }
 function nastaveni_uctu()
 {
  print '<h3>Jiné nastavení</h3><form action="nastaveni.php?menu='.intval($_GET["menu"]).'" method="post"><table id="tabulkaObsah" align="center">
    <tr>
     <td>ZASÍLAT NOVINKY NA EMAIL:</td>
     <td>';
      if( $this->noviky == 1)
      {
       echo "<input type='checkbox' name='novinky' checked='check' id='zaskrtavac'  /><span id='status_check'>ANO</span>";
      
      }
     else {
        echo "<input type='checkbox' name='novinky' id='zaskrtavac' /> <span id='status_check'>NE</span>";   
      }
     print '</td>
    </tr>
    </table></form>
';
 }
 function kontrola_osobnich_udaju($jmeno,$prijmeni,$telefon,$email)
 {
  $delka_jmeno = strlen($jmeno);  
  $delka_prijm = strlen($prijmeni); 
  $delka_telef = strlen($telefon); 
  $delka_email = strlen($email); 
  if($jmeno == "" or $prijmeni == "" or $email == "" )
  {
   echo "<p class='chyba'>Musíte zadat všechny povinné údaje (označené *)!</p>";
   $this->chyba = 1;
  }
  if($delka_jmeno > 99)
  {
   echo "<p class='chyba'>Jméno musí být max. 100 znaků dlouhé!</p>";
   $this->chyba = 1;
  }
  if($delka_prijm > 99)
  {
   echo "<p class='chyba'>Příjmení musí být max. 100 znaků dlouhé!</p>";
   $this->chyba = 1;
  }
  if($delka_email > 199)
  {
   echo "<p class='chyba'>Email musí být max. 200 znaků dlouhé!</p>";
   $this->chyba = 1;
  }
   if(!((preg_match("/^[\w-\.]+@([\w-]+\\.)+[a-zA-Z]{2,4}$/", $email))))
  {
   echo "<p class='chyba'>Email je ve špatném tvaru!</p>";
   $this->chyba = 1;
  }
  if(!empty($telefon))
  {
   if($delka_telef != 9)
   {
    echo "<p class='chyba'>Telefon musí být 9 znaků dlouhé!</p>";
    $this->chyba = 1;
   }   
   if(!is_numeric($telefon))
   {
   echo "<p class='chyba'>Telefon musí obsahovat pouze čísla!</p>";
    $this->chyba = 1;
   } 
  }
 }
 function aktualizace_udaju($jmeno,$prijmeni,$telefon,$email)
 {
     try {
   $aktualizace = dibi::query("UPDATE masaze_uzivatel SET jmeno = %s,prijmeni =%s,telefon=%i,email=%s WHERE idUziv = %i ",$jmeno,$prijmeni,$telefon,$email,$this->idKlienta);
   if($aktualizace)
   {
    $status = "<p class='ok'>Aktualizace dat proběhla úspěšně.</p>";
   }
   else
   {
    $status = "<p class='chyba'>Aktualizace se nezdařila.</p>";  
   }
   echo $status;        
   } catch (Exception $exc) {
         echo $exc->getMessage();
     }

  
 }
 function zasilaniNovinek($zaskrtnuto,$uzivatel)
{
 try {
 $novinky = dibi::query("UPDATE masaze_uzivatel SET zaslat_novinky = %i WHERE idUziv = %i",$zaskrtnuto,$uzivatel);
 if($novinky)
 {
  echo "Údaj se aktualizoval.";
 }
 return $novinky;
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
<h2> Nastavení </h2>
<?php
$nastaveni = new nastaveni($_SESSION["idMasazeKoudelny"]);
if(isset($_SESSION["idMasazeKoudelny"]))
{
  if(isset($_GET["zaskrtnuto"]))
  {
   $nastaveni->zasilaniNovinek($_GET["zaskrtnuto"],$_SESSION["idMasazeKoudelny"]);    
  }
 ?>
<ul id="nastaveni_menu">
   <li><a href="nastaveni.php?menu=1" title="výpis osobních údajů">Výpis osobních údajů</a></li>
   <li><a href="nastaveni.php?menu=2" title="upravit osobní údaje">Upravit osobní údaje</a></li>
   <li><a href="nastaveni.php?menu=3" title="nastavení účtu">Jiné nastavení</a></li>
</ul>
 <?php
    switch ($_GET["menu"]) {
        case 1:
        {
          $nastaveni->vypis_osobnich_udaju();   
         break;   
        }
        case 2:
        {
          if(isset($_POST["upravit_tlacitko"]))
          {
           $nastaveni->kontrola_osobnich_udaju($_POST["upravit_jmeno"], $_POST["upravit_prijmeni"], $_POST["upravit_telefon"], $_POST["upravit_email"]);
           if($nastaveni->chyba == 1)
           {
            $nastaveni->upravit_osobni_udaje();
           }
           else 
           {
            $nastaveni->aktualizace_udaju($_POST["upravit_jmeno"], $_POST["upravit_prijmeni"], $_POST["upravit_telefon"], $_POST["upravit_email"]); 
            $nastaveni->upravit_osobni_udaje(); 
           }
          }
          else
          {
           $nastaveni->upravit_osobni_udaje();     
          } 
         break;   
        }
        case 3:
        {
         $nastaveni->nastaveni_uctu(); 
         break;   
        }
        default:
           $nastaveni->vypis_osobnich_udaju();  
            break;
    }

}
else 
{
 echo "K této stránce nemáte přístup!";    
}

?>
</div>       
</div> 

</body>
</html>