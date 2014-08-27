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

class JoomlaBacklinkCheckerViewJoomlaBacklinkChecker extends JViewLegacy
{
    protected $_state;

    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_JOOMLABACKLINKCHECKER')." - ".JText::_('COM_JOOMLABACKLINKCHECKER_SUBMENU_ENTRIES'), 'joomlabacklinkchecker');

        if(JFactory::getUser()->authorise('joomlabacklinkchecker.checkcomplete', 'com_joomlabacklinkchecker'))
        {
            JToolBarHelper::custom('checkcomplete', 'check-complete', 'check-complete', JText::_('COM_JOOMLABACKLINKCHECKER_CHECKCOMPLETE'), false);
        }

        if(JFactory::getUser()->authorise('joomlabacklinkchecker.checkselection', 'com_joomlabacklinkchecker'))
        {
            JToolBarHelper::custom('checkselection', 'check-selection', 'check-selection', JText::_('COM_JOOMLABACKLINKCHECKER_CHECKSELECTION'), true);
        }

        if(JFactory::getUser()->authorise('joomlabacklinkchecker.new', 'com_joomlabacklinkchecker'))
        {
            JToolBarHelper::addNew();
        }

        if(JFactory::getUser()->authorise('joomlabacklinkchecker.edit', 'com_joomlabacklinkchecker'))
        {
            JToolBarHelper::editList();
        }

        if(JFactory::getUser()->authorise('core.delete', 'com_joomlabacklinkchecker'))
        {
            JToolBarHelper::deleteList();
        }

        if(JFactory::getUser()->authorise('core.admin', 'com_joomlabacklinkchecker'))
        {
            JToolBarHelper::preferences('com_joomlabacklinkchecker', '500');
        }

        $items = $this->get('Data');
        $pagination = $this->get('Pagination');
        $this->_state = $this->get('State');

        // Get Categories for selection list
        $categories = $this->get('Categories', 'categories');

        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_joomlabacklinkchecker/css/joomlabacklinkchecker.css');

        $this->assignRef('items', $items);
        $this->assignRef('pagination', $pagination);
        $this->assignRef('categories', $categories);

        require_once JPATH_COMPONENT.'/helpers/joomlabacklinkchecker.php';
        $donation_code_message = JoomlaBacklinkCheckerHelper::getDonationCodeMessage();
        $this->assignRef('donation_code_message', $donation_code_message);

        parent::display($tpl);
    }
}
