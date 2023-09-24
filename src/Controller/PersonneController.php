<?php

namespace App\Controller;

use App\Entity\Personne;
use App\Form\PersonneType;
use App\Form\ImporPersonneType;
use App\Repository\PersonneRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/personne')]
class PersonneController extends AbstractController
{
    #[Route('/', name: 'app_personne_index', methods: ['GET','POST'])]
    public function index(PersonneRepository $personneRepository, Request $request, EntityManagerInterface $entityManager): Response
    {   
        $personne = new Personne();
        $form = $this->createForm(ImporPersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        { 
            //récuperer le fichier importer 
            $file = $form->get('nom')->getData();

            //charger le fichier et flirer à dans le fichier
            $fichier = IOFactory::load($file->getPathname());

            //recuperer le contenu dans du fichier et affichier en tableau de chaine de caractère
            $donnees = $fichier->getActiveSheet()->toArray();

            //parcourir le tableau pour inserer dans la base de donnée
            foreach($donnees as $item)
            {
                //vérifier s'il y'a une ligne vide dans la base 
                if(!empty(array_filter($item)))
                {
                    //initialiser la classe pour chaque ligne
                    $personne = new Personne();

                    //inserer les données dans la base pour chaque ligne
                    $personne->setNom(strval($item[0]));
                    $personne->setPrenom(strval($item[1]));
                    $personne->setEmail(strval($item[2]));
                    
                    $entityManager->persist($personne);
                }
            }
            $entityManager->flush();
            return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('personne/index.html.twig', [
            'personnes' => $personneRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/new', name: 'app_personne_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $personne = new Personne();
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($personne);
            $entityManager->flush();

            return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personne/new.html.twig', [
            'personne' => $personne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personne_show', methods: ['GET'])]
    public function show(Personne $personne): Response
    {
        if ($personne)
        {
            return $this->render('personne/show.html.twig', [
                'personne' => $personne,
            ]);
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route('/{id}/edit', name: 'app_personne_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonneType::class, $personne);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('personne/edit.html.twig', [
            'personne' => $personne,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_personne_delete', methods: ['POST'])]
    public function delete(Request $request, Personne $personne, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personne->getId(), $request->request->get('_token'))) {
            $entityManager->remove($personne);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_personne_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     *             if($choix == 'ajouter')
            {
                //récuperer le fichier importer 
                $file = $formPointFonctionnement->get('observation')->getData();

                //charger le fichier et flirer à dans le fichier
                $fichier = IOFactory::load($file->getPathname());

                //recuperer le contenu dans du fichier et affichier en tableau de chaine de caractère
                $donnees = $fichier->getActiveSheet()->toArray();

                //parcourir le tableau pour inserer dans la base de donnée
                foreach($donnees as $item)
                {
                    //vérifier s'il y'a une ligne vide dans la base 
                    if(!empty(array_filter($item)))
                    {
                        //initialiser la classe pour chaque ligne
                        $pointFonctionnement = new PointFonctionnement();

                        //inserer les données dans la base pour chaque ligne
                        $pointFonctionnement->setT(strval($item[0]));
                        $pointFonctionnement->setU(strval($item[1]));
                        $pointFonctionnement->setI1(strval($item[2]));
                        $pointFonctionnement->setI2(strval($item[3]));
                        $pointFonctionnement->setI3(strval($item[4]));
                        $pointFonctionnement->setP(strval($item[5]));
                        $pointFonctionnement->setQ(strval($item[6]));
                        $pointFonctionnement->setCos(strval($item[7]));
                        $pointFonctionnement->setN(strval($item[8]));
                        $pointFonctionnement->setI(strval($item[9]));
                        $pointFonctionnement->setTamb(strval($item[10]));
                        $pointFonctionnement->setCa(strval($item[11]));
                        $pointFonctionnement->setCoa(strval($item[12]));
                        $pointFonctionnement->setObservation($item[13]);
                        $pointFonctionnement->setParametre($parametre);
                        
                        $em->persist($pointFonctionnement);
                    }
                }
                $em->flush();
                return $this->redirectToRoute('app_point_fonctionnement', ['id' => $parametre->getId()]);
            }
     */
}
