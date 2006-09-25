;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

defaultModule = "jelix"
defaultAction = "default_index"
defaultLocale = "fr_FR"
defaultCharset = "ISO-8859-1"
defaultTimeZone = "Europe/Paris"

checkTrustedModules = off

; list of modules : module,module,module
trustedModules =

pluginsPath = lib:jelix-plugins/,app:plugins/
modulesPath = lib:jelix-modules/,app:modules/
tplpluginsPath = lib:jelix/tpl/plugins/

dbProfils = dbprofils.ini.php

defaultTheme = default
use_error_handler = on
shared_session = off

[plugins]

[responses]
html = jResponseHtml
redirect = jResponseRedirect
redirectUrl = jResponseRedirectUrl
binary = jResponseBinary
text = jResponseText
jsonrpc = jResponseJsonrpc
xmlrpc = jResponseXmlrpc
xul = jResponseXul
xuloverlay = jResponseXulOverlay
xuldialog = jResponseXulDialog
xulpage = jResponseXulPage
rdf = jResponseRdf
xml = jResponseXml
zip = jResponseZip


[_coreResponses]
html = jResponseHtml
redirect = jResponseRedirect
redirectUrl = jResponseRedirectUrl
binary = jResponseBinary
text = jResponseText
jsonrpc = jResponseJsonrpc
xmlrpc = jResponseXmlrpc
xul = jResponseXul
xuloverlay = jResponseXulOverlay
xuldialog = jResponseXulDialog
xulpage = jResponseXulPage
rdf = jResponseRdf
xml = jResponseXml
zip = jResponseZip

[error_handling]
messageLogFormat = "%date%\t[%code%]\t%msg%\t%file%\t%line%\n"
logFile = error.log
email = root@localhost
emailHeaders = "From: webmaster@yoursite.com\nX-Mailer: Jelix\nX-Priority: 1 (Highest)\n"
quietMessage="Une erreur technique est survenue. D�sol� pour ce d�sagr�ment."

; mots cl�s que vous pouvez utiliser : ECHO, ECHOQUIET, EXIT, LOGFILE, SYSLOG, MAIL, TRACE
default      = ECHO EXIT
error        = ECHO EXIT
warning      = ECHO
notice       = ECHO
strict       = ECHO
; pour les exceptions, il y a implicitement un EXIT
exception    = ECHO

[compilation]
checkCacheFiletime  = on
force  = off

[urlengine]
; nom du moteur d'url :  simple ou significant
engine        = simple

; active l'analyse d'url (mettre � off si vous utilisez le mod_rewrite d'apache)
enableParser = on

multiview = off

; chemin url jusqu'au repertoire www (celui que vous tapez dans le navigateur pour acc�der � index.php etc.)
; peut �tre �gale � "/" si vous sp�cifiez www comme �tant le documentRoot de votre site au niveau du serveur
basePath = "/"

defaultEntrypoint= index

entrypointExtension= .php

notfoundAct = "jelix~notfound"

;indique si vous utilisez IIS comme serveur
useIIS = off

;indique le param�tre dans $_GET o� est indiqu� le path_info
IISPathKey = __JELIX_URL__

;indique si il faut stripslash� le path_info r�cup�r� par le biais de IISPathKey
IISStripslashes_path_key = on


[simple_urlengine_entrypoints]
; param�tres pour le moteur d'url simple : liste des points d'entr�es avec les actions
; qui y sont rattach�es


; nom_script_sans_suffix = "liste de selecteur d'action s�par� par un espace"
; selecteurs :
;   m~a@r    -> pour action "a" du module "m" r�pondant au type de requete "r"
;   m~*@r    -> pour toute action du module "m" r�pondant au type de requete "r"
;   @r       -> toute action de tout module r�pondant au type de requete "r"

index = "@classic"
xmlrpc = "@xmlrpc"
jsonrpc = "@jsonrpc"
rdf = "@rdf"


[logfiles]
default=messages.log
