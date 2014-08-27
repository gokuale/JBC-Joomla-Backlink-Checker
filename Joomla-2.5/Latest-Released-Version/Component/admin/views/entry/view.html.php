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

class JoomlaBacklinkCheckerViewEntry extends JViewLegacy
{
    function display($tpl = null)
    {
        $entry = $this->get('Data');

        // Get Categories for selection list
        $categories = $this->get('Categories', 'categories');

        if(empty($entry->id))
        {
            JToolBarHelper::title(JText::_('COM_JOOMLABACKLINKCHECKER').' - '.JText::_('COM_JOOMLABACKLINKCHECKER_NEWENTRY'), 'joomlabacklinkchecker-add');
            JToolBarHelper::save('save');
            JToolBarHelper::cancel('cancel');
        }
        else
        {
            JToolBarHelper::title(JText::_('COM_JOOMLABACKLINKCHECKER').' - '.JText::_('COM_JOOMLABACKLINKCHECKER_EDITENTRY'), 'joomlabacklinkchecker-edit');
            JToolbarHelper::apply('apply');
            JToolBarHelper::save('save');
            JToolBarHelper::custom('checkselection', 'extension', 'extension', JText::_('COM_JOOMLABACKLINKCHECKER_SAVECHECK_BUTTON'), false);
            JToolBarHelper::cancel('cancel', 'Close');
        }

        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_joomlabacklinkchecker/css/joomlabacklinkchecker.css');

        $this->assignRef('entry', $entry);
        $this->assignRef('categories', $categories);

        require_once JPATH_COMPONENT.'/helpers/joomlabacklinkchecker.php';
        $donation_code_message = JoomlaBacklinkCheckerHelper::getDonationCodeMessage();
        $this->assignRef('donation_code_message', $donation_code_message);

        parent::display($tpl);
    }
}
