<?php

namespace Symfony\Cmf\Bundle\SeoBundle\Model;

use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;

/**
 * The abstract class for the SeoPresentation Model.
 *
 * It contains all needed setters for the DI and the some helpers for getting
 * locale, document manager and the SeoMetadata.
 *
 * @author Maximilian Berghoff <Maximilian.Berghoff@gmx.de>
 */
abstract class AbstractSeoPresentation implements SeoPresentationInterface
{
    /**
     * Storing the content parameters - config values under cmf_seo.content.
     *
     * @var array
     */
    protected $contentParameters;

    /**
     * Storing the title parameters - config values under cmf_seo.title.
     *
     * @var array
     */
    protected $titleParameters;

    /**
     * @var bool
     */
    protected $redirect = false;

    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @var string
     */
    protected $defaultLocale;

    /**
     * @var SeoAwareInterface
     */
    protected $contentDocument;

    /**
     * Setter for the redirect property.
     *
     * @param $redirect
     */
    protected function setRedirect($redirect)
    {
        $this->redirect = $redirect;
    }

    /**
     * {@inheritDoc}
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * This method is needed to get the default title parameters injected. They are used for
     * concatenating the default values and the seo meta data or defining the strategy for that.
     *
     * @param array $titleParameters
     */
    public function setTitleParameters(array $titleParameters)
    {
        $this->titleParameters = $titleParameters;
    }

    /**
     * This method is the setter injection for the content parameters which contain strategies for
     * duplicate content.
     *
     * @param array $contentParameters
     */
    public function setContentParameters(array $contentParameters)
    {
        $this->contentParameters = $contentParameters;
    }

    /**
     * The document manager is needed to detect the current locale of the document.
     *
     * @param \Doctrine\Bundle\PHPCRBundle\ManagerRegistry $managerRegistry
     */
    public function setDoctrineRegistry(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    /**
     * Setter for the default locale of the application.
     *
     * If the list of translated titles does not contain the locale of the current document,
     * or the current document has no locale at all, this locale is used instead.
     *
     * @param $locale
     */
    public function setDefaultLocale($locale)
    {
        $this->defaultLocale = $locale;
    }

    /**
     * {@inheritDoc}
     */
    public function setContentDocument(SeoAwareInterface $contentDocument)
    {
        $this->contentDocument = $contentDocument;
    }

    /**
     * To get the Document Manager out of the registry, this method needs to be called.
     *
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    protected function getDocumentManager()
    {
        return $this->managerRegistry->getManager();
    }

    /**
     * Get the applications default locale.
     *
     * @return string
     */
    protected function getApplicationDefaultLocale()
    {
        return $this->defaultLocale;
    }

    /**
     * This method uses the DocumentManager to get the documents current locale.
     * @param string
     */
    protected function getModelLocale()
    {
        return $this->getDocumentManager()->getUnitOfWork()->getCurrentLocale($this->contentDocument);
    }
}
