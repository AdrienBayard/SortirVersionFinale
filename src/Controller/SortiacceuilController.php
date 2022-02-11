<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/acceuil',name: 'acceuil_')]
class SortiacceuilController extends AbstractController
{
    #[Route('/ville', name: 'ville')]
    public function indexVILLE(SortieRepository $sortieRepository, Request $request): Response
    {
        return $this->render('sortie/acceuil.html.twig');
    }
    #[Route('/', name: 'acceuil')]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('acceuil.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

     #[Route('/{id}', name:'show')]
    public function show(Sortie $sortie, Request $request): Response
    {
        $subscribeEvent = new SubscribeSortieType();
        $form = $this->createForm(SubscribeSortieType::class, $subscribeEvent);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            //gestion de l'inscription et désinscription
            if ($sortie->getAEteInscrit()->contains($this->getUser())) {
                $this->getUser()->removeEstInscrit($sortie);
            } else {
                //contrôle du nb max de participants
                if ($sortie->getAEteInscrit()->count()<$sortie->getNbMax()) {
                    $this->getUser()->addEstInscrit($sortie);
                } else {
                    $this->addFlash('error', "Inscription impossible, le nb de participants max est atteint");
                }

            }

            $entityManager->flush();
        }
        $users = $sortie->getAEteInscrit();

        return $this->render('sortie/acceuil.html.twig', [
            'sortie' => $sortie,
            'users' => $users,
            'form' => $form->createView(),
        ]);
    }


}
