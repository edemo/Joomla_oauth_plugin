@echo on
SET repo=e:\github-repok\Joomla_oauth_plugin\adalogin
SET www=e:\www\elovalasztok

xcopy %repo%\site\assets      %www%\components\com_adalogin\assets /Y /S /E
xcopy %repo%\site\controllers %www%\components\com_adalogin\controllers /Y /S /E
xcopy %repo%\site\helpers     %www%\components\com_adalogin\helpers /Y /S /E
xcopy %repo%\site\models      %www%\components\com_adalogin\models /Y /S /E
xcopy %repo%\site\views       %www%\components\com_adalogin\views /Y /S /E
copy  %repo%\site\*.php       %www%\components\com_adalogin
copy  %repo%\site\*.html      %www%\components\com_adalogin

copy %repo%\site\language\en-GB.com_adalogin.ini %www%\language\en-GB
copy %repo%\site\language\hu-HU.com_adalogin.ini %www%\language\hu-HU

xcopy %repo%\admin\assets      %www%\administrator\components\com_adalogin\assets /Y /S /E
xcopy %repo%\admin\controllers %www%\administrator\components\com_adalogin\controllers /Y /S /E
xcopy %repo%\admin\helpers     %www%\administrator\components\com_adalogin\helpers /Y /S /E
xcopy %repo%\admin\models      %www%\administrator\components\com_adalogin\models /Y /S /E
xcopy %repo%\admin\sql         %www%\administrator\components\com_adalogin\sql /Y /S /E
xcopy %repo%\admin\tables      %www%\administrator\components\com_adalogin\tables /Y /S /E
xcopy %repo%\admin\views       %www%\administrator\components\com_adalogin\views /Y /S /E
copy  %repo%\admin\*.php       %www%\administrator\components\com_adalogin
copy  %repo%\admin\*.html      %www%\administrator\components\com_adalogin
copy  %repo%\*.xml             %www%\administrator\components\com_adalogin

copy %repo%\admin\language\en-GB.com_adalogin.ini %www%\administrator\language\en-GB
copy %repo%\admin\language\hu-HU.com_adalogin.ini %www%\administrator\language\hu-HU












