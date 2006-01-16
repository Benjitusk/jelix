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
*
* Some parts of this file are took from Copix Framework v2.3dev20050901, CopixUrl.class.php,
* copyrighted by CopixTeam and released under GNU Lesser General Public Licence
* author : Gerald Croes, Laurent Jouanneau
* http://www.copix.org
*/

/**
 * objet responsable du parsing et de la cr�ation d'url
 */
class jUrlEngine {

    /**
     * Parse une chaine url
     * @param string $scriptNamePath    /path/index.php
     * @param string $pathinfo the path info of the url.
     * @param array  $params  url parameter ($_REQUEST)
     * @return jUrl l'objet url resultant
     */
    function parse($scriptNamePath, $params, $pathinfo ){
        $url = new jUrl($scriptNamePath, $params, $pathinfo);
        return $url;
    }

    /**
     * Modifie les donn�es de l'url selon le type d'url propos� par l'engine
     * (notament le pathinfo etc..)
     * @param jUrl $url l'url � transformer
     * @return void
     */
    function create(&$url){

    }
}


/**
* Objet url permettant de manipuler facilement une url
*/
class jUrl {
    /**
    * nom du script
    * @var string
    */
    public $scriptName;

    /**
    * param�tres de l'url
    * @var array
    */
    public $params;

    /**
    * info path, partie du path situ� apr�s le nom du script dans le path
    * @var string
    */
    public $pathInfo = '';

    /**
    * initialise l'objet
    * @param    string    $scriptname    nom du script
    * @param    array    $params    parametres
    */
    function __construct ($scriptname='', $params=array (), $pathInfo=''){
        $this->params      = $params;
        $this->scriptName  = $scriptname;
        $this->pathInfo    = $pathInfo;
    }

    /**
    * ajoute ou redefini un param�tre url
    * @param    string    $name    nom du param�tre
    * @param    string    $value    valeur du param�tre
    */
    public function setParam ($name, $value){
        $this->params[$name] = $value;
    }

    /**
    * supprime un param�tre
    * @param    string    $name    nom du param�tre
    */
    public function delParam ($name){
        if (isset($this->params[$name]))
        unset ($this->params[$name]);
    }

    /**
    * r�cup�re un param�tre
    * @param    string    $name    nom du param�tre
    * @param    string    $defaultValue   nom de la valeur par d�faut renvoy� si le param�tre n'existe pas.
    */
    public function getParam ($name, $defaultValue=null){
        if (isset($this->params[$name]))
          return $this->params[$name];
        else
          return $defaultValue;
    }

    /**
    * Clear parameters
    */
    public function clearParam (){
        $this->params = array ();
    }

    /**
    * renvoi l'url sous forme de chaine.
    * @param    boolean    $forxml indique si l'url est destin� � �tre int�gr� dans du code HTML/XML ou non
    * @param    boolean    $isUrlForApp indique si l'url est une url pour l'appli, ou pour un lien externe
    * @return    string    l'url form�e
    */
    public function toString ($forxml = false, $isUrlForApp=true){

        $urlobj=$this;
        if($isUrlForApp){
            // dans le cas d'une url pour jelix, on passe par le moteur d'url sp�cifique

            // on ne doit pas modifier les donn�es de l'url, il nous faut donc un clone
            $urlobj= clone $this;
            $urlobj->scriptName = 'index.php'; // pour le moment... � tester avec jUrl::getScript($urlobj->getParam('module'),$urlobj->getParam('action'));
            $engine = & jUrl::getEngine();
            $engine->create($urlobj); // set path info
        }
        if (count ($urlobj->params)>0){
            $url = $urlobj->scriptName.$urlobj->pathInfo.'?'.$urlobj->collapseParams ($forxml);
        }else{
            $url = $urlobj->scriptName.$urlobj->pathInfo;
        }

        return $url;
    }

    public function __toString(){
        return $this->toString();
    }


    /**
    * Transforms
    * @param boolean $forxml if the output must be HTML/XML compliant
    * @return string
    */
    public function collapseParams ($forxml = false) {
        return $this->_collapseParams($this->params, $forxml);
    }



    //============================== other methods that can be called without instanciation

    /**
    * get current Url
    */
    static function getCurrentUrl ($forxml = false) {
        if(isset($_SERVER["REQUEST_URI"])){
           return $_SERVER["REQUEST_URI"];
        }
        static $url = false;
        if ($url === false){
           $url = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'].$GLOBALS['gJCoord']->request->url_path_info.'?';
           $url.= self::_collapseParams($_GET,$forxml);
        }
        return $url;
    }

