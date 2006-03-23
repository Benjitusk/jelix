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
* Gen�rateur de r�ponse XUL
* @see jResponse
*/

class jResponseXul extends jResponse {
    /**
    * identifiant du g�n�rateur
    * @var string
    */
    protected $_type = 'xul';

    /**
     * contenu pour le header
     */
    protected $_overlays  = array ();
    protected $_CSSLink = array ();
    protected $_JSLink  = array ();
    protected $_JSCode  = array ();

    /**
     * nom de la balise racine
     */
    protected $_root = 'window';
    /**
     * attribut de la balise racine
     */
    public $rootAttributes= array();

    public $title = '';

    /**
     * @var jTpl
     */

    public $body = null;

    /**
     * selecteur du template principal
     * le contenu du template principal concerne le contenu de <body>
     */
    public $bodyTpl = 'myapp~mainxul';

    /**
     * template principal � afficher en cas d'erreur
     */
    public $bodyErrorTpl = 'myapp~errorxul';


    protected $_bodyTop = array();
    protected $_bodyBottom = array();
    protected $_headSent = false;


    /**
    * Contruction et initialisation
    */
    function __construct ($attributes=array()){
        $this->body = new jTpl();
        parent::__construct($attributes);
    }

    /**
     * g�n�re le contenu et l'envoi au navigateur.
     * Il doit tenir compte des erreurs
     * @return boolean    true si la g�n�ration est ok, false sinon
     */
    function output(){
        $this->_headSent = false;

        header('Content-Type:application/vnd.mozilla.xul+xml;charset='.$GLOBALS['gJConfig']->defaultCharset);
        $this->outputHeader();
        $this->_headSent = true;
        echo implode('',$this->_bodyTop);
        $this->body->display($this->bodyTpl);
        if($this->hasErrors()){
            echo '<vbox id="copixerror" style="border:3px solid red; background-color:#f39999;color:black;">';
            echo $this->getFormatedErrorMsg();
            echo '</vbox>';
        }
        echo implode('',$this->_bodyBottom);
        echo '</',$this->_root,'>';
        return true;
    }

    /**
     * g�n�re le contenu sans l'envoyer au navigateur
     * @return    string    contenu g�n�r� ou false si il y a une erreur de g�n�ration
     */
    function fetch(){
        ob_start();
        $ok = $this->output();
        $content= ob_get_contents();
        ob_end_clean();
        if($ok) return $content;
        else return false;
    }


    function outputErrors(){
        if(!$this->_headSent){
            echo '<?xml version="1.0" encoding="'.$GLOBALS['gJConfig']->defaultCharset.'" ?>'."\n";
            echo '<',$this->_root,' title="Erreurs" xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul">';
        }
        echo '<vbox>';
        if($this->hasErrors()){
           echo $this->getFormatedErrorMsg();
        }else{
           echo "<description style=\"color:#FF0000;\">Unknow error</description>";
        }
        echo '</vbox></',$this->_root,'>';
    }

    /**
     * formate les messages d'erreurs
     * @return string les erreurs format�es
     */
    protected function getFormatedErrorMsg(){
        $errors='';
        foreach( $GLOBALS['gJCoord']->errorMessages  as $e){
            // FIXME : Pourquoi utiliser htmlentities() ?
           $errors .=  '<description style="color:#FF0000;">['.$e[0].' '.$e[1].'] '.htmlentities($e[2], ENT_NOQUOTES, $this->_charset)." \t".$e[3]." \t".$e[4]."</description>\n";
        }
        return $errors;
    }

    /**
     * methode pour ajouter du contenu avant/apr�s le contenu principal
     */

    function addContent($content, $beforeTpl = false){
      if($beforeTpl){
        $this->_bodyTop[]=$content;
      }else{
         $this->_bodyBottom[]=$content;
      }
    }

    /**
     * m�thodes pour manipuler les processing instructions
     */


    function addOverlay ($src){
        $this->_overlays[] = $src;
    }
    function addJSLink ($src, $params=array()){
        if (!isset ($this->_JSLink[$src])){
            $this->_JSLink[$src] = $params;
        }
    }
    function addCSSLink ($src, $params=array ()){
        if (!isset ($this->_CSSLink[$src])){
            $this->_CSSLink[$src] = $params;
        }
    }
    function addJSCode ($code){
        $this->_JSCode[] = $code;
    }

    function outputHeader (){
        $charset = $GLOBALS['gJConfig']->defaultCharset;

        echo '<?xml version="1.0" encoding="'.$charset.'" ?>'."\n";

        // css link
        foreach ($this->_CSSLink as $src=>$param){
            if(is_string($param))
                echo  '<?xml-stylesheet type="text/css" href="',htmlspecialchars($param,ENT_COMPAT, $charset),'" ?>',"\n";
            else
                echo  '<?xml-stylesheet type="text/css" href="',htmlspecialchars($src,ENT_COMPAT, $charset),'" ?>',"\n";
        }
        $this->_otherthings();

        echo '<',$this->_root,' title="',htmlspecialchars($this->title,ENT_COMPAT, $charset),'"';
        foreach($this->rootAttributes as $name=>$value){
            echo ' ',$name,'="',htmlspecialchars($value,ENT_COMPAT, $charset),'"';
        }
        echo "  xmlns:html=\"http://www.w3.org/1999/xhtml\"
        xmlns=\"http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul\">\n";

        // js link
        foreach ($this->_JSLink as $src=>$params){
            echo '<script type="application/x-javascript" src="',$src,'" />',"\n";
        }


        // js code
        if(count($this->_JSCode)){
            echo '<script type="application/x-javascript">
<![CDATA[
 '.implode ("\n", $this->_JSCode).'
]]>
</script>';
        }
    }

    function _otherthings(){
        // overlays
        foreach ($this->_overlays as $src){
            echo  '<?xul-overlay href="',htmlspecialchars($src,ENT_COMPAT, $GLOBALS['gJConfig']->defaultCharset),'" ?>',"\n";
        }
    }


    function clearHeader ($what){
        $cleanable = array ('CSSLink', 'JSLink', 'JSCode', 'overlays');
        foreach ($what as $elem){
            if (in_array ($elem, $cleanable)){
                $name = '_'.$elem;
                $this->$name = array ();
            }
        }
    }
}


class jResponseXulOverlay extends jResponseXul {
    var $id = 'xuloverlay';
    var $_root = 'overlay';
    function _otherthings(){ } // pas d'overlay dans un overlay
}

class jResponseXulDialog extends jResponseXul {
    var $id = 'xuldialog';
    var $_root = 'dialog';
}
class jResponseXulPage extends jResponseXul {
    var $id = 'xulpage';
    var $_root = 'page';
}

?>