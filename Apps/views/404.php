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
                <h1 class="fw-bold mt-5 mb-3 text-center"><?= $data['status']['code'] . ' - ' . $data['status']['text'] ?></h1>
                <p class="lh-5 text-center"><?= $data['message']?? null ?></p>
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