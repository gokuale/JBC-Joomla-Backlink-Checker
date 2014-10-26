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

class JoomlaBacklinkCheckerModelJoomlaBacklinkChecker extends JModelLegacy
{
    protected $_total;
    protected $_pagination;

    function __construct()
    {
        parent::__construct();
        $mainframe = JFactory::getApplication();

        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest('joomlabacklinkchecker.limitstart', 'limitstart', 0, 'int');
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
        $search = $mainframe->getUserStateFromRequest('joomlabacklinkchecker.filter.search', 'filter_search', NULL);
        $category = $mainframe->getUserStateFromRequest('joomlabacklinkchecker.filter.category_id', 'filter_category_id', NULL);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
        $this->setState('filter.search', $search);
        $this->setState('filter.category_id', $category);
    }

    /**
     * Loads all or filtered entries from the database
     *
     * @return array
     */
    function getData()
    {
        if(empty($this->_data))
        {
            $query = $this->_db->getQuery(true);

            $query->select('*');
            $query->from('#__joomlabacklinkchecker AS a');

            // Is a search term provided?
            $search = $this->getState('filter.search');

            if(!empty($search))
            {
                $search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
                $query->where('(a.link_checkurl LIKE '.$search.') OR (a.link_url LIKE '.$search.') OR (a.link_titletext LIKE '.$search.') OR (a.link_linktext LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.back_link LIKE '.$search.') OR (a.back_linkurl LIKE '.$search.') OR (a.link_owner LIKE '.$search.') OR (a.linkowner_detail LIKE '.$search.') OR (a.link_email LIKE '.$search.')');
            }

            // Is a category selected?
            $category = $this->getState('filter.category_id');

            if(!empty($category))
            {
                $query->where('(a.category_id = '.(int)$category.')');
            }

            $query->order($this->_db->escape('id DESC'));
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
            $query->from('#__joomlabacklinkchecker AS a');

            // Is a search term provided?
            $search = $this->getState('filter.search');

            if(!empty($search))
            {
                $search = $this->_db->Quote('%'.$this->_db->escape($search, true).'%');
                $query->where('(a.link_checkurl LIKE '.$search.') OR (a.link_url LIKE '.$search.') OR (a.link_titletext LIKE '.$search.') OR (a.link_linktext LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.link_keywords LIKE '.$search.') OR (a.back_link LIKE '.$search.') OR (a.back_linkurl LIKE '.$search.') OR (a.link_owner LIKE '.$search.') OR (a.linkowner_detail LIKE '.$search.') OR (a.link_email LIKE '.$search.')');
            }

            // Is a category selected?
            $category = $this->getState('filter.category_id');

            if(!empty($category))
            {
                $query->where('(a.category_id = '.(int)$category.')');
            }

            $query->order($this->_db->escape('id ASC'));

            $this->_total = $this->_getListCount($query);
        }

        return $this->_total;
    }

    /**
     * Loads the data of the selected items
     *
     * @param $ids
     *
     * @return array
     */
    function getDataSelection($ids)
    {
        $table = $this->getTable('checkbacklinks', 'JoomlaBacklinkCheckerTable');
        $data = array();

        foreach($ids as $id)
        {
            $table->load($id);
            $data[] = clone $table;
        }

        return $data;
    }

}
