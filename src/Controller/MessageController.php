<?php

namespace App\Controller;

use mysqli;
use App\Entity\Users;
use App\Entity\Message;
use App\Entity\Miniplans;
use App\Entity\Plans;
use App\Form\MessagesType;
use App\Repository\MiniplansRepository;
use Doctrine\ORM\EntityManager;
use App\Repository\UsersRepository;
use PhpParser\Node\Expr\Cast\Array_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MessageController extends AbstractController
{
    /**
     * @Route("/message" , name="message")
     */
    public function index(): Response
    {
        return $this->render('message/index.html.twig', [
            'controller_name' => 'MessageController',
        ]);
    }

 
    /**
     * @Route("/send", name="send")
     */
    public function send(Request $request, UsersRepository $usersRepository): Response
    {
        $liste_des_admin= $usersRepository->findVisibleQuery1($this->getUser());
        $message = new Message;
        // dd($_SESSION);
        // $form = $this->createForm(MessagesType::class, $message);


    
        // $form->handleRequest($request);

        // if ($form->isSubmitted() && $form->isValid()) {

        //     $message->setSender($this->getUser());

        //     $em = $this->getDoctrine()->getManager();
        //     $em->persist($message);
        //     $em->flush();

        //     $this->addFlash('message', 'Message envoyé avec succès.');
        //     return $this->redirectToRoute('message');
        // }


        if($request->isMethod('POST'))
        {
            $message->setSender($this->getUser());
            $message->setTitre($request->get('titre'));
            $message->setMessage($request->get('message'));

            $objet_user_admin=$usersRepository->findOneBy(['id'=> $request->get('adminlist')]);
            $message->setRecipient($objet_user_admin);

            
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();


            
        }

        return $this->render('message/send.html.twig', [
            // 'form' => $form->createView(),
            'liste_admin'=>$liste_des_admin
        ]);
    }




       
/**
 * Undocumented function
 * @Route("envoyer/message/{id}" , name="envoyer_message")
 * @param Request $request
 * @param Plans $plans
 * @param MiniplansRepository $miniplansRepository
 * @param integer $id
 * @param UsersRepository $usersRepository
 * @param Miniplans $miniplans
 * @return Response
 */
public function Envoyermessage(Request $request,UsersRepository $usersRepository, Miniplans $miniplans): Response
{
    $users = $this->getUser();

    $miniplan = $miniplans;
    // dd($miniplans->getUser()->getUsername(), $miniplan, $users->getUsername());


    $message = new Message;


    if($request->isMethod('POST'))
        {
            $message->setSender($this->getUser());
            $message->setTitre($request->get('titre'));
            $message->setMessage($request->get('message'));

            $objet_user_admin=$usersRepository->findOneBy(['id'=> $request->get('admin_user')]);
            $message->setRecipient($objet_user_admin);            
            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();


            
        }

   

    return $this->render('message/send.html.twig', [
        // 'form' => $form->createView(),
        'miniplan' => $miniplan,
        'users' => $usersRepository->findOneBy(['id' => $users->getId()]),

    ]);
}



/**
 * @Route("/repondre", name="repondre_message")
 */
public function FunctionName(Request $request,UsersRepository $usersRepository): Response
{
    $users = $this->getUser();

    // dd($miniplans->getUser()->getUsername(), $miniplan, $users->getUsername());


    $message = new Message;
    if ($request->isMethod('POST')) {
        $message->setSender($this->getUser());
        $message->setRecipient($request->get('id_sender'));
        $message->setMessage($request->get('message'));
        




       
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        



    }
   return $this->redirectToRoute('message');
}


/**
 * @Route("Repondre/message/{id}", name="repondre")
 */
public function Repondre(Request $request,UsersRepository $usersRepository, Miniplans $miniplans): Response
{
    // $users = $this->getUser();

    $miniplan = $miniplans->getId();
    // dd($miniplans->getUser()->getUsername(), $miniplan, $users->getUsername());


    $message = new Message;
    $form = $this->createForm(MessagesType::class, $message);


    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        $message->setSender($miniplans->getUser());
        $message->setRecipient($miniplans->getUser());
        // $message->setTitre('message  du plan' . $plans->getTitre() . 'du batiment' . ' ' . $miniplans->getTitre());

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $this->addFlash('message', 'Message envoyé avec succès.');
        return $this->redirectToRoute('message');
    }

    return $this->render('message/send.html.twig', [
        'form' => $form->createView(),
        'miniplan' => $miniplan,
        // 'users' => $usersRepository->findOneBy(['id' => $users->getId()]),

    ]);
}





    

    /**
     * @Route("/received", name="received")
     */
    public function received(): Response
    {
        return $this->render('message/received.html.twig');
    }

    /**
     * @Route("/sent", name="sent")
     */
    public function sent(): Response
    {
        return $this->render('message/sent.html.twig');
    }



    /**
     * @Route("/read/{id}", name="read")
     */
    public function read(Message $message): Response
    {
        $message->setIsRead(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        return $this->render('message/read.html.twig', compact("message"));
    }






    


    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Message $message): Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($message);
        $em->flush();

        return $this->redirectToRoute("received");
    }

    /**
     * @Route("/listdesuser", name="list")
     *
     * @param UsersRepository $usersRepository
     * @return void
     */
    public function getChiens(UsersRepository $usersRepository){
 
      
        // $listChiens = $usersRepository->findAll();
        // $listChiens->getRoles();
        $response = new Response();
        $response->setContent(json_encode($usersRepository->findAll()))->headers->set('Content-Type', 'application/json');

        dd($response);
        return $this->redirectToRoute("received");


    }
}
