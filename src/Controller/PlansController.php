<?php

namespace App\Controller;

use ZipArchive;
use App\Entity\Plans;
use App\Entity\Users;
use App\Entity\Images;
use App\Form\PlansType;
use App\Data\SearchData;
use App\Entity\Fichiers;
use App\Entity\Miniplans;
use App\Entity\Superficie;
use App\Repository\PaysRepository;
use App\Repository\SuperficieRepository;
use App\Repository\PlansRepository;
use App\Repository\UsersRepository;
use App\Repository\ImagesRepository;
use App\Repository\MiniplansRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

/**
 * @Route("/plans")
 */
class PlansController extends AbstractController
{
    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/", name="plans_index", methods={"GET"})
     */
    public function index(PlansRepository $plansRepository ,UsersRepository $users,MiniplansRepository $miniplansRepository): Response
    { $user = $this->getUser();
        if ($user = $this->getUser()) {
            $user = $this->getUser(); 
            
            return $this->render('plans/index.html.twig',[
                'plans' => $plansRepository->findAll(),
                'users' => $users->findOneBy(['id' => $user->getId()]),
                // 'miniplans'=> $miniplansRepository->findOneBy(['id' => $id]),
                // 'miniplans'=>$miniplans->findOneBy(['id' => $user->getId()]),
                // 'miniplans'=> $miniplansRepository->findOneBy(['id' => $plansRepository->getId()]),

    
            ]);
           
        }else
        return $this->redirectToRoute('app_login');
       
      
    }


    /**
     * @Route("/liste/des/maisons", name="liste_plans_admin")
     */
    public function list_p(PlansRepository $plansRepository,Request $request): Response
    {

        $user = $this->getUser();
        $data = new SearchData();
        $data->page = $request->get('page', 1);
        // $form = $this->createForm(SearchPlanType::class, $data);
        // $form->handleRequest($request);

        $plans = $plansRepository->findsearchplan($data);

        return $this->render('plans/listplan.html.twig', [
            'plans' => $plansRepository->findAll(),
            // 'form' => $form->createView(),
            // 'plans' => $plans,
            
            
        
        ]);
    }
 
    /**
     *@IsGranted("ROLE_ARCHITECTE")
     * @Route("/new", name="plans_new", methods={"GET","POST"})
     */
    public function new(Request $request , PaysRepository $paysRepository): Response
    {   $plan = new Plans();
        $form = $this->createForm(PlansType::class, $plan);
        $form->handleRequest($request);
        $users = $this->getUser();   

        if ($form->isSubmitted() )        
        {
             //on recupere les images transmises
         $images = $form->get('images')->getData(); 
                    // on boucles sur les images 
             foreach ($images as $image) {
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = time();
               // on genere le nom de fichier
                $fichier = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

           
                 $image->move(
                     $this->getParameter('images_directory'),
                     $fichier
                 );
                 // on stocke le nom de l'image dans la base 
                 $img = new Images();
                 $img->setNom($fichier);
                 $plan->addImage($img);
             }
            $entityManager = $this->getDoctrine()->getManager();
            $plan ->setUser($users);
            $entityManager->persist($plan);
            $entityManager->flush();  
            return $this->redirectToRoute('plans_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('plans/new.html.twig', [
            'plan' => $plan,
            'user' => $users,
            'pays' => $paysRepository->findAll(),
            'form' => $form->createView(),
        ]);
    }


    /**
     * @IsGranted("ROLE_USER")
     * @Route("/{id}", name="plans_show", methods={"GET"})
     */
    public function show(Request $request,Plans $plan,int $id,PlansRepository $plansRepository): Response
    {
        $p = $plansRepository->findOneBy(['id' => $id]);

            $user = $this->getUser();
        return $this->render('plans/show.html.twig', [
            'plan' => $plan,
            'user' => $user,
            'p'=>$p,
        ]);
 
    }


   

     /**
      * @IsGranted("ROLE_USER")
      * @Route("/mini/plan/{id}", name="mini_plan_show", methods={"GET"})
      */
     public function listedesminiplans(Plans $plan,PlansRepository $plansRepository,int $id,MiniplansRepository $miniplansRepository): Response
     {
        // $p = $plansRepository->findOneBy(['id' => $id]);
        // dd($p);

        $total=0.;
        $p = $plansRepository->findOneBy(['id' => $id]);

        // $plan = $plans->getId();
        
         
        $miniplans = $plan->getMiniplans();

         return $this->render('miniplans/index.html.twig', ['miniplans' => $miniplans,'plan'=>$plan,'plans' => $p,'total'=>$total]);
     }

    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/edit/{id}", name="plans_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Plans $plan): Response
    {
        $form = $this->createForm(PlansType::class, $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() ) {

            //on recupere les images transmises
            $images = $form->get('images')->getData();
            // on boucle sur les images 
            foreach ($images as $image) {
                // on genere un nouveau nom de fichier 
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();
                // on copie le fichier dans le dossier upload 

                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                // on stock le nom de l'images dans la base 

                $img = new Images();
                $img->setNom($fichier);
                $plan->addImage($img);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('plans_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('plans/edit.html.twig', [
            'plan' => $plan,
            'form' => $form->createView(),
        ]);
    }


     /**
      * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/{id}", name="plans_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Plans $plan,PlansRepository $plansRepository,$id): Response
    { 
        $p = $plansRepository->findOneBy(['id' => $id])->getMiniplans();

        if ($this->isCsrfTokenValid('delete'.$plan->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($plan);
            $entityManager->flush();
        }

        return $this->redirectToRoute('plans_index');
    }
  

    /**
     * @IsGranted("ROLE_ARCHITECTE")
     * @Route("/supprime/image/{id}", name="plan_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Images $image, Request $request){
        $data = json_decode($request->getContent(), true);

        // On vérifie si le token est valide
        if($this->isCsrfTokenValid('delete'.$image->getId(), $data['_token'])){
            // On récupère le nom de l'image
            $nom = $image->getNom();
            // On supprime le fichier
            unlink($this->getParameter('images_directory').'/'.$nom);

            // On supprime l'entrée de la base
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // On répond en json
            return new JsonResponse(['success' => 1]);
        }
        
        else{
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }

}
