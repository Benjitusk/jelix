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

class CTSampleForm extends jController {

  function newform(){
      // cr�ation d'un formulaire vierge
      $form = jForms::create('sample');
      $rep= $this->getResponse("redirect");
      $rep->action="sampleform_show";
      return $rep;
  }

  function show(){
      // recup�re les donn�es du formulaire
      $form = jForms::get('sample');

      $rep = $this->getResponse('html');
      $rep->title = 'Edition d\'un formulaire';

      $tpl = new jTpl();
      $tpl->assign('form', $form->getContainer());
      $rep->body->assign('MAIN',$tpl->fetch('sampleform'));
      $rep->body->assign('page_title','formulaires');

      return $rep;
   }

   function save(){
      // r�cuper le formulaire
      // et le rempli avec les donn�es re�ues de la requ�te
      $form = jForms::fill('sample');

      $rep= $this->getResponse("redirect");
      $rep->action="sampleform_ok";
      return $rep;
   }

   function ok(){
      $form = jForms::get('sample');
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

   function destroy(){
      jForms::destroy('sample');
      $rep= $this->getResponse("redirect");
      $rep->action="sampleform_status";
      return $rep;
   }

   function status(){
      $rep = $this->getResponse('html');
      $rep->title = 'Etat des donn�es formulaire';

      $rep->body->assign('page_title','formulaires');

      $content='<h1>Donn�es en session des formulaires</h1>';
      $content.='<pre>'.htmlspecialchars(var_export($_SESSION['JFORMS'],true)).'</pre>';
      $rep->body->assign('MAIN',$content);
      return $rep;
   }

}

?>