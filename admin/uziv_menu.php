<ul>
 <?php
  if(isset($_SESSION["idAdminMaserskeStudioKoudelny"]))
  {
   print '
    <li><a href="http://www.masazekoudelny.cz" target="_blank">MASÁŽE</a></li>
    <li><a href="index.php?adminMenu=cisteni">Vyčištění databáze</a></li>
    <li><a href="index.php">Úvodní strana</a></li>
    <li><a href="admin_udaje.php">Admin údaje</a></li>
    <li><a href="zmena_hesla.php">Změnit heslo</a></li>
    <li><a href="index.php?odhlaseni">Odhlásit</a></li>';
  }
  else
  {
   print '
    <li><a href="http://www.masazekoudelny.cz" target="_blank">MASÁŽE</a></li>
    <li><a href="index.php">Přihlásit</a></li>';
  }
 ?>
</ul>