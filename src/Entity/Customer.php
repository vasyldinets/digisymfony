<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CustomerRepository")
 * @UniqueEntity(
 *     fields={"cardNumber"},
 *     message="This card is already exist"
 * )
 */
class Customer
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=16, name="card_number",  unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(max=16)
     */
    private $cardNumber;

    /**
     * @ORM\Column(type="integer", length=2, name="card_month")
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *     min=1,
     *     max=12,
     *      minMessage = "You must enter more than  {{ limit }}",
     *      maxMessage = "You must enter more less than {{ limit }}"
     * )
     */
    private $cardMonth;

    /**
     * @ORM\Column(type="integer", length=4, name="card_year")
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *     min=2018,
     *     max=2019,
     *      minMessage = "You must enter more than  {{ limit }}",
     *      maxMessage = "You must enter more less than {{ limit }}"
     * )
     */
    private $cardYear;

    /**
     * @ORM\Column(type="integer", length=3, name="card_cvv")
     * @Assert\NotBlank()
     * @Assert\Type("integer")
     * @Assert\Range(
     *     min=100,
     *     max=999,
     *     invalidMessage = "Wrong CVV",
     * )
     */
    private $cardCvv;

    /**
     * @ORM\Column(type="decimal", scale=2, name="card_limit")
     */
    private $cardLimit = 10000.00;



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     */
    public function setCardNumber($cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return mixed
     */
    public function getCardMonth()
    {
        return $this->cardMonth;
    }

    /**
     * @param mixed $cardMonth
     */
    public function setCardMonth($cardMonth): void
    {
        $this->cardMonth = $cardMonth;
    }

    /**
     * @return mixed
     */
    public function getCardYear()
    {
        return $this->cardYear;
    }

    /**
     * @param mixed $cardYear
     */
    public function setCardYear($cardYear): void
    {
        $this->cardYear = $cardYear;
    }

    /**
     * @return mixed
     */
    public function getCardCvv()
    {
        return $this->cardCvv;
    }

    /**
     * @param mixed $cardCvv
     */
    public function setCardCvv($cardCvv): void
    {
        $this->cardCvv = $cardCvv;
    }

    /**
     * @return mixed
     */
    public function getCardLimit()
    {
        return $this->cardLimit;
    }

    /**
     * @param mixed $cardLimit
     */
    public function setCardLimit($cardLimit): void
    {
        $this->cardLimit = $cardLimit;
    }

}
