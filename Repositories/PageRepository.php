<?php

/**
 * This file is part of the Venne:CMS (https://github.com/Venne)
 *
 * Copyright (c) 2011, 2012 Josef Kříž (http://www.josef-kriz.cz)
 *
 * For the full copyright and license information, please view
 * the file license.txt that was distributed with this source code.
 */

namespace CoreModule\Repositories;

use Venne;
use Venne\Doctrine\ORM\BaseRepository;
use CoreModule\Entities\UserEntity;

/**
 * @author Josef Kříž <pepakriz@gmail.com>
 */
class PageRepository extends BaseRepository
{


	/**
	 * Save structure
	 *
	 * @param array $data
	 */
	public function setStructure($data, $withoutFlush = self::FLUSH)
	{
		foreach ($data as $item) {
			foreach ($item as $item2) {
				$entity = $this->find($item2["id"]);
				$entity->parent = $item2["parent_id"] ? $this->find($item2["parent_id"]) : NULL;
			}
		}

		$this->flush($withoutFlush);
	}



	/**
	 * Check if page URL is unique.
	 *
	 * @param \CoreModule\Entities\PageEntity $page
	 * @return bool
	 */
	public function isUnique(\CoreModule\Entities\PageEntity $page)
	{
		$pages = $this->findBy(array("url" => $page->url));

		if (!$pages || (count($pages) == 1 && $pages[0] === $page )) {
			return true;
		}

		foreach ($pages as $item) {
			if($item === $page){
				continue;
			}

			foreach ($item->languages as $lang) {
				if ($page->isInLanguageAlias($lang->alias)) {
					return false;
				}
			}
		}
		return true;
	}

}
