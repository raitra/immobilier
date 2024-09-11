<?php 

namespace App\Controller;

use App\Entity\Quartier;
use App\Form\QuartierFormType;
use App\Repository\QuartierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuartierController extends AbstractController{
    
    public function __construct(private EntityManagerInterface $em)
    {
        
    }

    #[Route('/listQuartier', name:'app_list_quartier', methods:['GET'])]
    public function list():Response
    {
        $quartiers = $this->em->getRepository(Quartier::class)->findAll();
        return $this->render('quartier/list.html.twig', [
            'quartiers' => $quartiers
        ]);
    }

    #[Route('/insertQuartier', name:'app_insert_quartier', methods:['GET', 'POST'])]
    public function insertQuartier(Request $request):Response
    {
        $quartier = new Quartier();
        $form = $this->createForm(QuartierFormType::class,$quartier);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($quartier);
            $this->em->flush();
            $this->addFlash('success', 'Quartier Ajouter');
            return $this->redirectToRoute('app_list_quartier');
        }
        return $this->render('quartier/insertQuartier.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/showQuariter/{id}', name:'app_show_quartier', methods:['GET'])]
    public function showQuartier($id):Response
    {
        $quartier = $this->em->getRepository(Quartier::class)->findOneBy(['id' => $id]);
        if(!$quartier){
            return $this->render('page404.html.twig');
        }

        return $quartier;
    }

    #[Route('deleteQuartier/{id}', name:'app_delete_quartier', methods:['GET'])]
    public function deleteQuartier(Quartier $quartier, QuartierRepository $quartierRepository):Response
    {
        $quartierRepository->remove($quartier, true);
        $this->addFlash('success', 'Commune Supprimer');
        return $this->redirectToRoute('app_list_quartier');
    }

    #[Route('editQuartier/{id}', name:'app_edit_quartier', methods:['GET','POST'])]
    public function editQuartier(Request $request, Quartier $quartier):Response
    {
        $form = $this->createForm(QuartierFormType::class, $quartier, ['is_edit' => true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($quartier);
            $this->em->flush();
            $this->addFlash('success', 'Quartier Modifier');
            return $this->redirectToRoute('app_list_quartier');
        }
        return $this->render('quartier/editQuartier.html.twig',[
            'form' => $form
        ]);
    }


}