<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- <link rel="stylesheet" href="reset.css"> -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="stilius.css">
    <title>Namu darbas</title>
</head>

<body>
    <div>
        <?php
        session_start();
        // If not logged in  then back to homepage
        if (!$_SESSION['logged_in']) {
            header("Location: index.php");
            exit;
        }
        // logout
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['logout'])) {
                session_start();
                unset($_SESSION['username']);
                unset($_SESSION['password']);
                unset($_SESSION['logged_in']);
                header("Location: index.php");
            }
        }
        // back code ! need atttention
        $queryString = explode("/", $_SERVER['QUERY_STRING']);
        array_pop($queryString);
        if (count($queryString) == 1) {
            $urlString = explode("?", $_SERVER['REQUEST_URI']);
            array_pop($urlString);
            $newURL = implode("/", $urlString);
        } else {
            $urlString = explode("/", $_SERVER['REQUEST_URI']);
            array_pop($urlString);
            array_pop($urlString);
            $newURL = implode("/", $urlString) . "/";
        }
        // Print directories
        $path = './' . $_GET["path"];
        $files_and_dirs = scandir($path);

        // file download
        if (isset($_POST['download'])) {
            // print('Path to download: ' . './' . $_GET["path"] . $_POST['download']); // need to be fixed
            $file = './' . $_GET["path"] . $_POST['download'];
            $fileToDownloadEscaped = str_replace("&nbsp;", " ", htmlentities($file, null, 'utf-8'));
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename=' . basename($fileToDownloadEscaped));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fileToDownloadEscaped));
            flush();
            readfile($fileToDownloadEscaped);
            exit;
        }
        //create and delete dir
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST['delete'])) {
                $fileDelete = './' . $_GET['path'] . $_POST['delete'];
                if (is_file($fileDelete)) {
                    unlink($fileDelete);
                }
            } elseif (isset($_POST['create'])) {
                if ($_POST['create'] != "") {
                    $dirCreate = './' . $_GET['path'] . $_POST['create'];
                    if (!is_dir($dirCreate))
                        mkdir($dirCreate, 0777, true);
                }
            }
        }
        //file upload

        if (isset($_FILES['fileToUpload'])) {
            $errors = array();
            $file_name = $_FILES['fileToUpload']['name'];
            $file_size = $_FILES['fileToUpload']['size'];
            $file_tmp = $_FILES['fileToUpload']['tmp_name'];
            $file_type = $_FILES['fileToUpload']['type'];
            $file_ext = strtolower(end(explode('.', $_FILES['fileToUpload']['name'])));

            $extensions = array("jpeg", "jpg", "png", "pdf", "txt");

            if (in_array($file_ext, $extensions) === false) {
                $errors[] = "this type of file is not allowed, please select a JPEG, PNG, TXT or PDF file.";
            }

            if ($file_size > 10097152) {
                $errors[] = 'File size must be below 10 MB';
            }

            if (empty($errors) == true) {
                move_uploaded_file($file_tmp, './' . $_GET["path"] . $file_name);
                echo "Success";  // future update - reikia padaryti, kad kitoje vietoje rodytu succes , padaryti pop up
            } else {
                print_r($_FILES);
                print('<br>');
                print_r($errors);
            }
        }


        ?>
    </div>

    <header class="container">
        <div style="width: 60%;">
            <a href="<?php print($newURL) ?>">
                <button class="btn btn-info btn-lg">Back</button>
            </a>
            BIT2020 - Failu narsykle P.V
        </div>
        <div style="font-weight: bolder; " class="fa fa-home">
            <form class="logout" action="codes.php" method="POST">
                <input type="hidden" name="logout">
                <button type="submit" class="btn btn-info btn-lg">Log out</button>
            </form>
        </div>
        <div class="fa fa-search">
        </div>
    </header>
    <div class="loginas">
        <div style="border-bottom: lightslategray dotted 3px; font-weight: bolder; font-size: 25px;">
            <?php
            print('<h2>Directory contents: ' . str_replace('?path=/', '', $_SERVER['REQUEST_URI']) . '</h2>');
            ?>
        </div>
        <div class="signup" style="color: grey;">

        </div>
        <div>
            <?php
            print('<table><th>Type</th><th>Name</th><th>Actions</th>');
            foreach ($files_and_dirs as $fnd) {
                if ($fnd != ".." and $fnd != ".") {
                    print('<tr>');
                    print('<td>' . (is_dir($path . $fnd) ? "Directory" : "File") . '</td>');
                    print('<td>' . (is_dir($path . $fnd)
                        ? '<a href="' . (isset($_GET['path'])
                            ? $_SERVER['REQUEST_URI'] . $fnd . '/'
                            : $_SERVER['REQUEST_URI'] . '?path=' . $fnd . '/') . '">' . $fnd . '</a>'
                        : $fnd)
                        . '</td>');
                    print('<td>'
                        . (is_dir($path . $fnd)
                            ? ''
                            : '<form style="display: inline-block" action="" method="post">
                            <input type="hidden" name="download" value=' . str_replace(' ', '&nbsp;', $fnd) . '>
                            <button type="submit" class="btn btn-info btn-lg yellow">Download</button>
                           </form>
                            <form style="display: inline-block" action="" method="post">
                            <input type="hidden" name="delete" value=' . str_replace(' ', '&nbsp;', $fnd) . '>
                            <button type="submit" class="btn btn-info btn-lg red">Delete</button>
                            </form>')
                        . "</form></td>");
                    print('</tr>');
                }
            }
            print("</table>");

            ?>
        </div>
    </div>
    <div class="apacia1">
        <div>
            <form class="w-50 my-3" action=" " method="POST">
                <input class="form-control mb-2" name="create" placeholder="Directory name">
        </div>
        <div>
            <button type="submit" class="btn btn-info btn-lg">Create directory</button>
            </form>
        </div>
    </div>
    <div class="apacia1">
        <div>

        </div>
        <div>
            <form action="" method="post" enctype="multipart/form-data">
                <input class="btn btn-info btn-lg" type="file" name="fileToUpload" id="img"  />
        </div>
        <div>
            <button class="btn btn-info btn-lg"  type="submit">Upload file</button>
            </form>
        </div>
    </div>
    <div class="bottom">
        <div class="botdiv">
            <P>CONTACT US</P>
            <P>123456 7891254</P>
            <P>paulius.vaisvilas@gmail.com</P>
            <P>Kaunas Lithuania</P>
        </div>
        <div class="botdiv">
            <p>ABOUT US</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatibus, iste!</p>
            <div></div>
            <p></p>
        </div>
        <div class="botdiv">
            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Exercitationem reprehenderit corporis veritatis ducimus incidunt esse sequi maxime, fugit tempore nostrum harum asperiores dignissimos commodi ea accusantium ipsam dolor suscipit debitis?
            
        </div>
    </div>

</body>

</html>