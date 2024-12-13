</main>
<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer mt-5">
    <div class="container py-5">
        <div class="pb-4">
            <div class="row g-4">
                <div class="col-lg-12">
                    <div class="d-flex justify-content-end pt-3">
                        <div class="col-xl-5 col-lg-5 col-md-5">
                            <!-- social -->
                            <div class="footer-social f-right">
                                <a href="#"><i class="fab fa-twitter"></i></a>
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="<?= $config->company_whatsapp ?>"><i class="fab fa-whatsapp"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
            </div>
        </div>
    </div>
    <div class="container-fluid row g-5">
        <div class="col-lg-3 col-md-6" id="home">
            <div class="footer-item mb-4">
                <a href="<?= base_url('Landing_page') ?>"><img src="<?= base_url('assets/template') ?>/img/logo/logo-aluh-trans.png" alt="" height="150px"></a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6" id="about">
            <div class="footer-item">
                <h4 class="text-light mb-3">Why People Like us!</h4>
                <p class="mb-4">We are the first choice for those of you who want a superior and satisfying travel
                    experience, guaranteed by professional, flexible service and committed to providing
                    unforgettable comfort and satisfaction.</p>
                <!-- <a href="" class="btn border-secondary py-2 px-4 rounded-pill text-primary">Read More</a> -->
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="footer-item">
                <h4 id="contact" class="text-light mb-3">Contact</h4>
                <p>Address : <?= $config->company_address ?></p>
                <p>Email &nbsp;&nbsp;&nbsp; : <?= $config->company_email ?></p>
                <p>Phone &nbsp;&nbsp; : <?= $config->company_whatsapp ?></p>
                <!-- <p>Payment Accepted</p>
                <img src="<?= base_url('assets/template') ?>/img/logo/payment.png" alt=""> -->
            </div>
        </div>
    </div>
</div>
</div>
<!-- Footer End -->

<!-- Copyright Start -->
<div class="container-fluid copyright bg-dark py-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12">
                <div class="footer-copy-right text-center">
                    <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved <i
                            class="ti-heart" aria-hidden="true"></i> by <a href="https://colorlib.com"
                            target="_blank">Ajikpo</a>
                        <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer End-->
<!-- JS here -->

<!-- All JS Custom Plugins Link Here here -->
<script src="<?= base_url('assets/template') ?>/js/vendor/modernizr-3.5.0.min.js"></script>

<!-- Jquery, Popper, Bootstrap -->
<script src="<?= base_url('assets/template') ?>/js/vendor/jquery-1.12.4.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/popper.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/bootstrap.min.js"></script>
<!-- Jquery Mobile Menu -->
<script src="<?= base_url('assets/template') ?>/js/jquery.slicknav.min.js"></script>

<!-- Jquery Slick , Owl-Carousel Plugins -->
<script src="<?= base_url('assets/template') ?>/js/owl.carousel.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/slick.min.js"></script>
<!-- One Page, Animated-HeadLin -->
<script src="<?= base_url('assets/template') ?>/js/wow.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/animated.headline.js"></script>
<script src="<?= base_url('assets/template') ?>/js/jquery.magnific-popup.js"></script>

<!-- Scrollup, nice-select, sticky -->
<script src="<?= base_url('assets/template') ?>/js/jquery.scrollUp.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/jquery.nice-select.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/jquery.sticky.js"></script>

<!-- contact js -->
<script src="<?= base_url('assets/template') ?>/js/contact.js"></script>
<script src="<?= base_url('assets/template') ?>/js/jquery.form.js"></script>
<script src="<?= base_url('assets/template') ?>/js/jquery.validate.min.js"></script>
<script src="<?= base_url('assets/template') ?>/js/mail-script.js"></script>
<script src="<?= base_url('assets/template') ?>/js/jquery.ajaxchimp.min.js"></script>

<!-- Jquery Plugins, main Jquery -->
<script src="<?= base_url('assets/template') ?>/js/plugins.js"></script>
<script src="<?= base_url('assets/template') ?>/js/main.js"></script>

</body>

</html>