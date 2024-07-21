<?php
include('header/connection.php');
include('header/header.php');
include('header/navuser.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $feedback = htmlspecialchars($_POST["message"]);
    $name = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["email"]);

    // Validate feedback
    if (empty($feedback)) {
        echo '<span class="error">Please enter your feedback.</span>';
    } else {
        // Insert feedback into the database
        $sql = "INSERT INTO feedback (`e-mail`, `name`, `message`) VALUES (?, ?, ?)";
        $stmt = $db->prepare($sql);

        if ($stmt) {
            $stmt->bindParam(1, $email, PDO::PARAM_STR);
            $stmt->bindParam(2, $name, PDO::PARAM_STR);
            $stmt->bindParam(3, $feedback, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>alert('Problem submitted!: '); window.location.href='home.php';</script>";
            } else {
                echo '<span class="error">Error storing feedback in the database: ' . $stmt->errorInfo()[2] . '</span>';
            }

            $stmt->closeCursor();
        } else {
            echo '<span class="error">Error preparing statement: ' . $db->errorInfo()[2] . '</span>';
        }
    }

    // Close the database connection
    $db = null;
    exit();
}
?>


<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: space-around;
            flex-direction: column;
            height: 100vh;
            background-color: #bde5d2;
            font-family: 'Poppins', sans-serif;
        }

        .form-box {
            background-color: #fff;
            box-shadow: 0 0 10px rgba(36, 67, 40, 0.8);
            padding: 15px;
            border-radius: 8px;
            width: 500px;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        input, textarea {
            width: 100%;
            padding: 8px;
            margin-bottom: 12px;
            box-sizing: border-box;
            border-radius: 10px;
        }

        button {
            background-color: #368b44;
            color: #fff;
            padding: 10px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
            transition: .2s linear;
        }

        button:hover {
            background-color: #0a6808;
            transform: translateY(-10px);
        }
    </style>
</head>
<body>
    <div class='fb-form'>
        <form action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>' method='post' class='form-group'>
            <h2>Tell us what you think</h2>
            <input class='form-control' placeholder='Name' type='text' name='name'>
            <input class='form-control' placeholder='Email' type='text' name='email'>
            <textarea class='form-control' id='fb-comment' name='message' placeholder='Tell us what you think'></textarea>
            <input class='form-control btn btn-primary' type='submit' name='submit'>
        </form>
    </div>
</body>
</html>

