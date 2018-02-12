<?php

namespace App\Controller;

use App\Entity\Transaction;
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
class TransactionController extends Controller
{

    /**
     * @Route("/transaction", name="store_transaction")
     * @Method({"POST"})
     */
    public function store(Request $request, ValidatorInterface $validator){

        $request = $request->request;
        $em = $this->getDoctrine()->getManager();
        $userId = $this->getUser()->getId();
//       Check if exist customer
        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($request->getInt('customerId'));

        if (!$customer){
            return $this->json(new Response('Sorry Customer not existing!', Response::HTTP_NOT_FOUND, array('content-type' => 'application/json')));
        }
//        Check card limit
        if ($customer->getCardLimit() < $request->getDigits('amount')){
            return $this->json( new Response('Not enough money in your card'));
        }
//      Create new transaction
        $transaction = new Transaction();
        $transaction->setCustomerId($request->getInt('customerId'));
        $transaction->setSellerId($userId);
        $transaction->setAmount($request->get('amount'));
        $transaction->setOffset(-$request->get('amount'));
        $transaction->setLimit($customer->getCardLimit() - $request->get('amount'));
        $transaction->setDate(date('Y-m-d'));

        $errors = $validator->validate($transaction);
        if (count($errors) > 0) {
            $errorsString = (string) $errors;
            return $this->json(new Response($errorsString, Response::HTTP_BAD_REQUEST, array('content-type' => 'application/json')));
        } else {
            $em->persist($transaction);
            $em->flush();
//            If all ok - update card limit
            $customer->setCardLimit($transaction->getLimit());
            $em->persist($customer);
            $em->flush();
        }


        return $this->json($transaction);
    }

    /**
     * @Route("/transaction/{customerId}/{transactionId}", name="show_transaction")
     * @Method({"GET"})
     */
    public function show($customerId, $transactionId) {
        $transaction = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->findOneBy(['id' => $transactionId, 'customerId' => $customerId]
            );
        if (!$transaction){
            $response = new Response('Sorry Transaction not existing!', Response::HTTP_NOT_FOUND, array('content-type' => 'application/json'));
            return $response;
        }
        $response = $this->json($transaction);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }

    /**
     * @Route("/transaction/{transactionId}", name="update_transaction")
     * @Method({"PATCH"})
     */
    public function update(Request $request, ValidatorInterface $validator, $transactionId){

        $request = $request->request;
        $em = $this->getDoctrine()->getManager();

        $transaction = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->find($transactionId);

        if (!$transaction){
            return $this->json(new Response('Sorry Transaction not existing!', Response::HTTP_NOT_FOUND, array('content-type' => 'application/json')));
        }

        $customer = $this->getDoctrine()
            ->getRepository(Customer::class)
            ->find($transaction->getCustomerId());

//        Check difference between operations
        $updateLimit = $customer->getCardLimit() + ($transaction->getAmount() - $request->get('amount'));

//      Update transaction data
        $transaction->setAmount($request->get('amount'));
        $transaction->setLimit($updateLimit);
        $transaction->setOffset(-$request->get('amount'));
        $transaction->setDate(date('Y-m-d'));

        $errors = $validator->validate($transaction);
        if (count($errors) > 0) {
            $errorsString = (string)$errors;
            return $this->json(new Response($errorsString, Response::HTTP_BAD_REQUEST, array('content-type' => 'application/json')));
        }
        $em->persist($transaction);
        $em->flush();

//        Update cart limit
        $customer->setCardLimit($updateLimit);
        $em->persist($customer);
        $em->flush();

        return $this->json(new Response('success'));
    }

    /**
     * @Route("/transaction/{transactionId}", name="delete_transaction")
     * @Method({"DELETE"})
     */
    public function destroy($transactionId) {

        $em = $this->getDoctrine()->getManager();
        $transaction = $this->getDoctrine()
            ->getRepository(Transaction::class)
            ->find($transactionId);

        if (!$transaction){
            return $this->json(new Response('Sorry Transaction not existing!', Response::HTTP_NOT_FOUND, array('content-type' => 'application/json')));
        }

        $em->remove($transaction);
        $em->flush();

        return $this->json(new Response('success'));
    }

    /**
     * @Route("/transactions{token}", defaults={"token" = null})
     * @Method({"GET"})
     */
    public function getFilteredTransactions(Request $request){

        $repository = $this->getDoctrine()->getRepository(Transaction::class);
        $query = $request->query->all();
        $param = [];
        foreach ($query as $key => $value){
            if ($value && $key != '_url'){
                $param[$key] = $value;
            }
        }
        $transactions  = $repository->findBy($param);
        $response = $this->json($transactions);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }
}
