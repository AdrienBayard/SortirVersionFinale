<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\VilleType;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[IsGranted("ROLE_ADMIN")]
#[Route('/ville')]
class VilleController extends AbstractController
{
    #[Route('/', name: 'ville_index', methods: ['GET'])]
    public function index(VilleRepository $villeRepository): Response
    {
        return $this->render('ville/index.html.twig', [
            'villes' => $villeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'ville_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ville/new.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ville_show', methods: ['GET'])]
    public function show(Ville $ville): Response
    {
        return $this->render('ville/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    #[Route('/{id}/edit', name: 'ville_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VilleType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ville_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ville/edit.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'ville_delete', methods: ['POST'])]
    public function delete(Request $request, Ville $ville, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ville_index', [], Response::HTTP_SEE_OTHER);
    }
}
