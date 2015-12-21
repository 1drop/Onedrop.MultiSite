<?php
namespace Onedrop\MultiSite\Command;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Cli\CommandController;

/**
 * The MultiSite Command Controller
 *
 * @Flow\Scope("singleton")
 */
class MultiSiteCommandController extends CommandController
{

    /**
     * @Flow\Inject
     * @var \Onedrop\MultiSite\Service\SiteService
     */
    protected $siteService;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Log\SystemLoggerInterface
     */
    protected $systemLogger;

    /**
     * Create a new site from a site template
     *
     * This command uses a site template located in a sitePackage to create a new site with a new subdomain.
     *
     * @param string $sitePackage Package key of the Site containing the template under Templates/Content/Sites.xml
     * @param string $siteName The sitename
     * @param string $baseDomain The base domain name e.g. site.com => will be used like $siteName.site.com
     * @return void
     */
    public function newSiteCommand($sitePackage, $siteName, $baseDomain)
    {
        try {
            $site = $this->siteService->importSiteFromTemplate($sitePackage, $siteName, $baseDomain);
            $this->outputLine('Created a new site "%s"', array($site->getName()));
        } catch (\Exception $e) {
            $this->systemLogger->logException($e);
        }
    }
}