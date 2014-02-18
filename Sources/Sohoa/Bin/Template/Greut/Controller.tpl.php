<?php
/**
 * @var string $controller
 * @var array $action
 * @var bool $withView
 * @var bool $withAsync
 */
?>
namespace Application\Controller;

use Sohoa\Framework\Kit;

class <?php echo ucfirst($controller) ?> extends Kit {

<?php
if (is_array($action)) {
    foreach ($action as $e) {

        if ($withView === true)
            echo 'public function ' . ucfirst($e) . 'Action() {$this->' .$engine.'->render();}' . "\n\n";
        else
            echo 'public function ' . ucfirst($e) . 'Action() {}' . "\n\n";

        if ($withAsync === true)
            echo 'public function ' . ucfirst($e) . 'ActionAsync() {}' . "\n\n";
    }
}
?>
}
