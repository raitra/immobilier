<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CommuneType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
            return $this->render('commune/index.html.twig', [
                'communes' => $communes 
            ]);
    }

    #[Route('/insertCommune', name:'app_commune_insert', methods:['GET','POST'])]
    public function insert(Request $request): Response 
    {
        $commune = new Commune();
        $form = $this->createForm(CommuneType::class, $commune);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($commune);
            $this->em->flush();

            $this->addFlash('success', 'Commune Ajouter');
            return $this->redirectToRoute('app_commune');
        }else{
            $this->addFlash('error', 'Commune not Add');
            return $this->redirectToRoute('app_commune_insert');
        }
        return $this->render('commune/insertCommune.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function deleteCommune()
    {
        
    }
}
