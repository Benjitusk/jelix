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

 //=======================================
 //  ATTENTION !
 // ce controleur ne fonctionne pas pour le moment
 // il s'agit juste d'un prototype, servant de base de recherche
 // pour trouver l'api la plus ad�quate pour jForm....



  function newform(){
      // cr�ation d'un formulaire vierge
      $form = jForms::create('sample');
      $rep= $this->getResponse("redirect");
      $rep->action="forms_show";
      $rep->params['id']=0; //$form->ident();
      return $rep;
  }


  function edit(){
     $form = jForms::create('sample', 'id');
     // remplissage...
     $rep= $this->getResponse("redirect");
     $rep->action="forms_show";
     $rep->params['id']=$this->param('id');
     return $rep;
  }

  function show(){
      // recup�re les donn�es du formulaire dont l'id est dans le param�tre id
      $form = jForms::get('sample','id');

      $rep = $this->getResponse('html');
      $rep->title = 'Edition d\'un formulaire';

      $tpl = new jTpl();
      $tpl->assign('form', $form->getContainer());
      $rep->body->assign('MAIN',$tpl->fetch('sampleform'));

      return $rep;
   }

   function save(){
      // r�cuper le formulaire dont l'id est dans le param�tre id
      // et le rempli avec les donn�es re�ues de la requ�te
      $form = jForms::fill('sample','id');

      $rep= $this->getResponse("redirect");
      $rep->action="forms_ok";
      return $rep;
   }

   function ok(){
      $form = jForms::get('sample','id');
      $datas=$form->getContainer()->datas;

      $rep = $this->getResponse('html');
      $rep->title = 'Edition d\'un formulaire';
      $tpl = new jTpl();
      $tpl->assign('nom', $datas['nom']);
      $tpl->assign('prenom', $datas['prenom']);



      $rep->body->assign('MAIN',$tpl->fetch('sampleformresult'));
      return $rep;
   }

}

?>