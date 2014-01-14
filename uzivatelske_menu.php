<?php
function pridat_do_kosiku()
{
  if(isset($_GET["kupon"]) and !isset($_SESSION["kosik"]))
  {           
     $_SESSION["kosik"] = array();
   //dany produkt uz je v kosiku, inkrementujeme
    if(isset($_SESSION["kosik"][$_GET["kupon"]]))
    {
     $_SESSION["kosik"][$_GET["kupon"]]=$_GET["mnozstvi"];
    }         
    //tehle produkt jeste v kosiku neni
    else
    {
     $_SESSION["kosik"][$_GET["kupon"]]=$_GET["mnozstvi"];
    }
}
  if(isset($_GET["kupon"]) and isset($_SESSION["kosik"]))
  {           
   //dany produkt uz je v kosiku, pridame mnozstvi
    if(isset($_SESSION["kosik"][$_GET["kupon"]]))
    {
     $_SESSION["kosik"][$_GET["kupon"]]=$_GET["mnozstvi"];
    }         
    //tehle produkt jeste v kosiku neni
    else
    {
     $_SESSION["kosik"][$_GET["kupon"]]=$_GET["mnozstvi"];
    }
}
}
pridat_do_kosiku();

?>
<div id="kosik">
      <img src="Images/kosik.png" alt="košík" />
  <h3>Košík</h3>   
 
    <div class="polozka">
     <?php
      $celkemMnozstvi = count($_SESSION["kosik"]);
      if($celkemMnozstvi == 0)
      {
          
       echo "Košík je prázdný";
      }
      else
      {
       echo "<span>množství: </span> ".$celkemMnozstvi;
       print '<br /><a href="kosik.php" id="kosikOdkaz" class="odkaz_prihlas" title="Přejít do košíku">Přejít do košíku >></a>';
      }
          
     ?>
      </div>
    
</div>
<div id="uzivatel_menu">
<?php
echo "Je přihlášen/a <br /><b>".$_SESSION["jmenoMasazeKoudelny"]."</b>";
?>
<h3>Uživatelské menu</h3>
<ul> 
 <li><a href="zmena_hesla.php" title="Změna hesla" class="odkaz_prihlas">Změnit heslo</a></li>  
 <li><a href="nastaveni.php" title="Nastavení" class="odkaz_prihlas">Nastavení</a></li>  
 <li><a href="index.php?odhlaseni=1" title="odhlásit se" class="odkaz_prihlas">Odhlásit se</a></li>     
</ul>
</div>