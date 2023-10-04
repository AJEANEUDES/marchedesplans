<?php

namespace App\Controller;

use App\Entity\Achat;
use App\Entity\Miniplans;
use App\Entity\Plans;
use App\Entity\Panier;
use App\Repository\AchatRepository;
use App\Repository\PlansRepository;
use App\Repository\MiniplansRepository;
use App\Repository\MiniplanscompletRepository;
use App\Repository\PanierRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{

    private $client;

    public function __construct(HttpClientInterface $client)
    {
        // $this->passwordEncoder = $passwordEncoder;
        $this->client = $client;
    }

     /**
     * @Route("/", name="index")
     */
    public function index(Request $request, SessionInterface $session, MiniplansRepository $miniplansRepository, PanierRepository $panierRepository, AchatRepository $achatRepository): Response
    {

        $user = $this->getUser();


        if ($user) {
            $paygate = $panierRepository->findBy(['user' => $user->getId(), 'etat' => false, 'payement' => 'PAYGATE']);
        } else {
            $paygate = $panierRepository->findBy(['etat' => false, 'payement' => 'PAYGATE']);
        }


        sleep(10);

        if ($paygate) {

            $entityManager = $this->getDoctrine()->getManager();

            $data = $request->request;
            // $liste_des_achat = $plansRepository->findBy(['users' => $user->getId(), 'etat' => false, 'payement' => 'PAYGATE']);

            foreach ($paygate as $list) {

                $txref = $list->getTxreference();

                $postjsonData = json_encode([
                    "auth_token" => "f733a8d0-7f79-4d5b-b960-44a3e3f97979",
                    "tx_reference" => $txref
                ], JSON_THROW_ON_ERROR);

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

                            $payement_valider = $list->setEtat(true);

                            $achat_panier = $achatRepository->findBy(['users' => $user->getId(), 'panier' => $list->getId(), 'payement' => 'PAYGATE', 'etat' => 'en cours', 'demande' => null ]);
                        

                        if ($payement_valider) 
                        
                            {

                                foreach ($achat_panier as $achat_p) {

                                    $payement_valider =  $achat_p->setEtat("Confirmer");

                                    $payement_valider = $achat_p->getMiniplan()->setVente($achat_p->getMiniplan()->getVente() + 1);
                                }

                            }

                    } 
                    
                    else if ($responseData['status'] == 2) {


                        $payement_valider = $list->setEtat(false);
                        $entityManager->flush();

                        $this->addFlash('primary', 'Votre transaction est encours veuillez saisr le code de sécurité depuis votre compte pour valider la transaction');
                    } 
                    
                    else if ($responseData['status'] == 6 || $responseData['status'] == 4) 
                    
                    {

                        $payement_valider = $list->setEtat(false);
                    }
                }
            }

            // dd($achat_panier);

            $entityManager->persist($payement_valider);
            $entityManager->flush();
        }








        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];
        $total = 0;


        foreach ($panier as $id => $quantite) {
            $miniplan = $miniplansRepository->find($id);
            $dataPanier[] = [
                "miniplan" => $miniplan,
                "quantite" => $quantite
            ];
            $total += $miniplan->getPrix();
        }

        return $this->render('Partie_users/panier.html.twig', compact("dataPanier", "total"));
    }


    /**
     * @Route("/plancomplet/add/{id}", name="add_plan_complet")
     */
    public function addplancomplet(Miniplans $miniplan, SessionInterface $session, Plans $plan, MiniplansRepository $miniplansRepository, Request $request): Response
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $plan->getId();

        $mini = $miniplansRepository->findBy(['plans' => $id]);

        foreach ($mini as $m) {
            $mid = $m->getId();
            if (!empty($panier[$mid])) {
                $panier[$mid]++;
            } else {
                $panier[$mid] = 1;
            }
        }




        if (!empty($panier[$mid])) {
            $panier[$mid]++;
        } else {
            $panier[$mid] = 1;
        }

        $session->getFlashBag()->add('success', 'Vous venez d\'ajouter l\'ensemble des plans de la maison au panier !!');

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        // dd($panier);

        
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



        return $this->redirectToRoute("cart_index");
        // return $this->redirect($request->headers->get('referer'));

    }


    
    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Miniplans $miniplan,  SessionInterface $session, Request $request):Response
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $miniplan->getId();
        // $id1 = $plan->getId();
        // $id = $miniplanscomplet->getId();


        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }

        $session->getFlashBag()->add('success', 'Vous venez d\'ajouter un plan au panier !!');

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        // dd($panier);

        // return $this->redirectToRoute("iste_des_mini_plan");

        return $this->redirect($request->headers->get('referer'));

    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Miniplans $miniplan, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $miniplan->getId();
        // $id = $miniplanscomplet->getId();

        if (!empty($panier[$id])) {
            if ($panier[$id] > 1) {
                $panier[$id]--;
            } else {
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Miniplans $miniplan,  SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $miniplan->getId();
        // $id = $miniplanscomplet->getId();

        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }


    /**
     * @Route("achat4", name="achat4" , methods={"GET","POST"})
     */
    public function achat4(AchatRepository $achatRepository, PanierRepository $panierRepository, SessionInterface $session, Request $request, PlansRepository $plansRepository, MiniplansRepository $miniplansRepository)
    {
        $user = $this->getUser();


        $entityManager = $this->getDoctrine()->getManager();

        $data = $request->request;

        $panier = $session->get("panier");


        if ($request->isMethod('POST') && $this->getUser()) {



            $mon_panier = new Panier;
            $codepaygate = rand(10000, 999999);
            $mon_panier
                ->setUser($this->getUser())
                ->setTotal($data->get('amount'))
                ->setPayement("PAYGATE")
                ->setEtat(false)
                ->setCode($codepaygate);

            //API DE PAYGATE
            $postjsonData = json_encode([
                "auth_token" => "f733a8d0-7f79-4d5b-b960-44a3e3f97979",
                "phone_number" => "" . $data->get('phone_number'),
                "network" => "" . $data->get('network'),
                "identifier" => "" . $codepaygate,
                "amount" => "" . $data->get('amount'),
            ], JSON_THROW_ON_ERROR);

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

            // SI LA REQUETE REUSSI

            if ($response->getStatusCode() == 200) {

                $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
                if ($responseData['status'] == 0) {
                    $txref = $responseData['tx_reference'];

                    $mon_panier->setTxreference($txref);
                }



                if ($mon_panier->getTxreference() != null) {
                    $entityManager->persist($mon_panier);
                    $entityManager->flush();
                }
            }




            foreach ($panier as $id => $p) {

                $total = 0;
                $miniplan = $miniplansRepository->find($id);


                $achat = new Achat();

                $user = $this->getUser();

                $p = $achat
                    ->setUsers($this->getUser())
                    ->setEtat("en cours")
                    ->setPayement("PAYGATE")
                    ->setPlan($miniplan->getPlans())
                    ->setMiniplan($miniplan)
                    ->setPrix($miniplan->getPrix())
                    ->setPanier($mon_panier)
                    ->setTxreference($mon_panier->getTxreference())
                    ->setRetrait(false)
                    ->setStatusMail(false);



                $entityManager = $this->getDoctrine()->getManager();
               
                if ($mon_panier->getTxreference() != null) {

                    $entityManager->persist($p);
                    $entityManager->flush();
                }
            }

            $session->remove("panier");

            // return $this->redirectToRoute("listes_mes_achats");
            return $this->redirectToRoute("cart_index");

        } else
            return $this->redirectToRoute('app_login');
    }

}
    