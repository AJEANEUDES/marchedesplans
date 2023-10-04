<?php

namespace App\Controller;

use mysqli;
use App\Entity\Users;
use App\Entity\Message;
use App\Entity\Miniplans;
use App\Entity\Plans;
use App\Form\MessagesType;
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
     * @Route("/message", name="message")
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
    public function send(Request $request,UsersRepository $usersRepository): Response
    {
        $message = new Message;
        $form = $this->createForm(MessagesType::class, $message);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('message', 'Message envoyé avec succès.');
            return $this->redirectToRoute('message');
        }

        return $this->render('message/send.html.twig', [
            'form' => $form->createView()
        ]);

    }



    /**
     * @Route("envoyer/mesage ", name="envoyer_message")
     */
    public function FunctionName(Request $request,Plans $plans): Response
    {
        dd($plans->getId());
        $message = new Message;
        $form = $this->createForm(MessagesType::class, $message);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message->setSender($this->getUser());
            $message ->setRecipient($plans->getId());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();

            $this->addFlash('message', 'Message envoyé avec succès.');
            return $this->redirectToRoute('message');
        }

        return $this->render('$0.html.twig', []);
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








    public function sendValue(User $user, Request $request, AbonementRepository $repo)
    {
        $em = $this->getDoctrine()->getManager();

        // Vérifier si une traitement est encours

        if ($user->getEncours() == null || $user->getEncours() == false) {
            $data = $request->request;
            // Création de l'abonnement de l'artisan
            $abon = new Abonement();
            $abon->setArtisan($user);
            $abon->setType('Annuel');
            $abon->setSolde($user->getMetier()->getPrixAnnuel());
            $abon->setEtat(false);

            // Sauvegarde de l'abonnement
            $em->persist($abon);
            $em->flush();

            $postjsonData = json_encode([
                "auth_token" => "ef61afbb-a536-4c1a-ac45-18bf9dc71031",
                "phone_number" => "" . $data->get('phone_number'),
                "network" => "" . $data->get('network'),
                "identifier" => "" . $abon->getId(),
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

            // La requête a réussit
            if ($response->getStatusCode() == 200)
             {
                $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
                if ($responseData['status'] == 0) {
                    $abon->setTxreference($responseData['tx_reference']);
                    // Sauvegarde dans la base de donnée
                    $em->persist($abon);
                    $em->flush();

                    $user->setEncours(true);
                    $em->persist($user);
                    $em->flush();
                }
            } else {
                $this->addFlash('danger', 'La transaction n\'a pas été prise en compte veuillez rééssayer.');
            }
        } else {
            // On récupère le dernier abonnement
            $abon = $repo->findOneBy(['artisan' => $user->getId()], ['createAt' => 'DESC']);

            $postjsonData = json_encode([
                "auth_token" => "ef61afbb-a536-4c1a-ac45-18bf9dc71031",
                "tx_reference" => "" . $abon->getTxreference(),
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

            // Tout s'est bien passé
            if ($response->getStatusCode() == 200) {
                $responseData = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
                // Payement éffectué
                if ($responseData['status'] == 0) {
                    $user->setEncours(false);
                    $user->setExpiration(date_create('now')->modify('+12 month'));
                    $user->setActiver(true);
                    $em->persist($user);
                    $em->flush();

                    // Enregistrement de l'abonnement
                    $abon->setExpiration(date_create('now')->modify('+12 month'));
                    $abon->setEtat(true);
                    $abon->setPayementmethod($responseData['payment_method']);
                    $abon->setPayementreference($responseData['payment_reference']);
                    $abon->setDateDebut($responseData['datetime']);
                    $abon->setApistatuscode($responseData['status']);
                    $em->persist($user);
                    $em->flush();

                    $this->addFlash('success', 'Votre compte a été activé avec Succès');
                } else if ($responseData['status'] == 2) {
                    $this->addFlash('primary', 'Votre transaction est encours veuillez saisr le code de sécurité depuis votre compte pour valider la transaction');
                } else if ($responseData['status'] == 4 || $responseData['status'] == 6) {
                    // On suipprime l'abonement en cours
                    $em->remove($abon);
                    $em->flush();

                    // On remet encours à False
                    $user->setEncours(false);
                    $em->persist($user);
                    $em->flush();
                }
            }
        }
        return $this->redirectToRoute('artisan.dashboard');
    }
}
