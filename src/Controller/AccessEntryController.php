<?php

namespace App\Controller;

use App\Entity\AccessEntry;
use App\Entity\User;
use App\Form\AccessEntryType;
use App\Repository\AccessEntryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/access_entry")
 */
class AccessEntryController extends AbstractController
{
    /**
     * @Route("/", name="access_entry_index", methods={"GET"})
     */
    public function index(AccessEntryRepository $accessEntryRepository): Response
    {
        return $this->render('access_entry/index.html.twig', [
            'access_entries' => $accessEntryRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="access_entry_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $accessEntry = new AccessEntry();
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $this->getUser();
        $accessEntry->setCreatedBy($loggedInUser);
        $accessEntry->setModifiedBy($loggedInUser);
        $form = $this->createForm(AccessEntryType::class, $accessEntry);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $passwordOfLoggedInUser = $request->headers->get('php-auth-pw');
            if (!$passwordOfLoggedInUser) return $this->render('security/no_user_pw.html.twig');
            $userKey =  $loggedInUser->convertPasswordIntoUserKey($passwordOfLoggedInUser);
            $masterKey =  $loggedInUser->decrypt( $loggedInUser->getEncryptedMasterKey(),  $userKey);
            unset($passwordOfLoggedInUser);

            $accessEntry->encryptClassifiedFields($masterKey);

            $entityManager->persist($accessEntry);
            $entityManager->flush();

            return $this->redirectToRoute('access_entry_index');
        }

        return $this->render('access_entry/new.html.twig', [
            'access_entry' => $accessEntry,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_entry_show", methods={"GET"})
     */
    public function show(Request $request, AccessEntry $accessEntry): Response
    {
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $this->getUser();
        $passwordOfLoggedInUser = $request->headers->get('php-auth-pw');
        if (!$passwordOfLoggedInUser) return $this->render('security/no_user_pw.html.twig');

        $userKey =  $loggedInUser->convertPasswordIntoUserKey($passwordOfLoggedInUser);

        $masterKey =  $loggedInUser->decrypt( $loggedInUser->getEncryptedMasterKey(),  $userKey);

        unset($passwordOfLoggedInUser);
        // if ('dev' == $this->getParameter('kernel.environment')) dump(bin2hex($masterKey));
        $accessEntry->decryptClassifiedFields( $masterKey );

        return $this->render('access_entry/show.html.twig', [
            'access_entry' => $accessEntry,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="access_entry_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AccessEntry $accessEntry): Response
    {
        $loggedInUser = $this->getUser();
        $passwordOfLoggedInUser = $request->headers->get('php-auth-pw');
        if (!$passwordOfLoggedInUser) return $this->render('security/no_user_pw.html.twig');
        $userKey =  $loggedInUser->convertPasswordIntoUserKey($passwordOfLoggedInUser);
        $masterKey =  $loggedInUser->decrypt( $loggedInUser->getEncryptedMasterKey(),  $userKey);
        unset($passwordOfLoggedInUser);
        $accessEntry->decryptClassifiedFields( $masterKey );

        $form = $this->createForm(AccessEntryType::class, $accessEntry);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $accessEntry->setModifiedBy($loggedInUser);
            $accessEntry->encryptClassifiedFields($masterKey);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('access_entry_index', [
                'id' => $accessEntry->getId(),
            ]);
        }

        return $this->render('access_entry/edit.html.twig', [
            'access_entry' => $accessEntry,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="access_entry_delete", methods={"DELETE"})
     */
    public function delete(Request $request, AccessEntry $accessEntry): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accessEntry->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessEntry);
            $entityManager->flush();
        }

        return $this->redirectToRoute('access_entry_index');
    }
}
