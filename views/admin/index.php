<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/styles/all.css" />
    <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <title>Admin Dashboard</title>
    <style>
        h1 {
            text-align: center;
        }

        a {
            margin-right: 2%;
        }

        span {
            margin-left: 2%;
        }

        nav {
            border-bottom: Maroon solid 2px;
        }

        .navbar {
            position: relative;
            animation-name: nav-header;
            animation-duration: 2s;
            animation-delay: 1s;
        }

        @keyframes nav-header {
            0% {
                top: -40px;
            }

            100% {
                left: 0px;
                top: 0px;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-warning">
        <span class="navbar-brand mb-0 h1"><img src="images/favicon-32X32.png" alt="logo" /></span>
        <a href="/login.php?logout=1"><button type="button" class="btn btn-success">Log out</button></a>
    </nav>
    <h1>Admin Dashboard</h1>
</body>

</html>