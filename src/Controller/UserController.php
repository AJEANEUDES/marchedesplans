<?php

namespace App\Controller;

use mysqli;
use Stripe\Stripe;
use App\Entity\Achat;
use App\Entity\Plans;
use App\Entity\Users;
use App\Entity\Images;
use App\Entity\Fichiers;
use App\Data\SearchData;
use App\Entity\Miniplans;
use App\Form\SearchPlanType;
use App\Controller\Consulter;
use App\Entity\Consulter as EntityConsulter;
use Symfony\Component\HttpFoundation\Session\Session;
use App\Repository\TypeRepository;
use App\Repository\SuperficieRepository;
// use App\Repository\FormulairesRepository;
// use App\Entity\Formulaires;
// use App\Form\FormulairesType;
use App\Repository\FichiersRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use App\Form\ContactType;
use App\Form\EditUserType;
use App\Security\UsersAuthenticator;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Repository\AchatRepository;
use App\Repository\ConsulterRepository;
use App\Repository\PlansRepository;
use App\Repository\UsersRepository;
use App\Repository\ImagesRepository;
use App\Repository\MiniplansRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\SqlFormatter\Token;
use phpDocumentor\Reflection\Types\Float_;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Request;
use function PHPUnit\Framework\returnCallback;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Mime\Email;

/**
 * @Route("/")
 */
