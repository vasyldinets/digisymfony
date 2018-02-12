<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Transaction;
use Symfony\Component\HttpFoundation\Response;

class TransactionWebController extends Controller
{
    /**
     * @Route("/transactions", name="transactions")
     */
    public function index()
    {
        // replace this line with your own code!
        return $this->render('views/transactions.html.twig');
    }

    /**
     * @Route("/transactions/filter{token}", defaults={"token" = null})
     * @Method({"GET"})
     */
    public function getFilteredTransactionsWithPagination(Request $request){
        $userId = $this->getUser()->getId();
        $repository = $this->getDoctrine()->getRepository(Transaction::class);
        $query = $request->query->all();
        $limit = $request->query->get('showby');
        $page = $request->query->get('page');
        $page_string = $request->getSchemeAndHttpHost ().$request->getPathInfo().'?page=';
        $param = '';
        foreach ($query as $key => $value){
            if ($value && $key != '_url' && $key != 'showby' && $key != 'page'){
                if ($key == 'date'){
                    $param = $param." AND p.".$key."='".$value."'";
                } else {
                    $param = $param.' AND p.'.$key.'='.$value;
                }
            }
        }
        $transaction = $repository->getTransactionPaginated($page, $limit, $param, $userId);
        $maxPages = ceil($transaction->count() / $limit);
        $data = array(
            'data' => $transaction,
            'current_page' => $page,
            'last_page' => $maxPages,
            'per_page' => $limit,
            'first_page_url' => $page_string.'1',
            'last_page_url' => $page_string.$maxPages,
            'next_page_url' => $page < $maxPages ? $page_string.($page+1): null,
            'prev_page_url' => $page > 1 ? $page_string.($page-1): null,
        );
        $response = $this->json($data);
        $response->setSharedMaxAge(3600);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        return $response;
    }
}
