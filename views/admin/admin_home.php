<?php


if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['res'])) {
    header('Location: ../auth/index.php');
} else {
    include_once './admin_header.php';

    // var_dump($_SESSION['res']);


?>
    <div>
        dashboard
    </div>
<?php
    include_once './admin_footer.php';
}
?>