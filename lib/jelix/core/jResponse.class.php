<?php
/**
* @package    jelix
* @subpackage core
* @version    $Id$
* @author     Jouanneau Laurent
* @contributor
* @copyright  2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


/**
* classe de base pour l'objet  charg� de controler et de formater
* la r�ponse renvoy�e au navigateur
*/

abstract class jResponse {
    /**
    * identifiant du g�n�rateur de sortie
    * @var string
    */
    protected  $_type = null;

    protected $_errorMessages=array();

    protected $_attributes = array();

    /**
    * Contruction et initialisation
    */
    function __construct ($attributes){
       $this->_attributes = $attributes;
    }

    /**
     * g�n�re le contenu et l'envoi au navigateur.
     * Il doit tenir compte des appels �ventuels � addErrorMsg
     * @return boolean    true si la g�n�ration est ok, false sinon
     */
    abstract public function output();

    /**
     * g�n�re le contenu sans l'envoyer au navigateur
     * @return    string    contenu g�n�r� ou false si il y a une erreur de g�n�ration
     */
    abstract public function fetch();

    /**
     * affiche les erreurs graves
     */
    abstract public function outputErrors();


    /**
     * indique au g�n�rateur qu'il y a un message d'erreur/warning/notice � prendre en compte
     * cette m�thode stocke le message d'erreur
     * @param  string $type  type d'erreur dont la valeur est l'une du tableau codeString de la config du gestionnaire d'erreur
     * @param  string $code  code d'erreur (non utilis� en PHP4)
     * @return boolean    true= arret immediat ordonn�, false = on laisse le gestionnaire d'erreur agir en cons�quence
     */
    public function addErrorMsg($type, $code, $message, $file, $line){
        $this->_errorMessages[] = array($type, $code, $message, $file, $line);
        return false;
    }

    public final function getType(){ return $this->_type;}
}
?>