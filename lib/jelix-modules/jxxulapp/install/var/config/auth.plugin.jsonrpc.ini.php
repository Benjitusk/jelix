;<?php die(''); ?>
;for security reasons , don't remove or modify the first line

;============= Param�tres g�n�raux
; Db, Class ou LDS  ( respecter la casse des caract�res)
driver = Db

;============ Param�tres pour le plugin
; indique si on effectue un contr�le sur l'adresse ip
; qui a d�marr� la session.
secure_with_ip = 0

; action en cas de piratage de la session et si onError = 2
bad_ip_action = "jxxulapp~xul_badlogin"

;Timeout. Permet de forcer une authentification apr�s un certain temps �coul�
;sans action . temps en minutes. 0 = pas de timeout.
timeout = 0

; indique si il faut absolument ou non une authentification pour chaque action
; on = authentification necessaire pour toute action
;   sauf celles qui l'indiquent sp�cifiquement   (parametre action auth.required=false)
; off = authentification non requise pour toute action
;   sauf celles qui l'indiquent sp�cifiquement   (parametre action auth.required=true)
auth_required = on

; indique quoi faire en cas de d�faut d'authentification
; 1 = erreur. Valeur � mettre imp�rativement pour les web services (xmlrpc, jsonrpc...)
; 2 = redirection vers une action
on_error = 1

; action � executer en cas de d�faut d'authentification quand on_error = 2
on_error_action = "jxxulapp~xul_badlogin"

;selecteur de la cl� de locale du message d'erreur
error_message = "jxauth~autherror.notlogged"


;=========== Param�tres pour le module jauth

; nombre de secondes d'attentes apr�s un d�faut d'authentification
on_error_sleep = 3

enable_after_login_override = off
after_login = ""

enable_after_logout_override = off
after_logout = "jxxulapp~xul_badlogin"

;=========== Param�tres pour les drivers

; param�tres pour le driver db
[Db]
dao = "jxauth~jelixuser"

; nom de la fonction globale qui sert � crypter le mot de passe
password_crypt_function = md5

; param�tres pour le driver class
[Class]
class = ""
password_crypt_function = md5

[LDS]

