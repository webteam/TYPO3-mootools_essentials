<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2012 
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 *
 *
 * @package mootools_essentials
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Tx_MootoolsEssentials_Controller_IncludeJsController extends Tx_Extbase_MVC_Controller_ActionController {

	/**
	 * includes all needed Javascript
	 *
	 * @return void
	 */
	public function includeJsAction() {
		foreach ($this->settings['manifests'] as $key => $manifest) {
			$this->settings['manifests'][$key] = t3lib_div::getFileAbsFileName($manifest);
		}

		$packager = t3lib_div::makeInstance('Tx_MootoolsEssentials_Domain_Model_Packager');
		$packager->addManifests($this->settings['manifests']);
		$packager->addFiles($this->settings['load']['files']);

		$files = $packager->getCompleteFiles();

		$renderer = t3lib_div::makeInstance('t3lib_PageRenderer');
		foreach ($files as $file) {
			$renderer->addJsLibrary($file, $packager->getFilePath($file));
		}

		if (in_array('Behavior/Behavior', $files)) {
			if (in_array('Behavior/Delegator', $files)) {
				$renderer->addJsFooterInlineCode('behaviorAndDelegatorAtBottom', "var myBehavior = new Behavior().apply(document.body);	var myDelegator = new Delegator({getBehavior: function(){ return myBehavior; }}).attach(document.body);");
			} else {
				$renderer->addJsFooterInlineCode('behaviorAtBottom', "var myBehavior = new Behavior().apply(document.body);");
			}
		} else {
			if (in_array('Behavior/Delegator', $files)) {
				$renderer->addJsFooterInlineCode('delegatorAtBottom', "var myDelegator = new Delegator().attach(document.body);");
			}
		}
		return '';
	}

}
?>