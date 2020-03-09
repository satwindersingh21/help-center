<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Complaint[]|\Cake\Collection\CollectionInterface $complaints
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Complaint'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="complaints index large-9 medium-8 columns content">
    <h3><?= __('Complaints') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('tittle', 'Title') ?></th>
                <th scope="col"><?= $this->Paginator->sort('complaint_body', 'Complaint') ?></th>
                <th scope="col"><?= $this->Paginator->sort('status') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($complaints as $complaint): ?>
            <tr>
                <td><?= h($complaint->tittle) ?></td>
                <td><?= h($complaint->complaint_body) ?></td>
                <td><?= h(($complaint->status)? "Resolved": "Pending") ?></td>
                <td><?= h($complaint->created) ?></td>
               <td class="actions">
                   <?= $this->Html->link(__('Edit'), ['action' => 'edit', $complaint->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $complaint->id], ['confirm' => __('Are you sure you want to delete # {0}?', $complaint->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
