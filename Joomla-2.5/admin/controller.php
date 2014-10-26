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
jimport('joomla.application.component.controller');

class JoomlaBacklinkCheckerController extends JControllerLegacy
{
    public function display($cachable = false, $urlparams = false)
    {
        // We need the category model also in the default view for the selection list
        $view = $this->getView('joomlabacklinkchecker', 'html');

        if($model_categories = $this->getModel('categories'))
        {
            $view->setModel($model_categories, false);
        }

        // Set submenu
        require_once JPATH_COMPONENT.'/helpers/joomlabacklinkchecker.php';
        JoomlaBacklinkCheckerHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'joomlabacklinkchecker'));

        parent::display();
    }
}
