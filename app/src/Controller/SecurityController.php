<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @param Request $request
     * @return void
     */
    #[Route('/login', name:'login', methods:'POST')]
    public function login(Request $request)
    {

    }
}
