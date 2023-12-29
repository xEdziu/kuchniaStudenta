<?php

namespace App\Controller;

use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    private Mailer $mailer;

    private array $hosts = [
        "http://localhost:3000",
        "https://localhost:3000",
        "https://kuchnia-studenta.webace-group.dev"
    ];

    public function __construct(Mailer $mailer) {
        $this->mailer = $mailer;
    }
    #[Route('/api/contact', name: 'app_contact', methods: ['POST'])]
    public function contact(Request $request): Response
    {
        $res = new Response();
        $res->headers->set('Access-Control-Allow-Origin', $this->hosts);
        $res->headers->set('Access-Control-Allow-Methods', 'POST');

        $data = json_decode($request->getContent(), true);

        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $title = $data['title'];
        $message = $data['message'];

        if (!$email) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Niepoprawny adres email",
            ];
            $res->setContent(json_encode($response));
            return $res;
        }

        for ($i = 0; $i < 3; $i++) {
            $response = $this->mailer->sendContactMail($email, $title, $message);
            if ($response['success']) {
                $res->setContent(json_encode($response));
                return $res;
            }
        }

        $response = [
            "icon" => "error",
            "title" => "Nie udało się wysłać maila",
            "message" => "Skontaktuj się z administratorem",
            "footer" => "Kod błędu: 903",
            "data" => [
                "error" => $response['data']['error'],
                "code" => 903,
            ]
        ];

        $res->setContent(json_encode($response));
        return $res;
    }
}
