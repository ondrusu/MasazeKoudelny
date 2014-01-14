<?php
session_start();
require 'pripojeni.php';
if($_GET["odhlaseni"] == 1)
{
 unset($_SESSION["idMasazeKoudelny"]);
 unset($_SESSION["jmenoMasazeKoudelny"]);
 header("Location: index.php");
}
/*
 * čištění - uživatelé (neobjednali si nic už rok) pošle se jim mail, "nic jste si neobjendali rok, čau"
 * k akcím dát obrázek
 * "stránky se opravují" => dát tam proměnnou developmer a když bude jedna tak die();
 * v uživatelském menu dát vědět o novinkách
 * napsat editaci slev
 * pokusit se o MVC
 * 
 */
?>
<!DOCTYPE>
<html xmlns="http://www.w3.org/1999/xhtml" lang="cs">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="content-language" content="cs" />
<meta name="description" content="Masérské studio Rostislav Koudelný, Brno, Lesná" />
<meta name="keywords" content="masáže, maserské studio, studio, Rostislav Koudelný, Brno, Lesná, Brno Lesná, Brno Střed, Halasovo náměstí Brno" />
<link rel="stylesheet" href="style.css" type="text/css" />
<title>Masérské studio Rostislav Koudelný - úvodní stránka - www.masazekoudelny.cz</title>  
</head>
<body>
<?php
 function vypis_uvodu()
 {
  $vypis = dibi::query("SELECT obsah FROM masaze_clanek");
  $clanek = $vypis->fetch();
  echo stripslashes($clanek->obsah);
 }
 function vypisKontakt()
{
 $vypis = dibi::query("SELECT * FROM masaze_admin WHERE idAdmin = 1 LIMIT 1");
 $kontakt = $vypis->fetch();
 print '
   <table id="tabulkaObsah">
   <tr>
    <td colspan="2"><b>'.htmlspecialchars($kontakt->jmeno, ENT_QUOTES).' '.htmlspecialchars($kontakt->prijmeni, ENT_QUOTES).'</b></td>
   </tr>
   <tr>
    <td>Telefon: </td>
    <td>'.number_format(htmlspecialchars($kontakt->telefon, ENT_QUOTES),0,'',' ').'</td>
   </tr>
   <tr>
    <td>Email: </td>
    <td><a href="mailto:'.htmlspecialchars($kontakt->email, ENT_QUOTES).'" title="email" class="odkaz_prihlas">'.htmlspecialchars($kontakt->email, ENT_QUOTES).'</a></td>
   </tr>
   <tr>
    <td>IČO: </td>
    <td>'.number_format(htmlspecialchars($kontakt->ico, ENT_QUOTES),0,'',' ').'</td>
   </tr>
   <tr>
    <td>Bankovní spojení: </td>
    <td>'.number_format(htmlspecialchars($kontakt->cisloUctu, ENT_QUOTES),0,'',' ').' / 0'.$kontakt->kodBanky.'</td>
   </tr>
   </table>';
}
function vypisNovinek()
{
 $novinky = dibi::query("SELECT *,DATE_FORMAT(datumOd,'%e.%c.%Y') as datum FROM masaze_zpravy ORDER BY idZpravy DESC");
 while ($n = $novinky->fetch())
 {
  print '<div class="novinky">'.$n->datum.'<br>'.$n->textZpravy.'</div>';  
 }
 return;
}
?> 
<div id="hlavni">
 <?php
  require 'hlavicka.php';
 ?>   
  <div id="reklamaObsah">
  <a href="http://www.hosting90.cz/cz/webhosting?refid=95194">
   <img src="http://administrace.hosting90.cz/img/afiliate/h90-whs-horizontal.gif"/>
