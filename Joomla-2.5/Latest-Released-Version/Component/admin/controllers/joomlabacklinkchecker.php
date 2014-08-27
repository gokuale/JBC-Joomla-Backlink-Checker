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

class JoomlaBacklinkCheckerControllerJoomlaBacklinkChecker extends JControllerLegacy
{
    protected $_input;

    function __construct()
    {
        parent::__construct();

        $this->_input = JFactory::getApplication()->input;
        $this->registerTask('add', 'edit');

        if(!$this->_input->getCmd('task', ''))
        {
            $view = $this->getView('joomlabacklinkchecker', 'html');

            if($model_categories = $this->getModel('categories'))
            {
                $view->setModel($model_categories, false);
            }

            // Set submenu
            require_once JPATH_COMPONENT.'/helpers/joomlabacklinkchecker.php';
            JoomlaBacklinkCheckerHelper::addSubmenu($this->_input->getCmd('view', 'joomlabacklinkchecker'));
        }
    }

    /**
     * Loads the edit template after ACL checks
     *
     * @throws Exception
     */
    function edit()
    {
        if($this->_input->get('task', '', 'STRING') == 'add' AND !JFactory::getUser()->authorise('joomlabacklinkchecker.new', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        if($this->_input->get('task', '', 'STRING') == 'edit' AND !JFactory::getUser()->authorise('joomlabacklinkchecker.edit', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        $this->_input->set('view', 'entry');
        $this->_input->set('layout', 'form');
        $this->_input->set('hidemainmenu', 1);

        $view = $this->getView('entry', 'html', '', array('base_path' => $this->basePath, 'layout' => 'form'));

        if($model_categories = $this->getModel('categories'))
        {
            $view->setModel($model_categories, false);
        }

        parent::display();
    }

    /**
     * Entry point for complete check
     *
     * @throws Exception
     */
    public function checkcomplete()
    {
        if(!JFactory::getUser()->authorise('joomlabacklinkchecker.checkcomplete', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        if(!ini_get('allow_url_fopen') AND !function_exists('curl_init'))
        {
            $this->setRedirect(JRoute::_('index.php?option=com_joomlabacklinkchecker', false), JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_QUERYMETHOD'), 'error');

            return;
        }

        $this->backlinkCheck('checkcomplete');
    }

    /**
     * Entry point for selection check
     *
     * @throws Exception
     */
    public function checkselection()
    {
        if(!JFactory::getUser()->authorise('joomlabacklinkchecker.checkselection', 'com_joomlabacklinkchecker'))
        {
            throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
        }

        if(!ini_get('allow_url_fopen') AND !function_exists('curl_init'))
        {
            $this->setRedirect(JRoute::_('index.php?option=com_joomlabacklinkchecker', false), JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_QUERYMETHOD'), 'error');

            return;
        }

        $this->backlinkCheck('checkselection');
    }

    /**
     * Executes the backlink checks in dependence on the submitted type
     *
     * @param string $type
     */
    private function backlinkCheck($type)
    {
        JSession::checkToken() OR jexit('Invalid Token');

        // Try to increase all relevant settings to prevent timeouts on big sites
        ini_set('memory_limit', '128M');
        ini_set('error_reporting', 0);
        @set_time_limit(3600);

        $model_data = $this->getModel('joomlabacklinkchecker');

        if($type == 'checkcomplete')
        {
            $data = $model_data->getData();
        }
        elseif($type == 'checkselection')
        {
            $ids = array_map('intval', JFactory::getApplication()->input->get('id', array(), 'ARRAY'));
            $data = $model_data->getDataSelection($ids);
        }

        $model = $this->getModel('checkbacklinks');

        if(!empty($data) AND $model->checkBacklinks($data))
        {
            $this->_input->set('view', 'checkbacklinks');
            $this->_input->set('hidemainmenu', 1);
            parent::display();
        }
        else
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_CHECK_ERROR');
            $type = 'error';
            $this->setRedirect('index.php?option=com_joomlabacklinkchecker', $msg, $type);
        }
    }

    /**
     * Deletes selected entries
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

        $model = $this->getModel('checkbacklinks');

        if(!$model->delete())
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_ERROR_LINKCONNECTIONDELETE');
            $type = 'error';
        }
        else
        {
            $msg = JText::_('COM_JOOMLABACKLINKCHECKER_SUCCESS_LINKCONNECTIONDELETE');
            $type = 'message';
        }

        $this->setRedirect(JRoute::_('index.php?option=com_joomlabacklinkchecker', false), $msg, $type);
    }

    /**
     * Aborts the current operation
     */
    public function cancel()
    {
        $msg = JText::_('COM_JOOMLABACKLINKCHECKER_OPERATION_CANCELLED');
        $this->setRedirect('index.php?option=com_joomlabacklinkchecker', $msg, 'notice');
    }
}
