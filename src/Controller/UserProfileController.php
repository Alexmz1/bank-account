<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\UserRepository;

class UserProfileController extends AbstractController
{
    #[Route('/user-profile', name: 'app_user_profile')]
    public function index(): Response
    {

    // if (!$user) {
    //     // Vérifiez si la session est active
    //     throw new \Exception('Utilisateur non connecté ou session invalide.');
    // }
    

    return $this->render('user_profile/index.html.twig');
    }
}
