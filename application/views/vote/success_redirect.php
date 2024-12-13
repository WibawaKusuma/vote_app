<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Vote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .progress {
            height: 30px;
            border-radius: 5px;
        }

        .progress-bar {
            font-weight: bold;
            font-size: 1rem;
            text-align: center;
            border-radius: 5px;
        }

        .card {
            margin-top: 30px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0;
            padding: 20px;
        }

        /* Gambar Kandidat dengan efek hover */
        .card-img-top {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin: 0 auto;
            display: block;
            transition: transform 0.3s ease;
            /* Efek transisi untuk gambar */
        }

        .card-img-top:hover {
            transform: scale(1.1);
            /* Memperbesar gambar saat dihover */
        }

        .card-body {
            text-align: center;
            padding: 20px;
        }

        .card-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }

        .card-text {
            font-size: 1rem;
            color: #666;
        }

        .progress-bar {
            background-color: #28a745;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: bold;
            color: #333;
        }

        .footer p {
            margin: 0;
        }

        .container {
            max-width: 1200px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="card shadow-sm">
            <div class="card-header">
                <h3>Hasil Voting</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Looping Kandidat -->
                    <?php
                    $total_votes = array_sum(array_column($kandidat_votes, 'jumlah_suara')); // Total semua suara
                    foreach ($kandidat_votes as $k) :
                        // Menghitung persentase suara untuk setiap kandidat
                        $persentase = ($total_votes > 0) ? round(($k['jumlah_suara'] / $total_votes) * 100, 2) : 0;
                    ?>
                        <div class="col-md-6 mb-6">
                            <div class="card">
                                <!-- Menambahkan Foto Kandidat dengan bentuk bulat -->
                                <img src="<?= base_url('assets/template/img/kandidat/' .  $k['image']) ?>" class="card-img-top" alt="Foto Kandidat">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($k['nama_kandidat']) ?></h5>
                                    <p class="card-text">Jumlah Suara: <strong><?= $k['jumlah_suara'] ?></strong></p>
                                    <!-- Progres Bar untuk persentase suara -->
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar"
                                            style="width: <?= $persentase ?>%;" aria-valuenow="<?= $persentase ?>"
                                            aria-valuemin="0" aria-valuemax="100">
                                            <?= $persentase ?>%
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="footer">
                <p>Total Suara Masuk: <strong><?= $total_votes ?></strong></p>
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?= base_url('auth/logout') ?>" class="btn btn-primary" id="logoutButton">Logout <i class="fas fa-fw fa-sign-out-alt"></i></i></a>
                    </div>
                </div><br><br>
            </div>
        </div>
    </div>
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const logoutButton = document.getElementById('logoutButton');
        logoutButton.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah aksi default redirect

            Swal.fire({
                title: 'Terima Kasih!',
                text: 'Terima kasih telah melakukan vote, Anda berhasil logout.',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#007bff',
            }).then(() => {
                // Redirect ke URL logout setelah SweetAlert selesai
                window.location.href = "<?= base_url('auth/logout') ?>";
            });
        });
    </script>
</body>

</html>