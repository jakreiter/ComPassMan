<?php

namespace App\Controller;

use App\Entity\AccessEntryCategory;
use App\Form\AccessEntryCategoryType;
use App\Repository\AccessEntryCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/access_entry_category")
 */
class AccessEntryCategoryController extends AbstractController
{
    /**
     * @Route("/", name="access_entry_category_index", methods={"GET"})
     */
    public function index(AccessEntryCategoryRepository $accessEntryCategoryRepository): Response
    {
        return $this->render('access_entry_category/index.html.twig', [
            'access_entry_categories' => $accessEntryCategoryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="access_entry_category_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $accessEntryCategory = new AccessEntryCategory();
        $form = $this->createForm(AccessEntryCategoryType::class, $accessEntryCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accessEntryCategory);
            $entityManager->flush();

            return $this->redirectToRoute('access_entry_category_index');
        }

        return $this->render('access_entry_category/new.html.twig', [
            'access_entry_category' => $accessEntryCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_entry_category_show", methods={"GET"})
     */
    public function show(AccessEntryCategory $accessEntryCategory): Response
    {
        return $this->render('access_entry_category/show.html.twig', [
            'access_entry_category' => $accessEntryCategory,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="access_entry_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AccessEntryCategory $accessEntryCategory): Response
    {
        $form = $this->createForm(AccessEntryCategoryType::class, $accessEntryCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('access_entry_category_index', [
                'id' => $accessEntryCategory->getId(),
            ]);
        }

        return $this->render('access_entry_category/edit.html.twig', [
            'access_entry_category' => $accessEntryCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_entry_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AccessEntryCategory $accessEntryCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accessEntryCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessEntryCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('access_entry_category_index');
    }
}
