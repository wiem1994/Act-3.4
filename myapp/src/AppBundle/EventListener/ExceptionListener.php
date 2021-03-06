<?php



namespace AppBundle\EventListener;



use AppBundle\Controller\FileSystemImproved;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;



class ExceptionListener

{

    public function onKernelException(GetResponseForExceptionEvent $event)

    {

        // You get the exception object from the received event

        $exception = $event->getException();

        $message = sprintf(

            'My Error says: %s with code: %s',

            $exception->getMessage(),

            $exception->getCode()

        );

        $response = new FileSystemImproved();

        $response->writeInFile('errors.txt', $message);

        // Customize your response object to display the exception details

        $response = new Response();

        $response->setContent($message);


        // if ($exception instanceof HttpExceptionInterface) {

        //     $response->setStatusCode($exception->getStatusCode());

        //     $response->headers->replace($exception->getHeaders());
        // } else {

        //     $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        // }

        // sends the modified response object to the event

        // $event->setResponse($response);
    }
}
