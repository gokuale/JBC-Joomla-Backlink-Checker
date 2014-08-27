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

class JoomlaBacklinkCheckerModelCheckBacklinks extends JModelLegacy
{
    protected $_input;
    protected $_params;
    protected $_user_agent;

    function __construct()
    {
        parent::__construct();

        $this->_input = JFactory::getApplication()->input;
    }

    /**
     * Starting point for the backlink checks
     *
     * @param $data
     *
     * @return bool
     */
    function checkBacklinks($data)
    {
        $result_array = array();

        // Okay, let's check all entries which are provided
        foreach($data as $entry)
        {
            $result_array[] = array_merge($this->checkBacklink($entry), (array)$entry);
        }

        $this->_input->set('check_result', $result_array);

        return true;
    }

    /**
     * Main class for the backlink check process
     *
     * @param $entry
     *
     * @return array
     */
    private function checkBacklink($entry)
    {
        // Get needed data first
        $this->_params = JComponentHelper::getParams('com_joomlabacklinkchecker');
        $this->_user_agent = $this->getUserAgent();

        // Initialise main check information array
        $result = array();

        // Initial check status
        $result['check_status'] = true;

        // Read in domain and check whether it exists and is callable
        preg_match('@http[s]?://(([a-zA-Z0-9-\.]*)?\.?[a-zA-Z0-9-]+\.([a-z]{2,4}))@', $entry->link_checkurl, $link_checkurl_base);

        if(!empty($link_checkurl_base))
        {
            $link_checkurl_base_url = $link_checkurl_base[0];

            // General test whether the domain exists
            $domain_check = $this->getPageContent($link_checkurl_base_url, true);

            if(empty($domain_check) OR $domain_check != 200)
            {
                $result['result_error'] = 1;

                // Save check result to db
                $this->saveCheckStatus($entry->id, 0);

                return $result;
            }

            // Okay, domain exists - so, let's get the content of the page and do the checks
            $content = $this->getPageContent($entry->link_checkurl)->body;

            // Okay, do we have the content of the page?
            if(!empty($content))
            {
                // Collect all links on the loaded page
                preg_match_all('@<a\s[^>]*href=[\"\']([^\"\']*)[\"\'][^>]*>[<]?.*[>]?</a>@Uism', $content, $links, PREG_PATTERN_ORDER);

                $count = 0;
                $hits = array();

                // Prepare link URL - remove a possible slash at the end and mask it for regular expression procedure
                $link_url = preg_quote(trim($entry->link_url, '/'));

                foreach($links[1] as $link)
                {
                    if(preg_match('@^'.$link_url.'/?$@', $link))
                    {
                        $hits[] = $count;
                    }

                    $count++;
                }

                // If we have hit(s), then go on with the data collecting
                if(!empty($hits))
                {
                    $backlinks = array();

                    foreach($hits as $hit_count)
                    {
                        $backlinks[] = $links[0][$hit_count];
                    }

                    // Yes, we have (a) backlink(s) to our site
                    if(!empty($backlinks))
                    {
                        // Set correct flag for link availability
                        $result['result_url'] = 1;

                        // Maybe the partner entered the link more than once, check all occurrences
                        foreach($backlinks as $backlink)
                        {
                            // Helper variable for the cycle
                            $check_cycle = true;

                            if(preg_match('@rel=[\"\']nofollow[\"\']@', $backlink))
                            {
                                // Ooops, nofollow is set - Ugh!
                                $result['result_nofollow'] = 2;
                                $check_cycle = false;
                            }
                            else
                            {
                                $result['result_nofollow'] = 1;
                            }

                            if(preg_match('@_blank@', $backlink))
                            {
                                $result['result_target'] = '_blank';
                            }
                            else
                            {
                                $result['result_target'] = '_self';
                            }

                            // Is the title attribute set correctly?
                            if(!empty($entry->link_titletext))
                            {
                                if(preg_match('@title=[\"\']'.preg_quote($entry->link_titletext).'[\"\']@', $backlink))
                                {
                                    $result['result_titletext'] = 1;
                                }
                                else
                                {
                                    $result['result_titletext'] = 2;
                                    $check_cycle = false;
                                }
                            }

                            // Is the linked text set correctly?
                            if(!empty($entry->link_linktext))
                            {
                                if(preg_match('@>'.preg_quote($entry->link_linktext).'</[a|A]>@', $backlink))
                                {
                                    $result['result_linktext'] = 1;
                                }
                                else
                                {
                                    $result['result_linktext'] = 2;
                                    $check_cycle = false;
                                }
                            }

                            if(!empty($entry->link_keywords))
                            {
                                $link_keywords_array = array_map('trim', explode(',', $entry->link_keywords));
                                $ink_keywords_test = false;

                                foreach($link_keywords_array as $link_keyword)
                                {
                                    if(preg_match('@'.preg_quote($link_keyword).'@', $backlink))
                                    {
                                        $ink_keywords_test = true;
                                    }
                                }

                                if($ink_keywords_test == true)
                                {
                                    $result['result_keywords'] = 1;
                                }
                                else
                                {
                                    $result['result_keywords'] = 2;
                                    $check_cycle = false;
                                }
                            }

                            // Do we have a clean run? If yes, we don't to check the other hits
                            if($check_cycle == true)
                            {
                                break;
                            }
                        }

                        // If the helper variable is false outside the foreach cycle, then we do not have a valid link
                        if(empty($check_cycle))
                        {
                            $result['check_status'] = false;
                        }

                        // ROBOTS
                        // robot.txt - the URL should not be excluded from indexing by search engines in the robots text file
                        $content_robots = $this->getPageContent($link_checkurl_base_url.'/robots.txt')->body;

                        if(!empty($content_robots))
                        {
                            if(preg_match('@[d|D]isallow@', $content_robots) AND preg_match('@^'.$entry->link_url.'$@', $content_robots))
                            {
                                $result['result_robots'] = 2;
                                $result['check_status'] = false;
                            }
                            else
                            {
                                $result['result_robots'] = 1;
                            }
                        }
                        else
                        {
                            $result['result_robots'] = 0;
                        }

                        // META
                        // Checks whether the robots meta attribute is set
                        if(preg_match('@<meta name="robots" content="(.*)" />@Ui', $content, $match))
                        {
                            $result['result_robots-meta'] = $match[1];
                        }

                        // Almost done! Maybe we need a debug output to check the collected data from the page?
                        $debug = $this->_params->get('debug');

                        if(!empty($debug))
                        {
                            $links_all = '';
                            $n = 1;

                            foreach($links[0] as $link)
                            {
                                $links_all .= $n.'. '.htmlspecialchars($link).'<br />';
                                $n++;
                            }

                            $backlinks_all = '';
                            $n = 1;

                            foreach($backlinks as $backlink)
                            {
                                $backlinks_all .= $n.'. '.htmlspecialchars($backlink).'<br />';
                                $n++;
                            }

                            $debugoutput = '<h2>'.JText::_('COM_JOOMLABACKLINKCHECKER_DEBUGMODE').'</h2>';
                            $debugoutput .= '<p><strong>'.JText::_('COM_JOOMLABACKLINKCHECKER_DEBUGMODE_FOUNDLINKS').'</strong> '.count($links[0]).'</p>';
                            $debugoutput .= '<p><strong>'.JText::_('COM_JOOMLABACKLINKCHECKER_DEBUGMODE_LISTOWNLINKS').'</strong></p>';
                            $debugoutput .= '<p>'.$backlinks_all.'</p>';
                            $debugoutput .= '<p><strong>'.JText::_('COM_JOOMLABACKLINKCHECKER_DEBUGMODE_LISTLINKS').'</strong></p>';
                            $debugoutput .= '<p>'.$links_all.'</p>';

                            $result['result_debug'] = $debugoutput;
                        }

                        // Save check result to db
                        $this->saveCheckStatus($entry->id, $result['check_status']);

                        return $result;
                    }
                }
            }

            $result['result_error'] = 2;

            // Save check result to db
            $this->saveCheckStatus($entry->id, 0);

            return $result;
        }
    }

