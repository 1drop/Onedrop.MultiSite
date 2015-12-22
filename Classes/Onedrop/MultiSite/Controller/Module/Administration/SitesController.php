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
        $host = $this->request->getHttpRequest()->getBaseUri()->getHost();
        $possibleDomains = [];
        $hostParts = explode('.', $host);
        while (count($hostParts) > 1) {
            $possibleDomain = implode('.', $hostParts);
            $possibleDomains[$possibleDomain] = $possibleDomain;
            array_shift($hostParts);
        }

        $this->view->assignMultiple(array(
            'sitePackages' => $sitePackages,
            'possibleDomains' => $possibleDomains
        ));
    }

    /**
     * @param string $packageKey
     * @param string $baseDomain
     * @param string $siteName
     * @Flow\Validate(argumentName="$siteName", type="\TYPO3\Flow\Validation\Validator\NotEmptyValidator")
     */
    public function createMultiSiteAction($packageKey, $baseDomain, $siteName= '')
    {
        $this->siteService->importSiteFromTemplate($packageKey, $siteName, $baseDomain);
        $this->redirect('index');
    }

}