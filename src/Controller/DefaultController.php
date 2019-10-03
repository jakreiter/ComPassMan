<?php

namespace App\Controller;

use App\Entity\SurveyForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="home_page", methods="GET")
     */
    public function indexAction(): Response
    {
        return $this->redirectToRoute('access_entry_index');
    }

}
