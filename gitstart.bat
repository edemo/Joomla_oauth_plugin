@echo on
REM git init
XCOPY e:\www\elovalasztok\components\com_adalogin e:\github-repok\Joomla_oauth_plugin\adalogin\site /Y /S /E
COPY e:\www\elovalasztok\language\en-GB\en-GB.com_adalogin.ini e:\github-repok\Joomla_oauth_plugin\adalogin\site\langauge 
COPY e:\www\elovalasztok\language\hu-HU\hu-HU.com_adalogin.ini e:\github-repok\Joomla_oauth_plugin\adalogin\site\langauge 
XCOPY e:\www\elovalasztok\administrator\components\com_adalogin e:\github-repok\Joomla_oauth_plugin\adalogin\admin /Y /S /E
COPY e:\www\elovalasztok\administrator\language\en-GB\en-GB.com_adalogin.ini e:\github-repok\Joomla_oauth_plugin\adalogin\admin\langauge 
COPY e:\www\elovalasztok\administrator\language\hu-HU\hu-HU.com_adalogin.ini e:\github-repok\Joomla_oauth_plugin\adalogin\admin\langauge 
COPY e:\www\elovalasztok\administrator\language\en-GB\en-GB.com_adalogin.sys.ini e:\github-repok\Joomla_oauth_plugin\adalogin\admin\langauge 
COPY e:\www\elovalasztok\administrator\language\hu-HU\hu-HU.com_adalogin.sys.ini e:\github-repok\Joomla_oauth_plugin\adalogin\admin\langauge 








