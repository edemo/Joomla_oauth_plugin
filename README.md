# Joomla_oauth_plugin7

Joomla plugin for logging in with adatom.hu ADA service

Anonim Digitális Azonosítás (ADA) integráció joomla 3.x rendszerhez
===================================================================

Licensz: GNU/GPL
Szerző: Tibor Fogler 
Szerző email: tibor.fogler@gmail.com
Szerző web: adatmagus.hu
Verzió: 4.00   2016.11.10.

A Joomla rendszernek https: -el is elérhetőnek kell lennie.
Ha a látogató belépet az ADA login képernyőn; akkor a "redi" URL paraméterben megadott oldalra kerül.

ADA login - Joomla integráció előkészítése
==========================================
Lépj kapcsolatba az ADA rendszer adminisztrátorával (info@adatom.hu), megadandó adatok:
   A Joomla rendszer domain neve (pl: li-de.tk)
   Redirec link: https://yourdomain.hu/adalogin/index.php  (pl: https:/li-de.tk/adalogin/index.php)
Az ADA rendszer adminisztrátorától megkapod a következőket:
   application key ($appkey)
   secret ($secret)

Telepítés
=========
1. Másold be az "adalogin" könyvtárat a "{DOCUMENT_ROOT}/tmpl" könyvtárba
2. Telepitsd a com_adalogin komponenst a Joomla bövitmény kezelővel a "telepités könyvtárból" funkciót használva.
3. Konfigurált az "Adalogin" komponenst a Joomla admin felületen (appkey és secret beírása)
4. A menüben alakits ki egy "ADA_login" linket, itt adhatod meg azt az oldalt ahová a felhasználó sikeres belépés után kerül.
   Cpmponent scriptből is hivhatod a login eljárást:
   
   $controller->setRedirect(JURI::base().'index.php?option=com_login&redi='.base64_encode('secces_login_redirect_uri'));
   $controller->redirect();
   

Müködés
=======
Amikor egy felhasználó a "login" linkre kattint akkor átirányitásra kerül az
ADA rendszerbe, ahol regsiztrálhatja magát (ha ezt korábban még nem tette meg),
illetve "beléphet". Sikeres belépés után az ADA rendszer vissza adja a vezérlést
a JURI::base().'components/com_adalogin/index.php' filenak.

Amennyiben az adott ADA azonosítóval ez az első belépési kisérlet a joomla rendszerbe;
akkor megjelenik egy képernyő ahol a felhasználó láthatja az 
"ADA azonosítóját" és "ADA email címét". Most választhat magának egy "álnevet" amivel
a Joomla rendszerben szerepelni fog. 

Az álnév megadása után a rendszer ellenörzi, hogy
az álnév nem foglalt-e? Ha foglalt a felhasználó hibaüzenetet kap és új álnevet
adhat meg. A megfelelő álnév megadása után létrejön egy "joomla felhasználói adat", ahol a usernév
a választott álnév, email cím pedig az "ADA email". Ezután a felhasználó ezzel a "belépéssel"
automatikusan beléptetődik a joomla rendszerbe, és a menü paraméterben vagy "redi" adatban magadott lapra kerül.

A késöbbiekben az ADA rendszeren keresztüli bejelentkezés után a felhasználó
ezzel a "belépéssel" automatikusan beléptetődik a joomla rendszerbe és a  menü paraméterben vagy "redi" adatban 
megadott lapra kerül.



A joomla admin felületen a generált felhasználói adat jogosultságai beállíthatóak.
Alepértelmezetten az újonnan létrehozodd ADA belépési adatok a "Resgisztrált" csoport tagjai.
FIGYELEM az ilyen user jelszavát és email címét SOHA NE VÁLTOZTASSUK MEG!

Biztonsági figyelmeztetés
=========================
Az "appkey", "secret", "psw" adatok
bizalmasan kezelendőek, azok illetéktelen kezekbe kerülése lehetőséget adhat a 
joomla rendszerbe történő illegális belépésre.











