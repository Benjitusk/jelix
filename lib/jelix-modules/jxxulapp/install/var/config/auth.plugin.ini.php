;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

; Db ou Ldap
driver = Db

; nom de la fonction globale qui sert � crypter le mot de passe
; peut �tre vide, dans le cas o� le driver prend en charge le cryptage
password_crypt_function = md5

; indique si il faut absolument ou non une authentification
; on = authentification necessaire pour toute action
;   sauf celles qui l'indiquent sp�cifiquement   (parametre action auth.required=false)
; off = authentification non requise pour toute action
;   sauf celles qui l'indiquent sp�cifiquement   (parametre action auth.required=true)
auth_required = on


;Timeout. Permet de forcer une authentification apr�s un certain temps �coul�
;sans action . temps en minutes. 0 = pas de timeout.

timeout = 0

; indique quoi faire en cas de d�faut d'authentification
; 1 = erreur. Valeur � mettre imp�rativement pour les web services
; 2 = redirection vers une action
on_error = 2

; action � executer en cas de d�faut d'authentification quand onError = 2
on_error_action = "jxxulapp~default_login"

; nombre de secondes d'attentes apr�s un d�faut d'authentification
on_error_sleep = 3

;selecteur de la locale
error_message = "jxauth~autherror.notlogged"

; indique si on effectue un contr�le sur l'adresse ip
; qui a d�marr� la session.
secure_with_ip = 0

; action en cas de piratage de la session et si onError = 2
bad_ip_action = "jxxulapp~default_login"

enable_after_login_override = off
after_login =

enable_after_logout_override = off
after_logout = "jxxulapp~default_login"

login_template = "jxauth~login.form"

; param�tres pour le driver db
[Db]
dao = "jxauth~jelixuser"

