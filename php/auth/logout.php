<?php
// ============================================
// LOGOUT
// ============================================

require_once '../auth/session.php';

session_destroy();
header('Location: ../index.html');
exit;
