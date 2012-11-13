<?php
/**
* @package     jelix
* @subpackage  forms
* @author      Laurent Jouanneau
* @contributor Julien Issler, Dominique Papin
* @copyright   2006-2012 Laurent Jouanneau
* @copyright   2008-2011 Julien Issler, 2008 Dominique Papin
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 *
 */
require(JELIX_LIB_PATH.'forms/jFormsHtmlWidgetBuilder.class.php');

/**
 * HTML form builder
 * @package     jelix
 * @subpackage  jelix-plugins
 */
class jFormsBuilderHtml extends jFormsBuilderBase {
    protected $formType = '_html';

    protected $jFormsJsVarName = 'jForms';

    protected $options;

    public $isRootControl = true;

    public function getjFormsJsVarName() {
        return $this->jFormsJsVarName;
    }
    
    public function getIsRootControl() {
        return $this->isRootControl;
    }
    
    public function outputAllControls() {

        echo '<table class="jforms-table" border="0">';
        foreach( $this->_form->getRootControls() as $ctrlref=>$ctrl){
            if($ctrl->type == 'submit' || $ctrl->type == 'reset' || $ctrl->type == 'hidden') continue;
            if(!$this->_form->isActivated($ctrlref)) continue;
            if($ctrl->type == 'group') {
                echo '<tr><td colspan="2">';
                $this->outputControl($ctrl);
                echo '</td></tr>';
            }else{
                echo '<tr><th scope="row">';
                $this->outputControlLabel($ctrl);
                echo '</th><td>';
                $this->outputControl($ctrl);
                echo "</td></tr>\n";
            }
        }
        echo '</table> <div class="jforms-submit-buttons">';
        if ( $ctrl = $this->_form->getReset() ) {
            if(!$this->_form->isActivated($ctrl->ref)) continue;
            $this->outputControl($ctrl);
            echo ' ';
        }
        foreach( $this->_form->getSubmits() as $ctrlref=>$ctrl){
            if(!$this->_form->isActivated($ctrlref)) continue;
            $this->outputControl($ctrl);
            echo ' ';
        }
        echo "</div>\n";
    }

    public function outputMetaContent($t) {
        $resp= jApp::coord()->response;
        if($resp === null || $resp->getType() !='html'){
            return;
        }
        $config = jApp::config();
        $www = $config->urlengine['jelixWWWPath'];
        $bp = $config->urlengine['basePath'];
        $resp->addJSLink($www.'js/jforms_light.js');
        $resp->addCSSLink($www.'design/jform.css');
        $heConf = &$config->htmleditors;
        foreach($t->_vars as $k=>$v){
            if($v instanceof jFormsBase && count($edlist = $v->getHtmlEditors())) {
                foreach($edlist as $ed) {

                    if(isset($heConf[$ed->config.'.engine.file'])){
                        $file = $heConf[$ed->config.'.engine.file'];
                        if(is_array($file)){
                            foreach($file as $url) {
                                $resp->addJSLink($bp.$url);
                            }
                        }else
                            $resp->addJSLink($bp.$file);
                    }

                    if(isset($heConf[$ed->config.'.config']))
                        $resp->addJSLink($bp.$heConf[$ed->config.'.config']);

                    $skin = $ed->config.'.skin.'.$ed->skin;
                    if(isset($heConf[$skin]) && $heConf[$skin] != '')
                        $resp->addCSSLink($bp.$heConf[$skin]);
                }
            }
        }
    }

