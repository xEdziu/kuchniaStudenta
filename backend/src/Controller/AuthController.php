<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\Mailer;
use App\Service\RecaptchaService;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    private EntityManagerInterface $entityManagerInterface;
    private Mailer $mailer;
    private RecaptchaService $recaptchaService;
    private Response $res;

    private array $hosts = [
        "http://localhost:3000",
        "https://localhost:3000",
        "https://kuchnia-studenta.webace-group.dev"
    ];

    public function __construct(
        EntityManagerInterface $entityManagerInterface,
        Mailer $mailer,
        RecaptchaService $recaptchaService
    ) {
        $this->entityManagerInterface = $entityManagerInterface;
        $this->mailer = $mailer;
        $this->recaptchaService = $recaptchaService;
        $this->res = new Response();
        foreach ($this->hosts as $host) {
            $this->res->headers->set('Access-Control-Allow-Origin', $host);
        }
        $this->res->headers->set('Access-Control-Allow-Methods', 'POST');
    }

    #[Route('/api/register', name: 'app_auth_register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['name'];
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $pwd1 = $data['pwd1'];
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

        if (strlen($username) < 3 || strlen($username) > 50) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nazwa użytkownika musi mieć od 3 do 50 znaków",

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

        if (strlen($pwd1) < 8 || strlen($pwd1) > 50) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Hasło musi mieć od 8 do 50 znaków",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['username' => $username]);


        if ($user) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nazwa użytkownika jest już zajęta",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Email jest już zajęty",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $new_password = password_hash($pwd1, PASSWORD_ARGON2ID);

        try {
            $activation_hash = bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się wygenerować hasha aktywacyjnego",
                "footer" => "Skontaktuj się z administratorem",
                "data" => [
                    "error" => $e->getMessage()
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        if (!password_verify($pwd1, $new_password)) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się zahashować hasła",
                "footer" => "Skontaktuj się z administratorem",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($new_password);
        $user->setImage('default.webp');
        $user->setHash($activation_hash);
        $user->setActive(false);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();


        for ($i = 0; $i < 3; $i++) {
            $response = $this->mailer->sendActivationMail($email, $activation_hash);
            if ($response['data']['code'] === 0) {
                break;
            }
        }

        $response = [
            "icon" => "success",
            "title" => "Sukces",
            "message" => "Konto zostało utworzone",
            "footer" => "Sprawdź swoją skrzynkę odbiorczą",
            "data" => [
                "error" => null,
                "code" => 0,
            ]
        ];


        $this->res->setContent(json_encode($response));
        return $this->res;
    }

    #[Route('/api/activate/{activate}', name: 'app_auth_activate', methods: ['POST'])]
    public function activate(string $activate): Response
    {
        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy([
            'hash' => $activate,
            'active' => false
        ]);

        if (!$user) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Link aktywacyjny jest nieważny",
                "data" => [
                    "error" => null,
                    "code" => 403,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user->setActive(true);

        try {
            $user->setHash(bin2hex(random_bytes(32)));
        } catch (RandomException $e) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się wygenerować hasha aktywacyjnego",
                "footer" => "Skontaktuj się z administratorem",
                "data" => [
                    "error" => $e->getMessage(),
                    "code" => 502,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $response = [
            "icon" => "success",
            "title" => "Sukces",
            "message" => "Konto zostało aktywowane",
            "footer" => "Możesz się zalogować",
            "data" => [
                "error" => null,
                "code" => 0,
            ]
        ];

        $this->res->setContent(json_encode($response));
        return $this->res;
    }

    #[Route('/api/login', name: 'app_auth_login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];
        $token = $data['token'];

        if (!$this->recaptchaService->verify($token)) {
            $response = [
                "icon" => "warning",
                "title" => "Botom wstęp wzbroniony",
                "message" => "ReCaptcha nie została zweryfikowana",
                "footer" => "Spróbuj ponownie",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Niepoprawna dane",
                "footer" => "Spróbuj ponownie",
                "data" => [
                    "error" => null,
                    "code" => 401,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        if (!password_verify($password, $user->getPassword())) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Niepoprawne dane",
                "footer" => "Spróbuj ponownie",
                "data" => [
                    "error" => null,
                    "code" => 401,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        if (!$user->isActive()) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Konto nie zostało aktywowane",
                "footer" => "Sprawdź swoją skrzynkę odbiorczą",
                "data" => [
                    "error" => null,
                    "code" => 403,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $response = [
            "icon" => "success",
            "title" => "Sukces",
            "message" => "Zalogowano pomyślnie",
            "footer" => "Witaj, " . $user->getUsername(),
            "data" => [
                "error" => null,
                "code" => 0,
                "username" => $user->getUsername()
            ]
        ];

        $this->res->setContent(json_encode($response));
        return $this->res;
    }

    #[Route('/api/resetPassword', name: 'app_auth_reset_password', methods: ['POST'])]
    public function resetPassword(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
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

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['email' => $email]);

        if (!$user) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Niepoprawna dane",
                "footer" => "Spróbuj ponownie",
                "data" => [
                    "error" => null,
                    "code" => 401,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        try {
            $reset_hash = bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się wygenerować hasha resetującego",
                "footer" => "Skontaktuj się z administratorem",
                "data" => [
                    "error" => $e->getMessage(),
                    "code" => 502,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user->setHash($reset_hash);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        for ($i = 0; $i < 3; $i++) {
            $response = $this->mailer->sendResetPasswordMail($email, $reset_hash);
            if ($response['data']['code'] === 0) {
                break;
            }
        }

        $response = [
            "icon" => "success",
            "title" => "Sukces",
            "message" => "Wysłano maila resetującego",
            "footer" => "Sprawdź swoją skrzynkę odbiorczą",
            "data" => [
                "error" => null,
                "code" => 0,
            ]
        ];

        $this->res->setContent(json_encode($response));
        return $this->res;
    }

    #[Route('/api/changePassword', name: 'app_auth_change_password', methods: ['POST'])]
    public function changePassword(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $password = $data['password'];
        $hash = $data['hash'];
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

        if (strlen($password) < 8 || strlen($password) > 50) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Hasło musi mieć od 8 do 50 znaków",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['hash' => $hash]);

        if (!$user) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Link resetujący jest nieważny",
                "data" => [
                    "error" => null,
                    "code" => 403,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        if (password_verify($password, $user->getPassword())) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Hasło nie może być takie samo jak poprzednie",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $new_password = password_hash($password, PASSWORD_ARGON2ID);

        if (!password_verify($password, $new_password)) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się zahashować hasła",
                "footer" => "Skontaktuj się z administratorem",
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user->setPassword($new_password);

        try {
            $new_hash = bin2hex(random_bytes(32));
        } catch (RandomException $e) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się wygenerować hasha resetującego",
                "footer" => "Skontaktuj się z administratorem",
                "data" => [
                    "error" => $e->getMessage(),
                    "code" => 502,
                ]
            ];
            $this->res->setContent(json_encode($response));
            return $this->res;
        }

        $user->setHash($new_hash);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $response = [
            "icon" => "success",
            "title" => "Sukces",
            "message" => "Hasło zostało zmienione",
            "footer" => "Możesz się zalogować",
            "data" => [
                "error" => null,
                "code" => 0,
            ]
        ];

        $this->res->setContent(json_encode($response));
        return $this->res;
    }
}
