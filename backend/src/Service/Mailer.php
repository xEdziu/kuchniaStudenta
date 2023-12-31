<?php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private ?PHPMailer $mail;
    private bool $debug;
    private string $host;
    private int $port;
    private string $username;
    private string $password;

    /**
     * @throws Exception
     */
    public function __construct(bool $debug, string $host, int $port, string $username, string $password)
    {
        $this->debug = $debug;
        $this->host = $host;
        $this->port = $port;
        $this->username = $username;
        $this->password = $password;
        if ($this->debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
            $this->mail = new PHPMailer(true);
            $this->mail->SMTPDebug = 3;
            $this->mail->Debugoutput = 'html';
        } else {
            $this->mail = new PHPMailer();
            $this->mail->SMTPDebug = 0;
        }

        $this->mail->CharSet = "UTF-8";
        $this->mail->SMTPAuth = TRUE;
        $this->mail->SMTPAutoTLS = true;
        $this->mail->SMTPSecure = 'ssl';
        $this->mail->SMTPKeepAlive = true;
        $this->mail->Host = $this->host;
        $this->mail->Port = $this->port;
        $this->mail->WordWrap = 50;
        $this->mail->Priority = 1;
        $this->mail->isSMTP();
        $this->mail->isHTML();
        $this->mail->Username = $this->username;
        $this->mail->Password = $this->password;
        $this->mail->setFrom($this->username, 'Adrian z Kuchni Studenta');
        $this->mail->addReplyTo($this->username, 'Adrian z Kuchni Studenta');
        $this->mail->From = $this->username;
        $this->mail->FromName = "Adrian z Kuchni Studenta";
    }

    /**
     * Function to send activation mail
     *
     * @param string $email
     * Email address to send activation mail
     * @param string $hash
     * Hash to activate account
     * @return array
     * Response array: icon, title, message, footer, data [error, code]
     */
    public function sendActivationMail(string $email, string $hash): array
    {
        try {
            $this->mail->addAddress($email);
            $this->mail->Subject = "Aktywacja konta | Kuchnia Studenta";
            $this->mail->Body = "Witaj, aby aktywować konto kliknij w poniższy link: <br><br><a href='http://localhost:3000/activate/$hash'>Aktywuj konto</a>";
            $this->mail->AltBody = "Witaj, aby aktywować konto kliknij w poniższy link: <br><br><a href='http://localhost:3000/activate/$hash'>Aktywuj konto</a>";
            if ($this->mail->send()) {
                $response = [
                    "icon" => "success",
                    "title" => "Wysłano maila aktywacyjnego",
                    "message" => "Sprawdź swoją skrzynkę odbiorczą",
                    "data" => [
                        "error" => null,
                        "code" => 0,
                    ]
                ];
            } else {
                $response = [
                    "icon" => "error",
                    "title" => "Nie udało się wysłać maila aktywacyjnego",
                    "message" => "Skontaktuj się z administratorem",
                    "footer" => "Kod błędu: 901",
                    "data" => [
                        "error" => $this->mail->ErrorInfo,
                        "code" => 901,
                    ]
                ];
            }
        } catch (Exception $e) {
            $response = [
                "icon" => "error",
                "title" => "Nie udało się wysłać maila aktywacyjnego",
                "message" => "Skontaktuj się z administratorem",
                "footer" => "Kod błędu: 902",
                "data" => [
                    "error" => $e->getMessage(),
                    "code" => 902,
                ]
            ];
        }
        return $response;
    }

    /**
     * Function to send contact mail
     *
     * @param string $email
     * Email address to send contact mail
     * @param string $title
     * Title of contact mail
     * @param string $message
     * Message of contact mail
     * @return array
     * Response array: icon, title, message, footer, data [error, code]
     */
    public function sendContactMail(string $email, string $title, string $message): array
    {
        try {
            $this->mail->addAddress($this->username);
            $this->mail->Subject = "Kontakt | Kuchnia Studenta";
            $this->mail->Body = "Wiadomość od: $email <br><br> Temat: $title <br><br> Treść: $message";
            $this->mail->AltBody = "Wiadomość od: $email <br><br> Temat: $title <br><br> Treść: $message";
            if ($this->mail->send()) {
                $response = [
                    "icon" => "success",
                    "title" => "Wysłano maila",
                    "message" => "Niedługo otrzymasz odpowiedź na podany adres email",
                    "data" => [
                        "error" => null,
                        "code" => 0,
                    ]
                ];
            } else {
                $response = [
                    "icon" => "error",
                    "title" => "Nie udało się wysłać maila",
                    "message" => "Skontaktuj się z administratorem",
                    "footer" => "Kod błędu: 901",
                    "data" => [
                        "error" => $this->mail->ErrorInfo,
                        "code" => 901,
                    ]
                ];
            }
        } catch (Exception $e) {
            $response = [
                "icon" => "error",
                "title" => "Nie udało się wysłać maila",
                "message" => "Skontaktuj się z administratorem",
                "footer" => "Kod błędu: 902",
                "data" => [
                    "error" => $e->getMessage(),
                    "code" => 902,
                ]
            ];
        }
        return $response;
    }
    public function sendResetPasswordMail(string $email, string $reset_hash): array
    {
        try {
            $this->mail->addAddress($email);
            $this->mail->Subject = "Reset hasła | Kuchnia Studenta";
            $this->mail->Body = "Witaj, aby zresetować hasło kliknij w poniższy link: <br><br><a href='http://localhost:3000/reset-password/$reset_hash'>Resetuj hasło</a>";
            $this->mail->AltBody = "Witaj, aby zresetować hasło kliknij w poniższy link: <br><br><a href='http://localhost:3000/reset-password/$reset_hash'>Resetuj hasło</a>";
            if ($this->mail->send()) {
                $response = [
                    "icon" => "success",
                    "title" => "Wysłano maila resetującego hasło",
                    "message" => "Sprawdź swoją skrzynkę odbiorczą",
                    "data" => [
                        "error" => null,
                        "code" => 0,
                    ]
                ];
            } else {
                $response = [
                    "icon" => "error",
                    "title" => "Nie udało się wysłać maila resetującego hasło",
                    "message" => "Skontaktuj się z administratorem",
                    "footer" => "Kod błędu: 901",
                    "data" => [
                        "error" => $this->mail->ErrorInfo,
                        "code" => 901,
                    ]
                ];
            }
        } catch (Exception $e) {
            $response = [
                "icon" => "error",
                "title" => "Nie udało się wysłać maila resetującego hasło",
                "message" => "Skontaktuj się z administratorem",
                "footer" => "Kod błędu: 902",
                "data" => [
                    "error" => $e->getMessage(),
                    "code" => 902,
                ]
            ];
        }
        return $response;
    }

    public static function postAutoloadDump(): void
    {
        require_once __DIR__ . '/../../vendor/autoload.php';
    }

}
