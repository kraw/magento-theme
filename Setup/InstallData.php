<?php

namespace Ryker\Brenhouse\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Ryker\Brenhouse\Model\SocialFactory;

class InstallData implements InstallDataInterface
{

    const TABLE_CMS_BLOCK = 'cms_block';

    const TABLE_CMS_BLOCK_STORE = 'cms_block_store';

    const STORE_ID = 0;

    /**
     * EAV setup factory
     *
     * @var SocialFactory
     */
    private $socialFactory;


    /**
     * Init
     *
     * @param SocialFactory $socialFactory
     */
    public function __construct(SocialFactory $socialFactory)
    {
        $this->socialFactory = $socialFactory;
    }

    public function install( ModuleDataSetupInterface $setup, ModuleContextInterface $context ) {
        $twitter = [
            'title' => 'Twitter Profile Url',
            'key'   => 'twitter',
            'value' => ''
        ];
        $instagram = [
            'title' => 'Instagram Access Token',
            'key'   => 'instagram',
            'value' => ''
        ];
        $instagram2 = [
            'title' => 'Instagram Profile Url',
            'key'   => 'insta_url',
            'value' => ''
        ];

        $config = $this->socialFactory->create();
        $config->addData($twitter)->save();
        $config = $this->socialFactory->create();
        $config->addData($instagram)->save();
        $config = $this->socialFactory->create();
        $config->addData($instagram2)->save();


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dir = $objectManager->get('Magento\Framework\Filesystem');
        $reader = $dir->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::APP);
        $slidesDirectoryPath = $reader->getAbsolutePath('design/frontend/ryker/brenhouse/Magento_Theme/templates/html/slides/');
        $contentsDirectoryPath = $reader->getAbsolutePath('design/frontend/ryker/brenhouse/Magento_Theme/templates/html/contents/');

        $cmsBlockTable = $setup->getTable(self::TABLE_CMS_BLOCK);

        /* Set homepage slides */
        for ($i = 1; $i < 5; $i ++) {
            $path = $slidesDirectoryPath . 'slide-' . $i . '.phtml';
            $slideContent = file_get_contents($path);
            $this->setStaticBlock($setup, "slide_$i", "Slide $i", $slideContent);

            $this->createTestBlockStoreAssociation($setup, $this->getBlockId($setup->getConnection(), $cmsBlockTable, "slide_$i"));
        }

        /* Set homepage containers */
        for ($i = 1; $i < 4; $i ++) {
            $path = $contentsDirectoryPath . 'content' . $i . '.phtml';
            $content = file_get_contents($path);
            $this->setStaticBlock($setup, "content_$i", "Content $i", $content);

            $this->createTestBlockStoreAssociation($setup, $this->getBlockId($setup->getConnection(), $cmsBlockTable, "content_$i"));
        }

    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param $identifier
     * @param $title
     * @param $content
     */
    private function setStaticBlock(ModuleDataSetupInterface $setup, $identifier, $title, $content) {
        $setup->getConnection()->insertForce(
          $setup->getTable(self::TABLE_CMS_BLOCK), [
            'title' => $title,
            'identifier' => $identifier,
            'content' => $content,
            'is_active' => 1
          ]
        );
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param $testBlockId
     */
    private function createTestBlockStoreAssociation(ModuleDataSetupInterface $setup, $blockId)
    {
        $setup->getConnection()->insertForce(
          $setup->getTable(self::TABLE_CMS_BLOCK_STORE), [
            'store_id' => self::STORE_ID,
            'block_id' => $blockId
          ]
        );
    }

    /**
     * @param $connection
     * @param $cmsBlockTable
     * @return bool
     */
    private function getBlockId($connection, $cmsBlockTable, $identifier)
    {
        $select = $connection->select()
          ->from(['o' => $cmsBlockTable])
          ->where('o.identifier=?', $identifier)
          ->limit(1);

        $row = $connection->fetchRow($select);
        if ($row) {
            return $row['block_id'];
        }
        return false;
    }
}