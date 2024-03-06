<?php

session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once '../src/core/Controller.php';
require_once '../src/model/User.php';
require_once '../src/lib/vendor/autoload.php';

use \Firebase\JWT\JWT;

//use \Firebase\JWT\Key;

class SignUp extends Controller
{
    // class responsible for creating a user with an email, first name, last name, date of birth and password

    public function index()
    {
        // if the request method is POST, create a user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->insertUser();
        } else {
            // if the request method is not POST, return an error
            echo "Error: Request method not accepted.";
        }
        // // if the request method is POST, create a user
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $this->insertUser();
        // } else {
        //     // if the request method is not POST, render the form
        //     $this->renderForm();
        // }
    }

    protected function insertUser()
    {
        // get the request body
        $requestBody = file_get_contents('php://input');
        // decode the request body from JSON into an associative array
        $requestBody = json_decode($requestBody, true);
        $user = new User;

        echo "requestBody: ";

        // validate the email
        if (isset($requestBody['email'])) {
            $email = $requestBody['email'];
            $user->setEmail($email);
        } else {
            // if the email is not set, return an error
            echo "Error: Email is not set.";
            return;
        }

        // validate the first name
        if (isset($requestBody['firstName'])) {
            $firstName = $requestBody['firstName'];
            $user->setFirstName($firstName);
        } else {
            // if the first name is not set, return an error
            echo "Error: First name is not set.";
            return;
        }

        // validate the last name
        if (isset($requestBody['lastName'])) {
            $lastName = $requestBody['lastName'];
            $user->setLastName($lastName);
        } else {
            // if the last name is not set, return an error
            echo "Error: Last name is not set.";
            return;
        }

        // validate the date of birth
        if (isset($requestBody['dateOfBirth'])) {
            $dateOfBirth = $requestBody['dateOfBirth'];
            $user->setDateOfBirth($dateOfBirth);
        } else {
            // if the date of birth is not set, return an error
            echo "Error: Date of birth is not set.";
            return;
        }

        // validate the password
        if (isset($requestBody['password'])) {
            $password = $requestBody['password'];
            $user->setPassword($password);
        } else {
            // if the password is not set, return an error
            echo "Error: Password is not set.";
            return;
        }

        // validate the confirm password
        if (isset($requestBody['confirmPassword'])) {
            $confirmPassword = $requestBody['confirmPassword'];
            if ($confirmPassword !== $password) {
                echo "Error: Passwords do not match.";
                return;
            }
        } else {
            // if the confirm password is not set, return an error
            echo "Error: Confirm password is not set.";
            return;
        }

        // nice to check if the reference code is valid, exists, or is unused
        // // handle the reference code
        // // assuming User class has setReferenceCode() method
        // if (isset($requestBody['referenceCode'])) {
        //     $referenceCode = $requestBody['referenceCode'];
        //     $user->setReferenceCode($referenceCode);
        // } else {
        //     // if the reference code is not set, return an error
        //     echo "Error: Reference code is not set.";
        //     return;
        // }

        echo "user: ";
        // create the user
        $user->createUser();

        // return a success message
        echo "User created successfully.";

        // create the JWT
        $key = 'secret';
        $payload = array(
            // "iat" => time(),
            // "exp" => time() + (60 * 60),
            // "userId" => $user->getId(),
            "email" => $user->getEmail(),
            // "firstName" => $user->getFirstName(),
            // "lastName" => $user->getLastName(),
            // "dateOfBirth" => $user->getDateOfBirth(),
            // "password" => $user->getPassword()
        );

        $jwt = JWT::encode($payload, $key, 'HS256');
        print $jwt;
        echo $jwt;

        // // set the JWT as a cookie
        setcookie('jwt', $jwt, time() + (60 * 60), '/', 'localhost:3307');

        // return the JWT
        //echo json_encode($jwt);

        // list($headersB64, $payload, $sig) = explode('.', $jwt);
        // $decoded = json_decode(base64_decode($headersB64), true);

        // echo "Decode:\n" . print_r($decoded, true) . "\n";
    }

    // protected function insertUser()
    // {
    //     $user = new User;

    //     // validate the email
    //     if (isset($_POST['email'])) {
    //         $email = $_POST['email'];
    //         $user->setEmail($email);
    //     } else {
    //         // if the email is not set, return an error
    //         echo "Error: Email is not set.";
    //         return;
    //     }

    //     // validate the first name
    //     if (isset($_POST['firstName'])) {
    //         $firstName = $_POST['firstName'];
    //         $user->setFirstName($firstName);
    //     } else {
    //         // if the first name is not set, return an error
    //         echo "Error: First name is not set.";
    //         return;
    //     }

    //     // validate the last name
    //     if (isset($_POST['lastName'])) {
    //         $lastName = $_POST['lastName'];
    //         $user->setLastName($lastName);
    //     } else {
    //         // if the last name is not set, return an error
    //         echo "Error: Last name is not set.";
    //         return;
    //     }

    //     // validate the date of birth
    //     if (isset($_POST['dateOfBirth'])) {
    //         $dateOfBirth = $_POST['dateOfBirth'];
    //         $user->setDateOfBirth($dateOfBirth);
    //     } else {
    //         // if the date of birth is not set, return an error
    //         echo "Error: Date of birth is not set.";
    //         return;
    //     }

    //     // validate the password
    //     if (isset($_POST['password'])) {
    //         $password = $_POST['password'];
    //         $user->setPassword($password);
    //     } else {
    //         // if the password is not set, return an error
    //         echo "Error: Password is not set.";
    //         return;
    //     }

    //     // validate the confirm password
    //     if (isset($_POST['confirmPassword'])) {
    //         $confirmPassword = $_POST['confirmPassword'];
    //         if ($confirmPassword !== $password) {
    //             echo "Error: Passwords do not match.";
    //             return;
    //         }
    //     } else {
    //         // if the confirm password is not set, return an error
    //         echo "Error: Confirm password is not set.";
    //         return;
    //     }

    //     // create the user
    //     $user->createUser();

    //     // return a success message
    //     echo "User created successfully.";

    //     // return the user
    //     // echo json_encode($user);
    // }

    // public function renderForm()
    // {
    //     header("Content-Type: text/html; charset=UTF-8");
    //     require_once '../src/view/sign-up.html';
    // }
}