<?php

namespace App\Controller;

use App\Entity\Forme;
use App\Form\FormeType;
use App\Repository\FormeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/forme")
 */
class FormeController extends AbstractController
{
    /**
     * @Route("/", name="forme_index", methods={"GET"})
     */
    public function index(FormeRepository $formeRepository): Response
    {
        return $this->render('forme/index.html.twig', [
            'formes' => $formeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="forme_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $forme = new Forme();
        $form = $this->createForm(FormeType::class, $forme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($forme);
            $entityManager->flush();

            return $this->redirectToRoute('forme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forme/new.html.twig', [
            'forme' => $forme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="forme_show", methods={"GET"})
     */
    public function show(Forme $forme): Response
    {
        return $this->render('forme/show.html.twig', [
            'forme' => $forme,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="forme_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Forme $forme): Response
    {
        $form = $this->createForm(FormeType::class, $forme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('forme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('forme/edit.html.twig', [
            'forme' => $forme,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="forme_delete", methods={"POST"})
     */
    public function delete(Request $request, Forme $forme): Response
    {
        if ($this->isCsrfTokenValid('delete'.$forme->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($forme);
            $entityManager->flush();
        }

        return $this->redirectToRoute('forme_index', [], Response::HTTP_SEE_OTHER);
    }
}
