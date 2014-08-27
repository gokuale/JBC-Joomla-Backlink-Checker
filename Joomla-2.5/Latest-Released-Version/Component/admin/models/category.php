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

class JoomlaBacklinkCheckerModelCategory extends JModelLegacy
{
    protected $_data = null;
    protected $_id = null;
    protected $_input;
    protected $_error;

    function __construct()
    {
        parent::__construct();

        $this->_input = JFactory::getApplication()->input;

        $array = $this->_input->get('id', 0, 'ARRAY');
        $this->setId((int)$array[0]);
    }

    /**
     * Sets the ID to work with
     *
     * @param $id
     */
    function setId($id)
    {
        $this->_id = $id;
        $this->_data = null;
    }

    /**
 * Returns the current ID
 *
 * @return int
 */
    function getId()
    {
        return $this->_id;
    }

    /**
     * Returns the error if occurred
     *
     * @param null $i
     * @param bool $toString
     *
     * @return string
     */
    function getError($i = null, $toString = true)
    {
        return $this->_error;
    }

    /**
     * Loads a category entry from the database
     *
     * @return bool|JTable|mixed|stdClass
     */
    function getCategory()
    {
        if($this->state->get('task') != 'add')
        {
            $this->_data = $this->getInputSession();

            if(empty($this->_data))
            {
                $query = "SELECT * FROM ".$this->_db->quoteName('#__joomlabacklinkchecker_categories')." WHERE ".$this->_db->quoteName('id')." = ".$this->_db->quote($this->_id);
                $this->_db->setQuery($query);
                $this->_data = $this->_db->loadObject();

                if(empty($this->_data))
                {
                    $this->_data = $this->getTable('categories', 'JoomlaBacklinkCheckerTable');
                    $this->_data->id = 0;
                }
            }
        }
        else
        {
            $this->_data = $this->getTable('categories', 'JoomlaBacklinkCheckerTable');
            $this->_data->id = 0;
        }

        return $this->_data;
    }

    /**
     * Stores a category entry into the database
     *
     * @return bool
     */
    function store()
    {
        $row = $this->getTable('categories', 'JoomlaBacklinkCheckerTable');
        $data = array();

        $data['id'] = $this->_input->get('id', '', 'INT');
        $data['name'] = $this->_input->get('name', '', 'STRING');
        $data['description'] = stripslashes(preg_replace('@\s+(\r\n|\r|\n)@', ' ', $this->_input->get('description', '', 'STRING')));

        // Check whether mandatory fields were filled out
        if(empty($data['name']))
        {
            $this->_error = 'mandatory';

            return false;
        }

        // Do not save same URLs more than once
        if($this->checkEntry($data['name']) == false)
        {
            $this->_error = 'duplicate';

            return false;
        }

        // Save the category to the database
        if(!$row->save($data))
        {
            $this->_error = 'database';

            return false;
        }

        return true;
    }

    /**
     * Deletes category entries from the database
     *
     * @return bool
     * @throws Exception
     */
    function delete()
    {
        $ids = $this->_input->get('id', 0, 'ARRAY');
        $row = $this->getTable('categories', 'JoomlaBacklinkCheckerTable');

        foreach($ids as $id)
        {
            if(!$row->delete($id))
            {
                throw new Exception($row->getError());
            }
        }

        return true;
    }

    /**
     * Changes the publish state of a category item
     *
     * @param $state
     *
     * @return bool
     * @throws Exception
     */
    function publish($state)
    {
        $id = $this->_input->get('id', 0, 'ARRAY');
        $row = $this->getTable('categories', 'JoomlaBacklinkCheckerTable');

        if(!$row->publish($id, $state))
        {
            throw new Exception($row->getError());
        }

        return true;
    }

    /**
     * Checks whether a URL is already existing in the database
     *
     * @param $link_checkurl
     *
     * @return bool
     */
    private function checkEntry($link_checkurl)
    {
        $db = JFactory::getDbo();

        $query = "SELECT * FROM ".$db->quoteName('#__joomlabacklinkchecker_categories')." WHERE ".$db->quoteName('name')." = ".$db->quote($link_checkurl);
        $this->_db->setQuery($query);
        $row = $this->_db->loadAssoc();

        if(empty($row))
        {
            return true;
        }
        else
        {
            if($row['id'] == $this->_id)
            {
                return true;
            }
            else
            {
                return false;
            }
        }
    }

    /**
     * Stores the entered data to the session
     *
     * @param $input
     */
    public function storeInputSession($input)
    {
        $session = JFactory::getSession();
        $session->set('jbc_category_data', $input);

        return;
    }

    /**
     * Loads data from the session to restore the entered values
     *
     * @return bool|stdClass
     */
    private function getInputSession()
    {
        $session = JFactory::getSession();
        $input = $session->get('jbc_category_data');

        if(!empty($input))
        {
            $data = new stdClass();

            $data->id = $input->getInt('id');

            $name = $input->get('name', '', 'ARRAY');
            $data->name = $name[0];

            $description = $input->get('description', '', 'ARRAY');
            $data->description = $description[0];

            $session->clear('jbc_category_data');

            return $data;
        }
        else
        {
            return false;
        }
    }
}
