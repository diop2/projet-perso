<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Versement;

use App\Entity\Entreprise;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Controller\UserController;


class UserController extends AbstractController
{
   

    /**
     * @Route("/register", name="register", methods={"POST"})
     * 
     */
    public function register( Request $request, UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager, SerializerInterface $serializer,
            ValidatorInterface $validator)
    {

        $values = json_decode($request->getContent());
        if(isset($values->email , $values->password)) {

           
            $user = new User();
            
            $user->setEmail($values->email);
            if ($values->roles == 1) {
                $user->setRoles(["ROLE_ADMIN_COMPTE"]);
                $utilisateur=("AMS");
            }
            elseif ($values->roles == 2) {
                $user->setRoles(["ROLE_ADMIN_CAISSIER"]);
                $utilisateur=("CAS");
            }
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setNomComplet($values->nomComplet);
            $user->setAdresse($values->adresse);
            $user->setNci($values->nci);
            $user->setTel($values->tel);
            $user->setIsActive($values->isActive);

            $entreprise = new Entreprise();

            $entreprise->setNom($values->nom);
            $entreprise->setAdresse($values->adresse);
            $entreprise->setTel($values->tel);
            $entreprise->setNci($values->nci);
            $entreprise->setNomComplet($values->nomComplet);
            $entreprise->setLINEA($values->linea);                                                    
            $entreprise->setRaisonSocial($values->raisonsocial);
            $entreprise->setSolde($values->solde);
            $entreprise->setIsActive($values->isActive);
            
            
            $jour=date('d');
            $mois=date('m');
            $annee=date('Y');
            $heur=date('H');
            $minute=date('i');
            $seconde=date('s');
            $num=$utilisateur.$annee.$mois.$jour.$heur.$minute.$seconde;

            $entreprise->setNumeroCompte($num);

            $user->setEntreprise($entreprise);  
            
            $versement = new Versement;
           
            $versement->setNumeroCompte($num);
            $versement->setSolde($values->solde);
            $versement->setDateversement(new \DateTime('now'));

            $versement->setEntreprise($entreprise);
            $versement->setCaissier($user);
            $versement->setVersementUser($user);
           
            $errors = $validator->validate($user);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }

            $errors = $validator->validate($entreprise);
            if(count($errors)) {
                $errors = $serializer->serialize($errors, 'json');
                return new Response($errors, 500, [
                    'Content-Type' => 'application/json'
                ]);
            }
            
            $entityManager->persist($user);
            $entityManager->persist($entreprise);
            $entityManager->persist($versement);                                                                             
            $entityManager->flush();

            $data = [
                'status' => 201,
                'message' => 'nouveau partenaire creer'
            ];

            return new JsonResponse($data, 201);
        }
        $data = [
            'status' => 500,
            'message' => 'Vous devez renseigner les clés username et password'
        ];
        return new JsonResponse($data, 500);
    }

   
}