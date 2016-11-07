# Joomla_oauth_plugin

Joomla plugin for logging in with adatom.hu ADA service

Anonim Digitális Azonosítás (ADA) integráció joomla 3.x rendszerhez
===================================================================

Licensz: GNU/GPL
Szerző: Tibor Fogler 
Szerző email: tibor.fogler@gmail.com
Szerző web: adatmagus.hu
Verzió: 2.00   2016.03.04

A Joomla rendszernek https: -el is elérhetőnek kell lennie.
Ha a látogató belépet az ADA login képernyőn; akkor a Joomla rendszer kezdőlapra kerül.

ADA login - Joomla integráció előkészítése
==========================================
Lépj kapcsolatba az ADA rendszer adminisztrátorával (info@adatom.hu), megadandó adatok:
   A Joomla rendszer domain neve (pl: li-de.tk)
   Redirect link: https://yourdomain.hu/adalogin/index.php  (pl: https:/li-de.tk/adalogin/index.php)
Az ADA rendszer adminisztrátorától megkapod a következőket:
   application key ($appkey)
   secret ($secret)

Telepítés
=========
1. Másold be az "adalogin" könyvtárat a "{DOCUMENT_ROOT}" alá
2. Módositsd a {DOCUMENT_ROOT}/adalogin/sso-config.php file-ban található beállításokat.
   Megjegyzés: a PSW adatba egy tetszőlegesen választott, min 6 karakteres alfanumerikus jelszót irjál.
3. A web oldaladon helyezz el egy "Belépés" (login) linket ami a 
   {SITE_ROOT/adalogin/index.php} -ra mutat!

Müködés
=======
Amikor egy felhasználó a "login" linkre kattint akkor átirányitásra kerül az
ADA rendszerbe, ahol regsiztrálhatja magát (ha ezt korábban még nem tette meg),
illetve "beléphet". Sikeres belépés után az ADA rendszer vissza adja a vezérlést
az adalogin/index.php -nak.

Amennyiben az adott ADA azonosítóval ez az első belépési kisérlet a joomla rendszerbe;
akkor megjelenik egy képernyő ahol a felhasználó láthatja az 
"ADA azonosítóját" és "ADA email címét". Most választhat magának egy "álnevet" amivel
a Joomla rendszerben szerepelni fog. 

Az álnév megadása után a rendszer ellenörzi, hogy
az álnév nem foglalt-e? Ha foglalt a felhasználó hibaüzenetet kap és új álnevet
adhat meg. A megfelelő álnév megadása után létrejön egy "joomla felhasználói adat", ahol a usernév
a választott álnév, email cím pedig az "ADA email". Ezután a felhasználó ezzel a "belépéssel"
automatikusan beléptetődik a joomla rendszerbe, és a kezdő lapra kerül.

A késöbbiekben az ADA rendszeren keresztüli bejelentkezés után a felhasználó
ezzel a "belépéssel" automatikusan beléptetődik a joomla rendszerbe és a kezdő 
lapra kerül.

A joomla admin felületen a generált felhasználói adat jogosultságai beállíthatóak.
Alepértelmezetten az újonnan létrehozodd ADA belépési adatok a "Resgisztrált" csoport tagjai.

Biztonsági figyelmeztetés
=========================
Az "appkey", "secret", "psw" adatok
bizalmasan kezelendőek, azok illetéktelen kezekbe kerülése lehetőséget adhat a 
joomla rendszerbe történő illegális belépésre.











