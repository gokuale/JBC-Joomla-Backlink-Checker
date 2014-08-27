<?php
/**
 * @Copyright
 * @package         JBC - Joomla Backlink Checker
 * @author          Viktor Vogel {@link http://www.kubik-rubik.de}
 * @link            http://joomla-extensions.kubik-rubik.de/jbc-joomla-backlink-checker Project page
 *
 * @license         http://www.gnu.org/licenses/gpl.html GNU/GPL, see gpl_v3.txt
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
defined('_JEXEC') or die('Restricted access');
define('_JOOMLABACKLINKCHECKER_VERSION', '2.5-1');

if(!JFactory::getUser()->authorise('core.manage', 'com_joomlabacklinkchecker'))
{
    throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
}

require_once JPATH_COMPONENT.'/controller.php';

if($controller = JFactory::getApplication()->input->getWord('controller', ''))
{
    $path = JPATH_COMPONENT.'/controllers/'.$controller.'.php';

    if(file_exists($path))
    {
        require_once $path;
    }
    else
    {
        $controller = '';
    }
}

$classname = 'JoomlaBacklinkCheckerController'.$controller;
$controller = new $classname();
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
