<?php

namespace spec\Label305\AujaLaravel\Factory;

use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\URL;
use Label305\Auja\Menu\LinkMenuItem;
use Label305\Auja\Menu\Menu;
use Label305\Auja\Menu\ResourceMenuItem;
use Label305\Auja\Menu\SpacerMenuItem;
use Label305\AujaLaravel\I18N\Translator;
use Label305\AujaLaravel\Routing\AujaRouter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Config\Definition\Exception\Exception;

class AssociationMenuFactorySpec extends ObjectBehavior {

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var AujaRouter
     */
    private $aujaRouter;

    function let(Translator $translator, AujaRouter $aujaRouter) {
        $this->beConstructedWith($translator, $aujaRouter);

        $this->translator = $translator;
        $this->aujaRouter = $aujaRouter;
    }

    function it_is_initializable() {
        $this->shouldHaveType('Label305\AujaLaravel\Factory\AssociationMenuFactory');
    }

    function it_can_create_a_menu() {
        Url::shouldReceive('route');

        $this->create('Name', 1, 'Association')->shouldHaveType('Label305\Auja\Menu\Menu');
    }

    function its_created_menu_should_have_an_add_linkmenuitem_as_a_first_item() {
        Url::shouldReceive('route');

        $result = $this->create('Name', 1, 'Association');

        $menu = $result->getWrappedObject();
        /* @var $menu Menu */

        if (!($menu->getMenuItems()[0] instanceof LinkMenuItem)) {
            throw new \Exception('First item is not of type LinkMenuItem');
        }

        $item = $menu->getMenuItems()[0];
        /* @var $item LinkMenuItem */
        if (strpos($item->getText(), 'Add') === -1) {
            throw new Exception('First item does not contain \'Add\'');
        }
    }

    function its_created_menu_should_have_a_spacermenuitem_as_a_second_item() {
        Url::shouldReceive('route');

        $result = $this->create('Name', 1, 'Association');

        $menu = $result->getWrappedObject();
        /* @var $menu Menu */

        if (!($menu->getMenuItems()[1] instanceof SpacerMenuItem)) {
            throw new \Exception('Second item is not of type SpacerMenuItem');
        }
    }

    function its_created_menu_should_have_a_resourcemenuitem_as_a_third_item() {
        Url::shouldReceive('route');

        $result = $this->create('Name', 1, 'Association');

        $menu = $result->getWrappedObject();
        /* @var $menu Menu */

        if (!($menu->getMenuItems()[2] instanceof ResourceMenuItem)) {
            throw new \Exception('Second item is not of type ResourceMenuItem');
        }
    }
}