</a></div>
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
<?php
try {
  switch ($_GET["bocni_menu"]) {
    case "o_masazich":
    {
     print '<h2> O masáži </h2>
<img src="Images/meridian_front.jpg" class="obrazek" alt="ukázka lidského děla" />
<p> Tradice masáží sahá až do starého Egypta, což je asi 7000 let. 
Největší rozmach nastal kolem roku 400 před naším letopočtem v Římě, kde byla kultura těla na velmi vysoké úrovni. 
V této době byly masáže na vrcholu a začaly se k nim připojovat další procedury (mechanoterapie, vodoléčba atd.). 
Po celá staletí se masáže vyvíjely až do dnešní podoby, základy a hlavní myšlenka jsou po tisíciletí stejné. 
Snahou masáží je uvolnit tělo (odstranění únavy, osvěžení, zmírnění až odstranění bolesti, zlepšení pohyblivosti, atd…) a navodit psychickou pohodu, která je v dnešní době nepostradatelná. 
Žijeme v době, kdy je dotyk tabuizován. 
Při vzájemných dotycích se často cítíme nepříjemně, jako by se tím někdo vkrádal do našeho vnitřního světa. 
Dotyk se zredukoval na pouhý stisk ruky, institucionalizoval se, zkomercionalizoval. 
Dotýkat se nás smějí lékaři, a to pouze za diagnostickým nebo léčebným účelem. 
Také masáž chápeme jako placenou službu provedenou profesionálem. 
Z dotyku se prostě vytratil onen citový náboj, který mu právem náleží. 
Vždyť právě hmat je prvním ze smyslů, kterým komunikujeme ze světem, a to ještě před svým narozením, v mateřském lůně. 
Po narození, dříve než se začneme orientovat podle sluchu a později i zraku, je hmat nejdůležitějším pojítkem s okolním světem. 
Dotyk rodičovské ruky, pohlazení - to je všemocný lék na bolístky i na utrpěná bezpráví. 
Zamysleme se nad tím, zda jsme si našli chvilku času na to, abychom se pomazlili s dětmi, pohladili partnera nebo poplácali přítele po ramenou. 
Zkuste masáž, pomůže Vám od napětí, nervozity, únavy či bolesti hlavy, krku nebo zad. 
Potřebujete dodat energii, rozhoupat se k dílu, se kterým pro únavu nemůžete začít? 
Leckdy pomůže povzbuzují masáž. 
<img src="Images/5159812_090922_XS.jpg" class="obrazek" alt="ukázka masáže" />
Jste celkově rozladěni, nemůžete se soustředit na práci nebo vás bolí záda? 
Cítíte nepříjemnou únavu nebo Vás snad bolí nohy po celodenní práci, nákupech či obíhání úředních záležitostí? 
I zde z velké většiny obtíže odstraní nebo alespoň zmírní masáž. 
Jste přetažení a nemůžete usnout? 
Probouzíte se v noci a máte depresivní myšlenky? 
Zkuste to s masáží.
Možná Vás překvapí nečekaný efekt. 
Naučili jste se těmto obtížím čelit polykáním léků? 
Z jednorázového užití se stal zvyk, spíše zlozvyk. 
Již několik let užíváte „prášky na spaní”, a přesto nespíte. 
Kdybyste je vysadili, mělo by to stejný účinek. 
Naučte se podobné potíže odstraňovat masáží a budete ušetřeni lékové závislosti. 
Masáže jsou nejstarší formou léčení. 
Výhodou je, že k nim nepotřebujeme skoro nic a po instruktáži od odborného maséra si můžete sami kdykoliv ulevit od bolesti pomocí jednoduchých hmatů. 
Masáž se vždy těšila zájmu, a v součastné době její obliba ještě stoupá. 
Nelze se divit. 
Při součastném životním stylu, kdy většinu aktivit provozujeme vsedě nebo při nich vykonáváme chudý pohybový stereotyp, dochází k poruchám pohybového aparátu. 
Tyto poruchy se pak projevují celou škálou nepříjemných příznaků, počínaje pocitem trvalé únavy až po vysloveně bolestivé příhody. 
Je nutné si všímat všech bolestí a nepodceňovat je. 
Prvním stupněm je únava, další je bolest a posledním stupněm je tvorba patologických změn, které mohou vytvořit značnou překážku v běžném životě. 
Proto je nutné těmto potížím předcházet a jednou z účinných metod jak lze tyto handicapující pocity umírnit jsou masáž. </p>';
     break;
    }
    case "klasicka_masaz":
    {
     print '<h2> Klasická masáž </h2>
<p> Klasická masáž (Švédská masáž) se používá pro nápravu ztuhlého svalstva, bolestivých kloubů, při bolestech páteře. 
Umí odstranit bolesti hlavy a spoustu jiných zdravotních potíží. 
Při této masáži se pracuje hlavně se svaly pohybového ústrojí. 
Masáž nebolí a zanechá na těle masírovaného pocit klidu a uvolnění.
</p>';
     break;
    }
    case "sportovni_masaz":
    {
     print '<h2> Sportovní masáž </h2>
<p> Sportovní masáž je uspořádaný sled vhodných masérských hmatů, pomáhajících sportovci zbavovat se únavy nebo ho připravit na podání plného výkonu
U sportovců se používají různé formy sportovní masáže. Sportovní masáž vychází v historii z masáže klasické, má však svá specifika, kterými se od klasické masáže liší. Je rozdíl, máme-li připravit fyzickou a psychickou kondici sportovce těsně před sportovním výkonem, nebo naopak navodit zklidnění a rychlou regeneraci po vyčerpávající námaze. Jiné jsou požadavky na masáž atleta, jiné vzpěrače nebo kanoisty. 
<br />
Sportovní masáž se po stránce technické neliší od klasické
<ul>
<li>	bývá však razantnější. </li> 
<li>	v pořadí hmatů se provádí hmaty hnětací před roztíracími</li> 
<li>	pohotovostní masáž se provádí často na hřišti nebo závodišti, kde není k dispozici masážní lehátko.</li> 
<li>	podle druhu a účelu masáže se volí buď masáž celková nebo lokální </li> 
<li>	základním předpokladem pro všechny druhy sportovní masáže je dobrý zdravotní stav celého organizmu. Užívá se jedině tehdy, jestliže víme, že není spojena s nebezpečím poškození zdraví </li> 
</ul>
Existují různé formy sportovní masáže
<ul>
<li>	přípravná, </li>
<li>	odstraňující únavu,</li> 
<li>	pohotovostní (dráždivá, uklidňující),</li> 
<li>	v přestávkách mezi výkony,</li>
<li> sportovně – kosmetická, </li>
<li>	sportovně – léčebná. </li>
</ul>
</p>';
     break;
    }
    case "reflexni_masaz":
    {
     print '<h2> Reflexní masáž </h2>
<p> Je manuální léčebný zákrok na povrchu těla aplikovaný v místě druhotných, onemocněním reflexně vyvolaných změn. 
Technika reflexní masáže vychází z poznatků o změně kožní citlivosti při onemocnění vnitřních orgánů. 
Místem zásahu tudíž není primární nemocná tkáň nebo ústrojí. 
Reflexní masáž se liší od klasické technikou hmatů a místy aplikace. 
Reflexní masáž je tvořena soustavou hmatů, které se provádějí v určitém pořadí, většinou nasucho – bez použití masážních prostředků. 
Působí tlakem přes kůži na podkožní vazivo následně na nervovou soustavu, reflexně do hloubky. 
Příznivě ovlivňuje reflexní změny způsobené onemocněním vnitřních orgánů, pohybového aparátu, zvláště páteře. 
Účinná metoda k dosažení fyzického a následně i psychického zdraví. 
Základní sestavy jsou:
<br />1. šíjová - provádí se vsedě a je indikována při krčních potížích, migrénách, závratích 
<br />2. hrudní - prováděná vsedě, nejčastěji při bolestech hrudní páteře, při poruchách hrudních orgánů, astma a chronické bronchitidě 
<br />3. zádová - prováděná vleže, při vertebrogenních poruchách 
<br />4. pánevní - část se provádí vleže a část vsedě, při bolestech v kříži, poruchách pánevních  </p>';
     break;
    }
    case "manualni_lymfodrenaz":
    {
     print '<h2> Manuální lymfodrenáž </h2>
<p>
Jedná se o speciální hmatovou techniku zaměřenou na lymfatický systém. 
Hmaty se vykonávají na kůži a podkoží jsou velkoplošné krouživé o pomalé frekvenci zachovávají směr toku lymfy. 
Cílem techniky je redukce lymfatického otoku, odtok lymfy z tkání. 
Zvýšení transportní kapacity lymfy a zvýšení rezorpce do krve. 
Tato metoda se v klinické praxi začala používat už roku 1965 v Německu.. 
Lymfatické kapiláry mají 5 až 10 krát větší průměr než kapiláry krevní díky tomu mohou pojmou velké množství tekutin. 
80 % je uloženo hluboko a 20% leží uvnitř. 
Právě povrchová kontrakce lymfy umožňuje ovlivnit jejich průtok a směr toku formou vytírání kůže = základ manuální lymfodrenáže. 
</p>';
     break;
    }
    case "kosmeticka_masaz":
    {
     print '<h2> Kosmetická masáž </h2>
<p> Kosmetická masáž patří mezi nejúčinnější úkony v kosmetice. 
Během ošetření je třeba absolutního klidu, mimické svaly jsou uvolněné. 
Gradační křivka masáže pomalu stoupá, intenzita masáže se stupňuje a ke konci opět klesá. 
Masáž působí na vrásky vzniklé únavou, aktivuje výživu pleti a zvyšuje tonus. 
Účinek je trvalý jedině tehdy, provádí-li se masáž pravidelně. 
<br /> 1. Kůže získá svěží vzhled, zvyšuje se prokrvení. 
<br /> 2. Dochází k uvolňování mazových a potních žláz. 
<br /> 3. Podporuje se látková výměna a usnadňuje se odplavení nežádoucích produktů tkáňového metabolismu. 
<br /> 4. Zmírňuje únavu a příznivě ovlivňuje celou nervovou soustavu. 
<br />Zmírňuje únavu a příznivě ovlivňuje celou nervovou soustavu. 
</p>';
     break;
    }
    case "lavove_kameny":
    {
     print '<h2>Lávové kameny </h2>
<p> V součastném hektickém způsobu života je relaxování nevyhnutelnou podmínkou přežití dnešní rychlé a moderní doby. Negativní energie vycházejí z těla ven, rozum se zbavuje pochmúrných myšlenek a zůstává jen pocit duševné a tělesné pohody. Masáž lávovými kameny přivede klienta do zprávné nálady. Tělo a mysl se uvolní a tím vytvoří klidnou atmosféru. Teplo kamenů po těle působí jako dotyk slunečných paprsků, které jemně prohřívají tělo a uvolňují napětí.
</p>
<h2>Historie </h2>
<p>Masáž horkými kameny pochází z rituálů starých severoamerických kmenových Indiánů. Byli to staré praktiky, používané jako určité druhy rituálů. Indiáni používali studené a teplé kameny na tělo, čím potlačovali napětí v těle, uvolňovali svaly, mysl a duši. Toto indiánské umění používání kamenů využili při masážních technikách, přizpůsobili a skombinovali to s osvědčenými technikami klasických masáží. Proto je to aji určitá forma alternativní medicíny pro tělo aji duši. Z určitostí nemůžeme tvrdit, že to byli Indiáni, kteří kameny začali používat jako druh terapie.
O metodě Hot stones však můžeme říct, že se stala nejvyhledávanější technikou pro svoje jedinečné a relaxační metody. </p>';
     break;
    }
    case "bankovani":
    {
     print '<h2> Baňkování </h2><p>
<img src="Images/bankovani.jpg" class="obrazek" alt="obrázek baňkování" />
<div>   1. uvolňuje svalové spasmy
<br />2. působí léčebně u celé řady systémů – lymfatický, hormonální, pohybový
<br />3. řeší problémy se zažívacími orgány, močovými a dýchacími cestami 
<br />4. má obrovský význam na detoxikaci organismu.</p>';
     break;
    }
    case "permanentky":
    {
     print '<h2> Permanentky </h2>
<p>Možnost zakoupení dárkového poukazu
<br />Možnost zakoupení permanentek: </p>
<table align="center" id="tabulkaObsah">
<tr>
<th> Množství + zdarma</th>
<th> Druh masáže </th>
<th> Cena </th>
<tr>
<td> 5+1 </td>
<td> Klasická masáž záda a šíje </td>
<td> 900 KČ </td>
</tr>
<tr>
<td>10+1 </td>
<td>Klasická masáž záda a šíje </td>
<td>	2000 KČ  </td>
</tr>
<tr>
<td> 5+1</td>
<td> Klasická masáž kosti křížové, zad a šíje </td>
<td> 	1500 KČ </td>
</tr> 				
</tr>
<tr>
<td> 5+1 </td>
<td> Klasická masáž celková	 </td>
<td> 2000 KČ </td>
</tr>   				
<tr>
<td> 5+1</td>
<td> Sportovní masáž záda a šíje</td>
<td> 1050 KČ</td>
</tr> 
<tr>
<td> 10+1</td>
<td> Sportovní masáž záda a šíje</td>
<td> 2300 KČ</td>
</tr>   		
<tr>
<td>5+1 </td>
<td>Sportovní masáž celková </td>
<td>2150 KČ </td>
</tr>  					
<tr>
<td> 5+1</td>
<td>Reflexní masáž zádová sestava  </td>
<td>1300 KČ </td>
</tr>
<tr>
<td>5+1 </td>
<td> Reflexní masáž pro šíji a hlavu</td>
<td>1300 KČ </td>
</tr>
<tr>
<td>5+1 </td>
<td>Reflexní masáž celková	 </td>
<td>	2550 KČ </td>
</tr> 					
<tr>
<td> 5+1</td>
<td>Manuální lymfodrenáž zad a krku </td>
<td>	1550 KČ </td>
</tr>
 <tr>
<td>5+1 </td>
<td>Manuální lymfodrenáž rukou	 </td>
<td>1550 KČ </td>
</tr>  					
 <tr>
<td>5+1 </td>
<td>Manuální lymfodrenáž nohou </td>
<td> 1550 KČ</td>
</tr>  		 
<tr>
<td>5+1 </td>
<td> Manuální lymfodrenáž celková	</td>
<td>	5500 KČ </td>
</tr>  		 			                                
<tr>
<td>5+1 </td>
<td> Masáž lávovými kameny </td>
<td>	800 KČ </td>
</tr>  			
<tr>
<td> 1 </td>
<td> Masáž lávovými kameny	</td>
<td> 	4550 KČ </td>
</tr> 
<tr>
<td>5+1 </td>
<td>Kosmetická masáž obličeje </td>
<td> 1050 KČ </td>
</tr>
 <tr>
<td>10+1 </td>
<td> Kosmetická masáž obličeje</td>
<td> 2300 KČ </td>
</tr>  				
<tr>
<td> 5+1</td>
<td> Masáž baňky</td>
<td>	1300 KČ  </td>
</tr> 				
 <tr>
<td> 5+1</td>
<td> Masáž míčky</td>
<td>800 KČ  </td>
</tr>
</table>						
<p>Případný zájem o větší permanentku na jakoukoliv masáž lze domluvou  </p>';   
     break;   
    }
    case "lesna":
    {
     print '<h2>Galerie fotek</h2>
<a href="Images/P1070869.JPG" title="poliklinika 1" class="galerie"><img src="Images/nahledy/P1070869.JPG" alt="Poliklinika 1" /></a>
<a href="Images/P1070871.JPG" title="poliklinika 2" class="galerie"><img src="Images/nahledy/P1070871.JPG" alt="Poliklinika 2" /></a>
<a href="Images/P1070872.JPG" title="poliklinika 3" class="galerie"><img src="Images/nahledy/P1070872.JPG" alt="Poliklinika 3" /></a>
<a href="Images/P1070873.JPG" title="poliklinika 4" class="galerie"><img src="Images/nahledy/P1070873.JPG" alt="Poliklinika 4" /></a>
<a href="Images/P1070875.JPG" title="poliklinika 5" class="galerie"><img src="Images/nahledy/P1070875.JPG" alt="Poliklinika 5" /></a>
<a href="Images/P1070876.JPG" title="poliklinika 6" class="galerie"><img src="Images/nahledy/P1070876.JPG" alt="Poliklinika 6" /></a>
<a href="Images/P1070877.JPG" title="poliklinika 7" class="galerie"><img src="Images/nahledy/P1070877.JPG" alt="Poliklinika 7" /></a>
<a href="Images/P1070879.JPG" title="poliklinika 8" class="galerie"><img src="Images/nahledy/P1070879.JPG" alt="Poliklinika 8" /></a>';   
     break;   
    }
    case "o-mne":
    {
      $omne = dibi::query("SELECT oMne FROM masaze_admin WHERE idAdmin = 1 LIMIT 1");
      $mne = $omne->fetch();
      echo "<h2>O mně</h2><img src='Images/oMne.png' alt='obrázek o mě' class='obrazek' />".$mne->oMne;  
     break;   
    }
    case "nabidka-cenik":
    {
     print '<h2> Nabídka masáží a ceník </h2>
<table id="tabulkaObsah" align="center">
<tr>
<th> Název masáže</th>
<th> Doba masáže </th>
<th> Cena masáže </th>
</tr>
<tr>
<td> Klasická masáž záda a šíje </td>
<td> 30 min </td>
<td> 220 KČ </td>
</tr>
<tr>
<td> Klasická masáž kosti křížové, zad a šíje </td>
<td> 45 min </td>
<td>  340 KČ </td>
</tr>
<tr>
<td> Klasická masáž celková </td>
<td> 1 hod </td> 
<td>440 KČ </td>
</tr>       
<tr>
<td> Klasická masáž šíje</td>
<td>20 min </td> 
<td> 150 KČ</td>
</tr>
<tr>
<td>Klasická masáž hrudníku </td>
<td> 20 min </td> 
<td>80 KČ </td>
</tr>
<tr>
<td>Klasická masáž břicha </td>
<td> 20 min</td> 
<td> 80 KČ</td>
</tr>   
<tr>
<td>Klasická masáž dolní končetiny </td>
<td> 20 min</td> 
<td> 140 KČ</td>
</tr> 
<tr>
<td>Klasická masáž horní končetiny </td>
<td>20 min </td> 
<td> 140 KČ</td>
</tr>          
</tr> 
<tr>
<td> Reflexní masáž zádová sestava</td>
<td> 30 min</td> 
<td>300 KČ  </td>
</tr>   
<tr>
<td> Reflexní masáž pro šíji a hlavu</td>
<td> 30 min</td> 
<td> 300 KČ</td>
</tr>          
<tr>
<td>Reflexní masáž sestava hrudní </td>
<td> 20 min</td> 
<td> 250 KČ</td>
</tr> 
<tr>
<td> Reflexní masáž sestava pánevní</td>
<td>20 min  </td> 
<td> 250 KČ</td>
</tr>         
<tr>
<td> Reflexní masáž celková </td>
<td> 1 hod</td> 
<td> 550 KČ</td>
</tr>
<tr>
<td> Manuální lymfodrenáž zad a krku</td>
<td> 45 min</td> 
<td> 350 KČ</td>
</tr>       
<tr>
<td>Manuální lymfodrenáž rukou </td>
<td>40 min </td>
<td>350 KČ </td>
</tr>     
<tr>
<td> Manuální lymfodrenáž nohou</td>
<td>40 min  </td>
<td> 350 KČ</td>
</tr>
<tr>
<td> Manuální lymfodrenáž celková</td>
<td> 2 hod </td>
<td>1200 KČ </td>
</tr>
<tr>
<td> Sportovní masáž záda a šíje</td>
<td> 30 min</td>
<td> 250 KČ</td>
</tr>    
<tr>
<td>Sportovní masáž horní končetiny </td>
<td> 20 min</td>
<td> 170 KČ</td>
</tr>  
<tr>
<td> Sportovní masáž dolní končetiny</td>
<td> 20 min</td>
<td>170 KČ </td>
</tr>       
<tr>
<td> Sportovní masáž celková</td>
<td>1 hod </td>
<td>470 KČ </td>
</tr>
<tr>
<td> Masáž lávovými kameny</td>
<td> 1 hod</td>
<td>500 KČ </td>
</tr>       
<tr>
<td> Masáž lávovými kameny</td>
<td> 2 hod</td>
<td> 950 KČ </td>
</tr>   
<tr>
<td> Kosmetická masáž obličeje</td>
<td>  20 min</td>
<td>  250 KČ</td>
</tr>         
<tr>
<td>Masáž baňky </td>
<td> 30 min</td>
<td> 300 KČ</td>
</tr>   
<tr>
<td>Reflexní terapie plosky nohy </td>
<td> 45 min</td>
<td> 450 KČ</td>
</tr> 
</table>';   
     break;
    }
    case "kontakt":
    {
     print '<h2> Kontakt </h2>
       <img src="Images/kontakt.png" alt="obrázek kontakt" class="obrazek" />';
       vypisKontakt();
    print '
     <h3>Poliklinika Lesná</h3>
     <p>Pondělí, středa, pátek mě najdete na poliklinice <b>Halasovo náměstí 1 Brno</b>. 
     <br />Poliklinika se nachází 200 metrů od zastávky <i>Poliklinika Lesná</i> a 300 metrů ze zastávky <i>Halasovo náměstí</i>.</p>
     <iframe width="100%" height="250" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.cz/maps?q=Halasovo+n%C3%A1m%C4%9Bst%C3%AD+1+Brno&amp;ie=UTF8&amp;hl=cs&amp;hq=&amp;hnear=Halasovo+n%C3%A1m%C4%9Bst%C3%AD+597%2F1,+638+00+Brno&amp;t=m&amp;z=14&amp;brcurrent=5,0,0&amp;ll=49.223151,16.627197&amp;output=embed"></iframe><br /><small><a href="https://maps.google.cz/maps?q=Halasovo+n%C3%A1m%C4%9Bst%C3%AD+1+Brno&amp;ie=UTF8&amp;hl=cs&amp;hq=&amp;hnear=Halasovo+n%C3%A1m%C4%9Bst%C3%AD+597%2F1,+638+00+Brno&amp;t=m&amp;z=14&amp;brcurrent=5,0,0&amp;ll=49.223151,16.627197&amp;source=embed" style="color:#0000FF;text-align:left" target="_blank" style="float: right">Zvětšit mapu</a></small>';
      break;   
    }
    case "napoveda":
    {
      print '<h2>Nápověda</h2>
<p>Nevíte si rady? Zkuste najít svoji odpověď níže.</p>
<h3>Jak se registrovat?</h3>
<p>Registrační formulář najdete <a href="registrace.php" title="Registrace">ZDE</a> a nebo odkaz pod přihlašovacím formulářem.
Registrace je jednoduchá, stačí uvést pár údajů (tj. jméno, příjmení, email, heslo a potvrzení hesla a telefon a zodpovědět kontrolní otázku) a podvrdit tlačítkem
<i>registrovat</i>. A registrace je hotová.
</p>
<h3>Jak si objednat slevový kupón?</h3>
<p>Kupón si může objednat pouze přihlášený (resp. registrovaný) uživatel. Po přihlášení stačí přejít do sekce <i>Slevy na masáže</i> a nebo klikněte
    <a href="slevy.php" title="Slevy na masáže">ZDE</a> a vybrat si kupón a množství dle potřeby. Poté stačí kliknout na tlačítko <i>objednat</i> a kupón se Vám
    vloží do košíku. Obsah košíku si můžete zkontrolovat v uživatelském menu (ve sloupčeku na levé horní straně). Pak už stačí kliknout na <i>pokračovat</i>
    a vybrat metodu platby. Po potvrzení objednávky Vám příjde email s instrukcemi (kde si můžete kupón vyzvednout, číslo účtu aj.)

</p>'; 
      break;
    }
    case "reflexni-terapie-plosky-nohy":
    {
      print '<h2>Reflexní terapie plosky nohy</h2>
<p>Základy reflexní terapie vycházejí ze známého poznatku, že na všech zakončeních lidského těla existují reflexní plošky, odpovídající příslušným orgánům nebo oblastem těla.
Dalo by se tedy říci, že chodidlo vlastně představuje celé lidské tělo. 
Během léčebného sezení terapeut pracuje na všech reflexních bodech, nacházejících se na obou nohách. 
Obvykle začne na pravé noze.
Více času stráví prací na každé ze zón souvisejících se zvláštními symptomy nebo na těch, kde citlivost ukazuje, že konkrétní část těla nepracuje, jak by měla. 
Pro dosažení kvalitního účinku se doporučuje klientům, kteří mají ztvrdlou pokožku na chodidlech, před touto procedurou navštívit pedikúru.</p>

<h2>Jak pomáhá</h2>
<p>Pomáhá odstraňovat bloky reflexních drah,
zlepšuje činnost jednotlivých orgánů a krevní oběh,
napomáhá redukovat stres,
navozuje hluboké psychické i fyzické uvolnění,
posiluje nervovou soustavu.
Výsledný účinek
Zlepší se činnost vnitřních orgánů, rozproudí se energie v těle.
Kdy nedoporučuji
v těhotenství (do 4. měsíce),
při mykózách (plísních) nohou,
při infekčních onemocněních,
při trombózách hlubokých žil,
při těžších případech osteoporózy a artritidy,
u klientů, kteří se léčí s psychózami.</p>';
      break;
    }
    case "ke-stazeni":
    {
      switch ($_GET["download"]) {
       case "letak":
       {
        header("Content-Description: File Transfer");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=soubory/letak.png");
        readfile("soubory/letak.png");
        break;  
      }
      case "cenik":
      {
        header("Content-Description: File Transfer");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=soubory/nabidka_masazi_cenik.doc");
        readfile("soubory/nabidka_masazi_cenik.doc");  
       break;  
     }
   }   
   print '<h2> Ke stažení </h2>
<img src="Images/download.png" alt="stahování" class="obrazek" />
<table align="center" id="tabulkaObsah">
<tr>
<th>název souboru</th>
<th>formát</th>
<th>stáhnout</th>
</tr>
<tr>
<td>Leták</td>
<td>.png</td>
<td><a href="stazeni.php?download=letak" title="stáhnout leták" class="odkaz_prihlas">stáhnout</a></td>
</tr>    
<tr>
<td>Nabídka masáží a ceník</td>
<td>.doc</td>
<td><a href="stazeni.php?download=cenik" title="stáhnout ceník" class="odkaz_prihlas">stáhnout</a></td>
</tr>     
</table> ';
        break;
    }
    default:
      print '<h2> Úvodní stránka </h2>';
      vypis_uvodu();
      print '<h2> Novinky a upozornění </h2>';
      vypisNovinek();
        break;
}
} catch (Exception $exc) {
    echo $exc->getMessage();
}

?>    
</div>  
    <div id="reklamaObsah">
 <a href="http://www.hosting90.cz/cz/virtualni-servery?refid=95194">
   <img src="http://administrace.hosting90.cz/img/afiliate/vps-horizontal.gif"/>
</a>       
        
    </div>

</div> 
</body>
</html>