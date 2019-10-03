<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\UserNewType;
use App\Form\UserPassType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin/user")
 */
class UserController extends Controller
{

	/**
	 * @Route("/", name="user_index", methods="GET")
	 */
	public function index(Request $request, UserRepository $userRepository): Response
	{
	    // dump($request->server->getHeaders()['PHP_AUTH_PW']);
		return $this->render('user/index.html.twig', [
			'users' => $userRepository->findAll()
		]);
	}

	/**
	 * @Route("/new", name="user_new", methods="GET|POST")
	 */
	public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
	{
        /**
         * @var User $loggedInUser
         */
        $loggedInUser = $this->getUser();
		$user = new User();
		$form = $this->createForm(UserNewType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {

            $passwordOfLoggedInUser = $request->headers->get('php-auth-pw');
            if (!$passwordOfLoggedInUser) return $this->render('security/no_user_pw.html.twig');
            $userKey =  $loggedInUser->convertPasswordIntoUserKey($passwordOfLoggedInUser);
            $masterKey =  $loggedInUser->decrypt( $loggedInUser->getEncryptedMasterKey(),  $userKey);
            unset($passwordOfLoggedInUser);
            $user->setEncryptedMasterKey( $user->encrypt($masterKey, $user->convertPasswordIntoUserKey($user->getNewPassword()))  );

			$encoded = $encoder->encodePassword($user, $user->getNewPassword());
			$user->setPassword($encoded);
			$em = $this->getDoctrine()->getManager();
			$em->persist($user);
			$em->flush();
			// commented to avoid session -- $this->addFlash('success', 'User created.');
			return $this->redirectToRoute('user_index');
		}
		
		return $this->render('user/new.html.twig', [
			'user' => $user,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{id}", name="user_show", methods="GET")
	 */
	public function show(User $user): Response
	{

	    $key = $user->convertPasswordIntoUserKey('pasikonik2019');

	    $masterKey = $user->decrypt($user->getEncryptedMasterKey(), $key);

		return $this->render('user/show.html.twig', [
			'user' => $user
		]);
	}

	/**
	 * @Route("/{id}/edit", name="user_edit", methods="GET|POST")
	 */
	public function edit(Request $request, User $user): Response
	{
		$form = $this->createForm(UserType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {
			$this->getDoctrine()
				->getManager()
				->flush();
            // commented to avoid session -- $this->addFlash('success', 'Saved.');
			return $this->redirectToRoute('user_index');
		}
		
		return $this->render('user/edit.html.twig', [
			'user' => $user,
			'form' => $form->createView()
		]);
	}

    /**
     * @Route("/me/change_my_pass", name="user_change_my_pass", methods="GET")
     */
    public function changeMyPassword(Request $request): Response
    {

        return $this->redirectToRoute('user_change_pass', [
            'id' => $this->getUser()->getId()
        ]);
    }

	/**
	 * @Route("/{id}/change_pass", name="user_change_pass", methods="GET|POST")
	 */
	public function changePassword(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
	{
		$form = $this->createForm(UserPassType::class, $user);
		$form->handleRequest($request);
		
		if ($form->isSubmitted() && $form->isValid()) {

            $loggedInUser = $this->getUser();

			$plainPassword = $user->getNewPassword();

            $passwordOfLoggedInUser = $request->headers->get('php-auth-pw');
            if (!$passwordOfLoggedInUser) return $this->render('security/no_user_pw.html.twig');
            $userKey =  $loggedInUser->convertPasswordIntoUserKey($passwordOfLoggedInUser);
            $masterKey =  $loggedInUser->decrypt( $loggedInUser->getEncryptedMasterKey(),  $userKey);
            unset($passwordOfLoggedInUser);
            $user->setEncryptedMasterKey( $user->encrypt($masterKey, $user->convertPasswordIntoUserKey($user->getNewPassword()))  );

			$encoded = $encoder->encodePassword($user, $plainPassword);
			$user->setPassword($encoded);
			
			$this->getDoctrine()
				->getManager()
				->flush();

            // commented to avoid session -- $this->addFlash('success', 'Password changed.');
			
			return $this->redirectToRoute('user_change_pass', [
				'id' => $user->getId()
			]);
		}
		
		return $this->render('user/change_pass.html.twig', [
			'user' => $user,
			'form' => $form->createView()
		]);
	}

	/**
	 * @Route("/{id}", name="user_delete", methods="DELETE")
	 */
	public function delete(Request $request, User $user): Response
	{
		if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
			$em = $this->getDoctrine()->getManager();
			$em->remove($user);
			$em->flush();
            // commented to avoid session -- $this->addFlash('danger', 'Deleted.');
		}
		
		return $this->redirectToRoute('user_index');
	}
}