    /**
     * Saves the status of each backlink check
     *
     * @param int     $id
     * @param boolean $check_status
     *
     * @return boolean
     */
    function saveCheckStatus($id, $check_status)
    {
        $row = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');
        $data = array();

        $data['id'] = $id;
        $data['check_status'] = $check_status;
        $data['check_date'] = JFactory::getDate()->toMySQL();

        if(!$row->save($data))
        {
            $this->setError($this->_db->getErrorMsg());
            $this->_error = 'database';

            return false;
        }

        return true;
    }

    /**
     * This function loads the content of the provided URL. It uses the JHttp class of the Joomla! API and the factory
     * class of Joomla! 3 which was backported for Joomla! 2.5
     *
     * The socket transport driver is used to determine whether a called URL exists at all. The timeout of 5 seconds
     * helps to reduce the loading time of the request process.
     *
     * @param string $url
     * @param bool   $socket
     *
     * @return bool|int|JHttpResponse
     */
    private function getPageContent($url, $socket = false)
    {
        $content = new stdClass();

        // We need the factory class which helps us to determine all supported adapters by the used server
        if(!class_exists('JHttpFactory'))
        {
            include JPATH_COMPONENT_ADMINISTRATOR.'/helpers/jhttpfactory.php';
        }

        if(!empty($socket) AND ($http_socket = JHttpFactory::getAvailableDriver(new JRegistry, 'socket')))
        {
            $content = $http_socket->request('HEAD', new JUri($url), null, null, 5, $this->_user_agent);

            // We don't need the content, only the response code if the socket transport driver is used
            return $content->code;
        }
        else
        {
            // Normal query check with available handlers - cURL has always priority - use allow_url_fopen only as fallback
            // Normally you should get the content like this:
            // $jhttp_factory = JHttpFactory::getHttp();
            // $content = $jhttp_factory->get($url);
            // But so we can not set the user agent which is required on many websites (servers) to make the request valid
            if($http_curl = JHttpFactory::getAvailableDriver(new JRegistry, 'curl'))
            {
                $content = $http_curl->request('GET', new JUri($url), null, null, 5, $this->_user_agent);
            }
            elseif($http_fopen = JHttpFactory::getAvailableDriver(new JRegistry, 'stream'))
            {
                $content = $http_fopen->request('GET', new JUri($url), null, null, 5, $this->_user_agent);
            }
        }

        // Code 200? Everything okay, go on!
        if(!empty($content) AND $content->code == 200)
        {
            return $content;
        }
        else
        {
            return false;
        }
    }

    /**
     * Deletes link connections from database
     *
     * @return boolean
     */
    public function delete()
    {
        $ids = $this->_input->get('id', 0, 'ARRAY');
        $table = $this->getTable('entry', 'JoomlaBacklinkCheckerTable');

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

    /**
     * Gets the user agent for the request
     */
    private function getUserAgent()
    {
        $user_agent_option = $this->_params->get('user_agent_option', 0);
        $user_agent_value = $this->_params->get('user_agent_value', '');

        if(!empty($user_agent_option))
        {
            $user_agent = $user_agent_value;
        }
        else
        {
            if(!empty($_SERVER['HTTP_USER_AGENT']))
            {
                $user_agent = $_SERVER['HTTP_USER_AGENT'];
            }
            else
            {
                // Fall back - a valid user agent from Mozilla Firefox 27
                $user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:27.0) Gecko/20100101 Firefox/27.0';
            }
        }

        return $user_agent;
    }
}