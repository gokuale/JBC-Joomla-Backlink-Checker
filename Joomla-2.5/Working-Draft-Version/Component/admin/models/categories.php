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
jimport('joomla.application.component.model');

class JoomlaBacklinkCheckerModelCategories extends JModelLegacy
{
    protected $_total;
    protected $_pagination;

    function __construct()
    {
        parent::__construct();
        $mainframe = JFactory::getApplication();
        $this->_input = JFactory::getApplication()->input;

        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest('joomlabacklinkchecker.limitstart', 'limitstart', 0, 'int');
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $search = $mainframe->getUserStateFromRequest('joomlabacklinkchecker.filter.search', 'filter_search', null);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter.search', $search);
    }

    /**
     * Loads all or filtered entries from the database
     *
     * @return array
     */
    function getCategories()
    {
        if(empty($this->_data))
        {
            $query = $this->_db->getQuery(true);

            $query->select('*');
            $query->from('#__joomlabacklinkchecker_categories AS a');

            $search = $this->getState('filter.search');

            if(!empty($search))
            {
                $search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
                $query->where('(a.name LIKE '.$search.') OR (a.description LIKE '.$search.')');
            }

            $query->order($this->_db->escape('name ASC'));

            $this->_data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_data;
    }

    /**
     * Creates the pagination in the footer of the list
     *
     * @return JPagination
     */
    function getPagination()
    {
        if(empty($this->_pagination))
        {
            jimport('joomla.html.pagination');
            $this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->_pagination;
    }

    /**
     * Calculates the total number of all loaded entries
     *
     * @return int
     */
    function getTotal()
    {
        if(empty($this->_total))
        {
            $query = $this->_db->getQuery(true);

            $query->select('*');
            $query->from('#__joomlabacklinkchecker_categories AS a');

            $search = $this->getState('filter.search');

            if(!empty($search))
            {
                $search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
                $query->where('(a.name LIKE '.$search.') OR (a.description LIKE '.$search.')');
            }

            $query->order($this->_db->escape('name ASC'));

            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    /**
     * Deletes backup files from the server and the corresponding database entries
     *
     * @return boolean
     */
    function delete()
    {
        $ids = $this->_input->get('id', 0, 'ARRAY');
        $table = $this->getTable('categories', 'JoomlaBacklinkCheckerTable');

        foreach($ids as $id)
        {
            // Delete the backup file from the server
            $table->load($id);

            if(!$table->delete($id))
            {
                $this->setError($this->_db->getErrorMsg());

                return false;
            }
        }

        return true;
    }
}
