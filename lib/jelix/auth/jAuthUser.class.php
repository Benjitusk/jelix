<?php
/**
* @package    jelix
* @subpackage auth
* @version    $Id:$
* @author     Laurent Jouanneau
* @contributor Loic Mathaud
* @copyright  2006 Laurent Jouanneau
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

// pas de m�thode pour cet objet, car le user peut ne pas etre
// une instance de jAuthUser, tout d�pend du driver..
class jAuthUser {
    public $login = '';
    public $level = 0;
    public $email ='';
}

?>
