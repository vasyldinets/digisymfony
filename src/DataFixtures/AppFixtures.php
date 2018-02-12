<?php
namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Transaction;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // create 20 products! Bam!
        for ($i = 0; $i < 300; $i++) {

            $int= mt_rand(strtotime("5 February 2017"), strtotime("now"));

            $product = new Transaction();
            $product->setCustomerId(mt_rand(1, 5));
            $product->setSellerId(mt_rand(1, 2));
            $product->setAmount(mt_rand(1, 500));
            $product->setLimit(mt_rand(1, 50000));
            $product->setOffset(-$product->getAmount());
            $product->setDate(date('Y-m-d', $int));
            $manager->persist($product);
        }

        for ($i = 0; $i < 5; $i++) {
            $customer = new Customer();
            $customer->setName(mt_rand(1, 2));
            $customer->setCardNumber(mt_rand(4000000000000000, 6000000000000000));
            $customer->setCardMonth(mt_rand(1, 12));
            $customer->setCardYear(mt_rand(2018, 2025));
            $customer->setCardCvv(mt_rand(100, 999));
            $customer->setCardLimit(25000);
            $manager->persist($customer);
        }


        $manager->flush();
    }
}