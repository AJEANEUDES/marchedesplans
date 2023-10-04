<?php

namespace App\Controller;

use ArrayObject;
use App\Entity\Plans;
use App\Entity\Fichiers;
use App\Entity\Miniplans;
use App\Entity\Withdrawal;
use App\Form\MiniplansType;
use App\Repository\AchatRepository;
use App\Repository\PlansRepository;
use App\Repository\UsersRepository;
use PhpParser\Node\Expr\Cast\Array_;
use App\Repository\MiniplansRepository;
use App\Repository\WithdrawalRepository;
use Doctrine\ORM\Query\AST\OrderByItem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/miniplans")
 */
class MiniplansController extends AbstractController
{

    private $client;



    public function __construct(HttpClientInterface $client)
    {
        // $this->passwordEncoder = $passwordEncoder;
        $this->client = $client;
    }



    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/", name="miniplans_index", methods={"GET"})
     */
    public function index(WithdrawalRepository $withdrawalRepository, Request $request,
     MiniplansRepository $miniplansRepository, UsersRepository $users,
     PlansRepository $plansRepository, AchatRepository $achatRepository): Response
    {
        // $plans = $plansRepository->findOneBy(['id' => $id]);
        // miniplan.getAchats();
        //      $liste_de = $achatRepository->findBy(['etat'=>"Confirmer"]);

        //    dd($liste_de);


        $user = $this->getUser();

        if ($user = $this->getUser()) 
        {
            $user = $this->getUser();

            $total = 0.;



            $mini = $miniplansRepository->findBy(['user' => $user]);
            $list_des_achats = $achatRepository->findBy(['miniplan' => $mini, 'etat' => 'Confirmer', 'retrait' => false, 'demande' => null]);


            // dd($withdrawalRepository->findOneBy(["user" => $user],["user" => "DESC","user" => "ASC"]));
            foreach ($list_des_achats as $listdesachats) {



                $total += $listdesachats->getPrix();
            }




            return $this->render('miniplans/index.html.twig', [
                // 'plans'=>$plansRepository->findOneBy(['id' => $id]),
                'miniplans' => $miniplansRepository->findAll(),
                'users' => $users->findOneBy(['id' => $user->getId()]),
                // 'listdesachats'=>$listdesachats,
                'list_des_achats' => $list_des_achats,
                'total' => $total,

            ]);
        }
    }

    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/new", name="miniplans_new", methods={"GET","POST"})
     */
    public function new(Request $request, UsersRepository $usersRepository, MiniplansRepository $miniplansRepository, PlansRepository $plansRepository): Response
    {
        $miniplan = new Miniplans();
        $form = $this->createForm(MiniplansType::class, $miniplan);
        $form->handleRequest($request);
        $users = $this->getUser();


        $liste_des_plan = $plansRepository->findOneBy(['id' => $request->get('userplan')]);




        if ($form->isSubmitted()) {
            $plan = $form->getData()->getPlans();

            $fichiers = $form->get('fichiers')->getData();

            if ($fichiers) {
                $originalFilename = $fichiers->getClientOriginalName();
                // this is needed to safely include the file name as part of the URL

                // $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $fichiers->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichiers->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }


                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $fichiers = new Fichiers();
                $fichiers->setNom($newFilename);
                $miniplan->addFichier($fichiers);
            }


            $miniplan->setUser($users);
            $miniplan->setPlans($liste_des_plan);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($miniplan);
            $entityManager->flush();
            //   dd($miniplan);      


            return $this->redirectToRoute('miniplans_index', [], Response::HTTP_SEE_OTHER);
            $plan->addMiniplan($miniplan);
        }

        return $this->render('miniplans/new.html.twig', [
            'miniplan' => $miniplan,
            'users' => $usersRepository->findOneBy(['id' => $users->getId()]),

            'form' => $form->createView(),
        ]);
    }








    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/new/mini/plans/{id}", name="Ajout_un_miniplan", methods={"GET","POST"})
     */
    public function AjouterUnMiniPlan(Request $request, UsersRepository $usersRepository, PlansRepository $plansRepository, int $id, Plans $plan): Response
    {
        $plans = $plansRepository->findOneBy(['id' => $id]);


        $miniplan = new Miniplans();

        $form = $this->createForm(MiniplansType::class, $miniplan);
        $form->handleRequest($request);
        $users = $this->getUser();
        // $plans = $form->getData()->getPlans()->getId();

        if ($form->isSubmitted()) {
            // dd($plans);


            $fichiers = $form->get('fichiers')->getData();
            // dd('OK OK');
            if ($fichiers) {
                $originalFilename = $fichiers->getClientOriginalName();
                // this is needed to safely include the file name as part of the URL
                // $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $fichiers->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichiers->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $fichiers = new Fichiers();
                $fichiers->setNom($newFilename);
                $miniplan->addFichier($fichiers);
            }

            $miniplan->setUser($users);
            $miniplan->setPlans($plans);
            // $miniplan->setPlans($request->get('userplan'));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($miniplan);
            $entityManager->flush();



            return $this->redirectToRoute('miniplans_index', [], Response::HTTP_SEE_OTHER);
            $plans->addMiniplan($miniplan);
        }

        return $this->render('miniplans/new.html.twig', [
            'miniplan' => $miniplan,
            'plans'  => $plans,
            'plan' => $plan,
            'users' => $usersRepository->findOneBy(['id' => $users->getId()]),
            'form' => $form->createView(),
        ]);
    }



