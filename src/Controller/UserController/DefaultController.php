<?php
namespace App\Controller\UserController;

use App\Entity\Images;
use App\Entity\Plans;
use App\Repository\ImagesRepository;
use Twig\Environment;
use App\Repository\PlansRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractController
{
/**
 * Undocumented variable
 * @var Environment
 */
  private $twig;
public function __construct(Environment $twig)
{
   $this ->twig = $twig; 
}



 /**
  * Undocumented function
  *
  * @param PropertyRepository $repository
  * @return Response
  */

 
   
   

    

    public function show_plans(PlansRepository $plansRepository, ImagesRepository $imagesRepository ): Response
    {

       
        return $this->render('Partie_users/shop-gird.html.twig',[
            'plans' => $plansRepository->findAll(),


        ]);

        
      
    }
}