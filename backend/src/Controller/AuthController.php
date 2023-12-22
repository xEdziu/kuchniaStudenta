<?php

namespace App\Controller;

use App\Entity\User;
//use App\Service\ImageConverter;
use App\Service\Mailer;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{

    private EntityManagerInterface $entityManagerInterface;
//    private ImageConverter $imageConverter;
    private Mailer $mailer;

    public function __construct(EntityManagerInterface $entityManagerInterface,
//                    ImageConverter $imageConverter,
                    Mailer $mailer)
    {
        $this->entityManagerInterface = $entityManagerInterface;
//        $this->imageConverter = $imageConverter;
        $this->mailer = $mailer;
    }

    #[Route('/api/register', name: 'app_auth_register', methods: ['POST'])]
    public function register(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $username = $data['username'];
        $email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);
        $pwd1 = $data['pwd1'];
        $pwd2 = $data['pwd2'];
        $image = $request->files->get('image');

        if (strlen($username) < 3 || strlen($username) > 50) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nazwa użytkownika musi mieć od 3 do 50 znaków",

            ];
            return $this->json($response);
        }

        if (!$email) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Niepoprawny adres email",
            ];
            return $this->json($response);
        }

        if (strlen($pwd1) < 8 || strlen($pwd1) > 50) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Hasło musi mieć od 8 do 50 znaków",
            ];
            return $this->json($response);
        }

        if ($pwd1 !== $pwd2) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Hasła nie są takie same",
            ];
            return $this->json($response);
        }

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['username' => $username]);

        if ($user) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nazwa użytkownika jest już zajęta",
            ];
            return $this->json($response);
        }

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            $response = [
                "icon" => "warning",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Email jest już zajęty",
            ];
            return $this->json($response);
        }


        if ($image == null) {
            $filename = 'default.webp';
        } else {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $filename = $originalFilename.'-'.uniqid().'.'.$image->guessExtension();
            $destination = '../../../frontend/public/src/assets/images/users/';
            $image->move($destination, $filename);
        }


//        if ($image['error'] == 0) {
//            $filename = $image['name'] . '_' . uniqid() . '.webp';
//            $convert = $this->imageConverter->convertToWebp($image['pathname'],
//                '../../../frontend/public/src/assets/images/users/'. $filename);
//        }
//
//        if ($convert != null && !$convert['success']) {
//            $response = [
//                "icon" => "error",
//                "title" => "Chyba coś poszło nie tak",
//                "message" => "Nie udało się przesłać zdjęcia",
//                "footer" => $convert['error'],
//            ];
//            return $this->json($response);
//        }
//
//        if ($convert != null && $convert['success']) {
//            $filename = $convert['path'];
//        }

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
            return $this->json($response);
        }

        if (!password_verify($pwd1, $new_password)) {
            $response = [
                "icon" => "error",
                "title" => "Chyba coś poszło nie tak",
                "message" => "Nie udało się zahashować hasła",
                "footer" => "Skontaktuj się z administratorem",
            ];
            return $this->json($response);
        }

        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPassword($new_password);
        $user->setImage($filename);
        $user->setHash($activation_hash);
        $user->setActive(false);

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();


        for ($i = 0; $i < 3; $i++){
            $response = $this->mailer->sendActivationMail($email, $activation_hash);
            if ($response['data']['code'] === 0){
                break;
            }
        }

        return $this->json($response);
    }

    #[Route('/api/activate', name: 'app_auth_activate', methods: ['POST'])]
    public function activate(string $activate) : JsonResponse {
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
            return $this->json($response);
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
            return $this->json($response);
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

        return $this->json($response);
    }

    #[Route('/api/login', name: 'app_auth_login', methods: ['POST'])]
    public function login(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $password = $data['password'];

        $user = $this->entityManagerInterface->getRepository(User::class)->findOneBy(['username' => $email]);

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
            return $this->json($response);
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
            return $this->json($response);
        }

        if (!$user->getActive()) {
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
            return $this->json($response);
        }

        $response = [
            "icon" => "success",
            "title" => "Sukces",
            "message" => "Zalogowano pomyślnie",
            "footer" => "Witaj, " . $user->getUsername(),
            "data" => [
                "error" => null,
                "code" => 0,
            ]
        ];

        return $this->json($response);
    }
}
