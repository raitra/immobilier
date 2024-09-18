<?php 

namespace App\Controller;

use App\Entity\Logement;
use App\Form\LogementType;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogementController extends AbstractController{

    public function __construct
    (
        private EntityManagerInterface $em
    )
    {
        
    }

    #[Route('/logement', name:'app_logement_list', methods:['GET'])]
    public function index():Response
    {
        $logements = $this->em->getRepository(Logement::class)->findAll();
        return $this->render('logement/index.html.twig',[
            'logements' => $logements,
        ]);
    }

    #[Route('/insertLogement', name:'app_insert_logement', methods:['GET', 'POST'])]
    public function insertLog(Request $request):Response
    {
        $logement = new Logement();
        $form = $this->createForm(LogementType::class, $logement);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($logement);
            $this->em->flush();
            $this->addFlash('success', 'Logement Ajouter');
            return $this->redirectToRoute('app_logement_list');
        }
        return $this->render('logement/logInsert.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/log/{id}', name: 'app_logement_find_by_id', methods:['GET'])]
    public function findLogById(Logement $logement):Response
    {
        return $this->json($logement);
    }

    #[Route('/logUpdate/{id}', name:'app_logement_update', methods:['GET', 'POST'])]
    public function update(Request $request, Logement $logement):Response
    {
        $form = $this->createForm(LogementType::class, $logement, ['is_edit' => true]);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($logement);
            $this->em->flush();
            $this->addFlash('success', 'logement Modifier');
            return $this->redirectToRoute('app_list_logement');
        }
        return $this->render('logement/editLogement.html.twig',[
            'form' => $form
        ]);
    }



    #[Route('/logDelete/{id}', name: 'app_logement_delete', methods:['GET'])]
    public function deleteQuartier(Logement $logement, LogementRepository $logementRepository):Response
    {
        $logementRepository->remove($logement, true);
        $this->addFlash('success',  'Logement Supprimer');
        return $this->redirectToRoute('app_logement_list');
    }
}