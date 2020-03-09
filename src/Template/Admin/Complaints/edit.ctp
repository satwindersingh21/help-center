<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Complaint $complaint
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $complaint->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $complaint->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Complaints'), ['action' => 'index']) ?></li>

    </ul>
</nav>
<div class="complaints form large-9 medium-8 columns content">
    <?= $this->Form->create($complaint) ?>
    <fieldset>
        <legend><?= __('Edit Complaint') ?></legend>
        <?php
            echo $this->Form->control('tittle');
            echo $this->Form->control('complaint_body');
            echo $this->Form->control('status', ['label'=>'Resolved']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
