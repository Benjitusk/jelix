<?php
/**
* @package     testapp
* @subpackage  unittest module
* @version     $Id$
* @author      Jouanneau Laurent
* @contributor
* @copyright   2006 Jouanneau laurent
* @link        http://www.jelix.org
* @licence     GNU Lesser General Public Licence see LICENCE file or http://www.gnu.org/licenses/lgpl.html
*/

class AGDefault extends jActionGroup {

   function getDefault() {
      $rep = $this->getResponse('accueil');
      $rep->bodyTpl = 'accueil';
      $rep->title = 'test unitaires';
      return $rep;
   }

   /*
   *
   */
   function getTestEvents (){
      $rep = $this->getResponse('event');
      $rep->bodyTpl = '';
      $rep->title = 'test unitaires sur jEvent';

      $content='<p>Premier �v�nement : ';

      $response = jEvent::notify('TestEvent');
      $response = $response->getResponse ();
      $response = serialize($response[0]);
      $temoin = serialize(array('module'=>'unittest','ok'=>true));
      if($temoin == $response)
          $content .='r�ponse ok</p>';
      else{
          $content .='r�ponse mauvaise.</p><pre>';
          ob_start();
          var_dump($response);
          $content.= ob_get_contents();
          ob_end_clean();
          $content.='</pre><p>R�ponse attendue :</p><pre>';
          ob_start();
          var_dump($temoin);
          $content.= ob_get_contents();
          ob_end_clean();
          $content .='</pre>';
      }
      $content.='<hr /><p>Deuxi�me �v�nement : ';

      $temoin = array('hello'=>'world');
      $response = jEvent::notify('TestEventWithParams',$temoin );

      $response = $response->getResponse ();
      /*$response = serialize($response[0]['params']);
      $temoin = serialize($temoin);*/
      if($response[0]['params'] == 'world')
          $content .='r�ponse ok</p>';
      else{
          $content .='r�ponse mauvaise.</p><pre>';
          ob_start();
          var_dump($response);
          $content.= ob_get_contents();
          ob_end_clean();
          $content.='</pre><p>R�ponse attendue :</p><pre>';
          ob_start();
          var_dump($temoin);
          $content.= ob_get_contents();
          ob_end_clean();
          $content .='</pre>';
      }

      $rep->addTopOfBody($content);
      return $rep;
   }


}
?>