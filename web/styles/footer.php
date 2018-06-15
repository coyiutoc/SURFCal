<?php

if (basename($_SERVER['PHP_SELF']) === 'header.php') {
    require_once('../403.php');
}

echo <<< _END
                </div><!-- .container ends -->
            </div><!-- .main.global ends -->
            <footer class="global">
                <div class="container">
                    <p>Copyright &copy; $copy $siteTitle.</p>
                </div><!-- .container ends -->
            </footer>
        </div><!-- .wrapper ends -->
    </body>
</html>
_END

?>