class UserController extends AbstractController
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        // $this->passwordEncoder = $passwordEncoder;
        $this->client = $client;
    }

    /**
     * @Route("alpha/{id}", name="alpha")
     */
    public function alpha(
        MiniplansRepository $miniplansRepository,
        $id
    ): Response {
        $mp = $miniplansRepository->findOneBy(['id' => $id]);

        return $this->render('Partie_admin/Shared/aside.html.twig', [
            'miniplan' => $mp,
        ]);
    }

    // Liste des plans

    /**
     * @Route("/plans/liste/miniplans/{id}", name="liste_des_mini_plan")
     */
    public function listdesminiplan(
        PlansRepository $plansRepository,
        Request $request,
        $id
    ): Response {
        $plan = $plansRepository->findOneBy(['id' => $id]);

        // $plan = $plansRepository->findOneBy(['id' => $request->get('id_plan')])->getMiniplans();


          //le code pour les liens 
        
          if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = 'https';
        } else {
            $url = 'http';
        }

        // Ajoutez // à l'URL.
        $url .= '://';

        // Ajoutez l'hôte (nom de domaine, ip) à l'URL.
        $url .= $_SERVER['HTTP_HOST'];

        // Ajouter l'emplacement de la ressource demandée à l'URL
        $url .= $_SERVER['REQUEST_URI'];

        // Afficher l'URL

        $session = new Session();

        // set and get session attributes
        $session->set('url', $url);

        // dd($session->get('url'));

        return $this->render('Partie_users/listedesminiplan.html.twig', [
            'plan' => $plan,
        ]);
    }

    /**
     * @Route("/", name="admin_index")
     */
    public function indexadmin(
        Request $request,
        MiniplansRepository $miniplansRepository,
        UsersRepository $usersRepository,
        AchatRepository $achatRepository,
        PlansRepository $plansRepository,
        TypeRepository $typeRepository,
        SuperficieRepository $superficieRepository,
        ConsulterRepository $consulterRepository
    ): Response {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchPlanType::class, $data);
        $form->handleRequest($request);
        // dd($data);
        $plans = $plansRepository->findsearchplan($data);
        $user = $this->getUser();
        // $architecte = $this->getUser()->getRoles(['ROLE_ARCHITECTE']);
        $plans = $plansRepository->findBy([], ['id' => 'DESC'], 4, 0);
        $types = $typeRepository->findAll();
        $superficies = $superficieRepository->findAll();
        $total_des_plan = $miniplansRepository->findAll();
        $prix_total = 0.;
        $vente = 0;
        $total_des_achats = $achatRepository->findBy([
            'etat' => 'Confirmer',
            'retrait' => false,
            'demande' => null,
        ]);
        
        $total_des_utilisateur = $usersRepository->findAll();
        $consulter = $consulterRepository->consulter();

        $plans_les_plus_achater = $achatRepository->plans_les_plus_achater();

        foreach ($total_des_plan as $plan) {
            $vente += $plan->getVente();
        }

        foreach ($total_des_achats as $achat) {
            $prix_total += $achat->getPrix();
        }

        // dd($consulter);

        if (
            ($user &&
                ($this->getUser()->getRoles() == ['ROLE_ADMIN'] || [
                        'ROLE_ARCHITECTE',
                    ] || ['ROLE_SUPER_ADMIN'])) || ['ROLE_USER']
        ) {
            return $this->render('Partie_users/index.html.twig', [
                'lesplan' => $total_des_plan,
                'users' => $user,
                'plans' => $plans,
                'plans_les_plus_achater' => $plans_les_plus_achater,
                'consulter' => $consulter,
                'types' => $types,
                'nbredevente' => $vente,
                'fondtotal' => $prix_total,
                'superficies' => $superficies,
                'form' => $form->createView(),
            ]);
        } else {
            return $this->render('Partie_users/index.html.twig', [
                'lesplan' => $total_des_plan,
                'users' => $user,
                'plans_les_plus_achater' => $plans_les_plus_achater,
                'plans' => $plans,
                'lesutlisateur' => $total_des_utilisateur,
                'consulter' => $consulter,
                'types' => $types,
                'nbredevente' => $vente,
                'fondtotal' => $prix_total,
                'superficies' => $superficies,
                'form' => $form->createView(),
            ]);
        }
    }

    /**
     * @Route("/", name="index")
     */
    public function index(
        Request $request,
        PlansRepository $plansRepository,
        SuperficieRepository $superficieRepository,
        TypeRepository $typeRepository
    ): Response {
        $user = $this->getUser();
        // $this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN');

        if ($user && $this->getUser()->getRoles() == ['ROLE_USER']) {
            $plans = $plansRepository->findBy([], ['id' => 'DESC'], 4, 0);

            $types = $typeRepository->findAll();
            $superficies = $superficieRepository->findAll();
            return $this->render('Partie_users/index.html.twig', [
                'plans' => $plans,
                'types' => $types,
                'superficies' => $superficies,
            ]);
        } elseif (
            $user &&
            ($this->getUser()->getRoles() == ['ROLE_ADMIN'] || [
                'ROLE_SUPER_ADMIN',
            ])
        ) {
            return $this->redirectToRoute('admin_dashboard');
        } elseif (
            $user &&
            $this->getUser()->getRoles() == ['ROLE_ARCHITECTE']
        ) {
            return $this->redirectToRoute('architecte_dashboard');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/contact", name="contact", methods={"GET","POST"})
     */
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        // $formulaire = new Formulaires();
        $form = $this->createForm(ContactType::class);
        $contact = $form->handleRequest($request);
        // $submittedToken = $request->request->get('token');
        // dd($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if ($this->isCsrfTokenValid('form-contacts', $submittedToken)) {}

            $email = (new TemplatedEmail())
                ->from($contact->get('email')->getData())
                ->to('marchedesplans@gmail.com')
                ->subject('CONTACT DEPUIS LE SITE MARCHE DES PLANS')
                ->htmlTemplate('emails/contact.html.twig')
                ->context([
                    'nom' => $contact->get('nom')->getData(),
                    'mail' => $contact->get('email')->getData(),
                    'message' => $contact->get('message')->getData(),
                ]);

            $mailer->send($email);
            // $entityManager = $this->getDoctrine()->getManager();
            // $formulaire->setStatus('Nouveau');
            // $formulaire->setIp($_SERVER['REMOTE_ADDR']);
            // $entityManager->persist($formulaire);
            // $entityManager->flush();
            $this->addFlash(
                'success',
                'Votre Message  a été envoyé avec succès'
            );

            return $this->redirectToRoute('contact');
        }
        return $this->render('Partie_users/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/a/propos/de/nous", name="about_us")
     */
    public function about_us(
        PlansRepository $plansRepository,
        SuperficieRepository $superficieRepository,
        TypeRepository $typeRepository
    ): Response {
        $user = $this->getUser();
        $plans = $plansRepository->findBy([], ['id' => 'DESC'], 4, 0);

        $types = $typeRepository->findAll();

        $superficies = $superficieRepository->findAll();

        if (
            $user &&
            ($this->getUser()->getRoles() == ['ROLE_ADMIN'] || [
                    'ROLE_ARCHITECTE',
                ] || ['ROLE_SUPER_ADMIN'])
        ) {
            return $this->render('Partie_users/about_us.html.twig', [
                'plans' => $plans,
                'types' => $types,
                'superficies' => $superficies,
            ]);
        }
        return $this->render('Partie_users/about_us.html.twig', [
            'plans' => $plans,
            'types' => $types,
            'superficies' => $superficies,
        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */
    public function faq(): Response
    {
        return $this->render('Partie_users/faq.html.twig', []);
    }

    /**
     * @Route("/termes-et-conditions", name="terme")
     */
    public function terme(): Response
    {
        return $this->render('Partie_users/termes.html.twig', []);
    }

    /**
     * @Route("/commentcamarche", name="commentcamarche")
     */
    public function commentcamarche(): Response
    {
        return $this->render('Partie_users/commentcamarche.html.twig', []);
    }

    // Liste des maisons =boutique des maisons

    /**
     * @Route("/plan", name="plan_list")
     */

    public function plan_list(
        Request $request,
        SuperficieRepository $superficieRepository,
        TypeRepository $typeRepository,
        PlansRepository $plansRepository,
        ImagesRepository $imagesRepository
    ): Response {
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchPlanType::class, $data);
        $form->handleRequest($request);
        // dd($data);

        $plans = $plansRepository->findsearchplan($data);
        $types = $typeRepository->findAll();
        $superficies = $superficieRepository->findAll();

        return $this->render('Partie_users/shop-gird.html.twig', [
            'form' => $form->createView(),
            'plans' => $plans,
            'types' => $types,
            'superficies' => $superficies,
        ]);
    }

    /**
     * @Route("details/{id}", name="show_plan_details", methods={"GET"})
     */

    public function show_details(
        MiniplansRepository $miniplansRepository,
        ConsulterRepository $consulterRepository,
        PlansRepository $plansRepository,
        Request $request,
        Plans $plan,
        ImagesRepository $imagesRepository,
        int $i = 0,
        float $NB_LIMITED = 0.0,
        int $id
    ): Response {
        $user = $this->getUser();
        $consultes = $consulterRepository->findOneBy([
            'user' => $this->getUser(),
            'plans' => $plan,
        ]);
        $plan_consultes = $consulterRepository->findBy(['plans' => $plan]);

        if (!$user) 
        
        {
            if (!$consultes) {
                $consulter = new EntityConsulter();

                $consulter->setUser($this->getUser());
                $consulter->setPlans($plan);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($consulter);
                $entityManager->flush();
            }
        }

        $plans = $plansRepository->findOneBy(['id' => $id]);
        $current_mini_plan = $miniplansRepository->findBy(['plans' => $plan]);

        $entityManager = $this->getDoctrine()->getManager();
        $prixtotal = 0.;

        // $consulter = new Consulter();
        // $consulter = $consulter->setPlan_id();

        foreach ($current_mini_plan as $mini) {
            $prixtotal += $mini->getPrix();
        }

        $NB_LIMITED = sizeof($imagesRepository->findAll()) / 2;

        //le code pour les liens 

        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $url = 'https';
        } else {
            $url = 'http';
        }

        // Ajoutez // à l'URL.
        $url .= '://';

        // Ajoutez l'hôte (nom de domaine, ip) à l'URL.
        $url .= $_SERVER['HTTP_HOST'];

        // Ajouter l'emplacement de la ressource demandée à l'URL
        $url .= $_SERVER['REQUEST_URI'];

        // Afficher l'URL

        $session = new Session();

        // set and get session attributes
        $session->set('url', $url);

        // dd($session->get('url'));

        $this->addFlash(
            'error',
            'Vous devez vous connecter ou s\'inscrire avant d\'effectuer l\'achat du plan'
        );


        return $this->render('Partie_users/shop-details.html.twig', [
            'prixtotal' => $prixtotal,
            'plan_consultes' => $plan_consultes,
            'plans' => $plans,
            'plan' => $plan,
            'i' => $i,
            'NB_LIMITED' => $NB_LIMITED,
        ]);
    }

    /**
     * @Route("achat/{id}/{id1}", name="achat", methods={"GET"})
     */

    public function achat(
        PlansRepository $plansRepository,
        MiniplansRepository $miniplansRepository,
        int $id,
        $id1
    ): Response {
        if ($user = $this->getUser()) {
            $achat = new Achat();
            $user = $this->getUser();

            //    dd($miniplansRepository->findOneBy(['id' => $id1]));

            $entityManager = $this->getDoctrine()->getManager();

            $plan = $achat->setUsers($user);

            $plan = $achat->setEtat('en cours');
            $plan = $achat->setPayement('PAYZY');
            $plan = $achat->setRetrait(false);

            $p = $plansRepository->findOneBy(['id' => $id]);
            $mp = $miniplansRepository->findOneBy(['id' => $id1]);
            $plan = $achat->setPrix($mp->getPrix());

            $plan = $achat->setPlan($p);
            $plan = $achat->setMiniplan($mp);

            $entityManager->persist($plan);
            $entityManager->flush();

            $achat_id = $achat->getId();

            $client = HttpClient::create([
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Bearer ZK3ZHNM9F3MMSSM7EEZPG5K00070',
                    'Accept' => 'application/json',
                ],
            ]);

            $response = $client->request(
                'POST',
                'https://payzyapp.com/api/v1/transactions/demande/paiement',
                [
                    // these values are automatically encoded before including them in the URL
                    'body' => [
                        'description' =>
                            'Paiement de la partie' .
                            $mp->getTitre() .
                            'du plan' .
                            $p->getTitre(),
                        'commande_id' => '' . $achat_id,
                        'amount' => '' . $mp->__toInt(),
                    ],
                ]
            );

            if ($response->getStatusCode() == 200) {
                $body = json_decode($response->getContent());

                if ($body->status == 'ok') {
                    return $this->redirect('' . $body->redirectionUrl, 302);
                }
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/demande/payement", name="demande_payement")
     */
    public function payement(
        AchatRepository $achatRepository,
        PlansRepository $plansRepository,
        UsersRepository $usersRepository
    ): Response {
        $architecte = $this->getUser();

        return $this->render('Partie_admin/index.html.twig', [
            'users' => $usersRepository->findOneBy([
                'id' => $architecte->getId(),
            ]),
        ]);
    }

    /**
     * @Route("/payement", name="payement",methods={"POST"})
     */
    public function confirme_payment(
        Request $request,
        AchatRepository $achatRepository
    ): Response {
        $response = new Response();

        $hook = '9CC9SB0JDNMQ5YMFPDN2RN2V08EM';

        /* return response($request->event.' '.$request, 200)
         ->header('Content-Type', 'text/plain');*/

        $data = $request->getContent();

        $array = json_decode($data, true);

        if ($array['event'] == 'incoming_response') {
            if ($array['webhook'] == $hook) {
                if ($array['transactionStatus'] == 'confirmed') {
                    $int = (int) $array['transactionCommandeId'];

                    ////appelle de l'api payzy
                    $client = HttpClient::create([
                        'headers' => [
                            'Content-Type' =>
                                'application/x-www-form-urlencoded',
                            'Authorization' =>
                                'Bearer ZK3ZHNM9F3MMSSM7EEZPG5K00070',
                            'Accept' => 'application/json',
                        ],
                    ]);
                    $response = $client->request(
                        'POST',
                        'https://payzyapp.com/api/v1/transactions/demande/commande/status',
                        [
                            // these values are automatically encoded before including them in the URL
                            'body' => [
                                'commande_id' => '' . $int,
                            ],
                        ]
                    );

                    if ($response->getStatusCode() == 200) {
                        $body = json_decode($response->getContent());

                        if ($body->status == 'ok') {
                            $commande = $achatRepository->findOneBy([
                                'id' => $int,
                            ]);
                            $commande = $commande->setEtat('confirmé');
                            $commande = $commande
                                ->getMiniplan()
                                ->setVente(
                                    $commande->getMiniplan()->getVente() + 1
                                );
                            $entityManager = $this->getDoctrine()->getManager();
                            $entityManager->persist($commande);
                            $entityManager->flush();
                        }
                    }

                    return $response = new Response('ok', Response::HTTP_OK);
                    $response->send();
                } else {
                    return $response = new Response(
                        'Page not found1',
                        Response::HTTP_UNAUTHORIZED
                    );
                    $response->send();
                }
            } else {
                return $response = new Response(
                    'Page not found2',
                    Response::HTTP_UNAUTHORIZED
                );
                $response->send();
            }
        } else {
            return $response = new Response(
                'Page not found3',
                Response::HTTP_UNAUTHORIZED
            );
            $response->send();
        }
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * @Route("/comfirmerpaygate",name="payement36",methods={"GET","POST"})
     */
    public function confirmer_payement4(
        Request $request,
        AchatRepository $achatRepository,
        MiniplansRepository $miniplansRepository
    ) {
        $postjsonData = json_encode(
            [
                'auth_token' => '4699c5c0-7297-45d2-b6fc-c5f1f956323e',
            ],
            JSON_THROW_ON_ERROR
        );

        $response = $this->client->request(
            'POST',
            'https://paygateglobal.com/api/v1/status',
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ],
                'body' => $postjsonData,
            ]
        );
        $entityManager = $this->getDoctrine()->getManager();

        $data = $request->request;

        $user = $this->getUser();
        $mp = $miniplansRepository->findOneBy(['id' => $data->get('id_mini')]);

        $user = $this->getUser();

        if ($response->getStatusCode() == 200) {
            dd('enter  0000000000000000000000');

            $responseData = json_decode(
                $response->getContent(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if ($responseData['status'] == 0 && $user) {
                // payement valider

                $valider = $achatRepository
                    ->findOneBy([
                        'user' => $user->getId(),
                        'miniplan' => $mp->getId(),
                        'etat' => 'en Cours',
                    ])
                    ->setEtat('Confirmer');

                // $mp = $miniplansRepository->findOneBy(['id' => $data->get('id_mini')])->getTxReference();

                // $valider = $miniplansRepository->findOneBy(['id' => $data->get('id_mini')])->setTxReference(null);
                // $valider = $achatRepository->findOneBy(['Txreference' => $mp])->setEtat("Confirmer");

                $entityManager->persist($valider);
                $entityManager->flush();

                dd('Etat confirmé et mimiplan ref null');
            } elseif ($responseData['status'] == 2) {
                $this->addFlash(
                    'primary',
                    'Votre transaction est encours veuillez saisr le code de sécurité depuis votre compte pour valider la transaction'
                );
                dd('finaliser la transaction');
            } elseif (
                $responseData['status'] == 6 ||
                $responseData['status'] == 4
            ) {
                $supp = $achatRepository->findOneBy([
                    'user' => $user->getId(),
                    'miniplan' => $mp->getId(),
                    'etat' => 'en Cours',
                ]);

                $entityManager->remove($supp);
                $entityManager->flush();

                // dd('transaction supprimer et autre ');
            }
        }
    }

    /**
     * @Route("achat2/{id}", name="achat2" , methods={"GET"})
     */
    public function FunctionName(
        Request $request,
        PlansRepository $plansRepository,
        MiniplansRepository $miniplansRepository,
        AchatRepository $achatRepository,
        int $id
    ): Response {
        if ($user = $this->getUser()) {
            $achat_id = 19895365874517895897;
            $apiToken = '4699c5c0-7297-45d2-b6fc-c5f1f956323e';
            $url = '';
            $amount = 300;
            $description = 'new purshase from';

            $params = json_encode(
                [
                    'token' => '' . $apiToken,
                    'phone' => '' . $user->getTel(),
                    'amount' => '' . $amount,
                    'identifier' => '' . $achat_id,
                    'description' => '' . $description,
                ],
                JSON_THROW_ON_ERROR
            );

            $url222 = "https://paygateglobal.com/v1/page?token=$apiToken&amount=$amount&description=$description&identifier=$achat_id";

            $url1 = "https://paygateglobal.com/v1/page?token=$apiToken&amount=$amount&identifier=$achat_id&description=$description";

            return $this->redirect($url1);
        }
    }

    /**
     * @Route("achat4", name="achat4", methods={"GET","POST"})
     */
    public function achat4(
        Request $request,
        SessionInterface $session,
        PlansRepository $plansRepository,
        MiniplansRepository $miniplansRepository,
        AchatRepository $achatRepository
    ): Response {
        $entityManager = $this->getDoctrine()->getManager();

        $data = $request->request;

        $user = $this->getUser();
        $mp = $miniplansRepository->findOneBy(['id' => $data->get('id_mini')]);
        $p = $plansRepository->findOneBy(['id' => $data->get('id_plan')]);

        $alpha = $miniplansRepository->findOneBy([
            'id' => $data->get('id_mini'),
        ]);

        // && $achatRepository->findOneBy(['Txreference' => $alpha->getTxReference()]) == null
        $achatplan = 0;
        if ($request->isMethod('POST') && ($user = $this->getUser())) {
            $achat = new Achat();
            $user = $this->getUser();
            $plan = $achat->setUsers($user);
            $plan = $achat->setEtat('en cours');
            $plan = $achat->setPayement('PAYGATE');
            // $p = $plansRepository->findOneBy(['id' => $id]);

            $plan = $achat->setPlan($p);
            $plan = $achat->setMiniplan($mp);
            $plan = $achat->setRetrait(false);
            $plan = $achat->setStatusMail(false);
            $plan = $achat->setPrix($mp->getPrix());
            $entityManager->persist($plan);
            $entityManager->flush();
            $achat_id = $achat->getId();
            $rand = rand(999999, 9999999);
            // 999999  9999999

            $postjsonData = json_encode(
                [
                    'auth_token' => 'f733a8d0-7f79-4d5b-b960-44a3e3f97979',
                    // "auth_token" => "4699c5c0-7297-45d2-b6fc-c5f1f956323e",
                    // "auth_token" => "ef61afbb-a536-4c1a-ac45-18bf9dc71031",
                    'phone_number' => '' . $data->get('phone_number'),
                    'network' => '' . $data->get('network'),
                    // "identifier" => "" . $achat->getId(),
                    'identifier' => '' . $rand,
                    'amount' => '' . $achat->getMiniplan()->getPrix(),
                ],
                JSON_THROW_ON_ERROR
            );

            // dd($postjsonData);

            $response = $this->client->request(
                'POST',
                'https://paygateglobal.com/api/v1/pay',
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ],
                    'body' => $postjsonData,
                ]
            );

            // dd($response->getStatusCode());

            // La requête a réussi
            if ($response->getStatusCode() == 200) {
                $responseData = json_decode(
                    $response->getContent(),
                    true,
                    512,
                    JSON_THROW_ON_ERROR
                );

                // dd($responseData['status']);
                if ($responseData['status'] == 0) {
                    $txref = $responseData['tx_reference'];

                    $enre = $achat->setTxreference($txref);
                    // $achat->getMiniplan()->setTxReference($responseData['tx_reference']);
                    // Sauvegarde dans la base de donnée
                    // $valider = $achatRepository->findOneBy(['Txreference' => $txref]);
                    //  dd('transaction pris en compte');

                    if ($achat->getTxreference() != null) {
                        // dd($achat->getTxreference());
                        $entityManager->persist($enre);
                        $entityManager->flush();
                    }
                }

                sleep(2);
                return $this->redirectToRoute('listes_mes_achats');
                // dd($enre);

                // $this->addFlash('success', 'Vous avez payé le plan avec succes');
            } else {
                $this->addFlash(
                    'danger',
                    'La transaction n\'a pas été prise en compte veuillez rééssayer.'
                );
                dd('transaction pas pris en compte');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/stripe", name="payement_stripe")
     */
    public function stripe(): Response
    {
        if (isset($_POST['prix']) && !empty($_POST['prix'])) {
            require_once 'vendor/autoload.php';
            $prix = (float) $_POST['prix'];

            // On instancie Stripe
            \Stripe\Stripe::setApiKey(
                'sk_test_51JjeW9KNWJdBjny4R5mqgHev1jRmgmFWqtugWujwW0s8qG9AQfnH3mqQHnG2ZkqCGHydm8Kc5P6AEBpodMaP3B6h00TD150da6'
            );

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $prix * 100,
                'currency' => 'eur',
            ]);
        } else {
            header('Location: /index.php');
        }
        if (isset($_POST['prix']) && !empty($_POST['prix'])) {
            require_once 'vendor/autoload.php';
            $prix = (float) $_POST['prix'];

            // On instancie Stripe
            \Stripe\Stripe::setApiKey(
                'sk_test_51JjeW9KNWJdBjny4R5mqgHev1jRmgmFWqtugWujwW0s8qG9AQfnH3mqQHnG2ZkqCGHydm8Kc5P6AEBpodMaP3B6h00TD150da6'
            );

            $intent = \Stripe\PaymentIntent::create([
                'amount' => $prix * 100,
                'currency' => 'eur',
            ]);
        } else {
            header('Location: /index.php');
        }

        return $this->render('Partie_users/shop-details.html.twig', [
            'intent' => $user->createSetupIntent(),
        ]);
    }

    /**
     * @Route("/payment", name="payment")
     */
    public function index1(): Response
    {
        return $this->render('payment/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/checkout", name="checkout")
     */
    public function checkout(
        $stripeSK,
        Request $request,
        PlansRepository $plansRepository,
        MiniplansRepository $miniplansRepository,
        AchatRepository $achatRepository
    ): Response {
        $entityManager = $this->getDoctrine()->getManager();

        $data = $request->request;

        $user = $this->getUser();

        if ($user) {
            $mp = $miniplansRepository->findOneBy([
                'id' => $data->get('id_mini'),
            ]);
            $p = $plansRepository->findOneBy(['id' => $data->get('id_plan')]);

            Stripe::setApiKey($stripeSK);

            $achat = new Achat();
            $entityManager = $this->getDoctrine()->getManager();
            $plan = $achat->setUsers($user);
            $plan = $achat->setEtat('en cours');
            $plan = $achat->setPayement('STRIPE');
            $plan = $achat->setRetrait(false);
            $plan = $achat->setPlan($p);
            $plan = $achat->setPrix($mp->getPrix());
            $plan = $achat->setMiniplan($mp);
            $entityManager->persist($plan);
            $entityManager->flush();
            $achat_id = $achat->getId();
            $description = 'new purshase from' . $p->getId();

            // $apiToken = 'f733a8d0-7f79-4d5b-b960-44a3e3f97979';
            // $url = '';
            $amount = $achat->getMiniplan()->getPrix();

            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'eur',
                            'product_data' => [
                                'name' => '' . $description,
                            ],
                            'unit_amount' => floor($amount / 650) * 100,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',

                'success_url' => $this->generateUrl(
                    'success_url',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
                'cancel_url' => $this->generateUrl(
                    'cancel_url',
                    [],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ]);

            //  dd($session);

            return $this->redirect($session->url, 303);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/success-url", name="success_url")
     */
    public function successUrl(
        Request $request,
        PlansRepository $plansRepository,
        MiniplansRepository $miniplansRepository,
        AchatRepository $achatRepository
    ): Response {
        return $this->render('payment/success.html.twig', []);
    }

    /**
     * @Route("/cancel-url", name="cancel_url")
     */
    public function cancelUrl(): Response
    {
        return $this->render('payment/cancel.html.twig', []);
    }

    /**
     * @Route("/dernier/achat/", name ="dernier_achat" , methods={"GET","POST"} )
     */
    public function achat_unique_client(
        UsersRepository $usersRepository,
        MiniplansRepository $miniplansRepository,
        Request $request,
        PlansRepository $plansRepository,
        AchatRepository $achatRepository
    ) {
        $user = $this->getUser();

        $paygate = $achatRepository->findBy([
            'users' => $user->getId(),
            'etat' => 'en cours',
            'payement' => 'PAYGATE',
        ]);
        $payzy = $achatRepository->findBy([
            'users' => $user->getId(),
            'etat' => 'Confirmer',
            'payement' => 'PAYZY',
        ]);
        $liste_des_achat2 = $achatRepository->findBy([
            'users' => $user->getId(),
            'etat' => 'Confirmer',
        ]);

        if ($paygate) {
            $entityManager = $this->getDoctrine()->getManager();

            $data = $request->request;

            $liste_des_achat = $achatRepository->findBy([
                'users' => $user->getId(),
                'etat' => 'en cours',
                'retrait' => false,
            ]);
            $liste_des_achat2 = $achatRepository->findBy([
                'users' => $user->getId(),
                'etat' => 'Confirmer',
                'retrait' => false,
            ]);

            //dd($liste_des_achat2);
            $liste_des_achat2 = $achatRepository->findBy([
                'users' => $user->getId(),
            ]);
            // dd($listedesachat = $achatRepository->achatlist($user));

            foreach ($paygate as $list) {
                $txref = $list->getTxreference();

                $postjsonData = json_encode(
                    [
                        'auth_token' => 'f733a8d0-7f79-4d5b-b960-44a3e3f97979',
                        'tx_reference' => $txref,
                    ],
                    JSON_THROW_ON_ERROR
                );

                $response = $this->client->request(
                    'POST',
                    'https://paygateglobal.com/api/v1/status',
                    [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'body' => $postjsonData,
                    ]
                );

                if ($response->getStatusCode() == 200) {
                    $responseData = json_decode($response->getContent(), true);
                    if ($responseData['status'] == 0) {
                        $payement_valider = $list->setEtat('Confirmer');

                        $payement_valider = $list
                            ->getMiniplan()
                            ->setVente($list->getMiniplan()->getVente() + 1);
                    } elseif ($responseData['status'] == 2) {
                        $payement_valider = $list->setEtat('annuler');

                        // $entityManager = $this->getDoctrine()->getManager();
                        // $entityManager->remove($list);

                        // $entityManager->remove($list);
                        $entityManager->flush();
                        $this->addFlash(
                            'primary',
                            'Votre transaction est encours veuillez saisr le code de sécurité depuis votre compte pour valider la transaction'
                        );
                    } elseif (
                        $responseData['status'] == 6 ||
                        $responseData['status'] == 4
                    ) {
                        $payement_valider = $list->setEtat('annuler');
                    }
                }
            }

            return $this->render('Partie_users/dernierachat.html.twig', [
                // 'users' => $achat->findOneBy(['id' => $user->getId()]),
                'users' => $liste_des_achat2,
                //  'miniplan'=>$mp,
            ]);
        } elseif ($liste_des_achat2) {
            return $this->render('Partie_users/dernierachat.html.twig', [
                'users' => $liste_des_achat2,
            ]);
        } elseif ($payzy) {
            return $this->render('Partie_users/dernierachat.html.twig', [
                'users' => $liste_des_achat2,
            ]);
        } else {
            return $this->render('Partie_users/dernierachat.html.twig', [
                'users' => $liste_des_achat2,
            ]);
        }
        return $this->render('Partie_users/dernierachat.html.twig', [
            'users' => $liste_des_achat2,
        ]);
    }

    /**
     * @Route("/liste/mes/achats/", name ="listes_mes_achats" , methods={"GET","POST"} )
     */
    public function achat_list_client(
        UsersRepository $usersRepository,
        MiniplansRepository $miniplansRepository,
        Request $request,
        PlansRepository $plansRepository,
        AchatRepository $achatRepository,
        MailerInterface $mailer,
        PaginatorInterface $paginator,
        FichiersRepository $fichiersRepository
    ) {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        //la méthode findBy permet de récupérer les données avec des critères et de filtre et de tri

        $paygate = $achatRepository->findBy([
            'users' => $user->getId(),
            'etat' => 'en cours',
            'payement' => 'PAYGATE',
            'statusmail' => false,
        ]);

        $paygate_confirmation = $achatRepository->findBy([
            'users' => $user->getId(),
            'etat' => 'Confirmer',
            'payement' => 'PAYGATE',
            'statusmail' => false,
        ]);

        $payzy = $achatRepository->findBy([
            'users' => $user->getId(),
            'etat' => 'Confirmer',
            'payement' => 'PAYZY',
        ]);

        // liste de tous les achats de plan sur la page mes achats

        $liste_des_achat2 = $achatRepository->findBy(['users' => $user->getId(), 'etat' => "Confirmer", 'retrait' => false]);

        
        sleep(5);

        if ($paygate) {
            $data = $request->request;

            $liste_des_achat = $achatRepository->findBy([
                'users' => $user->getId(),
                'etat' => 'en cours',
                'retrait' => false,
            ]);
            $liste_des_achat2 = $achatRepository->findBy([
                'users' => $user->getId(),
                'etat' => 'Confirmer',
                'retrait' => false,
            ]);

            //dd($liste_des_achat2);
            $liste_des_achat2 = $achatRepository->findBy([
                'users' => $user->getId(),
            ]);
            // dd($listedesachat = $achatRepository->achatlist($user));

            foreach ($paygate as $list) {
                $txref = $list->getTxreference();

                // dd( $list->getTxreference());

                $postjsonData = json_encode(
                    [
                        'auth_token' => 'f733a8d0-7f79-4d5b-b960-44a3e3f97979',

                        'tx_reference' => $txref,
                    ],
                    JSON_THROW_ON_ERROR
                );

                $response = $this->client->request(
                    'POST',
                    'https://paygateglobal.com/api/v1/status',
                    [
                        'headers' => [
                            'Accept' => 'application/json',
                            'Content-Type' => 'application/json',
                        ],
                        'body' => $postjsonData,
                    ]
                );

                if ($response->getStatusCode() == 200) {
                    $responseData = json_decode($response->getContent(), true);


                    if ($responseData['status'] == 0) 
                    
                    {
                        $payement_valider = $list->setEtat('Confirmer');

                        $payement_valider = $list
                            ->getMiniplan()
                            ->setVente($list->getMiniplan()->getVente() + 1);

                        $this->addFlash(
                            'success',
                            'Votre achat a été initialisé. Vous avez payé votre plan avec succès'
                        );
                    } 
                    
                    elseif ($responseData['status'] == 2)
                    
                    {
                        $payement_valider = $list->setEtat('annuler');

                        // $entityManager = $this->getDoctrine()->getManager();
                        // $entityManager->remove($list);
                        // $entityManager->remove($list);

                        $entityManager->flush();
                        $this->addFlash(
                            'error',
                            'Votre transaction n\'a pas été prise en compte.<br>
                            Merci de recommencer.'
                        );
                    } 
                    
                    
                    elseif (
                        $responseData['status'] == 6 ||
                        $responseData['status'] == 4
                    ) 
                    {
                        $payement_valider = $list->setEtat('annuler');

                        $this->addFlash(
                            'error',
                            'Votre paiement n\'a pas abouti. Veuillez réessayer l\'achat du plan.'
                        );
                    }
                }
            }

            $entityManager->persist($payement_valider);
            $entityManager->flush();

            sleep(5);

            $envoimail = $achatRepository->findBy([
                'users' => $user->getId(),
                'etat' => 'Confirmer',
                'statusmail' => false,
            ]);

            //envoi de mail

            if ($envoimail) {
                foreach ($envoimail as $list2) {
                    //   dd($list2->getMiniplan()->getFichiers()->getNom());
                    //  $liste_des = $fichiersRepository->findOneBy(['miniplans' => $list2->getMiniplan()]);

                    // dd($liste_des);

                    // envoi de mail à Mailtraip.io
                    $html = $this->renderView('emails/facture.html.twig', [
                        'list' => $list2,
                    ]);
                    $email = (new TemplatedEmail())
                        ->from('no-repymarchedesplans@gmail.com')
                        ->to($user->getEmail())
                        // ->to('galaxiealpha09@gmail.com')
                        ->subject('PAIEMENT DES PLANS')
                        ->html($html);

                    $mailer->send($email);
                    $list2->setStatusMail(true);
                }

                $entityManager->persist($list2);
                $entityManager->flush();
            }
        } elseif ($paygate_confirmation) {
            $envoimail = $achatRepository->findBy([
                'users' => $user->getId(),
                'etat' => 'Confirmer',
                'statusmail' => false,
            ]);

            //envoi de mail

            if ($envoimail) {
                // dd($envoimail);

                foreach ($envoimail as $list2) {
                    // envoi de mail à Mailtraip.io
                    $html = $this->renderView('emails/facture.html.twig', [
                        'list' => $list2,
                    ]);
                    $email = (new TemplatedEmail())
                        ->from('no-repymarchedesplans@gmail.com')
                        ->to($user->getEmail())
                        // ->to('galaxiealpha09@gmail.com')
                        ->subject('PAIEMENT DES PLANS')
                        ->html($html);

                    $mailer->send($email);
                    $list2->setStatusMail(true);
                }

                $entityManager->persist($list2);
                $entityManager->flush();
            }
        } else {
            return $this->render('Partie_users/mesAchatsuser.html.twig', [
                'users' => $liste_des_achat2,
                'liste_des_achat2' => $liste_des_achat2,
            ]);
        }
        return $this->render('Partie_users/mesAchatsuser.html.twig', [
            'users' => $liste_des_achat2,
            'liste_des_achat2' => $liste_des_achat2,
        ]);
    }

    /**
     * @Route("/edit/my/profil/{id}", name="edit_my_profil")
     */
    public function editmyprofil(
        Users $users,
        UsersRepository $usersRepository,
        GuardAuthenticatorHandler $guardHandler,
        UsersAuthenticator $authenticator,
        UserPasswordEncoderInterface $passwordEncoder,
        Request $request
    ): Response {
        $user = $this->getUser();

        $form = $this->createForm(EditUserType::class, $users);
        $form->handleRequest($request);

        if ($request->isMethod('POST')) {
            $user->setPseudo($request->get('pseudo'));
            $user->setPseudo($request->get('prenoms'));
            $user->setEmail($request->get('email'));
            $user->setAdresse($request->get('adresse'));
            $user->setBanque($request->get('banque'));
            $user->setTel($request->get('tel'));

            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $request->get('password')
                )
            );

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

        return $this->render('Partie_users/editprofil.html.twig', [
            'user' => $user,
        ]);
    }
}