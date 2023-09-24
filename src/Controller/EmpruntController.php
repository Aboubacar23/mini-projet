<?php

namespace App\Controller;

use App\Entity\Objet;
use App\Entity\Emprunt;
use App\Form\EmpruntType;
use App\Repository\EmpruntRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt', name: 'app_emprunt_index')]
    public function index(EmpruntRepository $empruntRepository): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'items' => $empruntRepository->findBy([],['id' => 'desc']),
        ]);
    }

    #[Route('/new-emprunt/{id}', name: 'app_emprunt_new', methods: ['GET','POST'])]
    public function create(Objet $objet,Request $request,EmpruntRepository $empruntRepository,EntityManagerInterface $entityManager): Response
    {
        $emprunt = new Emprunt();
        $form = $this->createForm(EmpruntType::class, $emprunt);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            $emprunt->setObjet($objet);
            $objet->setStatut(1);
            $entityManager->persist($emprunt);
            $entityManager->persist($objet);
            $entityManager->flush();
            return $this->redirectToRoute('app_emprunt_index', [], Response::HTTP_SEE_OTHER);
        }

        $form->handleRequest($request);
        return $this->render('emprunt/new.html.twig', [
            'form' => $form->createView(),
            'objet' => $objet
        ]);
    }


    #[Route('/retour-objet/{id}', name: 'app_emprunt_retour')]
    public function retour(Emprunt $emprunt, EntityManagerInterface $entityManager, EmpruntRepository $empruntRepository): Response
    {
        if ($emprunt)
        {
            $objet = $emprunt->getObjet();

            if ($objet->isStatut())
            {
                $objet->setStatut(0); 
                $entityManager->persist($objet);
                $entityManager->remove($emprunt);
                $entityManager->flush();
                return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
            }
        }
        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
