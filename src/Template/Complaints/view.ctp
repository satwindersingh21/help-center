<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Complaint $complaint
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Complaint'), ['action' => 'edit', $complaint->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Complaint'), ['action' => 'delete', $complaint->id], ['confirm' => __('Are you sure you want to delete # {0}?', $complaint->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Complaints'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Complaint'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="complaints view large-9 medium-8 columns content">
    <h3><?= h($complaint->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('User') ?></th>
            <td><?= $complaint->has('user') ? $this->Html->link($complaint->user->id, ['controller' => 'Users', 'action' => 'view', $complaint->user->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tittle') ?></th>
            <td><?= h($complaint->tittle) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($complaint->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($complaint->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($complaint->modified) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Status') ?></th>
            <td><?= $complaint->status ? __('Yes') : __('No'); ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Complaint Body') ?></h4>
        <?= $this->Text->autoParagraph(h($complaint->complaint_body)); ?>
    </div>
</div>
