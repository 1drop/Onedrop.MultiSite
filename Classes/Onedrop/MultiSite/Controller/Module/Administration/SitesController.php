<?php

namespace Onedrop\MultiSite\Controller\Module\Administration;

use Onedrop\MultiSite\Service\SiteService;
use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Package\PackageManagerInterface;

/**
 * Class SitesController
 *
 * @package Onedrop\MultiSite\Controller\Module\Administration
 */
class SitesController extends \TYPO3\Neos\Controller\Module\Administration\SitesController
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
    public function newMultiSiteAction()
    {
        $sitePackages = $this->packageManager->getFilteredPackages('available', null, 'typo3-flow-site');
        $this->view->assignMultiple(array(
            'sitePackages' => $sitePackages
        ));
    }

    /**
     * @param string $packageKey
     * @param string $siteName
     */
    public function createMultiSiteAction($packageKey, $siteName= '')
    {
        $this->siteService->importSiteFromTemplate($packageKey, $siteName);
        $this->redirect('index');
    }

}