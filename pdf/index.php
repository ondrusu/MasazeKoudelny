<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="content-language" content="cs" />
<meta name="description" content="Masérské studio Koudelný" />
<meta name="keywords" content="Masérské studio Koudelný" />
<meta name="description" content="Masérské studio Koudelný" />
<title>Masérské studio Rostislav Koudelný - pdf - www.maserske-studio.wz.cz</title>  
</head>
<body>
<?php
include("mpdf.php");
require '../pripojeni.php';
echo count($vypis);
try {
$vypis = dibi::query("
         SELECT *,DATE_FORMAT(masaze_objednavky.datumObjednani,'%d.%m.%Y') as datumObjednani
         FROM masaze_slevy,masaze_objednavky 
         INNER JOIN masaze_objednavky_mn 
         ON masaze_objednavky_mn.idObje = masaze_objednavky.idObje 
         WHERE masaze_objednavky.IdUziv = (SELECT idUziv FROM masaze_uzivatel WHERE email = %s) 
         AND masaze_slevy.idSl = masaze_objednavky_mn.idSl
         AND masaze_objednavky.stav = 1
 ","ondras.cepec@gmail.com");

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
   <br /><span style="font-size:0.9em">Platnost: 3 měsíce od zakoupení</span></p>
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
 
/*$mpdf=new mPDF('UTF-8',array(297,209));
$mpdf->WriteHTML($html);
$mpdf->AddPage();
$mpdf->WriteHTML($html);
$mpdf->Output("slevovyKupon.pdf","D"); 

 */
?>
</body>
</html>