    /**
    * Adds parameters to the given url
    * @param string $url
    * @param array $params
    */
    static function appendToUrlString ($url, $params = array (), $forxml = false){
        if ((($pos = strpos ( $url, '?')) !== false) && ($pos !== (strlen ($url)-1))){
            return $url . ($forxml ? '&amp;' : '&').jUrl::_collapseParams ($params, $forxml);
        }else{
            return $url . '?'.jUrl::_collapseParams ($params, $forxml);
        }
    }

    /**
    * Gets the url string from parameters
    * @param string $actSel  action selector. if null we get the script path
    * @param array $params associative array with the parameters
    */
    static function get ($dest = null, $params = array (), $forxml = false) {
        if ($dest === null){
            return '/'.$GLOBALS['gJCoord']->request->url_script_path;
        }
        if($dest == '@'){
            $url = new jUrl('',array_merge($GLOBALS['gJCoord']->request->url->params,$params));
        }else{
            
            $url = new jUrl('',array_merge($params,jUrl::getAction ($dest)));
        }

        return $url->toString($forxml,true);
    }

    /**
     * Parse a url
     * @param string $scriptNamePath    /path/index.php
     * @param string $pathinfo the path info of the url.
     * @param array  $params  url parameter ($_REQUEST)
     * @return jUrl
     */
    static function parse($scriptNamePath, $pathinfo, $params ){
         $engine = jUrl::getEngine();
         return $engine->parse($scriptNamePath,$pathinfo, $params);
    }


    /**
    * collapse parameters to generate an url parameters string
    * @param array $params array of parameters
    * @param boolean $forxml if the string has to be html compliant (&amp; for &)
    * @return string the url
    * @access private
    */
    static private function _collapseParams ($params, $forxml = false) {
        $url = '';
        if (count ($params)>0){
            foreach ($params as $k=>$v){
                if ($url == ''){
                    $url = $k.'='.$v;
                }else{
                    $url .= ($forxml ? '&amp;' : '&').$k.'='.urlencode($v);
                }
            }
        }
        return $url;
    }

    /**
    * gets the module/action parameters from the destination string.
    * @param string $dest the destination to parse
    * @return assocative array where keys are module and action
    */
    static function getAction ($actionSelector){
        global $gJCoord, $gJConfig;

        if($actionSelector == '@'){
            // we get the current url
            return $gJCoord->request->url->params;
        }
        $sel = new JSelectorAct($actionSelector);
        if($sel->isValid()){        
           return array('module'=>$sel->module, 'action'=>$sel->ressource);
        }else{
          return false;
        }
    }

    static function getScript($module,$requestType, $nosuffix=false){
        global $gJConfig;
        $script = $gJConfig->urlengine->default_entrypoint;

        if(count($gJConfig->urlengine_specific_entrypoints)){
           $sep = $gJConfig->urlengine_specific_entrypoints;
           if(isset($sep[$module.'~'.$requestType])){
               $script = $sep[$module.'~'.$requestType];
           }else if(isset($sep['*~'.$requestType])){
               $script = $sep['*~'.$requestType];
           }else if(isset($sep[$module])){
               $script = $sep[$module];
           }
        }

        if(!$nosuffix && !$gJConfig->urlengine->multiview_on){
            $script.=$gJConfig->urlengine->entrypoint_extension;
        }
        return $script;
    }


    static function escape($str, $highlevel=false){
        if($highlevel){
            $str=strtr($str,'��������������','aaaeeeeiioouuu'); // supprime les caract�res accentu�s, et les quotes, doubles quotes
            $str=preg_replace("/([^\w])/"," ",$str); // remplace tout ce qui n'est pas lettre par un espace
            $str=preg_replace("/(?<=\s)\w{1,2}(?=\s)/"," ",$str); // enleve les mots de moins de 2 lettres
            $str=preg_replace("/( +)/","-",trim($str)); // on remplace les espaces et groupes d'espaces par -
            $str=strtolower($str); // on met en minuscule
            return $str;
        }else{
            return urlencode (strtr ($str, array ('-'=>'--', ' ' =>'-')));
        }
    }

    static function unescape($str){
        return strtr ($str, array ('--'=>'-', '-'=>' '));
    }

    static function getEngine($reset=false){
        static $engine = null;

        if($reset) $engine=null; // pour pouvoir faire les tests unitaires

        if($engine === null){
            if($GLOBALS['gJConfig']->urlengine['engine'] == 'default'){
                $engine = new jUrlEngine();// pas de &, car bug sur static
            }else{
                $file = JELIX_LIB_CORE_PATH.'url/jUrlEngine.'.$GLOBALS['gJConfig']->urlengine['engine'].'.class.php';
                if(!file_exists($file)){
                    trigger_error("Url engine doesn't exist",E_USER_ERROR);
                    return null;
                }
                include_once($file);
                $cl='jUrlEngine'.$GLOBALS['gJConfig']->urlengine['engine'];
                $engine = new $cl(); // pas de &, car bug sur static
            }
        }
        return $engine;
    }

}

?>