<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use App\Form\AnnulerSortieType;
use App\Form\SortiacceuilType;
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
        // Affichage de toutes les sorties
        /*        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortieRepository->findAll(),
        ]);*/


        // Tri de l'affichage des sorties par date et sortie publiée
        /* $sortiePublicated = $sortieRepository->findSortiePublicated("1");

        return $this->render('sortie/index.html.twig',
            ['sorties' => $sortiePublicated
        ]);
        */

        // Tri de l'affichage des sorties par date la plus proche
        $sortieDate = $sortieRepository->triSortieDate();
        return $this->render('sortie/index.html.twig',
            ['sorties' => $sortieDate
            ]);



    }
    #[Route('/indexfiltre', name: 'site_index_filtre', methods: ['GET','POST'])]
    public function indexfiltre(Request $resquest, SortieRepository $sortieRepository): Response
    {
        {// creation du formulaire
            $form = $this->createForm(SortiacceuilType::class);
            // Il n'est pas mappé
            $form->handleRequest($resquest);

            if ($form->isSubmitted() ) {

                // Requête recupére la liste d'event campus
                $sorties = $sortieRepository->filter($this->getUser()->getUserIdentifier(),$this->getUser(), $form->get('nom')->getData(), $form->get('search')->getData(), $form->get('minDate')->getData(), $form->get('maxDate')->getData(), $form->get('organiser')->getData(), $form->get('isAEteInscrit')->getData(), $form->get('isNotAEteInscrit')->getData(), $form->get('archived')->getData());
dump($this->getUser()->getUserIdentifier());
                // On redirige vers la  vue
                return $this->render('sortiacceuil/index.html.twig', ['sorties' => $sorties, 'form' => $form->createView()]);
            }
            return $this->render('sortiacceuil/index.html.twig', ['sorties' => $sortieRepository->displayWithoutFilter($this->getUser()), 'form' => $form->createView()]);
        }
    }
    #[Route('/new', name: 'sortie_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {

        $sortie = new Sortie();
        $lieu= new Lieu();
        $ville= new Ville();
        $site=new Site();
        $etat=new Etat();

        //$organisateur = $userRepository->findOneBy(["pseudo" => $this->getUser()->getUserIdentifier()], []);
        //$idOrganisateur=$organisateur->getId();

        $sortie->setOrganisateur($this->getUser()->getUserIdentifier());

        $sortie->setSite($this->getUser()->getSite());
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $sortie->setDateHeureDebut(new \DateTime());
            $sortie->getDateLimiteInscription(new \DateTime());
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
            //$sortie->setEtat(1);
            $sortie->setEtat($etat);
            //$sortie->setOrganisateur($idOrganisateur);

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
    public function edit(Request $request, Sortie $sortie, EntityManagerInterface $entityManager, UserRepository $userRepository, $id): Response
    {
        // permet de récuperer le pseudo de l'organisateur dans la sortie
        $organisateur = $entityManager->getRepository(Sortie::class)->find($id);
        $organisateurRecupere=$organisateur->getOrganisateur();
        dump($organisateurRecupere);
        $sorties = $sortie->getAEteInscrit();

        // permet de récuperer le pseudo de la personne identifiée sur la page
        $editeur=$this->getUser()->getUserIdentifier();
        dump($editeur);


        if( $editeur == $organisateurRecupere)
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
        else{
            $this->addFlash('warning', "Vous n'êtes pas l'organisateur de cet évènement!");
            return $this->render('sortie/show.html.twig', ['sortie' => $sortie, 'sorties' => $sorties
            ]);
        }
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
    public function add_participant(EntityManagerInterface $entityManager, Request $request, Sortie $sortie,int $id, SortieRepository $sortieRepository, UserRepository $userRepository, User $user){

        // permet de récuperer le pseudo de la personne identifiée sur la page
        $nouvelInscrit=$this->getUser()->getUserIdentifier();
        dump($nouvelInscrit);
      
//zakfinal
        //gestion de l'inscription et désinscription
        if ($sortie->getAEteInscrit()->contains($this->getUser())){
            $sorties = $sortie->getAEteInscrit();
            $this->addFlash('warning', "Vous êtes déjà inscrit à cet évènement!");
            return $this->render('sortie/show.html.twig', ['sortie' => $sortie, 'sorties' => $sorties
            ]);
        }else if ($sortie->getAEteInscrit()->count()>=$sortie->getNbInscriptionMax()) {
            $sorties = $sortie->getAEteInscrit();
            $this->addFlash('warning', "le nombre de paticipant maximum est depassé");
            return $this->render('sortie/show.html.twig', ['sortie' => $sortie, 'sorties' => $sorties
            ]);
        } else
        {


            $sortie = $em->getRepository(Sortie::class)->find($id);
/*main

        // permet de récuperer le pseudo de l'organisateur dans la sortie

       // $inscrit = $entityManager->getRepository(Sortie::class)->find($id);
        //$inscritRecupere=$inscrit->getOrganisateur();
        //dump($inscritRecupere);


        //$sorties = $sortie->getAEteInscrit();


        if($inscritRecupere != $nouvelInscrit){

            $sortie = $entityManager->getRepository(Sortie::class)->find($id);
*/
            $sortie->addAEteInscrit($this->getUser());
            $compteurInscrit=$sortie->getCompteur();
            $compteurInscrit= $compteurInscrit+1;
            $sortie->setCompteur($compteurInscrit);

            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'L\'inscription a été faite !');

/*main
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Tu es maintenant inscrit !');

            // return $this->render('sortie/show.html.twig',['id' => $id, 'sortie' => $sortieRepository->find($id)]);
            return $this->redirectToRoute('sortie_show', ['id'=>$sortie->getId()]);

}
        else{
            $this->addFlash('warning', "Vous êtes déjà inscrit à cet évènement!");
            return $this->render('sortie/show.html.twig', ['sortie' => $sortie, 'sorties' => $sorties
            ]);
        }
*/

            // return $this->render('sortie/show.html.twig',['id' => $id, 'sortie' => $sortieRepository->find($id)]);
            return $this->redirectToRoute('sortie_show', ['id' => $sortie->getId()]);
        }


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
