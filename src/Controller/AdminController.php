<?php

namespace App\Controller;

use DateTime;
use App\Entity\Users;
use App\Entity\Miniplans;
use App\Form\EditUserType;
use App\Repository\TypeRepository;
use App\Repository\AchatRepository;
use App\Repository\PlansRepository;
use App\Repository\UsersRepository;
use App\Security\UsersAuthenticator;
use App\Repository\MiniplansRepository;
use App\Repository\WithdrawalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    public function __construct()
    {
    }

    /**
     * @Route("/dashboard", name="dashboard")
     */
    public function dash(
        WithdrawalRepository $withdrawalRepository,
        AchatRepository $achatRepository,
        UsersRepository $usersRepository,
        MiniplansRepository $miniplansRepository,
        PlansRepository $plansRepository
    ): Response {
        $user = $usersRepository->findOneBy([
            'id' => $this->getUser()->getId(),
        ]);

        if ($this->getUser() && $this->getUser()->getRoles() == ['ROLE_USER']) {
            return $this->redirectToRoute('index');
        } elseif (
            $this->getUser() &&
            ($this->getUser()->getRoles() == ['ROLE_ARCHITECTE'] || [
                    'ROLE_ADMIN',
                ] || ['ROLE_SUPER_ADMIN'])
        ) {
            $total_des_plan = $miniplansRepository->findAll();
            $total_des_utilisateur = $usersRepository->findAll();
            $vente = 0;
            $mes_plan = $miniplansRepository->findBy([
                'user' => $this->getUser(),
            ]);
            $mes_maison = $plansRepository->findBy([
                'user' => $this->getUser(),
            ]);
            $mes_achat = $achatRepository->findBy([
                'users' => $this->getUser(),
            ]);
            $total_des_achats = $achatRepository->findBy([
                'etat' => 'Confirmer',
                'retrait' => false,
                'demande' => null,
            ]);
            $prix_total = 0.;

            foreach ($total_des_plan as $plan) {
                $vente += $plan->getVente();
            }

            foreach ($total_des_achats as $achat) {
                $prix_total += $achat->getPrix();
            }

            // dd($vente,$mes_plan,$mes_maison,$mes_achat,$prix_total);
            return $this->render('Partie_admin/index.html.twig', [
                'users' => $user,
                'lesplan' => $total_des_plan,
                'mesplan' => $mes_maison,
                'lesutlisateur' => $total_des_utilisateur,
                'nbredevente' => $vente,
                'fondtotal' => $prix_total,
            ]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * liste des utilisateur de la plateforme
     *
     * @route("/utilisateurs", name="utilisateurs")
     */

    public function userList(UsersRepository $users)
    {
        return $this->render('admin/userList.html.twig', [
            'users' => $users->findAll(),
        ]);
    }

    /**
     * @Route("new/user", name="new_user")
     */
    public function new(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): Response {
        $users = new Users();
        $form = $this->createForm(EditUserType::class, $users);

        $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid())
        //  {
        //     $entityManager = $this->getDoctrine()->getManager();
        //     $entityManager->persist($users);
        //     $entityManager->flush();

        //     $this->addFlash('message', 'utlisateur modifier avec succes');
        //     return $this ->redirectToRoute('admin_utilisateurs' , [], Response::HTTP_SEE_OTHER);
        // }

        if ($request->isMethod('POST')) {
            $users->setPseudo($request->get('pseudo'));
            $users->setEmail($request->get('email'));

            $users->setPassword(
                $passwordEncoder->encodePassword(
                    $users,
                    $request->get('password')
                )
            );

            // $objet_user = $usersRepository->findOneBy(['id' => $request->get('userlist')]);
            $users->setRoles($request->get('userlist'));

            $em = $this->getDoctrine()->getManager();
            $em->persist($users);
            $em->flush();
        }

        return $this->render('admin/newuser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/list/des/achats", name ="listes_des_achats")
     */

    public function achat_list(AchatRepository $achatRepository)
    {
        return $this->render('admin/achatList.html.twig', [
            'achats' => $achatRepository->findAll(),
        ]);
    }

    /**
     * @Route("/list/des/achats/paygate", name="listes_des_achats_paygate")
     */
    public function achat_par_paygate(
        AchatRepository $achatRepository
    ): Response {
        return $this->render('admin/achat_list_paygate.html.twig', [
            'achats' => $achatRepository->findBy(['payement' => 'paygate']),
        ]);
    }

    /**
     * @Route("/list/des/achats/payzy", name="listes_des_achats_payzy")
     */
    public function achat_par_payzy(AchatRepository $achatRepository): Response
    {
        return $this->render('admin/achat_list_payzy.html.twig', [
            'achats' => $achatRepository->findBy(['payement' => 'payzy']),
        ]);
    }

    /**
     * @Route("/liste/des/demandes/de/retrait", name="liste_des_demnandes_de_retrait")
     */
    public function Listedesdemandederetraits(
        WithdrawalRepository $withdrawalRepository
    ): Response {
        $user = $this->getUser();
        // $demandes = $withdrawalRepository->findAll();
        $demandes = $withdrawalRepository->findWithdrawall($user);

        return $this->render('Partie_admin/listdesdemandes.html.twig', [
            'demandes' => $demandes,
        ]);
    }

    /**
     * modifier utilisateur
     *
     * @Route("/utilisateur/modifier/{id}", name ="modifier_user")
     */

    public function EditUser(Users $users, Request $request)
    {
        $form = $this->createForm(EditUserType::class, $users);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($users);
            $entityManager->flush();

            $this->addFlash('message', 'utlisateur modifier avec succes');
            return $this->redirectToRoute('admin_utilisateurs');
        }

        return $this->render('admin/edituser.html.twig', [
            'userForm' => $form->createView(),
        ]);
    }

    /**
     *
     * @Route("/utilisateur/modifier/{id}, name ="modifier_user)
     */
    /*
    public function modifierplan()
    {
        // il faur etre admin avant de pouvoir accder a cette ligne 
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

    }
}
*/

    /**
     * @Route("edit/{id}", name="edit")
     */
    public function edit(UsersRepository $usersRepository): Response
    {
        $user = $this->getUser();

        return $this->render('Partie_admin/editprofil.html.twig', [
            'users' => $usersRepository->findOneBy(['id' => $user->getId()]),
        ]);
    }

    // src/Security/Voter/Back/UserVoter
    // ...

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

        return $this->render('Partie_admin/editprofil.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/confirmer", name="confirmer_achat")
     */
    public function FunctionName(
        Request $request,
        AchatRepository $achatRepository,
        MiniplansRepository $miniplansRepository
    ): Response {
        $id_achat = $achatRepository->findOneBy([
            'id' => $request->get('id_achat'),
        ]);
        if ($request->isMethod('POST')) {
            $newDate = new Date();

            $id_achat = $achatRepository->findOneBy([
                'id' => $request->get('id_achat'),
            ]);

            $alpha = $id_achat->setEtat('confirmer');
            $em = $this->getDoctrine()->getManager();
            $em->persist($alpha);
            $em->flush();
        }

        return $this->redirectToRoute('admin_listes_des_achats');
    }

    /**
     * @Route("/valider/une/demande", name="valider_une_demande")
     */
    public function valider_une_demande1(
        Request $request,
        WithdrawalRepository $withdrawalRepository,
        MiniplansRepository $miniplansRepository,
        UsersRepository $usersRepository,
        AchatRepository $achatRepository
    ) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $id_demande = $withdrawalRepository->findOneBy([
            'id' => $request->get('id_demande'),
        ]);

        $mini = $miniplansRepository->findBy([
            'user' => $id_demande->getUser(),
        ]);
        $list_des_achats = $achatRepository->findBy([
            'miniplan' => $mini,
            'etat' => 'Confirmer',
            'retrait' => false,
            'demande' => 'En cours',
        ]);
        // dd($achatRepository->findBy(['miniplan' => $mini,'etat'=>'Confirmer','retrait'=>false]));

        if (
            ($request->isMethod('POST') &&
                $this->getUser()->getRoles() == 'R0') ||
            'R1'
        ) {
            foreach ($list_des_achats as $listdesachats) {
                $demande = $listdesachats->setRetrait(true);
                $demande = $listdesachats->setDemande('Confirmer');
            }

            $demande = $id_demande->setEtat('Valider');

            $demande = $id_demande->setUpdatedAt(
                new \DateTimeImmutable('@' . strtotime('now'))
            );

            $em = $this->getDoctrine()->getManager();
            $em->persist($demande);
            $em->flush();
        }

        return $this->redirectToRoute('admin_liste_des_demnandes_de_retrait');
    }

    //crud au niveau des utilisateurs

    public function canSearch(array $data, Users $user)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
        return true;
    }

    public function canCreate(Users $user)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
        return true;
    }

    public function canRead(Users $subject, Users $user)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
        return true;
    }

    public function canUpdate(Users $subject, Users $user)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
        foreach ($subject as $user) {
            if ($user->hasRole('ROLE_ADMIN')) {
                return false;
            }
        }
        return true;
    }

    public function canDelete(array $subject, Users $user)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
        foreach ($subject as $user) {
            if ($user->hasRole('ROLE_ADMIN')) {
                return false;
            }
        }
        return true;
    }

    public function canPermuteEnabled(array $subject, Users $user)
    {
        if (!$this->security->isGranted('ROLE_ADMIN')) {
            return false;
        }
        foreach ($subject as $user) {
            if ($user->hasRole('ROLE_ADMIN')) {
                return false;
            }
        }
        return true;
    }
    // ...
}