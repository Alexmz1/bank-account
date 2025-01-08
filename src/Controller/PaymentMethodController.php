<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PaymentMethodController extends AbstractController
{
    #[Route('/payment-method', name: 'app_payment_method')]
    public function index(): Response
    {
        return $this->render('payment_method/index.html.twig', [
            'controller_name' => 'PaymentMethodController',
        ]);
    }
}
