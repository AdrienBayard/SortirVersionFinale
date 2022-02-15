<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\AnnulerSortieType;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie')]
class SortieController extends AbstractController
{
    #[Route('/', name: 'sortie_index', methods: ['GET'])]
    public function index(SortieRepository $sortieRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {

        $sortie = new Sortie();
        $lieu= new Lieu();
        $ville= new Ville();
        $site=new Site();
        $etat=new Etat();

        $organisateur = $userRepository->findOneBy(["pseudo" => $this->getUser()->getUserIdentifier()], []);
        $idOrganisateur=$organisateur->getId();

        $sortie->setSite($this->getUser()->getSite());
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setDateHeureDebut(new \DateTime());
            $sortie->getDateLimiteInscription(new \DateTime());
            $sortie->setOrganisateur(1);
            $lieuRecupere = $request->request->get("lieu", "");
            $lieu->setNom($lieuRecupere);
            $lieuRecupere = $request->request->get("rue", "");
            $lieu->setRue($lieuRecupere);
            $lieuRecupere = $request->request->get("latitude", "");
            $lieu->setLatitude((float)$lieuRecupere);
            $lieuRecupere = $request->request->get("longitude", "");
            $lieu->setLongitude((float)$lieuRecupere);
            $sortie->setLieu($lieu);
            $lieu->setVille($ville);
            //$sortie->setSite($site);
            $sortie->setEtat($etat);
            $sortie->setOrganisateur($idOrganisateur);

           // $site->setNom("nantes");
           // $ville->setCodePostal("44000");
            $etat->setLibelle("Créée");
            $villeRecupere= $request->request->get("ville", "Nantes");
            $ville->setNom($villeRecupere);
            $villeRecupere= $request->request->get("cp", "44000");
            $ville->setCodePostal($villeRecupere);

            $entityManager-> persist($ville);
            $entityManager->persist($lieu);
            $entityManager->persist($sortie);
            //$entityManager->persist($site);
            //$entityManager->persist($etat);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/new.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'sortie_show', methods: ['GET'])]
    public function show(Sortie $sortie): Response
    {

       $sorties = $sortie->getAEteInscrit();

        dump($sorties);

/*        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);*/

        return $this->render('sortie/show.html.twig', [
            'sortie' => $sortie, 'sorties' => $sorties
        ]);
    }

    #[Route('/{id}/edit', name: 'sortie_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/edit.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'sortie_delete', methods: ['POST'])]
    public function delete(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sortie->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sortie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/inscription/{id}', name: 'sortie_inscription')]
    public function add_participant(EntityManagerInterface $em, Request $request, Sortie $sortie,int $id, SortieRepository $sortieRepository){



        $sortie = $em->getRepository(Sortie::class)->find($id);
        $sortie->addAEteInscrit($this->getUser());



        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'L\'inscription a été faite !');

       // return $this->render('sortie/show.html.twig',['id' => $id, 'sortie' => $sortieRepository->find($id)]);
        return $this->redirectToRoute('sortie_show', ['id'=>$sortie->getId()]);



    }

    #[Route('/desister/{id}', name: 'sortie_desister')]
    public function desister_participant(EntityManagerInterface $em, Request $request, Sortie $sortie,int $id, SortieRepository $sortieRepository){

        $sortie = $em->getRepository(Sortie::class)->find($id);
        $sortie->removeAEteInscrit($this->getUser());

        $em->persist($sortie);
        $em->flush();
        $this->addFlash('success', 'Tu as été désinscrit !');
        //return $this->render('sortie/show.html.twig',['id' => $id, 'sortie' => $sortieRepository->find($id)]);
        return $this->redirectToRoute('sortie_show', ['id'=>$sortie->getId()]);

    }

    #[Route('/{id}/annuler', name:'sortie_annuler', methods:['GET', 'POST'] )]
    public function annuler(Request $request, Sortie $sortie, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(AnnulerSortieType::class, $sortie);
        $form->handleRequest($request);
        $etat=new Etat();

        if ($form->isSubmitted() && $form->isValid()) {
          $sortieRecuperee = $request->request->get("motif", "");
            $sortie->setMotif($sortieRecuperee);
           $etat->setLibelle("Annulée");
            $sortie->setEtat($etat);
            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('sortie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('sortie/annuler.html.twig', [
            'sortie' => $sortie,
            'form' => $form,
        ]);
    }


}
