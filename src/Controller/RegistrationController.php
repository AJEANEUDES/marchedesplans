<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistrationFormType;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Service\SendMailService;
use App\Service\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, UsersAuthenticator $authenticator, \Swift_Mailer $mailer, UsersRepository $usersRepository,
        SendMailService $mail, SessionInterface $session, JWTService $jwt): Response
    {

        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);




        if ($request->isMethod('POST')) 
        
        {
            $user->setPseudo($request->get('pseudo'));
            $user->setPrenoms($request->get('prenoms'));
            $user->setEmail($request->get('email'));


            $user->setPassword($passwordEncoder->encodePassword($user, $request->get('password')),);


            $objet_user = $usersRepository->findOneBy(['id' => $request->get('userlist')]);
            $user->setRoles($request->get('userlist'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            //On génère le JWT de l'utilisateur
            //création de header

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //création du payload

            $payload = [
                'user_id' =>$user->getId()
            ];

            //génération du token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // dd($token);

            //envoi de mail après l'inscription
            $mail->send(
                'no-reply@marchedesplans.com',
                $user->getEmail(),
                'Activation de votre compte sur le site Marché des Plans',
                'register',
               compact('user', 'token')
            );


            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );


            $this->addFlash(
                'success',
                'Veuillez activer votre compte en consultant votre email'
            );
            return $this->redirectToRoute('app_register');

        }

        return $this->render('registration/inscription.html.twig');

    }

       /**
     * @route("/verif/{token}",name="verify_user")
     *
     * 
     */
    public function VerifyUser($token, JWTService $jwt, UsersRepository $usersRepository, SessionInterface $session, EntityManagerInterface $em): Response
    {

        // dd($jwt->isValid($token));
        // dd($jwt->getPayload($token));
        // dd($jwt->check($token, $this->getParameter('app.jwtsecret')));

        //on vérifie si le token est valide, n'a pas expiré et n'a pas été modifié

        if($jwt->isValid($token) && !$jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret')))
        {
            //récupération du payload
            $payload = $jwt->getPayload($token);

            //récupération du token de l'utilisateur
            $user = $usersRepository->find($payload['user_id']);


            //vérification de l'existence de l'user et qu'il n'a pas encore activé son compte

            if($user && !$user->getIsverified()){
                $user->setIsVerified(true);
                $em->flush($user);
                
                // $session->getFlashBag()->add('success', 'Utilisateur activé');
                
                $this->addFlash(
                       'success',
                       'Utilisateur activé'
                    );
               
                 //redirection vers la page d'acueil du profil
                 return $this->redirectToRoute('index');
         
            }

        }

        //Un problème se pose dans le token
        // $session->getFlashBag()->add('success', 'Le token est invalide ou a expiré');
        
        $this->addFlash(
           'danger',
           'Le token est invalide ou a expiré'
        );

        return $this->redirectToRoute('app_login');

    }


     /**
     * @route("/renvoiverif",name="resend_verif")
     *
     */
    public function resendVerif(JWTService $jwt, SessionInterface $session, SendMailService $mail, UsersRepository $usersRepository): Response
    {
        //on vérifie l'user connecté
        $user = $this->getUser();

        if(!$user){

        // $session->getFlashBag()->add('danger', 'Vous devez être connecté pour accéder à cette page');

            $this->addFlash(
               'danger',
               'Vous devez être connecté pour accéder à cette page',
            );

            return $this->redirectToRoute('app_login');
        }

        if($user->getIsVerified())
        {

                
            $this->addFlash(
                'warning',
                'Cet Utilisateur est déjà activé',
             );
 
             return $this->redirectToRoute('index');   
        }


         //On génère le JWT de l'utilisateur
        //création de header

            $header = [
                'typ' => 'JWT',
                'alg' => 'HS256'
            ];

            //création du payload

            $payload = [
                'user_id' =>$user->getId()
            ];

            //génération du token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // dd($token);

            //envoi de mail après l'inscription
            $mail->send(
                'no-reply@marchedesplans.com',
                $user->getEmail(),
                'Activation de votre compte sur le site Marché des Plans',
                'register',
               compact('user', 'token')
            );


            // $session->getFlashBag()->add('success', 'Email de vérification envoyé');

            $this->addFlash(
                'success',
                'Email de vérification envoyé',
             );
 
             return $this->redirectToRoute('index');   

    }


    // /**
    //  * @route("/activation/{token}",name="activation")
    //  *
    //  * @return void
    //  */
    // public function activation($token, UsersRepository $usersRepo)
    // {
    //     // on verifie si un utilisateur a ce token 

    //     $user = $usersRepo->findOneBy(['activation_token' => $token]);

    //     // on verifie si aucun utilisateur nexiste ave ce token

    //     if (!$user) {
    //         // erreur 404
    //         throw $this->createNotFoundException('ce user n\'existe pas ');
    //     }

    //     // on supprime le token 
    //     $user->setActivationToken(null);
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $entityManager->persist($user);
    //     $entityManager->flush();

    //     // on envoie un flash user 
    //     $this->addFlash('user', 'votre compte a eté bien activé');

    //     //on retourne à l'aceuil 

    //     return $this->redirectToRoute('index');
    // }


    
}