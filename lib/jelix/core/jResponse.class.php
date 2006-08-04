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

    protected $_acceptSeveralErrors=true;

    protected $_httpHeaders = array();
    protected $_httpHeadersSent = false;

    /**
    * Contruction et initialisation
    */
    function __construct ($attributes=array()){
       $this->_attributes = $attributes;
    }

    /**
     * g�n�re le contenu et l'envoi au navigateur.
     * Il doit tenir compte des erreurs
     * @return boolean    true si la g�n�ration est ok, false sinon
     */
    abstract public function output();

    /**
     * affiche les erreurs graves
     */
    abstract public function outputErrors();


    public final function getType(){ return $this->_type;}
    public function getFormatType(){ return $this->_type;}
    public final function acceptSeveralErrors(){ return $this->_acceptSeveralErrors;}
    public final function hasErrors(){ return count($GLOBALS['gJCoord']->errorMessages)>0;}

    public function addHttpHeader($htype, $hcontent){ $this->_httpHeaders[$htype]=$hcontent;}

    protected function sendHttpHeaders(){
        foreach($this->_httpHeaders as $ht=>$hc){
            header($ht.': '.$hc);
        }
        $this->_httpHeadersSent=true;
        /*
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        */
    }
}
?>