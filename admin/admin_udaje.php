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
<title> Administrátorské údaje - Administrace masazekoudelny.cz</title>
</head>
<body>  
<?php
 class administratorske_udaje
 {
  public $chyba;
  
  private $prihlasEmail;
  private $jmeno;
  private $prijmeni;
  private $telefon;
  private $omne;
  private $ico;
  private $bankaUcet;
  private $bankaKod;
  private $administrator;
  private $status;
  
  public $error_jmeno;
  public $error_prijmeni;
  public $error_email;
  public $error_telefon;
  public $error_omne;
  public $error_ico;
  public $error_banka;

  function __construct($prihlasEmail,$jmeno,$prijmeni,$omne,$telefon,$ico,$bankaUcet,$bankaKod,$administrator,$status) {
      $this->prihlasEmail = $prihlasEmail;
      $this->jmeno = $jmeno;
      $this->prijmeni = $prijmeni;
      $this->telefon = $telefon;
      $this->omne = $omne;
      $this->ico = $ico;
      $this->bankaUcet = $bankaUcet;
      $this->bankaKod = $bankaKod;   
      $this->administrator = $administrator;   
      $this->status = $status;
  }
  function admin_menu()
  {
   require("menu.php");
  }
  function upravit_udaju() 
  {
   $e_udaje = dibi::query("SELECT * FROM masaze_admin WHERE idAdmin = %i LIMIT 1",$this->administrator);  
   $admin_u = $e_udaje->fetch();
   print '<form action="admin_udaje.php?editace_udaj='.$this->administrator.'" method="post" id="form_upload">
   <table align="center" id="sprava_tabulka">
    <tr>
     <td>Přihlašovací email:*</td>
     <td><input type="text" name="upravit_email" id="upravit_jmeno" value="';
     if($this->prihlasEmail)
     {
      echo htmlspecialchars($this->prihlasEmail, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->email, ENT_QUOTES);   
     }
   
    print '" class="input_sprava" /><div class="chybova_hlaska">'.$this->error_email.'</div></td>
    </tr> 
    <tr>
     <td>Jméno:*</td>
     <td><input type="text" name="upravit_jmeno" id="upravit_jmeno" value="';
     if($this->jmeno)
     {
      echo htmlspecialchars($this->jmeno, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->jmeno, ENT_QUOTES);   
     }
   
    print '" class="input_sprava" /><div class="chybova_hlaska">'.$this->error_jmeno.'</div></td>
    </tr> 
    <tr>
     <td>Příjmení:*</td>
     <td><input type="text" name="upravit_prijmeni" id="upravit_prijmeni" value="';
     if($this->prihlasEmail)
     {
      echo htmlspecialchars($this->prijmeni, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->prijmeni, ENT_QUOTES);   
     }
     print '" class="input_sprava" /><div class="chybova_hlaska">'.$this->error_prijmeni.'</div></td>
    </tr> 
    <tr>
     <td>Tel. číslo:*</td>
     <td><input type="text" name="upravit_telefon" id="upravit_telefon" value="';
     if($this->telefon)
     {
      echo htmlspecialchars($this->telefon, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->telefon, ENT_QUOTES);   
     }
     print '" class="input_sprava" /><div class="chybova_hlaska">'.$this->error_telefon.'</div></td>
    </tr> 
    <tr>
     <td>O mně:</td>
     <td><textarea name="upravit_mne" class="dlouhy_text_edit">';
     if($this->omne)
     {
      echo htmlspecialchars($this->omne, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->oMne, ENT_QUOTES);   
     }
     print '</textarea><div class="chybova_hlaska">'.$this->error_omne.'</div></td>
    </tr>
    <tr>
     <td>IČO:</td>
     <td><input type="text" name="upravit_ico" id="upravit_me" value="';
     if($this->ico)
     {
      echo htmlspecialchars($this->ico, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->ico, ENT_QUOTES);   
     }
     print '" class="input_sprava" /><div class="chybova_hlaska">'.$this->error_ico.'</div></td>
    </tr>
    <tr>
     <td>Banka:</td>
     <td><input type="text" name="upravit_ucet" id="upravit_me" value="';
     if($this->bankaUcet)
     {
      echo htmlspecialchars($this->bankaUcet, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->cisloUctu, ENT_QUOTES);   
     }
     print '" class="input_sprava" style="width:185px" /> / <input type="text" name="upravit_kodBanky" id="upravit_me" value="';
     if($this->bankaKod)
     {
      echo htmlspecialchars($this->bankaKod, ENT_QUOTES);   
     }
     else 
     {
      echo htmlspecialchars($admin_u->kodBanky, ENT_QUOTES);   
     }
     print '" class="input_sprava" style="width:50px" /><div class="chybova_hlaska">'.$this->error_banka.'</div></td></td>
    </tr>
     <tr>
     <td>Status:</td>
     <td>'; ?>
    <select name="status">
        <option value="1" <?= ($admin_u->status == 1 ? "selected='select'" : "")?>>Nepřítomný</option>
        <option value="0" <?= ($admin_u->status == 0 ? "selected='select'" : "") ?>>Přítomný</option>
    </select>
<?php
     print '</td>
    </tr>
    <tr>
     <td><a href="admin_udaje.php" title="zpět na výpis údajů">ZPĚT</a>
     <td align="right"><input type="submit" name="upravit_ulozit" class="potvrzeni_btn" value="Uložit údaje" /></td>
    </tr> 
   </table></from>';  

   return $e_udaje;   
  }
  function kontrola_udaju()
  {    
   $delka_jmeno = strlen($this->jmeno);
   $delka_prijm = strlen($this->prijmeni);
   $delka_email = strlen($this->prihlasEmail);
   $delka_telef = strlen($this->telefon);
   $delka_omne = strlen($this->omne);  
   if(!empty($this->omne))
   {
    if($delka_omne > 1000)
    {
     $this->error_omne = "O mně může být jen na 100 znaků!";
     $this->chyba = 1;   
    }  
   }  
   if($delka_jmeno > 100)
   {
     $this->error_jmeno = "Jméno je moc dlouhé. MAX 200 znaků!";
     $this->chyba = 1;
   }
   if($delka_prijm > 100)
   {
    $this->error_prijmeni = "Příjmení je moc dlouhé. MAX 200 znaků!";
     $this->chyba = 1;
   }
   if($delka_email > 100)
   {
    $this->error_email = "Email je moc dlouhé. MAX 200 znaků!";
     $this->chyba = 1;
   }
   if($delka_telef != 9 or !is_numeric($this->telefon))
   {
    $this->error_telefon = "Délka telefonu misí být MAX 9 číslic!</p>";
     $this->chyba = 1;
   }
   if(!((preg_match("/^[\w-\.]+@([\w-]+\\.)+[a-zA-Z]{2,4}$/", $this->prihlasEmail))))
   {
    $this->error_email = "Email není ve správném tvaru!</p>";
     $this->chyba = 1;      
   }
   if(!is_numeric($this->ico))
   {
    $this->error_ico = "Ičo musí být číslo!</p>";
    $this->chyba = 1;     
   }
   if(!is_numeric($this->bankaUcet))
   {
    $this->error_banka = "Číslo účtu musí být číslo!";
    $this->chyba = 1;     
   }
   if(!is_numeric($this->bankaKod))
   {
    $this->error_banka = "Kód banky musí být číslo!";
    $this->chyba = 1;     
   }
  }  
  function vlozeni_do_db()
  {
   $vlozeni = dibi::query("UPDATE masaze_admin SET jmeno = %s,prijmeni = %s,email = %s,telefon =%i,oMne = %s,ico= %i,cisloUctu = %i,kodBanky = %i, status = %i WHERE idAdmin = %i",$this->jmeno,$this->prijmeni,$this->prihlasEmail,$this->telefon,$this->omne,$this->ico,$this->bankaUcet,$this->bankaKod,$this->status,$this->administrator);    
   return $vlozeni;
  }
 }
