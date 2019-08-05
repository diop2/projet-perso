<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Versement;
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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class EntrepriseController extends AbstractController
{
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function partenaire( Request $request,EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordEncoderInterface $passwordEncoder)
    {
       
        $values = json_decode($request->getContent());
        if(isset($values->email , $values->password)) {

           
            $user = new User();
            
            $user->setEmail(trim($values->email));
            if ($values->roles == 1) {
                $user->setRoles(["ROLE_ADMIN"]);
                $utilisateur=("AD");
            }
            elseif ($values->roles == 2) {
                $user->setRoles(["ROLE_UTILISATEUR"]);
                $utilisateur=("UT");
            }
            $user->setPassword($passwordEncoder->encodePassword($user, $values->password));
            $user->setNomComplet(trim($values->nomComplet));
            $user->setAdresse(trim($values->adresse));
            $user->setNci(trim($values->nci));
            $user->setTel(trim($values->tel));
            $user->setIsActive(trim($values->isActive));

            $entreprise = new Entreprise();

            $entreprise->setNom(trim($values->nom));
            $entreprise->setAdresse(trim($values->adresse));
            $entreprise->setTel(trim($values->tel));
            $entreprise->setNci(trim($values->nci));
            $entreprise->setNomComplet(trim($values->nomComplet));
            $entreprise->setLINEA(trim($values->linea));                                                    
            $entreprise->setRaisonSocial(trim($values->raisonsocial));
            $entreprise->setSolde(trim($values->solde));
            $entreprise->setIsActive(trim($values->isActive));
            
            
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
