<?php

namespace App\Controller;

use App\Service\Mailer;
use App\Service\RecaptchaService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

    private Mailer $mailer;

    private Response $res;
    private RecaptchaService $recaptchaService;

    private array $hosts = [
        "http://localhost:3000",
        "https://localhost:3000",
        "https://kuchnia-studenta.webace-group.dev"
    ];

    public function __construct(Mailer $mailer, RecaptchaService $recaptchaService)
    {
        $this->mailer = $mailer;
        $this->recaptchaService = $recaptchaService;
        $this->res = new Response();
        foreach ($this->hosts as $host) {
            $this->res->headers->set('Access-Control-Allow-Origin', $host);
        }
        $this->res->headers->set('Access-Control-Allow-Methods', 'POST');
    }
    #[Route('/api/contact', name: 'app_contact', methods: ['POST'])]
    public function contact(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $title = $data['title'];
        $message = $data['message'];
        $token = $data['token'];

        if (!$this->recaptchaService->verify($token)) {
            $response = [
                "icon" => "warning",
                "title" => "Botom wstęp wzbroniony",
                "message" => "ReCaptcha nie została zweryfikowana",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        if (!$email) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Niepoprawny adres email",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        for ($i = 0; $i < 3; $i++) {
            $response = $this->mailer->sendContactMail($email, $title, $message);
            if ($response['icon'] == "success") {
                $this->res->setContent(json_encode($response));
                return $this->res;
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

        $this->res->setContent(json_encode($response));
        return $this->res;
    }
}
