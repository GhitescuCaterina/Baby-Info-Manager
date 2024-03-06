<?php

session_start();
header("Content-Type: application/json; charset=UTF-8");
require_once '../src/core/Controller.php';
require_once '../src/model/User.php';
require_once '../src/lib/vendor/autoload.php';

use \Firebase\JWT\JWT;

class LogIn extends Controller
{
    public function index()
    {
        // if the request method is POST, log in a user
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->logInUser();
        } else {
            // if the request method is not POST, return an error
            echo "Error: Request method not accepted.";
        }
    }

    protected function logInUser()
    {
        // get the request body
        $requestBody = file_get_contents('php://input');
        // decode the request body from JSON into an associative array
        $requestBody = json_decode($requestBody, true);
        $user = new User;

        // validate the email
        if (isset($requestBody['email'])) {
            $email = $requestBody['email'];
            $user->setEmail($email);
        } else {
            // if the email is not set, return an error
            echo "Error: Email is not set.";
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

        // check if the user exists
        $userExists = $user->checkIfUserExists();

        if ($userExists) {
            // if the user exists, check if the password is correct
            $passwordCorrect = $user->checkIfPasswordCorrect($password);

            if ($passwordCorrect) {
                //if the password is correct, log in the user
                $this->logIn($email);

                echo "Success: User logged in.";
            } else {
                // if the password is incorrect, return an error
                echo "Error: Password is incorrect.";
                return;
            }
        } else {
            // if the user does not exist, return an error
            echo "Error: User does not exist.";
            return;
        }
    }

    public function logIn($email)
    {
        $key = 'secret';

        // create a payload for the JWT
        $payload = array(
            "email" => $email
        );

        // create a JWT
        $jwt = JWT::encode($payload, $key, "HS256");

        // set the JWT as a cookie
        setcookie('jwt', $jwt, time() + (60 * 60), '/', 'localhost:3307');

        // redirect to the home page
        // header("Location: http://localhost:3307/");
    }
}