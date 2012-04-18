

<h2><?= $logicName ?> Tableaux Rules</h2>

<? foreach ( $nodeRules as $rule ): ?>
    <h3><?= $this->Inflect->human( $rule->getName() ) ?></h3>
    Conditions: <? print_r( $rule->getConditions() ) ?>
<? endforeach ?>
