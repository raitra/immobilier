<?php

namespace App\Controller;

use App\Entity\Commune;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class CommuneController extends AbstractController
{

    public function __construct
    (
        private EntityManagerInterface $em
    )
    {
        
    }

    #[Route('/commune', name: 'app_commune', methods:['GET'])]
    public function index(): Response
    {
        $communes = $this->em->getRepository(Commune::class)->findAll();
        if(count($communes)>0){

            return $this->json([
                'status' => 200,
                'communes' => $communes
            ],200);
        }else{
            return $this->json([
                'status' => 400,
                'communes' => 'Il n\'y a pas aucune communes'
            ],200);
        }
    }
}
