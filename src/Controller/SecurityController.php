<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends Controller
{

	/**
	 * @Route("/admin/login", name="admin_login")
	 */
	public function login(Request $request, AuthenticationUtils $authenticationUtils)
	{

	}
}
