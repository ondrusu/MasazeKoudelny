<div id="autor">
  <a href="http://www.oc.masazekoudelny.cz/" title="Stránky tvůrce"><img src="Image/logo_author.png" alt="Logo autora stránek" /></a>
</div>  
<?php
 function kdo_prihlasen($uzivatel)
 {
  $vyber_uziv = dibi::query("SELECT * FROM masaze_admin WHERE idAdmin = %i LIMIT 1",$uzivatel);
  $makler = $vyber_uziv->fetch();
   echo $makler->jmeno." ".$makler->prijmeni;
   return $vyber_uziv;        
 }

?>
<div id="makler_header">
<?php
if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
{
 echo "Přihlášen/a ";
 kdo_prihlasen($_SESSION["idAdminMaserskeStudioKoudelny"]);   
}
else 
{
 echo "Nikdo nepřihlášen.";    
}
?>
</div>
<div id="logo_spol"></div>   
<h1>Maserské studio - Rostislav Koudelný</h1>
<h2>administrační systém</h2> 