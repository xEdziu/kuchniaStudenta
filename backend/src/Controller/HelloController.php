<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    #[Route('/api/hello', name: 'hello')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to our great page! - api/hello',
            'path' => 'src/Controller/HelloController.php',
        ]);
    }
}
