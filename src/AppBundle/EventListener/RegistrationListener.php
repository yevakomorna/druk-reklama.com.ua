<?php
namespace AppBundle\EventListener;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Psr\Log\LoggerInterface;

class RegistrationListener implements EventSubscriberInterface {

    private $logger;

    public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents() {
        return array(FOSUserEvents::REGISTRATION_SUCCESS => ['onRegistrationSuccess', -10], );
    }

    public function onRegistrationSuccess(FormEvent $event) {

        $user = $event->getForm()->getData();
        $user->setUsername($user->getEmail());
        $this->logger->info($user->getUsername().' ;');
    }
}
