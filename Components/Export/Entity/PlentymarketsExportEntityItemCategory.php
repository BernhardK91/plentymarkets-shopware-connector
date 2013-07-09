<?php
require_once __DIR__ . '/../../Soap/Models/PlentySoapObject/GetItemCategoryCatalogBase.php';
require_once __DIR__ . '/../../Soap/Models/PlentySoapRequest/GetItemCategoryCatalogBase.php';
require_once __DIR__ . '/../../Soap/Models/PlentySoapRequest/AddItemCategory.php';

/**
 *
 * @author Daniel Bächtle <daniel.baechtle@plentymarkets.com>
 */
class PlentymarketsExportEntityItemCategory
{

	/**
	 *
	 * @var array
	 */
	protected $mappingShopwareID2PlentyID = array();

	/**
	 *
	 * @var arrray
	 */
	protected $PLENTY_nameAndLevel2ID = array();

	/**
	 * Build the index and export the missing data to plentymarkets
	 */
	public function export()
	{
		$this->buildPlentyNameAndLevelIndex();
		$this->doExport();
		$this->buildMapping();
	}

	/**
	 * Build an index of the existing data
	 *
	 * @todo language
	 */
	protected function buildPlentyNameAndLevelIndex()
	{
		// Fetch the category catalog from plentmakets
		$Request_GetItemCategoryCatalogBase = new PlentySoapRequest_GetItemCategoryCatalogBase();
		$Request_GetItemCategoryCatalogBase->Lang = 'de'; // string
		$Request_GetItemCategoryCatalogBase->Level = null; // int
		$Request_GetItemCategoryCatalogBase->Page = 0;

		do
		{
			$Response_GetItemCategoryCatalogBase = PlentymarketsSoapClient::getInstance()->GetItemCategoryCatalogBase($Request_GetItemCategoryCatalogBase);
			foreach ($Response_GetItemCategoryCatalogBase->Categories->item as $Category)
			{
				$this->PLENTY_nameAndLevel2ID[$Category->Level][$Category->Name] = $Category->CategoryID;
			}
		}
		while (++$Request_GetItemCategoryCatalogBase->Page < $Response_GetItemCategoryCatalogBase->Pages);
	}

	/**
	 * Export the missing categories to plentymarkets
	 */
	protected function doExport()
	{
		foreach (Shopware()->Models()
			->getRepository('Shopware\Models\Category\Category')
			->findBy(array(
			'blog' => 0
		)) as $Category)
		{
			$Category instanceof Shopware\Models\Category\Category;

			if ($Category->getLevel() == 0)
			{
				continue;
			}

			if (array_key_exists($Category->getLevel(), $this->PLENTY_nameAndLevel2ID) && array_key_exists($Category->getName(), $this->PLENTY_nameAndLevel2ID[$Category->getLevel()]))
			{
				$categoryIdAdded = $this->PLENTY_nameAndLevel2ID[$Category->getLevel()][$Category->getName()];
			}

			else
			{
				$Request_AddItemCategory = new PlentySoapRequest_AddItemCategory();
				$Request_AddItemCategory->Lang = 'de';
				$Request_AddItemCategory->Level = $Category->getLevel();
				$Request_AddItemCategory->MetaDescription = $Category->getMetaDescription();
				$Request_AddItemCategory->MetaKeywords = $Category->getMetaKeywords();
				$Request_AddItemCategory->MetaTitle = $Category->getCmsHeadline();
				$Request_AddItemCategory->Name = $Category->getName();
				$Request_AddItemCategory->Text = $Category->getCmsText();

				$Response_AddItemCategory = PlentymarketsSoapClient::getInstance()->AddItemCategory($Request_AddItemCategory);
				$categoryIdAdded = (integer) $Response_AddItemCategory->ResponseMessages->item[0]->SuccessMessages->item[0]->Value;
			}

			$this->mappingShopwareID2PlentyID[$Category->getId()] = $categoryIdAdded;
		}
	}

	protected function buildMapping()
	{
		foreach (Shopware()->Models()
			->getRepository('Shopware\Models\Category\Category')
			->findBy(array(
			'blog' => 0,
			'level' => 1
		)) as $Category)
		{
			$Category instanceof Shopware\Models\Category\Category;

			$path = array(
				$this->mappingShopwareID2PlentyID[$Category->getId()]
			);

			//
			$children1 = $Category->getChildren();

			if (count($children1))
			{
				// plentymarkets level 2
				foreach ($children1 as $Child2)
				{
					$Child2 instanceof Shopware\Models\Category\Category;

					$path[1] = $this->mappingShopwareID2PlentyID[$Child2->getId()];

					//
					$children2 = $Child2->getChildren();

					if (count($children2))
					{
						// plentymarkets level 2
						foreach ($children2 as $Child3)
						{
							$Child3 instanceof Shopware\Models\Category\Category;

							$path[2] = $this->mappingShopwareID2PlentyID[$Child3->getId()];

							//
							$children3 = $Child3->getChildren();

							if (count($children3))
							{
								// plentymarkets level 2
								foreach ($children3 as $Child4)
								{
									$Child4 instanceof Shopware\Models\Category\Category;

									$path[3] = $this->mappingShopwareID2PlentyID[$Child4->getId()];

									//
									$children4 = $Child4->getChildren();

									if (count($children4))
									{
										// plentymarkets level 2
										foreach ($children4 as $Child5)
										{
											$Child5 instanceof Shopware\Models\Category\Category;

											$path[4] = $this->mappingShopwareID2PlentyID[$Child5->getId()];

											//
											$children5 = $Child5->getChildren();

											if (count($children5))
											{
												// plentymarkets level 2
												foreach ($children5 as $Child6)
												{
													$Child6 instanceof Shopware\Models\Category\Category;

													$path[5] = $this->mappingShopwareID2PlentyID[$Child6->getId()];
													PlentymarketsMappingController::addCategory($Child6->getId(), implode(';', $path));
												}
											} // 6
											else
											{
												unset($path[5]);
												PlentymarketsMappingController::addCategory($Child5->getId(), implode(';', $path));
											}
										}
									} // 5
									else
									{
										unset($path[5], $path[4]);
										PlentymarketsMappingController::addCategory($Child4->getId(), implode(';', $path));
									}
								}
							} // 4
							else
							{
								unset($path[5], $path[4], $path[3]);
								PlentymarketsMappingController::addCategory($Child3->getId(), implode(';', $path));
							}
						}
					} // 3
					else
					{
						unset($path[5], $path[4], $path[3], $path[2]);
						PlentymarketsMappingController::addCategory($Child2->getId(), implode(';', $path));
					}
				}
			} // 2
			else
			{
				unset($path[5], $path[4], $path[3], $path[2], $path[1]);
				PlentymarketsMappingController::addCategory($Category->getId(), implode(';', $path));
			}
		}
	}
}
