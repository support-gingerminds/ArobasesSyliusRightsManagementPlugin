<?php

declare(strict_types=1);

namespace Arobases\SyliusRightsManagementPlugin\Menu;

use Arobases\SyliusRightsManagementPlugin\Access\Checker\AdminUserAccessChecker;
use Arobases\SyliusRightsManagementPlugin\Provider\CurrentAdminUserProvider;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    private AdminUserAccessChecker $adminUserAccessChecker;

    private CurrentAdminUserProvider $currentAdminUserProvider;

    public function __construct(AdminUserAccessChecker $adminUserAccessChecker, CurrentAdminUserProvider $currentAdminUserProvider)
    {
        $this->adminUserAccessChecker = $adminUserAccessChecker;
        $this->currentAdminUserProvider = $currentAdminUserProvider;
    }

    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $menu->getChild('configuration')->addChild('roles', [
            'route' => 'arobases_sylius_rights_management_plugin_admin_role_index',
        ])->setLabel('arobases_sylius_rights_management_plugin.menu.admin.roles')->setLabelAttribute('icon', 'users');

        foreach ($menu->getChildren() as $rootChildren) {
            foreach ($rootChildren->getChildren() as $children) {
                dump($this->currentAdminUserProvider->getCurrentAdminUser(), $children->getExtra('routes')[0]['route'],$this->currentAdminUserProvider->getCurrentAdminUser()->getRights());
                if (!$this->adminUserAccessChecker->isUserGranted($this->currentAdminUserProvider->getCurrentAdminUser(), $children->getExtra('routes')[0]['route'])) {
                    $rootChildren->removeChild($children);
                }
            }
        }
    }
}
