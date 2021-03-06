<?php
declare(strict_types = 1);

namespace TYPO3\CMS\Core\Tests\Unit\Page;

use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Page\AssetRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Unit\UnitTestCase;

class AssetRendererTest extends UnitTestCase
{
    /**
     * @var AssetRenderer
     */
    protected $assetRenderer;

    public function setUp(): void
    {
        parent::setUp();
        $this->resetSingletonInstances = true;
        $this->assetRenderer = GeneralUtility::makeInstance(AssetRenderer::class);
    }

    /**
     * @param array $files
     * @param array $expectedResult
     * @param array $expectedMarkup
     * @dataProvider \TYPO3\CMS\Core\Tests\Unit\Page\AssetDataProvider::filesDataProvider
     */
    public function testStyleSheets(array $files, array $expectedResult, array $expectedMarkup): void
    {
        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        foreach ($files as $file) {
            [$identifier, $source, $attributes, $options] = $file;
            $assetCollector->addStyleSheet($identifier, $source, $attributes, $options);
        }
        self::assertSame($expectedMarkup['css_no_prio'], $this->assetRenderer->renderStyleSheets());
        self::assertSame($expectedMarkup['css_prio'], $this->assetRenderer->renderStyleSheets(true));
    }

    /**
     * @param array $files
     * @param array $expectedResult
     * @param array $expectedMarkup
     * @dataProvider \TYPO3\CMS\Core\Tests\Unit\Page\AssetDataProvider::filesDataProvider
     */
    public function testJavaScript(array $files, array $expectedResult, array $expectedMarkup): void
    {
        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        foreach ($files as $file) {
            [$identifier, $source, $attributes, $options] = $file;
            $assetCollector->addJavaScript($identifier, $source, $attributes, $options);
        }
        self::assertSame($expectedMarkup['js_no_prio'], $this->assetRenderer->renderJavaScript());
        self::assertSame($expectedMarkup['js_prio'], $this->assetRenderer->renderJavaScript(true));
    }

    /**
     * @param array $sources
     * @param array $expectedResult
     * @param array $expectedMarkup
     * @dataProvider \TYPO3\CMS\Core\Tests\Unit\Page\AssetDataProvider::inlineDataProvider
     */
    public function testInlineJavaScript(array $sources, array $expectedResult, array $expectedMarkup): void
    {
        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        foreach ($sources as $source) {
            [$identifier, $source, $attributes, $options] = $source;
            $assetCollector->addInlineJavaScript($identifier, $source, $attributes, $options);
        }
        self::assertSame($expectedMarkup['js_no_prio'], $this->assetRenderer->renderInlineJavaScript());
        self::assertSame($expectedMarkup['js_prio'], $this->assetRenderer->renderInlineJavaScript(true));
    }

    /**
     * @param array $sources
     * @param array $expectedResult
     * @param array $expectedMarkup
     * @dataProvider \TYPO3\CMS\Core\Tests\Unit\Page\AssetDataProvider::inlineDataProvider
     */
    public function testInlineStyleSheets(array $sources, array $expectedResult, array $expectedMarkup): void
    {
        $assetCollector = GeneralUtility::makeInstance(AssetCollector::class);
        foreach ($sources as $source) {
            [$identifier, $source, $attributes, $options] = $source;
            $assetCollector->addInlineStyleSheet($identifier, $source, $attributes, $options);
        }
        self::assertSame($expectedMarkup['css_no_prio'], $this->assetRenderer->renderInlineStyleSheets());
        self::assertSame($expectedMarkup['css_prio'], $this->assetRenderer->renderInlineStyleSheets(true));
    }
}
