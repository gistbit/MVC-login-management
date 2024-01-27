<?php use function App\helper\response; ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title><?= $data['title']?? 'Error' ?></title>
    <!-- Bootstrap css-->
    <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.css">
</head>

<body>
    <div class="container col-xl-12 mx-auto my-auto">
        <div class="row align-items-center mt-4">
            <div class="col-md-10 mx-auto my-auto col-lg-4">
                <h1 class="fw-bold mt-5 mb-3 text-center"><?= response()->getStatusCode() . ' - ' . response()->getStatusCodeText(response()->getStatusCode()) ?></h1>
                <p class="lh-5 text-center">Halaman <strong><?= $data['path'] ?></strong> tidak ditemukan</p>
            </div>
        </div>
        <div class="row align-items-center">
            <h6 class="fw-bold mt-5 text-center"><a href="/">HOME</a></h6>
        </div>
    </div>
    <!-- Bootstrap js-->
    <script src="/assets/js/bootstrap/bootstrap.min.js"></script>
</body>

</html>