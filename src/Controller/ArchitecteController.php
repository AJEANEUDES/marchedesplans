<?php

namespace App\Controller;


use App\Entity\Achat;
use App\Entity\Plans;
use App\Entity\Users;
use App\Form\EditUserType;
use App\Repository\AchatRepository;
use App\Repository\PlansRepository;
use App\Repository\UsersRepository;
use App\Repository\WithdrawalRepository;
use App\Repository\MiniplansRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Security\UsersAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;


/**
     * @Route("/", name="architecte_")
     */

class ArchitecteController extends AbstractController
{
     /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dash( SessionInterface $session,WithdrawalRepository $withdrawalRepository,AchatRepository $achatRepository,UsersRepository $usersRepository,MiniplansRepository $miniplansRepository,PlansRepository $plansRepository): Response
    {
        // dd($session);

        // $user = $usersRepository->findOneBy(['id' => $this->getUser()->getId()]);
        $user = $usersRepository->findAll();

        // dd($user);

        if($this->getUser() && $this->getUser()->getRoles()==['ROLE_USER'])
        {
           return $this->redirectToRoute('index');    
    }
    elseif ($this->getUser() && ($this->getUser()->getRoles()==['ROLE_ARCHITECTE']||['ROLE_ADMIN']||['ROLE_SUPER_ADMIN'])) 
    {

        $total_des_plan=$miniplansRepository->findAll();
        $total_des_utilisateur= $usersRepository->findAll();
        $vente= 0;
        $mes_plan=$miniplansRepository->findBy(['user'=>$this->getUser()]);
        $mes_maison=$plansRepository->findBy(['user'=>$this->getUser()]);
        $mes_achat= $achatRepository->findBy(['users'=>$this->getUser()]);
        $total_des_achats = $achatRepository->findBy(['etat' => 'Confirmer', 'retrait' => false,'demande'=>null]);
        $prix_total = 0.;

        

        foreach ($total_des_plan as $plan) {
            $vente+=$plan->getVente();
           

        }

        foreach ($total_des_achats as $achat) {

        $prix_total += $achat->getPrix();
            
        }

        
        

        // dd($vente,$mes_plan,$mes_maison,$mes_achat,$prix_total);
        return $this->render(
            'Partie_admin/index.html.twig'
            ,
            [
                'users' =>$user ,
                'lesplan'=>$total_des_plan,
                'mesplan'=>$mes_maison,
                'lesutlisateur'=>$total_des_utilisateur,
                'nbredevente'=>$vente,
                'fondtotal'=>$prix_total,

            ]
        );    }
    
    else
   return $this->redirectToRoute('app_login');
    }


    
/**
 * @Route("/edit/my/profil/{id}", name="edit_my_profil")
 */
public function editmyprofil(Users $users ,UsersRepository $usersRepository, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator,  UserPasswordEncoderInterface $passwordEncoder,Request $request): Response
{  $user = $this->getUser();


    $form = $this->createForm(EditUserType::class ,$users);
    $form->handleRequest($request);
    

    if ($request->isMethod('POST')) {
        $user->setPseudo($request->get('pseudo'));
        $user->setPrenoms($request->get('prenoms'));
        $user->setEmail($request->get('email'));
        $user->setAdresse($request->get('adresse'));
        $user->setBanque($request->get('banque'));
        $user->setTel($request->get('tel'));


        $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password')),);


       
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        



        return $guardHandler->authenticateUserAndHandleSuccess(
            $user,
            $request,
            $authenticator,
            'main' // firewall name in security.yaml
        );
    }


   
    return $this ->render('Partie_admin/editprofilarchitecte.html.twig',[
        'user' => $user,
    
    
       ]); 
      

    
}


  /**
     * @Route("/terme", name="terme")
     */
    public function terme(): Response
    {

        return $this->render('Partie_admin/terme.html.twig', []);
    }



}
