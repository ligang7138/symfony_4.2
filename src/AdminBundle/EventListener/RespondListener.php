<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\AdminBundle\EventListener;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Bridge\Monolog\Logger;

/**
 * Builds the response object.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
final class RespondListener
{
    private $logger = null;

    const METHOD_TO_CODE = [
        'POST' => Response::HTTP_CREATED,
        'DELETE' => Response::HTTP_NO_CONTENT,
    ];

    public function setLogger(Logger $logger){
        if(empty($this->logger)){
            $this->logger = $logger;
        }
    }

    /**
     * Creates a Response to send to the client according to the requested format.
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();
        $headers = $request->headers;


        if ($response instanceof JsonResponse) {

            switch ($response->getStatusCode()){
                case Response::HTTP_PRECONDITION_FAILED:
                case Response::HTTP_UNAUTHORIZED:
                    if($headers->contains('accept','application/json')){
                        $data = json_decode($response->getContent(),true);
                        if(preg_match("/(?<=alert\(')[^']+/",$data['msg'],$match)){
                            $data['msg'] = $match[0];
                        }
                        $data['msg'] = $data['msg'];
                        $response->setData($data);
                    }else{
                        $data = json_decode($response->getContent(),true);
                        $response =  new Response($data['msg'],$data['status']);
                    }
                    break;
                case Response::HTTP_INTERNAL_SERVER_ERROR:
                    $data = json_decode($response->getContent(),true);
                    $data['msg'] = strip_tags($data['msg']);
                    $response->setData($data);
                    break;
            }
            $this->logger->debug('',['data'=>$response->getContent()]);
            $event->setResponse($response);
        }
    }
}
