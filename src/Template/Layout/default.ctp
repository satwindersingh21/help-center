<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'Help Center: ';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>
    <?= $this->Html->script('jquery.min') ?>
    <?= $this->Html->script('jquery.validate.min') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
    <style>
        .top-bar-section ul li{
            margin: 20px 10px;
            background-color: #E23633 !important;
        }
    </style>
</head>
<body>
<nav class="top-bar expanded" data-topbar role="navigation">

    <div class="top-bar-section">
        <div class="left" style="padding: 20px;">
            <img src="<?= SITE_URL; ?>img/unnamed.png" style="height:100px">
        </div>
        <ul class="right">
            <?php if (empty($authUser)) { ?>
                <li><a  href="<?= SITE_URL . "users/register/"; ?>">Register</a></li>
                <li><a  href="<?= SITE_URL . "users/login/"; ?>">Login</a></li>
            <?php } else { ?>
                <li><a  href="<?= SITE_URL . "employees"; ?>">Departments</a></li>
                <li><a  href="<?= SITE_URL . "holidays"; ?>">Holidays</a></li>
                <li><a  href="<?= SITE_URL . "complaints"; ?>">Complaints</a></li>
                <li><a  href="<?= SITE_URL . "users/logout"; ?>">Logout</a></li>
            <?php } ?>
        </ul>
    </div>
</nav>
<?= $this->Flash->render() ?>
<div class="container clearfix">
    <?= $this->fetch('content') ?>
</div>
<footer>
</footer>
</body>
</html>
