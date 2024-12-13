<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Vote</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* body {
            background-color: #e0f7fa;
        } */

        .vote-card {
            border: 1px solid white;
            border-radius: 8px;
            transition: transform 0.3s;
            /* box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .card-img-top {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
            transition: transform 0.3s;
        }

        .card-img-top:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            width: 200px;
            height: 200px;
            object-fit: cover;
            border-radius: 50%;
        }

        .vote-btn {
            margin-top: 15px;
        }

        .modal-header {
            background-color: #007bff;
            color: white;
        }

        .nomor-undi {
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin: 0 auto;
        }

        .nomor-undi:hover {
            background-color: lightcoral;
            color: white;
        }

        .pilihan.selected {
            background-color: #007bff;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header text-center bg-primary text-white">
                        <h3><?php echo @$kandidat[0]->nama_acara ?></h3>
                    </div>
                    <div class="card-body">
                        <form id="votingForm" method="post" action="<?= site_url('vote/create') ?>">
                            <input type="hidden" name="p[id_pemilih]" value="<?= $pemilih['id_pemilih'] ?>">
                            <input type="hidden" name="p[nim]" value="<?= $pemilih['nim'] ?>">
                            <input type="hidden" name="p[password]" value="<?= $pemilih['password'] ?>">
                            <div class="row">
                                <!-- Looping Kandidat -->
                                <?php foreach ($kandidat as $index => $k): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card vote-card text-center">
                                            <img src="<?= base_url('assets/template/img/kandidat/' . $k->image) ?>" class="card-img-top mx-auto mt-3" alt="Candidate <?= $index + 1 ?>">
                                            <div class="card-body">
                                                <h2 class="nomor-undi mb-4 col-md-2 pilihan" data-value="<?= $index + 1 ?>">
                                                    <?= $index + 1 ?>
                                                </h2>
                                                <h5 class="card-title"><?= htmlspecialchars($k->nama_kandidat) ?></h5>
                                                <button type="button" class="btn btn-primary mb-2 mt-2"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#detailsModal<?= $index + 1 ?>">
                                                    Lihat Detail
                                                </button>
                                                <!-- <input type="radio" name="p[pilihan]" value="<?= $index + 1 ?>" class="form-check-input d-none"> -->
                                                <input type="radio" name="p[pilihan]" value="<?= $k->id_detail ?>" class="form-check-input d-none">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="text-center">
                                <p class="text-danger mt-2" id="errorMessage" style="display: none;">
                                    Silahkan pilih kandidat sebelum mengirim vote!
                                </p>
                                <button type="submit" class="btn btn-success vote-btn col-md-4">Kirim Vote</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Looping Modal -->
        <?php foreach ($kandidat as $index => $k): ?>
            <div class="modal fade" id="detailsModal<?= $index + 1 ?>" tabindex="-1" aria-labelledby="detailsModalLabel<?= $index + 1 ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="detailsModalLabel<?= $index + 1 ?>">
                                Kandidat <?= $index + 1 ?> - Visi & Misi
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Visi</strong></p>
                            <p> <?= nl2br(htmlspecialchars($k->visi)) ?></p>
                            <p><strong>Misi</strong> </p>
                            <p><?= nl2br(htmlspecialchars($k->misi)) ?></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
        const pilihanElements = document.querySelectorAll('.nomor-undi');
        const radioInputs = document.querySelectorAll('input[name="p[pilihan]"]');
        const errorMessage = document.getElementById('errorMessage');
        const form = document.getElementById('votingForm');

        pilihanElements.forEach((element, index) => {
            element.addEventListener('click', () => {
                pilihanElements.forEach(el => el.classList.remove('selected'));
                element.classList.add('selected');
                radioInputs[index].checked = true;
            });
        });

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const selected = document.querySelector('input[name="p[pilihan]"]:checked');

            if (!selected) {
                errorMessage.style.display = 'block';
            } else {
                errorMessage.style.display = 'none';

                Swal.fire({
                    title: 'Konfirmasi Pilihan',
                    text: `Apakah Anda yakin ingin memilih kandidat nomor ${selected.value}?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Pilih',
                    cancelButtonText: 'Batal',
                    confirmButtonColor: '#007bff',
                    cancelButtonColor: '#d33',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Vote Berhasil!',
                            text: `Terima kasih, Anda telah memilih kandidat nomor ${selected.value}!`,
                            icon: 'success',
                            confirmButtonColor: '#007bff',
                        }).then(() => {
                            // Tambahkan delay sebelum submit form
                            setTimeout(() => {
                                form.submit();
                            }, 500); // Delay 1 detik
                        });
                    }
                });
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>