    /**
     * @Route("/{id}", name="miniplans_show", methods={"GET"})
     */
    public function show(Miniplans $miniplan): Response
    {
        return $this->render('miniplans/show.html.twig', [
            'miniplan' => $miniplan,
        ]);
    }

    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/{id}/edit", name="miniplans_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Miniplans $miniplan): Response
    {
        $form = $this->createForm(MiniplansType::class, $miniplan);
        $form->handleRequest($request);
        $users = $this->getUser();


        if ($form->isSubmitted()) {


            $fichiers = $form->get('fichiers')->getData();

            if ($fichiers) {
                $originalFilename = $fichiers->getClientOriginalName();
                // this is needed to safely include the file name as part of the URL
                $newFilename =  $originalFilename . '-' . uniqid() . '.' . $fichiers->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichiers->move(
                        $this->getParameter('brochures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $fichiers = new Fichiers();
                $fichiers->setNom($newFilename);
                $miniplan->addFichier($fichiers);
            }

            $miniplan->setUser($users);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($miniplan);
            $entityManager->flush();

            return $this->redirectToRoute('miniplans_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('miniplans/edit.html.twig', [
            'miniplan' => $miniplan,
            'form' => $form->createView(),
        ]);
    }



    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/{id}", name="miniplans_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Miniplans $miniplan): Response
    {
        if ($this->isCsrfTokenValid('delete' . $miniplan->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($miniplan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('miniplans_index');
    }





    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/supprime/image/{id}", name="miniplans_delete_image", methods={"DELETE"})
     * @param Fichiers $fichiers
     * @param Request $request
     * @return void
     */
    public function deleteImage(Fichiers $fichiers, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if ($this->isCsrfTokenValid('delete' . $fichiers->getId(), $data['_token'])) {
            // On récupère le nom de l'image
            $nom = $fichiers->getNom();
            // On supprime le fichier
            unlink($this->getParameter('brochures_directory') . '/' . $nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($fichiers);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }






    /**
     * @Route("/liste/achats/client/", name ="listes_achats_client" , methods={"GET","POST"} )
     */
    public function achat_list_client(UsersRepository $usersRepository, MiniplansRepository $miniplansRepository, Request $request, PlansRepository $plansRepository, AchatRepository $achatRepository)
    {
        $user = $this->getUser();

        $paygate = $achatRepository->findBy(['users' => $user->getId(), 'etat' => "en cours", 'payement' => 'PAYGATE']);
        $payzy = $achatRepository->findBy(['users' => $user->getId(), 'etat' => "Confirmer", 'payement' => 'PAYZY']);
        $liste_des_achat2 = $achatRepository->findBy(['users' => $user->getId(), 'etat' => "Confirmer"]);




        if ($paygate) {

            $entityManager = $this->getDoctrine()->getManager();

            $data = $request->request;




            // $list = $usersRepository->findOneBy(['id' => $user->getId()]);

            $liste_des_achat = $achatRepository->findBy(['users' => $user->getId(), 'etat' => "en cours", 'retrait' => false]);
            $liste_des_achat2 = $achatRepository->findBy(['users' => $user->getId(), 'etat' => "Confirmer", 'retrait' => false]);

            //  dd($achatRepository->findBy(['users' =>$user->getId()]));
            // $listedesachat = $achatRepository->achatlist($user);
            $liste_des_achat2 = $achatRepository->findBy(['users' => $user->getId()]);


            foreach ($liste_des_achat as $list) {

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
                    if ($responseData['status'] == 0) {

                        $payement_valider = $list->setEtat("Confirmer");

                        $payement_valider = $list->getMiniplan()->setVente($list->getMiniplan()->getVente() + 1);
                    } else if ($responseData['status'] == 2) {

                        $payement_valider = $list->setEtat("annuler");

                        // $entityManager = $this->getDoctrine()->getManager();
                        // $entityManager->remove($list);

                        // $entityManager->remove($list);
                        $entityManager->flush();
                        $this->addFlash('primary', 'Votre transaction est encours veuillez saisr le code de sécurité depuis votre compte pour valider la transaction');
                    } else if ($responseData['status'] == 6 || $responseData['status'] == 4) {


                        $payement_valider = $list->setEtat("annuler");


                        // dd('transaction supprimer et autre ');
                    }
                }
            }

            // dd($payement_valider);
            $entityManager->persist($payement_valider);
            $entityManager->flush();

            return $this->render('Partie_admin/achats.html.twig', [
                // 'users' => $achat->findOneBy(['id' => $user->getId()]),
                'users' => $liste_des_achat2,
                //  'miniplan'=>$mp,

            ]);
        } elseif ($liste_des_achat2) {
            return $this->render('Partie_admin/achats.html.twig', [
                'users' => $liste_des_achat2,



            ]);
        } elseif ($payzy) {
            return $this->render('Partie_admin/achats.html.twig', [
                'users' => $liste_des_achat2,

            ]);
        } else {
            return $this->render('Partie_admin/achats.html.twig', [
                'users' => $liste_des_achat2,

            ]);
        }
        return $this->render('Partie_admin/achats.html.twig', [
            'users' => $liste_des_achat2,

        ]);
    }


    /**
     * @IsGranted("ROLE_ARCHITECTE")         
     * @Route("/demande/de/retrait", name="demande_de_retrait")
     */
    public function achat_sur_le_plan(UserPasswordEncoderInterface $passwordEncoder, Request $request, MiniplansRepository $miniplansRepository, UsersRepository $usersRepository, AchatRepository $achatRepository): Response
    {
        $user = $this->getUser();
        $total = 0.;
        $totaldesachatdelaplateforme = 0.;



        $mini = $miniplansRepository->findBy(['user' => $user]);
        $list_des_achats = $achatRepository->findBy(['miniplan' => $mini, 'etat' => 'Confirmer', 'retrait' => false, 'demande' => null]);

        $total_des_achat = $achatRepository->findBy(['etat' => 'Confirmer', 'retrait' => false, 'demande' => null]);

        // foreach ($total_des_achat as $totaldesachats) {

        //     $total += $totaldesachats->getPrix();

        // }

        // dd($total);






        if ($user && $request->isMethod('POST')) {


            foreach ($list_des_achats as $listdesachats) {

                $total += $listdesachats->getPrix();

                $comission = ($total * 5) / 100;

                $rendu = $total - $comission;

                $w = $listdesachats->setDemande("En cours");

                foreach ($total_des_achat as $totaldesachats) {

                    $totaldesachatdelaplateforme += $totaldesachats->getPrix();



                    if ($total <= $totaldesachatdelaplateforme) {

                        $withdrawal = new Withdrawal;

                        $w =   $withdrawal->setUser($user);
                        $w =  $withdrawal->setPrix($total);
                        $w =  $withdrawal->setCommission($comission);
                        $w =  $withdrawal->setReste($rendu);
                        $w =  $withdrawal->setTel($request->get('telephone'));
                        $w = $withdrawal->setEtat("En attente");
                    }
                }
            }




            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($w);
            $entityManager->flush();


            $session->getFlashBag()->add('success', 'Votre demande a été prise en compte');

            return $this->redirect($request->headers->get('referer'));
            // return $this->redirectToRoute('admin_dashboard');
        } else return $this->redirectToRoute('app_login');
    }
}
