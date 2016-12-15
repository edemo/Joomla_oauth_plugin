# Joomla_oauth_plugin

Joomla plugin for logging in with adatom.hu ADA service

IN ENGLISH
==========

Anonymous Digital Identification (ADA) system integration, Joomla 3.x
=====================================================================
see: https://adatom.hu/

License: GNU / GPL
Author: Tibor Fogler
Author email: tibor.fogler@gmail.com
Web Author: adatmagus.hu
Version 4.01 15/12/2016.

Version info
============
2016.10.15  V 4.01 powered extrafields, logout function


The Joomla system https: were posted should also be available.
When a visitor enters the ADA login screen; it is a "redi" URL parameter specified page.

ADA login - Joomla integration preparation
==========================================
ADA contact the system administrator (info@adatom.hu) Data to be given:
   The Joomla system domain name (eg: li-de.tk)
   Redirect link https://yourdomain.hu/components/com_adalogin/index.php 
The ADA system administrators will receive the following:
   Key application (appkey)
   Secret (Secret)

Installation
============
1. Copy the "adalogin" folder in the "{DOCUMENT_ROOT} / tmpl" directory
2. Install the com_adalogin component for Joomla extensions manager
3. Configure the "Adalogin" component for Joomla admin panel (entering appkey and secret)
4. Provide a menu "ADA_login" link, you go to the page on which the user comes after a successful entry.
   Component scripts can also call the login method:
   
   $Controller->setRedirect (JURI::base().'index.php?option=com_login&redi='.base64_encode( 'secces_login_redirect_uri'));
   $Controller->redirect();
   

Operation
=========
When a user clicks on the "login" menu you will be redirected to the
ADA system where regsiztrálhatja yourself (if you have not already done so)
or "enter". After a successful entry into the system returns control to ADA
JURI::base().'components/com_adalogin/index.php' file.

In case the ADA identifier is the first attempt to joomla entry system;
you will see a form where the user can see the
"ADA ID" and "ADA e-mail address." Now you can choose an "alias" which
It will be included in the Joomla system.

After entering the alias, the system verifies that
the alias is not busy? If busy  user receives an error message contained new alias
You can enter. After setting the correct pseudonym created a "joomla user data", where the user name
the chosen name, email address and the "ADA email". Then, the user with the "entry"
automated logged into joomla, and the menu parameter or "redi" Data is the core that tab.

After a subsequently sign over the ADA system to the user
this "entry" automatic loged in the Joomla system and menu parameter or "redi" data
It is specified tab.



The Joomla admin permissions can be configured to generate user data.
Default is set up in the newly ADA data entry members of the "Resgistered" group.
WARNING such user's password and email address NEVER CHANGE!

security alert
=========================
The "appkey", "secret", "PSW" data
should be treated as confidential, they are avoiding the wrong hands could allow the
joomla illegal entry into the system.

MAGYAR
======

Anonim Digitális Azonosítás (ADA) integráció joomla 3.x rendszerhez
===================================================================
Lásd: https://adatom.hu

Licensz: GNU/GPL
Szerző: Tibor Fogler 
Szerző email: tibor.fogler@gmail.com
Szerző web: adatmagus.hu
Verzió: 4.01   2016.12.15.

Verzió infó
2016.12.15 V 4.01 extrafields támogatás, logout funkció

A Joomla rendszernek https: -el is elérhetőnek kell lennie.
Ha a látogató belépet az ADA login képernyőn; akkor a "redi" URL paraméterben megadott oldalra kerül.

ADA login - Joomla integráció előkészítése
==========================================
Lépj kapcsolatba az ADA rendszer adminisztrátorával (info@adatom.hu), megadandó adatok:
   A Joomla rendszer domain neve (pl: li-de.tk)
   Redirect link: https://yourdomain.hu/components/com_adalogin/index.php  
Az ADA rendszer adminisztrátorától megkapod a következőket:
   application key (appkey)
   secret (secret)

Telepítés
=========
1. Másold be az "adalogin" könyvtárat a "{DOCUMENT_ROOT}/tmpl" könyvtárba
2. Telepitsd a com_adalogin komponenst a Joomla bövitmény kezelővel
3. Konfigurált az "Adalogin" komponenst a Joomla admin felületen (appkey és secret beírása)
4. A menüben alakits ki egy "ADA_login" linket, itt adhatod meg azt az oldalt ahová a felhasználó sikeres belépés után kerül.
   Component scriptből is hivhatod a login eljárást:
   
   $controller->setRedirect(JURI::base().'index.php?option=com_login&redi='.base64_encode('secces_login_redirect_uri'));
   $controller->redirect();
   

Müködés
=======
Amikor egy felhasználó a "login" menüpontra kattint akkor átirányitásra kerül az
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



