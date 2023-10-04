<?php

namespace App\Controller;

use App\Entity\Superficie;
use App\Form\SuperficieType;
use App\Repository\SuperficieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/superficie" )
 */
class SuperficieController extends AbstractController
{
    /**
     * @Route("/", name="superficie_index", methods={"GET"})
     */
    public function index(SuperficieRepository $superficieRepository): Response
    {
       
        return $this->render('superficie/index.html.twig', [
            'superficies' => $superficieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="superficie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        
        $superficie = new Superficie();
        $form = $this->createForm(SuperficieType::class, $superficie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($superficie);
            $entityManager->flush();

            return $this->redirectToRoute('superficie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('superficie/new.html.twig', [
            'superficie' => $superficie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="superficie_show", methods={"GET"})
     */
    public function show(Superficie $superficie): Response
    {
        
        return $this->render('superficie/show.html.twig', [
            'superficie' => $superficie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="superficie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Superficie $superficie): Response
    {
       
        $form = $this->createForm(SuperficieType::class, $superficie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('superficie_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('superficie/edit.html.twig', [
            'superficie' => $superficie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="superficie_delete", methods={"POST"})
     */
    public function delete(Request $request, Superficie $superficie): Response
    {
       
        if ($this->isCsrfTokenValid('delete'.$superficie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($superficie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('superficie_index', [], Response::HTTP_SEE_OTHER);
    }
}
