<?php

require_once 'Zend/EventManager/EventCollection.php';
require_once 'Zend/EventManager/EventManager.php';

/**
 * Description of App_EventTrigger
 *
 * @author Ajmal Hussain
 */
class App_EventTrigger {

    protected $events;

    public function events(Zend_EventManager_EventCollection $events = null) {
        if (null !== $events) {
            $this->events = $events;
        } elseif (null === $this->events) {
            $this->events = new Zend_EventManager_EventManager(__CLASS__);
        }
        return $this->events;
    }

    public function bar($baz, $bat = null) {
        $params = compact('baz', 'bat');
        $this->events()->trigger(__FUNCTION__, $this, $params);
    }

}
