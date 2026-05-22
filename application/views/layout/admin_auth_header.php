<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : 'Admin'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/home.css'); ?>">
</head>
<body class="admin-auth-body<?php echo !empty($body_class) ? ' ' . $body_class : ''; ?>">
    <?php if (!empty($flash_error) || !empty($flash_success)): ?>
        <div class="flash-stack">
            <div class="content-shell">
                <?php if (!empty($flash_error)): ?>
                    <div class="flash-message flash-error"><?php echo $flash_error; ?></div>
                <?php endif; ?>
                <?php if (!empty($flash_success)): ?>
                    <div class="flash-message flash-success"><?php echo $flash_success; ?></div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
