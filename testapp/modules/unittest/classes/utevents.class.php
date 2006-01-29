<?php

class UTEvents extends UnitTestCase {

    function testEvents() {
      $this->sendMessage("�venement simple");
      $response = jEvent::notify('TestEvent');
      $response = $response->getResponse ();
      $response = serialize($response[0]);
      $temoin = serialize(array('module'=>'unittest','ok'=>true));

      $this->assertTrue($temoin == $response, 'Premier ev�nement');

      $this->sendMessage("�venement avec param�tres");
      $temoin = array('hello'=>'world');
      $response = jEvent::notify('TestEventWithParams',$temoin );
      $response = $response->getResponse ();

      $this->assertTrue(($response[0]['params'] == 'world'), 'Deuxi�me ev�nement');
    }
}

?>