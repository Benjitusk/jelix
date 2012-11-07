<?php
/**
* @package     jelix
* @subpackage  forms
* @author      Claudio Bernardes
* @copyright   2012 Claudio Bernardes
* @link        http://www.jelix.org
* @licence     http://www.gnu.org/licenses/lgpl.html GNU Lesser General Public Licence, see LICENCE file
*/

/**
 * HTML form builder
 * @package     jelix
 * @subpackage  jelix-plugins
 * @link http://developer.jelix.org/wiki/rfc/jforms-controls-plugins
 */

class radiobuttons_htmlFormWidget extends jFormsHtmlWidgetBuilder {
    function outputJs() {
        $jFormsJsVarName = $this->builder->getjFormsJsVarName();

        $this->builder->jsContent .= "c = new ".$jFormsJsVarName."ControlString('".$this->ctrl->ref."', ".$this->escJsStr($this->ctrl->label).");\n";
        
        $this->commonJs($this->ctrl);
    }
    
    function outputControl() {
        $attr = $this->getControlAttributes();
        $value = $this->getValue($this->ctrl);

        $id = $this->builder->getName().'_'.$this->ctrl->ref.'_';
        $attr['name'] = $this->ctrl->ref;
        unset($attr['title']);
        if(is_array($value)){
            if(isset($value[0]))
                $value = $value[0];
            else
                $value = '';
        }
        $value = (string) $value;
        $span ='<span class="jforms-radio jforms-ctl-'.$this->ctrl->ref.'"><input type="radio"';
        $this->showRadioCheck($this->ctrl, $attr, $value, $span);
    }
}
