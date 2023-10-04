<?php
namespace App\Controller\AdminController;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

use Twig\Environment;

class AdminController extends AbstractController
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

 
   
}