<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/styles/all.css" />
    <link rel="stylesheet" href="/styles/bootstrap-5.1.3-dist/css/bootstrap.min.css" />
    <script src="/styles/bootstrap-5.1.3-dist/js/bootstrap.min.js" defer></script>
    <title>Login</title>
    <link rel="icon" type="image/x-icon" href="/images/favicon.ico">
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

        .screen {
            background-color: "#bbd0c9";
            /* height: 100vh; */
        }

        .box {
            margin-top: 2%;
            margin-bottom: 2%;
        }

        .form {
            padding: 3%;
            border: black solid 2px;
            position: relative;
            animation-name: form;
            animation-duration: 2s;
        }

        label {
            padding-bottom: 5%;
        }

        @keyframes form {
            0% {
                left: -50%;
            }

            100% {
                left: 0px;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: Verdana, sans-serif;
        }

        .mySlides {
            display: none;
        }

        img {
            vertical-align: middle;
        }

        /* Slideshow container */
        .slideshow-container {
            max-width: 1000px;
            position: relative;
            margin: auto;
        }

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            width: 100%;
            text-align: center;
        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
            height: 15px;
            width: 15px;
            margin: 0 2px;
            background-color: #bbb;
            border-radius: 50%;
            display: inline-block;
            transition: background-color 0.6s ease;
        }

        .active {
            background-color: #717171;
        }

        /* Fading animation */
        .fade {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {
                opacity: 0.4;
            }

            to {
                opacity: 1;
            }
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {
            .text {
                font-size: 11px;
            }
        }

        .btn {
            border: #21081a solid 2px
        }
    </style>
</head>

<body style="background-color: #be847a;">
    <nav class="navbar navbar-light bg-warning">
        <span class="navbar-brand mb-0 h1"> <img src="images/favicon-32X32.png" alt="logo" /> </span>
        <a href="/"><button type="button" class="btn btn-success">Home page</button></a>
    </nav>

    <div class="box container" style="height: 80vh;">
        <div class="row">
            <div class="col-4 form" style="background-color: #880808; color: white;">
                <form method="post">
                    <h2 style="text-align: center;">Login</h2>
                    <br />
                    <br />
                    <div class="form-group">
                        <label htmlFor="exampleInputEmail1">User Name</label>
                        <input type="text" name="username" class=" form-control" placeholder="Enter User Name" />
                    </div>
                    <br />
                    <div class=" form-group">
                        <label htmlFor="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Enter Password" />
                    </div>
                    <br />
                    <div class="row">
                        <div class="col-2"></div>
                        <div class="col-5">
                            <button type="submit" class="btn btn-info" style="width: 150%; border: #21081a solid 2px">
                                LOGIN
                            </button>
                        </div>
                        <div class="col-5"></div>
                    </div>
                </form>
            </div>
            <div class="col-8">
                <div class="slideshow-container">
                    <div class="mySlides fade">
                        <div class="numbertext">1 / 2</div>
                        <img src="images/slide-1.jpg" alt="slide-1" style=" width: 100%; height: 450px" />
                    </div>

                    <div class="mySlides fade">
                        <div class="numbertext">2 / 2</div>
                        <img src="images/slide-2.jpg" alt="slide-2" style=" width: 100%; height: 450px;" />
                    </div>
                </div>
                <br />

                <div style="text-align: center;">
                    <span class="dot"></span>
                    <span class="dot"></span>
                </div>
            </div>
        </div>

    </div>
    <br /><br />
    <footer class="text-center text-white fixed-bottom row" style="background-color: #21081a;">
        <!-- Grid container -->
        <div class="container p-3"></div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center p-2" style="background-color: rgba(0, 0, 0, 0.2);">
            © 2020 Copyright:
            <a class="text-white" href="https://mdbootstrap.com/">MDBootstrap.com</a>
        </div>
        <!-- Copyright -->
    </footer>

    <script type="text/javascript">
        let slideIndex = 0;
        showSlides();

        function showSlides() {
            let i;
            let slides = document.getElementsByClassName("mySlides");
            let dots = document.getElementsByClassName("dot");
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
            }
            slideIndex++;
            if (slideIndex > slides.length) {
                slideIndex = 1;
            }
            for (i = 0; i < dots.length; i++) {
                dots[i].className = dots[i].className.replace(" active", "");
            }
            if (slides[slideIndex - 1] && dots[slideIndex - 1]) {
                slides[slideIndex - 1].style.display = "block";
                dots[slideIndex - 1].className += " active";
            }
            setTimeout(showSlides, 2000); // Change image every 2 seconds
        }
    </script>
</body>

</html>