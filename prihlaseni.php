<?php
class prihlaseni
{
 public $chyba;
 private $email;
 private $heslo;
 public function __construct($email,$heslo) {
     $this->email = $email;
     $this->heslo = $heslo;
 }
 function form_prihlas()
 {
  print '<h3 style="color:white">Přihlášení</h3>
  <table id="formular" align="center"><form action="index.php" method="post">
  <tr>
   <td class="text">EMAIL:</td>
   <td><input type="text" name="login_email" class="input" /></td>
  </tr>
  <tr>
   <td class="text">HESLO:</td>
   <td><input type="password" name="login_heslo" class="input" /></td>
  </tr>
  <tr>
   <td colspan="2" align="center"><input type="submit" name="login_tlacitko" value="příhlásit se" /></td>
  </tr>
  <tr>
   <td colspan="2"><a href="zapomenute_heslo.php" title="zapomenuté heslo" class="odkaz_prihlas">zapomněli jste heslo?</a></td>
  </tr>  
  <tr>
  <td colspan="2"><a href="registrace.php" title="registrace" class="odkaz_prihlas">registrace</a></td>
  </tr> 
 </form></table>';  
 }
 function kontrola_prihlas()
 {
  if(!((preg_match("/^[\w-\.]+@([\w-]+\\.)+[a-zA-Z]{2,4}$/", $this->email))))
  {
   echo "<div class='chyba'>Email není ve správném tvaru!</div>"; 
   $this->chyba = 1;
  } 
  $heslo_sifra = sha1("#@%$007MaSaZe".$this->heslo);
  $SelectLogin = dibi::query("SELECT * FROM masaze_uzivatel WHERE email=%s AND heslo=%s
         ",$this->email,$heslo_sifra);
  $radek = count($SelectLogin);
  $log_adm = $SelectLogin->fetch();
  if($radek == 1)
  {   
    $_SESSION["idMasazeKoudelny"] = htmlspecialchars($log_adm->idUziv, ENT_QUOTES);
    $_SESSION["jmenoMasazeKoudelny"] = htmlspecialchars($log_adm->jmeno, ENT_QUOTES)." ".htmlspecialchars($log_adm->prijmeni, ENT_QUOTES);
  }
  else 
  {
   echo "<p class='chyba'>Přihlašovací údaje jsou chybné!</p>";
   $this->chyba = 1;    
  }
   return $SelectLogin;     
 }
 function uzivatelske_menu()
 {
  require 'uzivatelske_menu.php';
 }

}
$prihlaseni = new prihlaseni($_POST["login_email"],$_POST["login_heslo"]);
try {
    if(isset($_POST["login_tlacitko"]))
   {
    $prihlaseni->kontrola_prihlas();
    if($prihlaseni->chyba == 1)
    {
     $prihlaseni->form_prihlas();
    }
    else {
    $prihlaseni->uzivatelske_menu();    
    }
   }
   else
   {
    if(isset($_SESSION["idMasazeKoudelny"]))
    {
     $prihlaseni->uzivatelske_menu(); 
    }
    else 
    {
     $prihlaseni->form_prihlas();   
    }
    
   }   
} catch (Exception $exc) {
    echo $exc->getMessage();
}

?>
