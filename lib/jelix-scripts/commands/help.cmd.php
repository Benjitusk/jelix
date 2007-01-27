<?php
/**
* @package     jelix-scripts
* @author      Jouanneau Laurent
* @contributor
* @copyright   2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU General Public Licence see LICENCE file or http://www.gnu.org/licenses/gpl.html
*/


class helpCommand extends JelixScriptCommand {

    public  $name = 'help';
    public  $allowed_options=array();
    public  $allowed_parameters=array('command'=>false);

    public  $syntaxhelp ="[COMMANDE]";
    public  $help="      COMMANDE : nom de la commande dont vous voulez l'aide (param�tre facultatif)";


    public function run(){
       if(isset($this->_parameters['command'])){
          if($this->_parameters['command'] == 'help'){
             $command=$this;
          }else{
             $command = jxs_load_command($this->_parameters['command']);
          }
          $this->disp("\nUtilisation de la commande ".$this->_parameters['command']." :\n");
          $this->disp("# php jelix.php  [--NOM_APP] ".$this->_parameters['command']." ". $command->syntaxhelp."\n\n");
          $this->disp($command->help."\n\n");
       }else{
          $this->disp("
Utilisation g�n�rale :
    ".$_SERVER['argv'][0]." [--NOM_APP] COMMANDE [OPTIONS] [PARAMETRES]

    NOM_APP  : nom de l'application concern�e. Si non pr�sent, le nom de
               l'application doit �tre dans une variable d'environnement
               JELIX_APP_NAME
    COMMANDE : nom de la commande � executer
    OPTIONS  : une ou plusieurs options. Le nom d'une option commence par un
               tiret et peut �tre suivi par une valeur.
               exemple :
                 -override
                 -project-path /foo/bar
    PARAMETRES : une ou plusieurs valeurs qui se suivent

    Les options et param�tres � indiquer d�pendent de la commande. Les options
    sont toujours facultatives, ainsi que certains param�tres.
    Consulter l'aide d'une commande en faisant :
       ".$_SERVER['argv'][0]." help COMMANDE

Liste des commandes disponibles :\n\t");

          $list = jxs_commandlist();
          foreach($list as $cmd)
             $this->disp($cmd.' ');
          $this->disp("\n\n");

       }
    }

    protected function disp($str){
       if( DISPLAY_HELP_UTF_8){
         echo utf8_encode($str);
       }else{
         echo $str;
       }
    }
}


?>