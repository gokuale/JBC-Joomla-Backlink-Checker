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

class JoomlaBacklinkCheckerControllerCategories extends JControllerLegacy
{
    protected $_input;

    function __construct()
    {
        parent::__construct();

        $this->_input = JFactory::getApplication()->input;

        $this->registerTask('add', 'edit');

        if(!$this->_input->getCmd('task', ''))
        {
            // Set submenu
            require_once JPATH_COMPONENT.'/helpers/joomlabacklinkchecker.php';
            JoomlaBacklinkCheckerHelper::addSubmenu($this->_input->getCmd('view', 'joomlabacklinkchecker'));
        }
    }

    /**
     * Loads the edit form for new and existing category entries
     *
     * @throws Exception
     */
    function edit()
    {
        if($this->_input->get('task', '', 'STRING') == 'add' AND !JFactory::getUser()->authorise('joomlabacklinkchecker.categoriesnew', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        if($this->_input->get('task', '', 'STRING') == 'edit' AND !JFactory::getUser()->authorise('joomlabacklinkchecker.categoriesedit', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $this->_input->set('view', 'category');
        $this->_input->set('layout', 'form');
        $this->_input->set('hidemainmenu', 1);
        parent::display();
    }

    /**
     * Deletes selected entries and the corresponding backup archives
     *
     * @throws Exception
     */
    public function remove()
    {
        JSession::checkToken() OR jexit('Invalid Token');

        if(!JFactory::getUser()->authorise('core.delete', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $model = $this->getModel('categories');

        if($model->delete())
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_SUCCESS_CATEGORYDELETE');
            $type = 'message';
        }
        else
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_CATEGORYDELETE');
            $type = 'error';
        }

        $this->setRedirect(JRoute::_('index.php?option=com_joomlabacklinkchecker&view=categories', false), $msg, $type);
    }
}
