<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--favicon-->
    <link rel="icon" href="{{ asset('template') }}/assets/images/favicon-32x32.png" type="image/png" />
    <!-- loader-->
    <link href="{{ asset('template') }}/assets/css/pace.min.css" rel="stylesheet" />
    <script src="{{ asset('template') }}/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('template') }}/assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/css/app.css" rel="stylesheet">
    <link href="{{ asset('template') }}/assets/css/icons.css" rel="stylesheet">
    <title>{{ title() }}</title>
</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">

        <div class="error-404 d-flex align-items-center justify-content-center">
            <div class="container">
                <div class="card">
                    <div class="row g-0">
                        <div class="col-xl-5">
                            <div class="card-body p-4">
                                <h1 class="display-1"><span class="text-warning">4</span><span
                                        class="text-danger">1</span><span class="text-primary">9</span></h1>
                                <h2 class="font-weight-bold display-4">Page Expired!</h2>
                                <p>Looks like you are lost!
                                    <br>May be you are not connected to the internet for a long time!
                                </p>
                                <div class="mt-5"> <a href="{{ route('login') }}"
                                        class="btn btn-lg btn-warning px-md-5 radius-30">Login</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7">
                            <img src="{{ asset('template') }}/assets/images/login-images/forgot-password-frent-img.jpg"
                                class="img-fluid" alt="">
                        </div>
                    </div>
                    <!--end row-->
                </div>
            </div>
        </div>

    </div>
    <!-- end wrapper -->
    <!-- Bootstrap JS -->
    <script src="{{ asset('template') }}/assets/js/bootstrap.bundle.min.js"></script>
</body>

</html>
