<?php

class User
    // implements JsonSerializable
{
    private $id;
    private $email;
    private $firstName;
    private $lastName;
    private $dateOfBirth;
    private $password;
    private $createdAt;
    private $updatedAt;

    public function __construct1()
    {
        $this->id = null;
        $this->email = null;
        $this->firstName = null;
        $this->lastName = null;
        $this->dateOfBirth = null;
        $this->password = null;
        $this->createdAt = null;
        $this->updatedAt = null;
    }


    public function __construct2($id, $email, $firstName, $lastName, $dateOfBirth, $password, $createdAt, $updatedAt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->dateOfBirth = $dateOfBirth;
        $this->password = $password;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    // Deprecated: Return type of User::jsonSerialize() should either be compatible with JsonSerializable::jsonSerialize(): mixed, or the #[\ReturnTypeWillChange] attribute should be used to temporarily suppress the notice
    // public function jsonSerialize()
    // {
    //     return [
    //         'id' => $this->id,
    //         'email' => $this->email,
    //         'firstName' => $this->firstName,
    //         'lastName' => $this->lastName,
    //         'dateOfBirth' => $this->dateOfBirth,
    //         'password' => $this->password,
    //         'createdAt' => $this->createdAt,
    //         'updatedAt' => $this->updatedAt
    //     ];
    // }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        if (strlen($email) < 1) {
            // if the email is less than 1 character, return an error
            throw new Exception("Error: Email must be at least 1 character.");
        } else if (strlen($email) > 255) {
            // if the email is greater than 255 characters, return an error
            throw new Exception("Error: Email must be less than 255 characters.");
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // if the email is not a valid email, return an error
            throw new Exception("Error: Email is not valid.");
        } else {
            $this->email = $email;
        }
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setFirstName($firstName)
    {
        if (strlen($firstName) < 1) {
            // if the first name is less than 1 character, return an error
            throw new Exception("Error: First name must be at least 1 character.");
        } else if (strlen($firstName) > 255) {
            // if the first name is greater than 255 characters, return an error
            throw new Exception("Error: First name must be less than 255 characters.");
        } else {
            $this->firstName = $firstName;
        }
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setLastName($lastName)
    {
        if (strlen($lastName) < 1) {
            // if the last name is less than 1 character, return an error
            throw new Exception("Error: Last name must be at least 1 character.");
        } else if (strlen($lastName) > 255) {
            // if the last name is greater than 255 characters, return an error
            throw new Exception("Error: Last name must be less than 255 characters.");
        } else {
            $this->lastName = $lastName;
        }
    }

    public function getDateOfBirth()
    {
        return $this->dateOfBirth;
    }

    public function setDateOfBirth($dateOfBirth)
    {
        if (strlen($dateOfBirth) < 1) {
            // if the date of birth is less than 1 character, return an error
            throw new Exception("Error: Date of birth must be at least 1 character.");
        } else if (strlen($dateOfBirth) > 255) {
            // if the date of birth is greater than 255 characters, return an error
            throw new Exception("Error: Date of birth must be less than 255 characters.");
        }
        // verify if the age is at lest 16
        $dateOfBirth = new DateTime($dateOfBirth);
        $now = new DateTime();
        $interval = $now->diff($dateOfBirth);
        $age = $interval->y;
        if ($age < 16) {
            // if the age is less than 16, return an error
            throw new Exception("Error: Age must be at least 16.");
        } else {
            $this->dateOfBirth = $dateOfBirth;
        }
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        if (strlen($password) < 8) {
            // if the password is less than 1 character, return an error
            throw new Exception("Error: Password must be at least 8 character.");
        } else if (strlen($password) > 255) {
            // if the password is greater than 255 characters, return an error
            throw new Exception("Error: Password must be less than 255 characters.");
        }
        // verify if the password has at least a number, a lowercase letter, an uppercase letter and a special character
        if (!preg_match("#[0-9]+#", $password)) {
            // if the password does not have at least a number, return an error
            throw new Exception("Error: Password must contain at least 1 number.");
        }
        if (!preg_match("#[a-z]+#", $password)) {
            // if the password does not have at least a lowercase letter, return an error
            throw new Exception("Error: Password must contain at least 1 lowercase letter.");
        }
        if (!preg_match("#[A-Z]+#", $password)) {
            // if the password does not have at least an uppercase letter, return an error
            throw new Exception("Error: Password must contain at least 1 uppercase letter.");
        }
        if (!preg_match("#\W+#", $password)) {
            // if the password does not have at least a special character, return an error
            throw new Exception("Error: Password must contain at least 1 special character.");
        }

        // hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $password;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt)
    {
        if (is_string($createdAt)) {
            $this->createdAt = $createdAt;
        } else {
            throw new Exception("Error: created at must be a string.");
        }
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt)
    {
        if (is_string($updatedAt)) {
            $this->updatedAt = $updatedAt;
        } else {
            throw new Exception("Error: updated at must be a string.");
        }
    }

    public function createUser()
    {
        $mysqli = require '../src/model/Database.php';

        $sql = "INSERT INTO users (email, first_name, last_name, date_of_birth, password) VALUES (?, ?, ?, ?, ?)";
        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            throw new Exception("Error: could not prepare statement.");
        }
        $date = $this->dateOfBirth->format('Y-m-d H:i:s');
        $stmt->bind_param(
            "sssss",
            $this->email,
            $this->firstName,
            $this->lastName,
            $date,
            $this->password
        );

        if ($stmt->execute()) {
            /////////////////////////////////////////////////////////////////// DE SCHIMBAT

            // echo ("Signup successful");
            // header("Location: signup-success.html");
        } else {
            if ($mysqli->errno === 1062) {
                throw new Exception("Error: email already exists.");
            } else
                throw new Exception("Error: could not execute statement.");
        }
    }

    public function checkIfUserExists()
    {
        $mysqli = require '../src/model/Database.php';

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            throw new Exception("Error: could not prepare statement.");
        }

        $stmt->bind_param("s", $this->email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();
            if ($user) {
                return true;
            } else {
                return false;
            }
        } else {
            throw new Exception("Error: could not execute statement.");
        }
    }

    public function checkIfPasswordCorrect($passwordClear)
    {
        $mysqli = require '../src/model/Database.php';

        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $mysqli->stmt_init();

        if (!$stmt->prepare($sql)) {
            throw new Exception("Error: could not prepare statement.");
        }

        $stmt->bind_param("s", $this->email);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            $user = $result->fetch_assoc();
            // echo "User:" . implode(" ", $user);
            // echo "Password:" . $this->password;


            if ($user) {
                if (password_verify($passwordClear, $user['password'])) {
                    echo "Password is correct";
                    return true;
                } else {
                    echo "HEY? Password is incorrect";
                    return false;
                }
            } else {
                throw new Exception("Error: could not execute statement.");
            }
        }
    }
}