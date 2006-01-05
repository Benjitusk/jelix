<?php
/**
* @package     jelix
* @subpackage  core
* @version     $Id$
* @author      Jouanneau Laurent
* @contributor
* @copyright   2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
* G�n�rateur de r�ponse Text
*/

class jResponseText extends jResponse {
    /**
    * identifiant du g�n�rateur de sortie
    * @var string
    */
    protected $_type = 'text';

    /**
     * contenu
     * @var string
     */
    public $content = '';


    /**
     * g�n�re le contenu et l'envoi au navigateur.
     * @return boolean    true si la g�n�ration est ok, false sinon
     */
    public function output(){
        global $gJConfig;
        header('Content-Type: text/plain;charset='.$gJConfig->defaultCharset);
        header("Content-length: ".strlen($this->content));
        echo $this->content;
        return true;
    }

    /**
     * g�n�re le contenu sans l'envoyer au navigateur
     * @return    string    contenu g�n�r� ou false si il y a une erreur de g�n�ration
     */
    public function fetch(){
        return $this->content;
    }


    public function outputErrors(){
        global $gJConfig;
        header('Content-Type: text/plain;charset='.$gJConfig->defaultCharset);
        echo implode("\n",$this->_errorMessages);
    }


    /**
     * indique au g�n�rateur qu'il y a un message d'erreur/warning/notice � prendre en compte
     * cette m�thode stocke le message d'erreur
     * @return boolean    true= arret immediat ordonn�, false = on laisse le gestionnaire d'erreur agir en cons�quence
     */
    public function addErrorMsg($type, $code, $message, $file, $line){
        $this->_errorMessages[] = "[$type $code] $message \t$file \t$line\n";
        return false;
    }
}
?>