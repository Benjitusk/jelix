<?php
/**
* @package     jelix-scripts
* @version     $Id$
* @author      Jouanneau Laurent
* @contributor
* @copyright   2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/



class createdaoCommand extends JelixScriptCommand {

    public  $name = 'createdao';
    public  $allowed_options=array('-profil'=>true, '-empty'=>false);
    public  $allowed_parameters=array('module'=>true,'name'=>true, 'table'=>false);

    public  $syntaxhelp = "[-profil nom] [-empty] MODULE DAO TABLEPRINCIPALE";
    public  $help="
    Cr�er un nouveau fichier de dao

    -profil (facultatif) : indique le profil � utiliser pour se connecter �
                           la base et r�cup�rer les informations de la table
    -empty (facultatif) : ne se connecte pas � la base et g�n�re un fichier
                          dao vide

    MODULE : le nom du module concern�.
    DAO :  nom du dao � cr�er.
    TABLEPRINCIPALE : nom de la table principale sur laquelle s'appuie le dao
                      (cette commande ne permet pas de g�n�rer un dao
                      s'appuyant sur de multiple table)";


    public function run(){

       jxs_init_jelix_env();

       $path= $this->getModulePath($this->_parameters['module']);

       $filename= $path.'daos/';
       $this->createDir($filename);

       $filename.=strtolower($this->_parameters['name']).'.dao.xml';

       $profil= $this->getOption('-profil');

       $param = array('name'=>($this->_parameters['name']),
              'table'=>($this->_parameters['table']));

       if($this->getOption('-empty')){
          $this->createFile($filename,'dao_empty.xml.tpl',$param);
       }else{

         $tools = jDb::getTools($profil);
         $fields = $tools->getFieldList($this->_parameters['table']);

         $properties='';
         $primarykeys='';
         foreach($fields as $fieldname=>$prop){

            switch($prop->type){

               case 'varchar':
               case 'text':
               case 'mediumtext':
               case 'longtext':
               case 'tinytext':
               case 'char':
               case 'enum':
               case 'set':
                  $type='string';
                  break;
               case 'tinyint':
               case 'int':
               case 'integer':
               case 'smallint':
               case 'year':
                  if($prop->auto_increment ){
                     $type='autoincrement';
                  }else{
                     $type='int';
                  }
                  break;

               case 'mediumint':
               case 'bigint':
                  if($prop->auto_increment ){
                     $type='bigautoincrement';
                  }else{
                     $type='numeric';
                  }
                  break;
               case 'float':
               case 'double':
               case 'decimal':
                  $type='float';
                  break;

               case 'date':
               case 'datetime':
               case 'timestamp':
               case 'time':
                  $type='date';
                  break;
               default:
                  $type='';
            }

            if($type!=''){
               $properties.="\n    <property name=\"$fieldname\" fieldname=\"$fieldname\"";
               $properties.=' datatype="'.$type.'"';
               if($prop->primary){
                  if($primarykeys != '')
                     $primarykeys.=','.$fieldname;
                  else
                     $primarykeys.=$fieldname;
               }
               if($prop->not_null)
                  $properties.=' required="yes"';
               $properties.='/>';
            }

         }
         $param['properties']=$properties;
         $param['primarykeys']=$primarykeys;
         $this->createFile($filename,'dao.xml.tpl',$param);
       }
    }
}


?>
