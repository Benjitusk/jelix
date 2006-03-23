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
        if($this->hasErrors()){
            foreach( $GLOBALS['gJCoord']->errorMessages  as $e){
               echo '['.$e[0].' '.$e[1].'] '.$e[2]." \t".$e[3]." \t".$e[4]."\n";
            }
        }else{
            echo "[unknow error]\n";
        }
    }
}
?>