$admin_udaj = new administratorske_udaje(
        $_POST["upravit_email"],
        $_POST["upravit_jmeno"],
        $_POST["upravit_prijmeni"],
        $_POST["upravit_mne"],
        $_POST["upravit_telefon"],
        $_POST["upravit_ico"],
        $_POST["upravit_ucet"],
        $_POST["upravit_kodBanky"],
        $_SESSION["idAdminMaserskeStudioKoudelny"],
        $_POST["status"]
        ); 
?>    
<div id="header">
 <?php
  require 'header.php';
 ?>       
</div>  
<div id="stred">
 <div id="text"> 
 <h2>Administrátorské údaje</h2>
  <?php 
 if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   if(isset($_POST["upravit_ulozit"])) 
   {
    $admin_udaj->kontrola_udaju();
    if($admin_udaj->chyba == 1)
    {
     $admin_udaj->upravit_udaju();   
    } 
    else
    {  
     echo "<p class='je_ok'>Údaje byly aktualizovány.</p>";
     $admin_udaj->vlozeni_do_db();
     $admin_udaj->upravit_udaju(); 
    }    
   }
   else 
   {
    $admin_udaj->upravit_udaju();  
   }
  }
  else
  {
   echo "<p id='NeniPristup'>Nemáte přístup ke stránce. Prosím přihlašte se <a href='index.php' title='Přihlašovací stránka'>ZDE</a></p>";
  }
?>   
 </div> 
 <div id="menu">
  <?php
  if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   $admin_udaj->admin_menu();  
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