<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

#[Route('/gestion', name: 'gestion')]
class GestionSortieController extends AbstractController
{
    #[Route('/inscription/{id}', name: '_inscription')]

    public function inscription(
        EntityManagerInterface $entityManager,
        Sortie $sortie,
        Request $request
    ): Response
    {
        $referer=$request->headers->get('referer');

















/*        $sortie = new Sortie();
        $sortie->setSortie_id($this->get);???????????????

            //$this->getUser()->getUserIdentifier());
        $entityManager->persist($sortie);

        $participant = new User();
        $participant->joinSortie($sortie);
        $entityManager->persist($participant);

        $entityManager->flush();*/

        return $this->render('@EasyAdmin/page/login.html.twig');
    }

    private function getRequest()
    {
    }
}
