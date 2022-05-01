<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" type="text/css" href="/styles/all.css" />
    <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js"></script>
    <title>Check Balance</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico" />

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

        .box {
            margin-top: 2%;
            margin-bottom: 2%;
        }

        .item {
            padding: 2%;
            margin: 1%;
        }

        .btn {
            border: #21081a solid 2px;
        }

        .fade {
            animation: fadeInAnimation ease 3s;
            animation-iteration-count: 1;
            animation-fill-mode: forwards;
        }

        @keyframes fadeInAnimation {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light bg-warning">
        <span class="navbar-brand mb-0 h1"><img src="./../../images/favicon-32x32.png" alt="logo" /></span>
        <a href="/customer/index.php"><button type="button" class="btn btn-success">Dashboard</button></a>
    </nav>
    <div class="container box fade" style="background-color: #880808; color: white; border: #21081a solid 2px">
        <h1>Check Your Account Balance</h1>
        <br />
        <div class="row">
            <div class="col-3"></div>
            <div class="col-7">
                <form method="POST" class="form-row align-items-center">
                    <div class="row">
                        <div class="col-3 item">
                            <label for="owner_id"> Owner ID</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="owner_id" id="owner_id" required />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-3 item">
                            <label for="acc_no">Account No</label>
                        </div>
                        <div class="col-1 item"></div>
                        <div class="col-3 item">
                            <input type="text" name="acc_no" id="acc_no" required />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-4 item">
                            <button type="submit" class="btn btn-info" style="width: 150%">
                                Check Balance
                            </button>
                        </div>
                        <div class="col-1"></div>
                    </div>
                </form>
            </div>
            <div class="col-2"></div>
        </div>
    </div>



    <footer class="text-center text-white fixed-bottom row" style="background-color: #21081a">
        <!-- Grid container -->
        <div class="container p-3"></div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-2" style="background-color: rgba(0, 0, 0, 0.2)">
            © 2020 Copyright:
            <a class="text-white" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>
</body>

</html>