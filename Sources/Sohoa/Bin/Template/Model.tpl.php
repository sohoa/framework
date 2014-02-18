<?php
/**
 * @var string $controller
 * @var array $action
 * @var bool $withView
 * @var bool $withAsync
 */
?>
namespace Application\Model;

use Sohoa\Framework\Kit;

class <?php echo ucfirst($resource) ?> extends \Hoa\Model\Model {

<?php
if (is_array($action)) {
    foreach ($action as $e) {
        switch($e) {
            case 'index':
            case 'new':
            case 'create':
                echo 'public function '.$e.' () {}'."\n\n";
                break;
            default:
                echo 'public function '.$e.' ($id) {}'."\n\n";
        }

    }
}
?>
}
