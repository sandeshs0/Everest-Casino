<?php 
$url = 'http://localhost/Everest UI-Pack/public/admin/';
$page = explode('/', $_SERVER['PHP_SELF']);
$page = end($page);
?>


        <!-- Footer Start -->
        <div class="container-fluid pt-5 px-4">
            <div class="bg-secondary rounded-top p-4">
                <div class="row">
                    <div class="col-12 text-center">
                        &copy; <a href="#">Everest</a>, All Right Reserved. 
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
        </div>
        <!-- Content End -->




        <!-- Back to Top -->
        <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/chart/chart.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/easing/easing.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/waypoints/waypoints.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/tempusdominus/js/moment.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="<?php echo $url;?>assets/lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>
    <script src="//cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            $.ajax({
                type: "GET",
                url: "manage-category.php",
                data: {"id": <?php echo $product_id;?>},
                success: function (data) {
                    console.log(data)
                }
            });
        });
    </script>
    <script>
        CKEDITOR.replace( 'description' );
    </script>

    <!-- Template Javascript -->
    <script src="<?php echo $url;?>assets/js/main.js"></script>
</body>

</html>