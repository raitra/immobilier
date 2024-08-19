<?php 

namespace App\Controller;

use App\Entity\Logement;
use App\Repository\LogementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('api')]
class LogementController extends AbstractController{

    public function __construct
    (
        private EntityManagerInterface $em
    )
    {
        
    }

    #[Route('/log', name:'app_logement_list', methods:['GET'])]
    public function index():Response
    {
        $dataLogement = $this->em->getRepository(Logement::class)->findAll();
        return $this->json($dataLogement);
    }

    #[Route('/log-insert', name:'app_logement_insert', methods:['POST'])]
    public function insert(Request $request ):Response
    {
        $data = json_decode($request->getContent(),true);
        $logement = new Logement();
        $logement->setLot($data['lot']);
        $logement->setLoyer($data['loyer']);
        $logement->setSuperficie($data['superficie']);
        $logement->setRue($data['rue']);
        $this->em->persist($logement);       
        $this->em->flush();
        if($logement){
            return $this->json([
                'status' => 202,
                'Logements' => $logement
            ],202);
        }else{
            return $this->json([
                'status' => 404,
                'Error' => 'No data'
            ],404);
        }
    }

    #[Route('/log/{id}', name: 'app_logement_find_by_id', methods:['GET'])]
    public function findLogById(Logement $logement):Response
    {
        return $this->json($logement);
    }

    #[Route('/logUpdate/{id}', name:'app_logement_update', methods:['GET', 'POST'])]
    public function update(Request $request, Logement $logement):Response
    {
        $data = json_decode($request->getContent(),true);
        $logement->setLot($data['lot']);
        $logement->setLoyer($data['loyer']);
        $logement->setSuperficie($data['superficie']);
        $logement->setRue($data['rue']);
        $this->em->persist($logement);       
        $this->em->flush();
        if($logement){
            return $this->json([
                'status' => 202,
                'Logements' => $logement
            ],202);
        }else{
            return $this->json([
                'status' => 404,
                'Error' => 'No data'
            ],404);
        }
    }

    #[Route('/logDelete/{id}', name: 'app_logement_delete', methods:['POST'])]
    public function deleteLog(LogementRepository $logementRepository, Logement $logement):Response
    {
        $logementRepository->remove($logement,true);
        return new Response('Delete Success', Response::HTTP_NO_CONTENT);
    }
}