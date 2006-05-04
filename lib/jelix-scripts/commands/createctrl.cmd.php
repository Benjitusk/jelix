<?php

/**
* @package     jelix-scripts
* @version     $Id$
* @author      Jouanneau Laurent
* @contributor Loic Mathaud
* @copyright   2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/

class createctrlCommand extends JelixScriptCommand {

    public  $name = 'createctrl';
    public  $allowed_options=array('-cmdline'=>false);
    public  $allowed_parameters=array('module'=>true,'name'=>true, 'method'=>false);

    public  $syntaxhelp = "[-cmdline] MODULE NOM_CONTROLEUR [NOM_METHOD]";
    public  $help="
    Permet de cr�er un nouveau fichier d'une classe jController ou jControllerCmdLine

    Si l'option -cmdline est pr�sente, cr�� un controller de type jControllerCmdLine,
    pour d�velopper des scripts en ligne de commande. Sinon, le controller cr�� est
    de type jController.
    
    MODULE : le nom du module concern�.
    NOM_CONTROLEUR :  nom du controleur que vous voulez cr�er.
    NOM_METHOD (facultatif) : nom de la premi�re m�thode. Par d�faut, elle a
                              le nom index.";
    

    public function run(){
       $path= $this->getModulePath($this->_parameters['module']);

       $agfilename= $path.'controllers/';
       $this->createDir($agfilename);
       
       if ($this->getOption('-cmdline')) { 
            $type = 'cmdline';
       } else {
            $type = 'classic';
       }
       
       $agfilename.=strtolower($this->_parameters['name']).'.'. $type .'.php';

       $method = $this->getParam('method','index');

       $param= array('name'=>$this->_parameters['name'] , 'method'=>$method);

       if ($this->getOption('-cmdline')) {
            $tplname = 'controller.cmdline.tpl';
       } else {
            $tplname = 'controller.tpl';
       }
       $this->createFile($agfilename,$tplname,$param);

    }
}


?>
