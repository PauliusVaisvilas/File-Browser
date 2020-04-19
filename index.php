<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="stilius.css">
    <title>Namu darbas</title>
</head>

<body>
    <header class="container">
        <div style="width: 60%;">
            BIT2020 - Failu narsykle
        </div>
        <div style="font-weight: bolder; " class="fa fa-home">
            Paulius Vaisvilas
        </div>
        <div class="fa fa-search">
        </div>
    </header>
    <div class="loginas">
        <div style="border-bottom: lightslategray dotted 3px; font-weight: bolder; font-size: 25px;">
            Login
        </div>
        <div>
            <form action="" method="POST">
                <div class="email">
                    <label for="username">Username</label>
                    <input class="btn btn-info btn-l white" type="text" class="form-control" name="username" placeholder="paulius">
                </div>
                <div class="email">
                    <label for="password">Password</label>
                    <input class="btn btn-info btn-l, white" type="password" class="form-control" name="password" placeholder="test">
                </div>
                <div class="login">
                    <button class="btn btn-info btn-l" type="submit" name="login" >Login</button>
                    <div class="login2">
                        <a href="https://www.google.lt/" style="text-decoration: none; color: turquoise;">Forgotten
                            password?</a>
                    </div>
                </div>
                <?php
                //login
                session_start();
                $message = '';
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    if (isset($_POST['login'])) {
                        if ($_POST['username'] == "paulius" && $_POST['password'] == "test") {
                            $_SESSION['logged_in'] = true;
                            $_SESSION['timeout'] = time();
                            $_SESSION['username'] = 'paulius';
                            header("Location: codes.php");
                        } else {
                            $message = "Wrong username or password";
                        }
                    }
                }
                echo  "<p class=\"alertmsg \">$message</p>";

                ?>
            </form>
        </div>
    </div>
</body>

</html>