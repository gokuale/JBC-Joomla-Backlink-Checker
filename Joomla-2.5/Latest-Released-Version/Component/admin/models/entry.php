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

class JoomlaBacklinkCheckerModelEntry extends JModelLegacy
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
     * Loads a link connection entry from the database
     *
     * @return bool|JTable|mixed|stdClass
     */
    function getData()
    {
        if($this->state->get('task') != 'add')
        {
            $this->_data = $this->getInputSession();

            if(empty($this->_data))
            {
                $query = "SELECT * FROM ".$this->_db->quoteName('#__joomlabacklinkchecker')." WHERE ".$this->_db->quoteName('id')." = ".$this->_db->quote($this->_id);
                $this->_db->setQuery($query);
                $this->_data = $this->_db->loadObject();

                if(empty($this->_data))
                {
                    $this->_data = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');
                    $this->_data->id = 0;
                }
            }
        }
        else
        {
            $this->_data = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');
            $this->_data->id = 0;
        }

        return $this->_data;
    }

    function store()
    {
        $row = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');
        $data = array();

        $data['id'] = $this->_input->get('id', '', 'INT');
        $data['link_checkurl'] = $this->_input->get('link_checkurl', '', 'STRING');
        $data['link_url'] = $this->_input->get('link_url', '', 'STRING');
        $data['link_titletext'] = $this->_input->get('link_titletext', '', 'STRING');
        $data['link_linktext'] = $this->_input->get('link_linktext', '', 'STRING');
        $data['link_keywords'] = $this->_input->get('link_keywords', '', 'STRING');
        $data['back_link'] = $this->_input->get('back_link', '', 'STRING');
        $data['back_linkurl'] = $this->_input->get('back_linkurl', '', 'STRING');
        $data['link_owner'] = $this->_input->get('link_owner', '', 'STRING');
        $data['linkowner_detail'] = stripslashes(preg_replace('@\s+(\r\n|\r|\n)@', ' ', $this->_input->get('linkowner_detail', '', 'STRING')));
        $data['link_email'] = $this->_input->get('link_email', '', 'STRING');
        $data['link_date'] = JFactory::getDate()->toMySQL();
        $data['check_status'] = 0;
        $data['check_date'] = '0000-00-00 00:00:00';
        $data['category_id'] = $this->_input->get('category_id', '', 'INT');

        // Check whether mandatory fields were filled out
        if(empty($data['link_checkurl']) OR empty($data['link_url']))
        {
            $this->_error = 'mandatory';

            return false;
        }

        // Save the entry to the database
        if(!$row->save($data))
        {
            $this->setError($this->_db->getErrorMsg());
            $this->_error = 'database';

            return false;
        }

        return true;
    }

    /**
     * Deletes link connection entries from the database
     *
     * @return bool
     * @throws Exception
     */
    function delete()
    {
        $ids = $this->_input->get('id', 0, 'ARRAY');
        $row = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');

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
     * Changes the publish state of a link connection item
     *
     * @param $state
     *
     * @return bool
     * @throws Exception
     */
    function publish($state)
    {
        $id = $this->_input->get('id', 0, 'ARRAY');
        $row = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');

        if(!$row->publish($id, $state))
        {
            throw new Exception($row->getError());
        }

        return true;
    }

    /**
     * Stores the entered data to the session
     *
     * @param $input
     */
    public function storeInputSession($input)
    {
        $session = JFactory::getSession();
        $session->set('jbc_data', $input);

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
        $input = $session->get('jbc_data');

        if(!empty($input))
        {
            $data = new stdClass();

            $data->id = $input->getInt('id');

            $link_checkurl = $input->get('link_checkurl', '', 'ARRAY');
            $data->link_checkurl = $link_checkurl[0];

            $link_url = $input->get('link_url', '', 'ARRAY');
            $data->link_url = $link_url[0];

            $link_titletext = $input->get('link_titletext', '', 'ARRAY');
            $data->link_titletext = $link_titletext[0];

            $link_linktext = $input->get('link_linktext', '', 'ARRAY');
            $data->link_linktext = $link_linktext[0];

            $link_keywords = $input->get('link_keywords', '', 'ARRAY');
            $data->link_keywords = $link_keywords[0];

            $back_link = $input->get('back_link', '', 'ARRAY');
            $data->back_link = $back_link[0];

            $back_linkurl = $input->get('back_linkurl', '', 'ARRAY');
            $data->back_linkurl = $back_linkurl[0];

            $link_owner = $input->get('link_owner', '', 'ARRAY');
            $data->link_owner = $link_owner[0];

            $linkowner_detail = $input->get('linkowner_detail', '', 'ARRAY');
            $data->linkowner_detail = $linkowner_detail[0];

            $link_email = $input->get('link_email', '', 'ARRAY');
            $data->link_email = $link_email[0];

            $link_date = $input->get('link_date', '', 'ARRAY');
            $data->link_date = $link_date[0];

            $data->check_status = 0;

            $data->check_date = '0000-00-00 00:00:00';

            $data->category_id = $input->getInt('category_id');

            $session->clear('jbc_data');

            return $data;
        }
        else
        {
            return false;
        }
    }
}
