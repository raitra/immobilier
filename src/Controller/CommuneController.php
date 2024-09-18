<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CommuneType;
use App\Repository\CommuneRepository;
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

    #[Route('/', name: 'app_commune', methods:['GET'])]
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
         }
        return $this->render('commune/insertCommune.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/{id}/communeDelete', name:'app_commune_delete', methods:['GET','POST'])]
    public function deleteCommune(Commune $commune,CommuneRepository $communeRepository):Response
    {
        //if($this->isCsrfTokenValid('delete_commune'.$commune->getId(), $request->request->get('__token'))){
            $communeRepository->remove($commune, true);
            $this->addFlash('success', 'Commune Supprimer');
        //}
        return $this->redirectToRoute('app_commune');

    }

    #[Route('/{id}/show', name:'app_commune_one', methods:['GET'])]
    public function showOne($id):Response
    {
        $entity = $this->em->getRepository(Commune::class)->findOneBy(['id' => $id]);
        if(!$entity){
            return $this->render('page404.html.twig');
        }else{
            return $this->render('commune/show.html.twig', [
                'commune' => $entity
            ]);       
        }
    }

    #[Route('/{id}/edit', name:'app_commune_edit', methods:['GET', 'POST'])]
    public function editCommune(Request $request, Commune $commune)
    {
        $form = $this->createForm(CommuneType::class, $commune, ['is_edit'=> true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($commune);
            $this->em->flush();

            $this->addFlash('success', 'Commune Modifier');
            return $this->redirectToRoute('app_commune');
        }
        //     $this->addFlash('error', 'Commune not update');
        //     return $this->redirectToRoute('app_commune_edit');
        // }
        return $this->render('commune/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
