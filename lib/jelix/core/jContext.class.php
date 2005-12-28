<?php
/**
* @package    jelix
* @subpackage core
* @version    $Id:$
* @author    Laurent Jouanneau
* @contributor
* @copyright  2001-2005 CopixTeam, 2005-2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence  http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*
* Classe orginellement issue du framework Copix 2.3dev20050901. http://www.copix.org (CopixContext)
* Une partie du code est sous Copyright 2001-2005 CopixTeam (licence LGPL)
* Auteurs initiaux : Gerald Croes et Laurent Jouanneau
* Adapt�e et am�lior�e pour Jelix par Laurent Jouanneau
*/

class jContext {

    /**
    * Empilement d'un contexte.
    * @param string $module  le nom du module dont on empile le contexte
    */
    static function push ($module){
        array_push ($GLOBALS['gJContext'], $module);
    }

    /**
    * D�pilement d'un contexte.
    * @return string element d�pil�. (le contexte qui n'est plus d'actualit�.)
    */
    static function pop (){
       return array_pop ($GLOBALS['gJContext']);
    }

    /**
    * r�cup�re le contexte actuel
    * @return string le nom du contexte actuel si d�fini, sinon retourne false
    */
    static function get (){
        return end($GLOBALS['gJContext']);
    }

    /**
    * r�initialise le contexte.
    */
    static function clear (){
        $GLOBALS['gJContext'] = array ();
    }
}
?>