<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <title>Login</title>
    <meta name="google-signin-client_id"
        content="848005554800-hq4fu22o449tu1ff2g9tsgdbaksljs9c.apps.googleusercontent.com">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" />

    <!--  -->
    <link rel="stylesheet" href="/assets/css/loginStyle.css" />

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" />

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js"></script>
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon.png">
</head>

<body>
    <div class="container-fluid" style="max-height: 100vh">
        <div class="row">
            <div class="col-md-6">
                <img src="assets/images/login_image.png" class="responsive" />
            </div>
            <div class="col-md-5 col-lg-4 col-10 pt-5 welcome-container" style="height:500px">
                <h2>WELCOME BACK!</h2>
                <div class="mb-3">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <h5>Email</h5>
                        <input class="email" type="email" name="email" placeholder="Enter Your Email Address"
                            value="{{ old('email') }}" />
                </div>
                <div class="mb-3">
                    <h5>Password</h5>
                    <div class="unsee-icon">
                      <input id="password" class="password" type="password" name="password" placeholder="********" />
                      <i id="password_visibility" class="fa fa-eye-slash" onclick="togglePasswordVisibility()"></i>
                    </div>
                </div>
                <button class="button button3">Login</button>
                <span><a href="/forgot-password">Forgot Password?</a></span>
            </div>
        </div>
        </form>
    </div>
</body>
@include('sweetalert::alert')
<script>
    function togglePasswordVisibility() {
        let password = document.getElementById('password');
        let passwordVisibility = document.getElementById('password_visibility');

        passwordVisibility.classList.toggle("fa-eye");
        passwordVisibility.classList.toggle("fa-eye-slash");

        const type = password.getAttribute("type") === "password" ? "text" : "password";
        password.setAttribute("type", type);
    }
</script>

</html>
