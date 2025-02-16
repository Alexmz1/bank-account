<?php

namespace App\Controller;

use App\Service\InvestmentRecommendationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InvestmentController extends AbstractController
{
    #[Route('/investment/recommendations', name: 'investment_recommendations')]
    public function index(InvestmentRecommendationService $investmentService): Response
    {
        $recommendations = $investmentService->getRecommendations();

        return $this->render('investment/recommendations.html.twig', [
            'recommendations' => $recommendations,
        ]);
    }
}
