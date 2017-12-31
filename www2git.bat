@echo on
SET repo=e:\github-repok\Joomla_oauth_plugin\adalogin
SET www=e:\www\elovalasztok

COPY  %www%\administrator\components\com_adalogin\*.xml             %repo% 
XCOPY %www%\components\com_adalogin                                 %repo%\site /Y /S /E
XCOPY %www%\administrator\components\com_adalogin                   %repo%\admin /Y /S /E

COPY  %www%\language\en-GB\en-GB.com_adalogin.ini                   %repo%\site\langauge 
COPY  %www%\language\hu-HU\hu-HU.com_adalogin.ini                   %repo%\site\langauge 

COPY  %www%\administrator\language\en-GB\en-GB.com_adalogin.ini     %repo%\admin\langauge 
COPY  %www%\administrator\language\hu-HU\hu-HU.com_adalogin.ini     %repo%\admin\langauge
COPY  %www%\administrator\language\en-GB\en-GB.com_adalogin.sys.ini %repo%\admin\langauge
COPY  %www%\administrator\language\hu-HU\hu-HU.com_adalogin.sys.ini %repo%\admin\langauge








