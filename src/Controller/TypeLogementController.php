<?php

namespace App\Controller;

use App\Entity\TypeLogement;
use App\Form\TypeLogementFormType;
use App\Repository\TypeLogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TypeLogementController extends AbstractController
{
    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    #[Route('/listType', name:'app_list_type_logement', methods:['GET'])]
    public function listType():Response
    {
        $typeLogements = $this->em->getRepository(TypeLogement::class)->findAll(); 
        return $this->render('type/listType.html.twig',[
            'types' => $typeLogements
        ]);
    }

    #[Route('/insertTypelogement', name:'app_insert_type_logement', methods:['GET', 'POST'])]
    public function insertTypeLog(Request $request):Response
    {
        $types = new TypeLogement();
        $form = $this->createForm(TypeLogementFormType::class, $types);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($types);
            $this->em->flush();
            $this->addFlash('success', 'Type logement Ajouter');
            return $this->redirectToRoute('app_list_type_logement');
        }
        return $this->render('type/insertTypelogement.html.twig',[
            'form' => $form
        ]);

        return $this->render('type/insertType',[]);
    }

    #[Route('deleteType/{id}', name:'app_delete_type_logement', methods:['GET'])]
    public function deleteQuartier(TypeLogement $typeLogement, TypeLogementRepository $typeLogementRepository):Response
    {
        $typeLogementRepository->remove($typeLogement, true);
        $this->addFlash('success', 'Type logement Supprimer');
        return $this->redirectToRoute('app_list_type_logement');
    }

    #[Route('editTypeLogement/{id}', name:'app_edit_type_logement', methods:['GET','POST'])]
    public function editQuartier(Request $request, TypeLogement $typeLogement):Response
    {
        $form = $this->createForm(TypeLogementFormType::class, $typeLogement, ['is_edit' => true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($typeLogement);
            $this->em->flush();
            $this->addFlash('success', 'Type de logement Modifier');
            return $this->redirectToRoute('app_list_type_logement');
        }
        return $this->render('quartier/editQuartier.html.twig',[
            'form' => $form
        ]);
    }    
}