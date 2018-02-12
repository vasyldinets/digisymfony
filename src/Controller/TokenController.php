<?php

namespace App\Controller;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class TokenController extends Controller
{

    /**
     * @Route("/credential/token")
     * @Method("POST")
     */
    public function newTokenAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['username' => $request->getUser()]);
        if (!$user) {
            throw $this->createNotFoundException();
        }
        $isValid = $this->get('security.password_encoder')
            ->isPasswordValid($user, $request->getPassword());
        if (!$isValid) {
            return $this->json(new Response("Something wrong.", Response::HTTP_BAD_REQUEST, array('content-type' => 'application/json')));
        }
        $token = $this->get('lexik_jwt_authentication.encoder')
            ->encode([
                'username' => $user->getUsername(),
                'exp' => time() + 3600*24*30 // 30 day expiration
            ]);

        return $this->json($token);
    }
}
