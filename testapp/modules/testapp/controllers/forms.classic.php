<?php
/**
* @package     testapp
* @subpackage  testapp module
* @version     $Id$
* @author      Jouanneau Laurent
* @contributor
* @copyright   2005-2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

class CTForms extends jController {

    function listform(){
        $rep = $this->getResponse('html');
        $rep->title = 'Liste d\'instance de formulaire';
        $rep->body->assign('page_title','formulaires instances multiples');

        $tpl = new jTpl();
        // on triche ici, il n'y a pas d'api car inutile en temps normal
        $tpl->assign('liste', $_SESSION['JFORMS']['sample']); 
        $rep->body->assign('MAIN',$tpl->fetch('forms_liste'));
        return $rep;
    }

    /**
     * creation d'un nouveau formulaire vierge
     * et redirection vers le formulaire html
     */
    function newform(){
        // cr�ation d'un formulaire vierge
        $form = jForms::create('sample');
        $rep= $this->getResponse("redirect");
        $rep->action="forms_showform";
        $rep->params['id']= $form->id();
        return $rep;
    }

    /**
     * creation d'un formulaire avec des donn�es initialis� � partir d'un enregistrement (factice)
     * et redirection vers le formulaire html
     */
    function edit(){
        $id = $this->param('id');
        $form = jForms::create('sample', $this->param('id'));
        // remplissage du formulaire. Ici on le fait � la main, mais �a pourrait
        // �tre � partir d'un dao
        if($id == 1){
            $form->setData('nom','Dupont');
            $form->setData('prenom','Laurent');
        }elseif($id == 2){
            $form->setData('nom','Durant');
            $form->setData('prenom','George');
        }else{
            $form->setData('nom','inconnu');
            $form->setData('prenom','inconnu');
        }
    
        // redirection vers le formulaire
        $rep= $this->getResponse("redirect");
        $rep->action="forms_showform";
        $rep->params['id']= $form->id(); // ou $id, c'est pareil
        return $rep;
    }



    /**
     * affichage du formulaire html
     */
    function showform(){
        $rep = $this->getResponse('html');
        $rep->title = 'Edition d\'un formulaire';
        $rep->body->assign('page_title','formulaires');


        // recup�re les donn�es du formulaire dont l'id est dans le param�tre id
        $form = jForms::get('sample',$this->param('id'));
        if($form){
            $tpl = new jTpl();
            $tpl->assign('form', $form->getContainer());
            $tpl->assign('id', $form->id());
            $rep->body->assign('MAIN',$tpl->fetch('forms_edit'));
        }else{
            $rep->body->assign('MAIN','<p>mauvais id</p>' );
        }
    
        return $rep;
    }

    function save(){

        // comme on laisse la possibilit� dans le formulaire, de pouvoir specifier
        // l'id du formulaire, on compare le nouvel id avec l'ancien pour cr�er
        // un nouveau form en cas de new id
        $id = $this->param('id');
        $newid = $this->param('newid');

        if($id != $newid){
            $id=$newid;
            jForms::create('sample',$id);
        }

        // r�cupe le formulaire et le rempli avec les donn�es re�ues de la requ�te
        $form = jForms::fill('sample',$id);
    
        // on pourrait ici enregistrer les donn�es apr�s un $form->check()
        // non implement� pour le moment...

        $rep= $this->getResponse("redirect");
        $rep->action="forms_listform";
        return $rep;
    }

    function view(){
        $form = jForms::get('sample',$this->param('id'));
        $rep = $this->getResponse('html');
        $rep->title = 'Contenu d\'un formulaire';
        $rep->body->assign('page_title','formulaires');

        if($form){
            $tpl = new jTpl();
            $tpl->assign('nom', $form->getData('nom'));
            $tpl->assign('prenom', $form->getData('prenom'));
            $tpl->assign('id', $this->param('id'));
            $rep->body->assign('MAIN',$tpl->fetch('forms_view'));
        }else{
            $rep->body->assign('MAIN','<p>le formulaire n\'existe pas</p>');
        }
        return $rep;
    }

   function destroy(){
      jForms::destroy('sample',$this->param('id'));
      $rep= $this->getResponse("redirect");
      $rep->action="forms_listform";
      return $rep;
   }



}

?>