<!DOCTYPE html>
<html lang="en">

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
</style>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Tampilkan SweetAlert dan arahkan setelah 3 detik
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '<?= $this->session->flashdata('success'); ?>',
                text: "Anda Sudah Memilih, Anda akan dialihkan ke halaman hasil vote dalam beberapa saat...",
                icon: 'success',
                showConfirmButton: false,
                timer: 3000,
                willClose: () => {
                    // Redirect ke halaman vote setelah SweetAlert ditutup
                    window.location.href = "<?= base_url('vote/hasil_vote'); ?>";
                }
            });
        });
    </script>
</head>

<body>
    <!-- Halaman kosong karena semua ditangani oleh SweetAlert -->
</body>

</html>