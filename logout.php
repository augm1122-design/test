<?php
session_start();
session_unset(); // إزالة جميع متغيرات الجلسة
session_destroy(); // تدمير الجلسة
header('Location: log_in_student.php');
exit();
?>