    protected function outputHeaderScript(){
                echo '<script type="text/javascript">
//<![CDATA[
'.$this->jFormsJsVarName.'.tForm = new jFormsForm(\''.$this->_name.'\');
'.$this->jFormsJsVarName.'.tForm.setErrorDecorator(new '.$this->options['errorDecorator'].'());
'.$this->jFormsJsVarName.'.declareForm(jForms.tForm);
//]]>
</script>';
    }

    /**
     * output the header content of the form
     * @param array $params some parameters <ul>
     *      <li>"errDecorator"=>"name of your javascript object for error listener"</li>
     *      <li>"method" => "post" or "get". default is "post"</li>
     *      </ul>
     */
    public function outputHeader($params){
        $this->options = array_merge(array('errorDecorator'=>$this->jFormsJsVarName.'ErrorDecoratorHtml',
            'method'=>'post'), $params);
        if (isset($params['attributes']))
            $attrs = $params['attributes'];
        else
            $attrs = array();

        echo '<form';
        if (preg_match('#^https?://#',$this->_action)) {
            $urlParams = $this->_actionParams;
            $attrs['action'] = $this->_action;
        } else {
            $url = jUrl::get($this->_action, $this->_actionParams, 2); // returns the corresponding jurl
            $urlParams = $url->params;
            $attrs['action'] = $url->getPath();
        }
        $attrs['method'] = $this->options['method'];
        $attrs['id'] = $this->_name;

        if($this->_form->hasUpload())
            $attrs['enctype'] = "multipart/form-data";

        $this->_outputAttr($attrs);
        echo '>';

        $this->outputHeaderScript();

        $hiddens = '';
        foreach ($urlParams as $p_name => $p_value) {
            $hiddens .= '<input type="hidden" name="'. $p_name .'" value="'. htmlspecialchars($p_value). '"'.$this->_endt. "\n";
        }

        foreach ($this->_form->getHiddens() as $ctrl) {
            if(!$this->_form->isActivated($ctrl->ref)) continue;
            $hiddens .= '<input type="hidden" name="'. $ctrl->ref.'" id="'.$this->_name.'_'.$ctrl->ref.'" value="'. htmlspecialchars($this->_form->getData($ctrl->ref)). '"'.$this->_endt. "\n";
        }

        if($this->_form->securityLevel){
            $tok = $this->_form->createNewToken();
            $hiddens .= '<input type="hidden" name="__JFORMS_TOKEN__" value="'.$tok.'"'.$this->_endt. "\n";
        }

        if($hiddens){
            echo '<div class="jforms-hiddens">',$hiddens,'</div>';
        }

        $errors = $this->_form->getContainer()->errors;
        if(count($errors)){
            $ctrls = $this->_form->getControls();
            echo '<ul id="'.$this->_name.'_errors" class="jforms-error-list">';
            $errRequired='';
            foreach($errors as $cname => $err){
                if(!$this->_form->isActivated($ctrls[$cname]->ref)) continue;
                if ($err === jForms::ERRDATA_REQUIRED) {
                    if ($ctrls[$cname]->alertRequired){
                        echo '<li>', $ctrls[$cname]->alertRequired,'</li>';
                    }
                    else {
                        echo '<li>', jLocale::get('jelix~formserr.js.err.required', $ctrls[$cname]->label),'</li>';
                    }
                }else if ($err === jForms::ERRDATA_INVALID) {
                    if($ctrls[$cname]->alertInvalid){
                        echo '<li>', $ctrls[$cname]->alertInvalid,'</li>';
                    }else{
                        echo '<li>', jLocale::get('jelix~formserr.js.err.invalid', $ctrls[$cname]->label),'</li>';
                    }
                }
                elseif ($err === jForms::ERRDATA_INVALID_FILE_SIZE) {
                    echo '<li>', jLocale::get('jelix~formserr.js.err.invalid.file.size', $ctrls[$cname]->label),'</li>';
                }
                elseif ($err === jForms::ERRDATA_INVALID_FILE_TYPE) {
                    echo '<li>', jLocale::get('jelix~formserr.js.err.invalid.file.type', $ctrls[$cname]->label),'</li>';
                }
                elseif ($err === jForms::ERRDATA_FILE_UPLOAD_ERROR) {
                    echo '<li>', jLocale::get('jelix~formserr.js.err.file.upload', $ctrls[$cname]->label),'</li>';
                }
                elseif ($err != '') {
                    echo '<li>', $err,'</li>';
                }
            }
            echo '</ul>';
        }
    }

    public $jsContent = '';

    public $lastJsContent = '';

    public function outputFooter(){
        echo '<script type="text/javascript">
//<![CDATA[
(function(){var c, c2;
'.$this->jsContent.$this->lastJsContent.'
})();
//]]>
</script>';
        echo '</form>';
    }

    public function getWidget($ctrl, $builder = null) {
        $pluginName = $ctrl->type . $this->formType;
        $className = $pluginName . 'FormWidget';
        if ($builder === null)
            $builder = $this;
        $plugin = jApp::loadPlugin($pluginName, 'formwidget', '.formwidget.php', $className, array($ctrl, $builder));
        return $plugin;
    }

    public function outputControlLabel($ctrl){
        $plugin = $this->getWidget($ctrl);
        if (!is_null($plugin)) {
            $plugin->outputLabel();
        } else {
            //Throw error
        }
    }

    public function outputControl($ctrl, $attributes=array()){
        $plugin = $this->getWidget($ctrl);
        if (!is_null($plugin)) {
            $plugin->outputControl();
            $plugin->outputHelp();
            $plugin->outputJs();
        } else {
            //Throw error
        }
    }

    protected function _outputAttr(&$attributes) {
        foreach($attributes as $name=>$val) {
            echo ' '.$name.'="'.htmlspecialchars($val).'"';
        }
    }

    protected function escJsStr($str) {
        return '\''.str_replace(array("'","\n"),array("\\'", "\\n"), $str).'\'';
    }
}
