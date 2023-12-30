<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyAccountController extends AbstractController
{
    private EntityManagerInterface $entityManagerInterface;
    private Response $res;

    private array $hosts = [
        "http://localhost:3000",
        "https://localhost:3000",
        "https://kuchnia-studenta.webace-group.dev"
    ];

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->res = new Response();
        foreach ($this->hosts as $host) {
            $this->res->headers->set('Access-Control-Allow-Origin', $host);
        }
        $this->res->headers->set('Access-Control-Allow-Methods', 'POST');
    }

    #[Route('/api/account/{username}', name: 'app_my_account')]
    public function getUserInfo(string $username, Request $req): Response
    {
        $user = $this->entityManagerInterface->getRepository('App:User')->findOneBy(['username' => $username]);

        if (!$user) {
            $this->res->setContent(json_encode([
                'error' => 'User not found',
                'user' => null,
                'user_recipes' => null
            ]));
            return $this->res;
        }

        $origin = $req->headers->get('origin');
        if (!in_array($origin, $this->hosts)) {
            $this->res->setContent(json_encode([
                'error' => 'Incorrect origin',
                'code' => 403
            ]));
            return $this->res;
        }

        $recipes = $this->entityManagerInterface->getRepository('App:Recipe')->findBy(['user_id' => $user->getId()]);

        if (!$recipes) {
            $recipes = null;
        }

        $this->res->setContent(json_encode([
            'user' => $user->toArray(),
            'user_recipes' => $recipes
        ]));
        return $this->res;
    }

    #[Route('/api/account/{username}/edit', name: 'app_my_account_edit')]
    public function editUser(string $username, Request $req): Response
    {
        $user = $this->entityManagerInterface->getRepository('App:User')->findOneBy(['username' => $username]);

        if (!$user) {
            $this->res->setContent(json_encode([
                'error' => 'User not found',
                'code' => 404
            ]));
            return $this->res;
        }

        $origin = $req->headers->get('origin');
        if (!in_array($origin, $this->hosts)) {
            $this->res->setContent(json_encode([
                'error' => 'Incorrect origin',
                'code' => 403
            ]));
            return $this->res;
        }

        $data = json_decode($req->getContent(), true);

        $image = $data['image'];
        $username = $data['username'];

        if (!$image || !$username) {
            $this->res->setContent(json_encode([
                'error' => 'Incorrect data',
                'code' => 404,
                'icon' => 'error',
                'title' => 'Błąd!',
                'message' => 'Niepoprawne dane!',
            ]));
            return $this->res;
        }

        $user->setImage($image);
        $user->setUsername($username);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $this->res->setContent(json_encode([
            'icon' => 'success',
            'title' => 'Zrobione!',
            'message' => 'Zmiany zostały zapisane!',
            'code' => 200
        ]));
        return $this->res;
    }
}
