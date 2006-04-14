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

  function newform(){
      // cr�ation d'un formulaire vierge
      $form = jForms::create('sample');
      $rep= $this->getResponse("redirect");
      $rep->action="forms_show";
      $rep->params['fid']= $form->id();
      return $rep;
  }


  function edit(){
     $form = jForms::create('sample', $this->param('newsid'));
     // remplissage...
     $rep= $this->getResponse("redirect");
     $rep->action="forms_show";
     $rep->params['id']= $form->id();
     return $rep;
  }

  function show(){
      // recup�re les donn�es du formulaire dont l'id est dans le param�tre id
      $form = jForms::get('sample','fid');

      $rep = $this->getResponse('html');
      $rep->title = 'Edition d\'un formulaire';

      $tpl = new jTpl();
      $tpl->assign('form', $form->getContainer());
      $rep->body->assign('MAIN',$tpl->fetch('sampleform'));
      $rep->body->assign('page_title','formulaires');

      return $rep;
   }

   function save(){
      // r�cuper le formulaire dont l'id est dans le param�tre id
      // et le rempli avec les donn�es re�ues de la requ�te
      $form = jForms::fill('sample','fid');

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

      $rep->body->assign('page_title','formulaires');
      $rep->body->assign('MAIN',$tpl->fetch('sampleformresult'));
      return $rep;
   }

}

?>