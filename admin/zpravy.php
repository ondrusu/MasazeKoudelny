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
<title>Zprávy uživatelům - Administrace masazekoudelny.cz</title>
</head>
<body>
<?php
class zpravy
{
function admin_menu()
 {
  require("menu.php");
 }
 function zpravyUzivatelum($predmet,$zprava)
{
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
  $emaily = dibi::query("SELECT email FROM masaze_uzivatel");
  while ($e = $emaily->fetch())
  {
   $mail->AddAddress($e->email);   
  }
  $mail->Subject = $predmet; 
  
  $mail->Body = '
     <div>
      <div style="width:100%;border-bottom: 3px solid red">
       <img src="http://masazekoudelny.cz/Images/logo.png" alt="logo" style="float:left;width:100px" />
       <h3 style="font-size: 1.5em;line-height: 80px">Maserské studio Rostislav Koudelný</h3>
      </div>   
      <div id="textEmailu">'.$zprava.'</div>
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
     print '<p class="ok">Emaily byly odeslány!</p>';  
    }         
 }     
 function nepritomnost($uzivatel,$zpravaText)
 {
     try {
       $nepritomnost = array(
	 "datumOd" => date("Y-m-d"),
         "textZpravy" => $zpravaText
       );
       $status = dibi::query("UPDATE masaze_admin SET status = 1 WHERE idAdmin = %i",$uzivatel); 
       $zprava = dibi::query("INSERT INTO masaze_zpravy",$nepritomnost);
       
       if($status)
       {
        echo "<p class='je_ok'>Status byl nastaven na nepřítomný.</p>";
       }
       if($zprava)
       {
        echo "<p class='je_ok'>Zpráva se vložila do databáze.</p>";           
       }
     } 
     catch (Exception $ex) {
         echo $ex->getMessage();
     }
     return;
   // nastavi se status na 1
   // ulozi se na zed zprava
 }
 function novinka($zpravaText)
 {
     try {
       $novinka = array(
	 "datumOd" => date("Y-m-d"),
         "textZpravy" => $zpravaText
       );  
       $zprava = dibi::query("INSERT INTO masaze_zpravy",$novinka);
       if($zprava)
       {
        echo "<p class='je_ok'>Zpráva se vložila do databáze.</p>";      
       }
     } 
     catch (Exception $exc) {
         echo $exc->getMessage();
     }   
     return;
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
   if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
 {  
 $zpravy = new zpravy();
 if(isset($_POST["odeslat"]))
 {
     foreach ($_POST["zalezitost"] as $value) {
     switch ($value) {
         case 0:
         if(isset($_POST["predmet"]) && isset($_POST["zprava"]))
         {
         $zpravy->zpravyUzivatelum($_POST["predmet"], stripslashes($_POST["zprava"]));   
         }
          break;
         case 1:
         if(isset($_POST["zprava"]))
         {
          $zpravy->nepritomnost($_SESSION["idAdminMaserskeStudioKoudelny"], stripslashes($_POST["zprava"]));   
         }
         break;
         case 2:
         if(isset($_POST["zprava"]))
         {
           $zpravy->novinka(stripslashes($_POST["zprava"]));  
         }
         

             break;
     }
    }
 }
 
 
 ?>
    <form action="zpravy.php" method="post"algin="center">
      <table id="sprava_tabulka">      
       <tr>
       <td>Předmět</td>
       <td align="left"><input type="text" name="predmet" class="input_sprava" /></td>
      </tr>   
       <tr>
       <td>Záležitost*</td>
       <td align="left">
           <label for="0">Poslat emailem: <input type="checkbox" name="zalezitost[]" value="0" /></label><br>
           <label for="1">Nepřítomnost: <input type="checkbox" name="zalezitost[]" value="1" /></label><br>
           <label for="2">Novinka: <input type="checkbox" name="zalezitost[]" value="2" /></label>
      <!--     <select name="zalezitost">
               <option value="0">Zpráva</option>
               <option value="1">Nepřítomnost</option>
               <option value="2">Novinka</option>
           </select>   -->      
       </td>
      </tr>  
       <tr>
       <td>Zpráva*</td>
       <td><textarea name="zprava" class="ckeditor"></textarea></td>
      </tr>
       <tr>
       <td colspan="2"><input type="submit" name="odeslat" value="Odeslat" /></td>
      </tr>
      </table> 
    </form>
             <?php
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
   $zpravy->admin_menu();  
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