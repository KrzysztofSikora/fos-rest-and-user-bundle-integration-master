<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ApiController extends FOSRestController
{


    /**
     * @Rest\View( statusCode=Response::HTTP_OK)
     * @Rest\Get("/all-users")
     *
     */
    public function getAllUsersAction(Request $request){

        $result = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->findAll();
        if(empty($result)){
            return new JsonResponse(['message' => 'The requested ressource was not found'], Response::HTTP_NOT_FOUND);
        }
        return $result;

    }

    /**
     * @Rest\View( statusCode=Response::HTTP_OK)
     * @Rest\Get("/user-detail/{user}")
     *
     */
    public function getUserDetailAction(Request $request){



       $userId = $request->attributes->get('user');

        $result = $this->getDoctrine()->getManager()->getRepository('AppBundle:User')->find($userId);
        if(empty($result)){
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        return $result;

    }


    /**
     * @Rest\View( statusCode=Response::HTTP_OK)
     * @Rest\Get("/delete")
     *
     */
    public function getDeleteUserAction(Request $request){

        if (!$this->getUser()) {
            throw new AccessDeniedHttpException();
        }

        $id = $this->getUser()->getId();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('AppBundle:User')->find($id);

        if(empty($entity)){
            return new JsonResponse(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($entity);
        $em->flush();

        return new JsonResponse(['message' => 'Removed'], Response::HTTP_OK);

    }

}
