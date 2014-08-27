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

class JoomlaBacklinkCheckerControllerCategory extends JControllerLegacy
{
    protected $_input;

    function __construct()
    {
        parent::__construct();

        $this->registerTask('apply', 'save');
        $this->_input = JFactory::getApplication()->input;
    }

    /**
     * Saves the category item to the database and redirects the call to the corresponding page
     */
    function save()
    {
        JSession::checkToken() OR jexit('Invalid Token');

        $model = $this->getModel('category');

        if($model->store())
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_SUCCESS_CATEGORYSAVE');
            $type = 'message';
        }
        else
        {
            // Save the input data to avoid loss, but only if task is apply
            $model->storeInputSession($this->_input);

            if($model->getError() == 'duplicate')
            {
                $msg = JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_CATEGORYDUPLICATE');
            }
            elseif($model->getError() == 'mandatory')
            {
                $msg = JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_CATEGORYMANDATORY');
            }
            else
            {
                $msg = JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_CATEGORYSAVE');
            }

            $type = 'error';

            // If an error occurred, then always redirect back to the edit form
            if(!$model->getId())
            {
                $this->_input->set('url_current', 'option=com_joomlabacklinkchecker&controller=categories&task=edit');
                $this->setRedirect('index.php?'.$this->_input->getString('url_current', ''), $msg, $type);
            }
            else
            {
                $this->setRedirect('index.php?'.$this->_input->getString('url_current', ''), $msg, $type);
            }

            return;
        }

        if($this->task == 'apply')
        {
            $this->setRedirect('index.php?'.$this->_input->getString('url_current', '').'&id='.$this->_input->getInt('id', 0), $msg, $type);
        }
        else
        {
            $this->setRedirect('index.php?option=com_joomlabacklinkchecker&view=categories', $msg, $type);
        }
    }

    /**
     * Removes categories from the database and redirects the call to the corresponding page
     */
    function remove()
    {
        JSession::checkToken() OR jexit('Invalid Token');

        $model = $this->getModel('category');

        if($model->delete())
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_SUCCESS_CATEGORYDELETE');
            $type = 'error';
        }
        else
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_CATEGORYDELETE');
            $type = 'message';
        }

        $this->setRedirect(JRoute::_('index.php?option=com_joomlabacklinkchecker&view=categories', false), $msg, $type);
    }

    /**
     * Aborts the current operation
     */
    function cancel()
    {
        $msg = JText::_('COM_JOOMLABACKLINKCHECKER_OPERATION_CANCELLED');
        $this->setRedirect('index.php?option=com_joomlabacklinkchecker&view=categories', $msg, 'notice');
    }
}
