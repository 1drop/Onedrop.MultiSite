<?php

namespace Onedrop\MultiSite\Controller\Module\Administration;

use Onedrop\MultiSite\Service\SiteService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\PackageManagerInterface;
use TYPO3\Neos\Controller\Module\AbstractModuleController;

/**
 * Class SitesController
 *
 * @package Onedrop\MultiSite\Controller\Module\Administration
 */
class SitesController extends AbstractModuleController
{

    /**
     * @Flow\Inject
     * @var PackageManagerInterface
     */
    protected $packageManager;

    /**
     * @Flow\Inject
     * @var SiteService
     */
    protected $siteService;

    /**
     * Create a new subsite
     *
     * @return void
     */
    public function newSiteAction()
    {
        $sitePackages = $this->packageManager->getFilteredPackages('available', null, 'typo3-flow-site');
        $this->view->assignMultiple(array(
            'sitePackages' => $sitePackages
        ));
    }

    /**
     * @param string $sitePackage
     * @param string $siteName
     */
    public function createSiteFromSitePackage($sitePackage, $siteName= '')
    {

    }

}