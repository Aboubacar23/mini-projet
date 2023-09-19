<?php

namespace App\Controller;

use App\Repository\ObjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(ObjetRepository $objetRepository): Response
    {
        $objets = $objetRepository->findBy([],['id'=>'desc']);
        return $this->render('home/index.html.twig', [
            'items' => $objets,
        ]);
    }
}
