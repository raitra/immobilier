<?php

namespace App\Controller;

use App\Entity\Individu;
use App\Form\IndividuFormType;
use App\Repository\IndividuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Individucontroller extends AbstractController
{
	public function __construct
    (
        private EntityManagerInterface $em
    )
    {
        
    }

    #[Route('/listIndividu', name:'app_list_individu', methods:['GET'])]
    public function listIndividu():Response
    {
        $individus = $this->em->getRepository(Individu::class)->findAll();
        return $this->render('individu/listIndividu.html.twig', [
            'individus' => $individus
        ]);
    }

    #[Route('/insertIndividu', name:'app_insert_individu', methods:['GET', 'POST'])]
    public function insertIndividu(Request $request):Response
    {
        $individu = new Individu();
        $form = $this->createForm(IndividuFormType::class, $individu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($individu);
            $this->em->flush();
            $this->addFlash('success', 'Individu Ajouter');
            return $this->redirectToRoute('app_list_individu');
        }
        return $this->render('individu/logInsert.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/individuUpdate/{id}', name:'app_individu_update', methods:['GET', 'POST'])]
    public function update(Request $request, Individu $individu):Response
    {
        $form = $this->createForm(IndividuFormType::class, $individu, ['is_edit' => true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($individu);
            $this->em->flush();
            $this->addFlash('success', 'individu Modifier');
            return $this->redirectToRoute('app_list_individu');
        }
        return $this->render('individu/editIndividu.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/individuDelete/{id}', name: 'app_individu_delete', methods:['GET'])]
    public function deleteQuartier(Individu $individu, IndividuRepository $individuRepository):Response
    {
        $individuRepository->remove($individu, true);
        $this->addFlash('success',  'individu Supprimer');
        return $this->redirectToRoute('app_list_individu');
    }

    
}

