<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        setTimeout(function() {
            window.location.href = "<?= base_url('auth/logout'); ?>";
        }, 3000);
    </script>
</head>

<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="text-center p-5 bg-white rounded shadow-lg" style="max-width: 400px; width: 100%;">
            <img src="<?= base_url('assets/template/img/logo/logo-primakaravote.jpg') ?>" alt="Logo" class="img-fluid mb-4" style="max-height: 100px;">
            <h4 class="text-danger mb-4"><?= $this->session->flashdata('error'); ?></h4>
            <p class="mb-3">Anda akan dialihkan ke halaman logout dalam beberapa saat...</p>
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-3 text-muted">Harap tunggu...</p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>