function smazatAkci(id)
 {
   if(confirm("Opravdu chcete smazat tuto akci?")) {
         jQuery.ajax({
	   url: "slevy.php",
	   data: "smazat="+id,
	   cache: false,
	   success: function(status)
           {
             $("#radek_"+id).css("background","#f67f90");
             $("#radek_"+id).css("color","white");
             $("#radek_"+id).hide(1000);
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	               alert("Chyba - " + textStatus + " " + errorThrown);
	         }
	   });
        }  
        return false;
 }

function zmenaStavuPlatby(id,email,zpusobPlatby)
 {
   if(confirm("Opravdu chcete odstranit tuto položku za zaplacenou?")) {
         jQuery.ajax({
	   url: "objednavky.php",
	   data: "stavPlatby="+id+"&email="+email+"&platba="+zpusobPlatby,
	   cache: false,
	   success: function(status)
           {
             $("#radek_"+id).css("background","#f67f90");
             $("#radek_"+id).css("color","white");
             $("#radek_"+id).hide(1000);       
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	               alert("Chyba - " + textStatus + " " + errorThrown);
	         }
	   });
        }      

        return false;
 }
 function zmenitZobrazeno(id,hodnota)
 {
  if(confirm("Opravdu chcete změnit zobrazení?")) {
         jQuery.ajax({
	   url: "slevy.php",
	   data: "IdZobrazeno="+id+"&hodnota="+hodnota,
	   cache: false,
	   success: function(status)
           {
            alert("Akce proběhla");  
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	               alert("Chyba - " + textStatus + " " + errorThrown);
	         }
	   });
        }      

        return false;     
     
 }
 function zmenaVycerpano(idObje,idSlevy,value)
 {
     var vycerpanoHodnota = value.options[value.selectedIndex].value;
   jQuery.ajax({
	   url: "objednavky.php",
	   data: "idObje="+idObje+"&idSlevy="+idSlevy+"&hodnota="+vycerpanoHodnota,
	   cache: false,
	   success: function(status)
           {
            alert("Uloženo.");  
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	               alert("Chyba - " + textStatus + " " + errorThrown);
	         }
	   });
   return false;
 }
 
 
$(document).ready(function(){
  $("#zaskrtavac").click(function(){
    var zaskrtnuto_hodnota;
    if(this.checked)
    {
     zaskrtnuto_hodnota = 1;
    }
    else
    {
     zaskrtnuto_hodnota = 0;
    }
   jQuery.ajax({
	   url: "nastaveni.php",
	   data: "zaskrtnuto="+zaskrtnuto_hodnota,
	   cache: false,
	   success: function(status)
           {
            if(zaskrtnuto_hodnota == 1)
            {
             $("#status_check").text("ANO");
             alert(" Nyní se budou posílat na váš email informace o akcích.");
            }
            if(zaskrtnuto_hodnota == 0)
            {
             $("#status_check").text("NE");  
             alert(" Nyní se nebudou posílat na váš email informace o akcích.");
            }
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	               alert("Chyba - " + textStatus + " " + errorThrown);
	         }
	   });
         });  
$( "#dialog" ).hide();   
$("#pridatSlevu").click(function(){
   $( "#dialog" ).dialog({
      height: 410,
      width:480,
      modal: true, 
      draggable: true, 
      resizable: false, 
      buttons: {
        "Vytvořit slevu": function() {
        var nazev = document.formular.nazev.value;
        var popis = document.formular.popis.value;
        var puvodniCena = document.formular.puvodniCena.value;
        var cenaPoSleve = document.formular.cena.value;    
        if(nazev === "" || popis === "" || puvodniCena === "" || cenaPoSleve === "")  
        {
         alert("Musíte vyplnit všechny údaje!\n");
         return 0;
        }   
        if(nazev.lenght > 49)
        {
         alert("Název musí být maximálně 50 znaků dlouhý.\n");
         return 0;
        }
        if(isNaN(parseInt(puvodniCena)) || isNaN(parseInt(cenaPoSleve)))
        {
         alert("Původní cena nebo cena po slevě nesmí obsahovat text.\n");
         return 0;
        }
        jQuery.ajax({
	   url: "slevy.php",
	   data: "nazev="+nazev+
                 "&popis="+popis+
                 "&puvodniCena="+puvodniCena+
                 "&cenaPoSleve="+cenaPoSleve,
	   cache: false,
	   success: function()
           {
            alert("Slevový kupón byl vytvořen.");
            document.formular.reset();
	   },
	   error: function(XMLHttpRequest, textStatus, errorThrown) {
	               alert("Chyba - " + textStatus + " " + errorThrown);
	         }
	   }); 
        },
        "Zavřít": function() {
          $( this ).dialog( "close" );
          location.reload();
        }
      }
           
});
});


 
/*konec-document-ready*/    
});