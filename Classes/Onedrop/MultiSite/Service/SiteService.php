<?php
namespace Onedrop\MultiSite\Service;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Http\Request;
use TYPO3\Flow\Utility\Environment;
use TYPO3\Fluid\View\StandaloneView;
use TYPO3\Neos\Domain\Model\Domain;
use TYPO3\Neos\Domain\Model\Site;
use TYPO3\Neos\Domain\Repository\DomainRepository;
use TYPO3\Neos\Domain\Service\SiteImportService;

/**
 * A service for manipulating sites
 *
 * @Flow\Scope("singleton")
 */
class SiteService
{
    /**
     * @Flow\Inject
     * @var DomainRepository
     */
    protected $domainRepository;

    /**
     * @Flow\Inject
     * @var SiteImportService
     */
    protected $siteImportService;

    /**
     * @Flow\Inject
     * @var Environment
     */
    protected $environment;

    /**
     * @Flow\Inject
     * @var \TYPO3\Flow\Resource\ResourceManager
     */
    protected $resourceManager;


    /**
     * @param string $sitePackage
     * @param string $siteName
     * @param string $baseDomain
     * @return Site
     */
    public function importSiteFromTemplate($sitePackage, $siteName, $baseDomain = '')
    {
        if (empty($baseDomain)) {
            $request = Request::createFromEnvironment();
            $baseDomain = $request->getBaseUri()->getHost();
        }

        $siteTemplate = new StandaloneView();
        $siteTemplate->setTemplatePathAndFilename(FLOW_PATH_PACKAGES . 'Sites/' . $sitePackage . '/Resources/Private/Templates/Content/Sites.xml');
        $siteTemplate->assignMultiple(['siteName' => $siteName, 'siteNodeName' => \TYPO3\TYPO3CR\Utility::renderValidNodeName($siteName), 'packageKey' => $sitePackage]);

        $generatedSiteImportXmlContent = $siteTemplate->render();

        $dataTemporaryPath = $this->environment->getPathToTemporaryDirectory();
        $temporarySiteXml = $dataTemporaryPath . uniqid($siteName) . '.xml';
        file_put_contents($temporarySiteXml, $generatedSiteImportXmlContent);

        $site = $this->siteImportService->importFromFile($temporarySiteXml);

        $domain = new Domain();
        $domain->setActive(true);
        $domain->setSite($site);
        $domain->setHostPattern(\TYPO3\TYPO3CR\Utility::renderValidNodeName($siteName) . '.' . $baseDomain);
        $this->domainRepository->add($domain);

        return $site;
    }

}