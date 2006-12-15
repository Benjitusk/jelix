<?php
/**
* @package     jelix
* @subpackage  core
* @version     $Id$
* @author      Loic Mathaud
* @author      Yannick Le Gu�dart
* @contributor Laurent Jouanneau
* @copyright   2005-2006 Loic Mathaud
* @copyright   2006 Yannick Le Gu�dart
* @copyright   2006 Laurent Jouanneau
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

/**
*
*/
require_once(JELIX_LIB_TPL_PATH.'jTpl.class.php');
require_once(JELIX_LIB_RESPONSE_PATH.'jResponseXmlFeed.class.php');

/**
* Rss2.0 response
* @package  jelix
* @subpackage core
* @link http://blogs.law.harvard.edu/tech/rss
* @link http://www.stervinou.com/projets/rss/
* @since 1.0b1
*/
class jResponseRss20 extends jResponseXMLFeed {
    protected $_type = 'rss2.0';

    /**
     * Class constructor
     *
     * @param  array
     * @return void
     */
    function __construct ($attributes = array()) {
        $this->_template 	= new jTpl();
        $this->_mainTpl 	= 'jelix~rss20';

        $this->infos = new jRSS20Info ();


        parent::__construct ($attributes);
        $this->infos->language = $this->lang;
    }

    /**
     * Generate the content and send it
     * Errors are managed
     * @return boolean true if generation is ok, else false
     */
    final public function output (){
        $this->_headSent = false;

        $this->_httpHeaders['Content-Type'] =
                'application/xml;charset=' . $this->charset;

        $this->sendHttpHeaders ();
        
        echo '<?xml version="1.0" encoding="'. $this->charset .'"?>', "\n";
        $this->_outputXmlHeader ();
        
        $this->_headSent = true;

        // $this->_outputOptionals();

        $this->_template->assign ('rss', $this->infos);
        $this->_template->assign ('items', $this->itemList);

        $this->_template->display ($this->_mainTpl);

        if ($this->hasErrors ()) {
            echo $this->getFormatedErrorMsg ();
        }
        echo '</rss>';
        return true;
    }

    final public function outputErrors() {
        if (!$this->_headSent) {
             if ($this->_sendHttpHeader) {
                header('Content-Type: text/xml;charset='.$this->charset);
             }
             echo '<?xml version="1.0" encoding="'. $this->charset .'"?>';
        }

        echo '<errors xmlns="http://jelix.org/ns/xmlerror/1.0">';
        if ($this->hasErrors()) {
            echo $this->getFormatedErrorMsg();
        } else {
            echo '<error>Unknow Error</error>';
        }
        echo '</errors>';
    }

    /**
     * Format error messages
     * @return string formated errors
     */
    protected function getFormatedErrorMsg(){
        $errors = '';
        foreach ($GLOBALS['gJCoord']->errorMessages  as $e) {
            // FIXME : Pourquoi utiliser htmlentities() ?
           $errors .=  '<error type="'. $e[0] .'" code="'. $e[1] .'" file="'. $e[3] .'" line="'. $e[4] .'">'.htmlentities($e[2], ENT_NOQUOTES, $this->charset). '</error>'. "\n";
        }
        return $errors;
    }

    /**
     * create a new item
     * @param string $title the item title
     * @param string $link  the link
     * @param string $date  the date of the item
     * @return jXMLFeedItem
     */
    public function createItem($title,$link, $date){
        $item = new jRSSItem();
        $item->title = $title;
        $item->id = $item->link = $link;
        $item->published = $date;
        return $item;
    }


}

// dates au format RFC822: Sat, 07 Sep 2002 00:00:01 GMT

/**
 * meta data of the channel
 * @package    jelix
 * @subpackage core
 * @since 1.0b1
 */
class jRSS20Info extends jXMLFeedInfo{
    /**
     * lang of the channel
     * @var string
     */
    public $language;
    /**
     * email of the content manager
     * @var string
     */
    public $managingEditor;
    /**
     * email of technical responsible
     * @var string
     */
    public $webMaster;
    /**
     * publication date
     * format:  yyyy-mm-dd hh:mm:ss
     * @var string
     */
    public $published;
    /**
     * specification url
     * example : http://blogs.law.harvard.edu/tech/rss
     * @var string
     */
    public $docs='';
    /**
     * not implemented
     * @var string
     */
    public $cloud; // indique un webservice par lequel le client peut s'enregistrer aupr�s du serveur
                  // pour �tre tenu au courant des modifs
                  //=array('domain'=>'','path'=>'','port'=>'','registerProcedure'=>'', 'protocol'=>'');
    /**
     * time to live of the cache, in minutes
     * @var string
     */
    public $ttl;
    /**
     * image title
     * @var string
     */
    public $imageTitle;
    /**
     * web site url corresponding to the image
     * @var string
     */
    public $imageLink;
    /**
     * width of the image
     * @var string
     */
    public $imageWidth;
    /**
     * height of the image
     * @var string
     */
    public $imageHeight;
    /**
     * Description of the image (= title attribute for the img tag)
     * @var string
     */
    public $imageDescription;

    /**
     * Pics rate for this channel
     * @var string
     */
    public $rating;
    /**
     * field form for the channel
     * it is an array('title'=>'','description'=>'','name'=>'','link'=>'')
     * @var array
     */
    public $textInput;
    /**
     * list of hours that agregator should ignore
     * ex (10, 21)
     * @var array
     */
    public $skipHours;
    /**
     * list of day that agregator should ignore
     * ex ('monday', 'tuesday')
     * @var array
     */
    public $skipDays;

    function __construct ()
    {
            $this->_mandatory = array ( 'title', 'webSiteUrl', 'description');
    }
}

/**
 * content of an item in a syndication channel
 * @package    jelix
 * @subpackage core
 * @since 1.0b1
 */
class jRSSItem extends jXMLFeedItem {

    /**
     * comments url
     * @var string
     */
    public $comments;
    /**
     * media description, attached to the item
     * the array should contain this keys :  'url', 'size', 'mimetype'
     * @var array
     */
    public $enclosure;
    /**
     * says if the id is a permanent link
     * @var boolean
     */
    public $idIsPermalink;
    /**
     * url of  rss channel of the information source
     * @var string
     */
    public $sourceUrl;
    /**
     * Title of the information source
     * @var string
     */
    public $sourceTitle;
}

?>
