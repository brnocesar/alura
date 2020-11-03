<?php

namespace App\EventListeners;

use App\Factory\EntityFactoryException;
use App\Factory\ResponseFactory;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;

class ExceptionHandler implements EventSubscriberInterface
{
    /**
     * @var LoggerInterface
     */
    // protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * The code must not depend on runtime state as it will only be called at compile time.
     * All logic depending on runtime state must be put into the individual methods handling the events.
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        // return [
        //     KernelEvents::EXCEPTION => 'handleGenericException'
        // ];
        return [
            KernelEvents::EXCEPTION => [
                ['handleEntityException', 1],
                ['handle404Exception', 0],
                ['handleGenericException', -1],
            ],
        ];
    }

    public function handle404Exception(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof NotFoundHttpException) {

            $response = ResponseFactory::fromError($exception)->getResponse();
            $response->setStatusCode($exception->getStatusCode()); // tem que ver isso aqui
            $event->setResponse($response);
        }
    }

    public function handleEntityException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        if ($exception instanceof EntityFactoryException) {

            $response = ResponseFactory::fromError($exception)->getResponse();
            $response->setStatusCode($exception->getCode()); // tem que ver isso aqui
            $event->setResponse($response);
        }
    }

    public function handleGenericException(ExceptionEvent $event)
    {
        $this->logger->critical('Uma exceção ocorreu. {stack}', [
            'stack' => $event->getThrowable()->getTraceAsString()
        ]);

        $response = ResponseFactory::fromError($event->getThrowable())->getResponse();
        $event->setResponse($response);
    }
}