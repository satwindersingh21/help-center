<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Complaint $complaint
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Complaints'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="complaints form large-9 medium-8 columns content">
    <?= $this->Form->create($complaint) ?>
    <fieldset>
        <legend><?= __('Add Complaint') ?></legend>
        <?php
            echo $this->Form->control('user_id', ['type' => 'hidden', 'value'=>$authUser['id']]);
            echo $this->Form->control('tittle');
            echo $this->Form->control('complaint_body',['label'=>'Complaint'] );

        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
