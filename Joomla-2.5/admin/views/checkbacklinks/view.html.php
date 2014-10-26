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

class JoomlaBacklinkCheckerViewCheckBacklinks extends JViewLegacy
{
    function display($tpl = null)
    {
        JToolBarHelper::title(JText::_('COM_JOOMLABACKLINKCHECKER').' - '.JText::_('COM_JOOMLABACKLINKCHECKER_RESULT'), 'joomlabacklinkchecker-add');
        JToolBarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_joomlabacklinkchecker');

        $check_result = JFactory::getApplication()->input->get('check_result', array(), 'ARRAY');
        $this->assignRef('check_result', $check_result);

        // Show the edit button?
        if(JFactory::getUser()->authorise('joomlabacklinkchecker.edit', 'com_joomlabacklinkchecker'))
        {
            $this->edit_allowed = true;
        }

        // Debug mode only if activated, task is checkselection, single entry is checked and debug output is available
        // Maybe could be changed in a further version with a popup display for each entry...
        $params = JComponentHelper::getParams('com_joomlabacklinkchecker');

        if($params->get('debug', 0) AND JFactory::getApplication()->input->get('task') == 'checkselection' AND count($check_result) == 1 AND !empty($check_result[0]['result_debug']))
        {
            $this->assignRef('debug', $params->get('debug', 0));
        }

        $document = JFactory::getDocument();
        $document->addStyleSheet('components/com_joomlabacklinkchecker/css/joomlabacklinkchecker.css');

        require_once JPATH_COMPONENT.'/helpers/joomlabacklinkchecker.php';
        $donation_code_message = JoomlaBacklinkCheckerHelper::getDonationCodeMessage();
        $this->assignRef('donation_code_message', $donation_code_message);

        parent::display($tpl);
    }
}
