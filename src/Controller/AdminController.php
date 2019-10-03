<?php

namespace App\Controller;

use App\Entity\SurveyForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_default", methods="GET")
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('user_index');
    }

}
