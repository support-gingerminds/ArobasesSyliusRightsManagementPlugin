<?php

declare(strict_types=1);

namespace Arobases\SyliusRightsManagementPlugin\Provider;

use Symfony\Bundle\SecurityBundle\Security;

class CurrentAdminUserProvider
{

    public function __construct(private Security $security)
    {
    }

    public function getCurrentAdminUser(): ?UserInterface
    {
        if (null === $this->security->getUser()) {
            return null;
        }

        return $this->security->getUser();
    }
}
