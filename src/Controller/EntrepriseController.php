<?php

namespace App\Controller;
use App\Entity\Entreprise;
use App\Controller\EntrepriseController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function register( Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());

            $entreprise = new Entreprise();

            $entreprise->setNom($values->nom);
            $entreprise->setLINEA($values->linea);                                                                                                                                                                                                                      
            $entreprise->setRaisonSocial($values->raisonsocial);
            $entreprise->setSolde($values->solde);
            $errors = $validator->validate($entreprise);


           
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'Donnes Entreprise ajouter'
            ];

            return new JsonResponse($data, 201);
        
        $data = [
            'status' => 500,
            'message' => 'utilisateur non ajouter'
        ];
        return new JsonResponse($data, 500);
    }
}
