<?php
/**
* @package    jelix
* @subpackage jforms
* @version    $Id$
* @author     Jouanneau Laurent
* @contributor
* @copyright   2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence    GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/


class jFormsCompiler implements jISimpleCompiler {

    public function compile($selector){
        global $gJCoord;
        $sel = clone $selector;

        $sourceFile = $selector->getPath();
        $cachefile = $selector->getCompiledFilePath();

        // compilation du fichier xml
        $xml = simplexml_load_file ( $sourceFile);
        if(!$xml){
           return false;
        }

        $foundAction=false;
        foreach($xml->request as $req){
            if(isset($req['type'])){
                $requesttype=$req['type'];
            }else{
                trigger_error(jLocale::get('jelix~errors.ac.xml.request.type.attr.missing',array($sourceFile)), E_USER_ERROR);
                jContext::pop();
                return false;
            }

        }





        return true;
    }
}



interface jIFormGenerator {

   // on indique un objet form
   // il renvoi dans un tableau le code g�n�r� correspondant
   /*
   startform : code g�n�r� pour le debut du formulaire (balise <form> en html) Peut contenir %ATTR%
   head : code g�n�r� � ajouter dans l'en-t�te de page
   controls : tableau assoc de chaque contr�le g�n�r�. Peuvent contenir %ATTR%
   endform : code g�n�r� pour la fin du formulaire


   %ATTR% : remplac�s par les attributs suppl�mentaires indiqu�s par l'utilisateur dans le template
   */
   function buildForm($formObject);

}


?>