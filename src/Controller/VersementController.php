<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Versement;
use App\Entity\Entreprise;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\DateTime;

class VersementController extends AbstractController
{
    /**
     * @Route("/versement", name="versement",  methods={"GET"})
     */
    public function versement ( Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $values = json_decode($request->getContent());

        if (isset($values->NumeroCompte,  $values->caissier_id,  $values->solde)) {
                if($values->solde>75000 &&  $values->NumeroCompte!=''  /* && $values->entreprise!='' */ ) {
                    
                    $versement = new Versement();
                    
                    $versement->setNumeroCompte(trim($values->NumeroCompte));
                    $versement->setSolde(trim($values->solde));
                    $versement->setDateversement(new \DateTime('now'));

                    
                    $identreprise=$this->getDoctrine()->getRepository(Entreprise::class)->find($values->entreprise_id);
                    $versement->setEntreprise($identreprise);
                    $identreprise->setSolde($identreprise->getSolde()+$values->solde);

                    $caissier=$this->getDoctrine()->getRepository(USer::class)->find($values->caissier_id);
                    $versement->setCaissier($caissier);
                    $caissier->setSolde($caissier->getSolde()+$values->solde);

                    $entityManager->persist($versement);
                    $entityManager->flush();
                    
                    $errors = $validator->validate($versement);
                    if(count($errors)) {
                        $errors = $serializer->serialize($errors, 'json');
                        return new Response($errors, 500, [
                            'Content-Type' => 'application/json'
                        ]);
                    }
                    

                    $data = [
                        'status' => 200,
                        'message' => 'Vesement effectuer'
                    ];

                    return new JsonResponse($data, 200);
                }
                        else {
                            $data = [
                                'status' => 201,
                                'message' => 'Versement inferieur a 75000'
                            ];
        
                            return new JsonResponse($data, 201);
                        }
                       
        }
        $data = [
           
            'message' => 'numero compte n\'existe pas'
        ];
        return new JsonResponse($data, 500);
    }
}
