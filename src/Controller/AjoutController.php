<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use App\Repository\AchatRepository;
use App\Repository\UsersRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/types" , name="admin_")
 */
class AjoutController extends AbstractController

{
   
    /**
     * @Route("/", name="ajout_index", methods={"GET"})
     */
    public function index(TypeRepository $typeRepository): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('ajout/index.html.twig', [
            'types' => $typeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $type = new Type();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('admin_ajout_index', [], Response::HTTP_SEE_OTHER);

        }

        return $this->render('ajout/new.html.twig', [
            'type' => $type,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_show", methods={"GET"})
     */
    public function show(Type $type): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('ajout/show.html.twig', [
            'type' => $type,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Type $type): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_ajout_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ajout/edit.html.twig', [
            'type' => $type,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="type_delete", methods={"POST"})
     */
    public function delete(Request $request, Type $type): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN');
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_ajout_index', [], Response::HTTP_SEE_OTHER);
    }

    

}
