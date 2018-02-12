<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Customer;


/**
 * @Route("/api")
 */
class CustomerController extends Controller
{
    /**
     * @Route("/customer", name="store_customer")
     * @Method({"POST"})
     */
    public function store(Request $request, ValidatorInterface $validator) {
        $request = $request->request;

          if ($request->get('cardYear') < date('Y') || ($request->get('cardYear') == date('Y') && $request->get('cardMonth') < date('m')) ){
              return $this->json(new Response('Expired Card', Response::HTTP_BAD_REQUEST, array('content-type' => 'application/json')));
          }
        $em = $this->getDoctrine()->getManager();
        $customer = new Customer();

        $customer->setName($request->get('name'));
        $customer->setCardNumber($request->getInt('cardNumber'));
        $customer->setCardMonth($request->getInt('cardMonth'));
        $customer->setCardYear($request->getInt('cardYear'));
        $customer->setCardCvv($request->getInt('cardCvv'));
        $errors = $validator->validate($customer);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return $this->json(new Response($errorsString, Response::HTTP_BAD_REQUEST, array('content-type' => 'application/json')));
        } else {
            $em->persist($customer);
            $em->flush();
        }

        return $this->json($customer->getId());
    }
}
