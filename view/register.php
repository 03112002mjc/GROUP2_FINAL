

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Notebook</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+3:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../public/css/register.css">
</head>
<body>
    <div class="register-container">
        <div class="logo">
            <img src="../../public/img/CITE.png" alt="">
        </div>
        <h1>Register</h1>
        <p id="fill-up">Fill the following Form</p>

        <?php if (isset($_GET['error'])) { ?>
            <p class="message error"><?php echo $_GET['error']; ?></p>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <p class="message success"><?php echo $_GET['success']; ?></p>
        <?php } ?>

        <form action="../controller/RegisterController.php" method="post">
            <label>Name</label>
            <?php if (isset($_POST['name'])) { ?>
                <input type="text" name="name" placeholder="ex. Juan S. Dela Cruz" value="<?php echo $_POST['name']; ?>"><br>
            <?php } else { ?>
                <input type="text" name="name" placeholder="ex. Juan S. Dela Cruz"><br>
            <?php } ?>

            <label>Email</label>
            <?php if (isset($_POST['eml'])) { ?>
                <input type="email" name="email" placeholder="xxx@gmail.com" value="<?php echo $_POST['eml']; ?>"><br>
            <?php } else { ?>
                <input type="email" name="email" placeholder="xxx@gmail.com"><br>
            <?php } ?>

            <div class="user-box">
                <label for="password">Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" placeholder="Password" required />
                    <span class="password-toggle-icon"><i class="fas fa-eye"></i></span>
                </div>
            </div><br>

            <button type="submit">Register</button>
        </form>
    </div>
    <div class="register-container2">
        <p class="text-muted text-center"><small>Already have an account?</small></p>
        <a href="../index.php" class="btn btn-default btn-block">Login</a>
    </div>
    <footer id="footer">
        <p>
            <small>&copy; 2024 | Notebook by <a href="github.com"><small>Group2</small></a><br> WebDev</small>
        </p>
    </footer>

<script src="../public/js/login.js"></script>

</body>
</html>
