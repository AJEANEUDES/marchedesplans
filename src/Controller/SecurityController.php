<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\returnCallback;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SecurityController extends AbstractController
{

    protected $session;
    protected $tokenStorage;
    protected $router;
    protected $maxIdleTime;

    public function __construct(SessionInterface $session, TokenStorageInterface $tokenStorage, RouterInterface $router, $maxIdleTime = 0)
    {
        
    }
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();
            
        $this->addFlash(
            'error',
            'Vous devez vous connecter ou s\'inscrire avant d\'effectuer l\'achat du plan'
        );

        return $this->render('registration/register.html.twig', ['last_username' => $lastUsername, 'error' => $error]);

        
    }
    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    
    public function compteAction(Request $request)
    {
        $maxIdleTime = 20;
        $session = $request->getSession();
        if (time() - $session->getMetadataBag()->getLastUsed() > $maxIdleTime) {
            $session->invalidate();
            $session->clear();
            return $this->redirectToRoute('app_login');
        } else {
            // le contenu de mon action 
        }
    }



}