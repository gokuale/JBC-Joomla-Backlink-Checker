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

class JoomlaBacklinkCheckerHelper
{
    public static function addSubmenu($view_name)
    {
        JSubMenuHelper::addEntry(JText::_('COM_JOOMLABACKLINKCHECKER_MENU_ENTRIES'), 'index.php?option=com_joomlabacklinkchecker', $view_name == 'joomlabacklinkchecker');
        JSubMenuHelper::addEntry(JText::_('COM_JOOMLABACKLINKCHECKER_MENU_CATEGORIES'), 'index.php?option=com_joomlabacklinkchecker&amp;view=categories', $view_name == 'categories');

        if(JFactory::getUser()->authorise('joomlabacklinkchecker.new', 'com_joomlabacklinkchecker'))
        {
            JSubMenuHelper::addEntry(JText::_('COM_JOOMLABACKLINKCHECKER_MENU_NEW_ENTRY'), 'index.php?option=com_joomlabacklinkchecker&amp;controller=joomlabacklinkchecker&amp;task=add', $view_name == 'entry');
        }

        if(JFactory::getUser()->authorise('joomlabacklinkchecker.categoriesnew', 'com_joomlabacklinkchecker'))
        {
            JSubMenuHelper::addEntry(JText::_('COM_JOOMLABACKLINKCHECKER_MENU_NEW_CATEGORY'), 'index.php?option=com_joomlabacklinkchecker&amp;controller=categories&amp;task=add', $view_name == 'category');
        }

        JSubMenuHelper::addEntry(JText::_('COM_JOOMLABACKLINKCHECKER_MENU_ABOUT'), 'index.php?option=com_joomlabacklinkchecker&amp;view=about', $view_name == 'about');
    }

    /**
     * Checks whether the donation code was entered and if the code is correct.
     * The code is taken from the main Kubik-Rubik Donation Code Check field.
     *
     * @return string
     */
    public static function getDonationCodeMessage()
    {
        $params = JComponentHelper::getParams('com_joomlabacklinkchecker');
        $donation_code = $params->get('donation_code');

        $session = JFactory::getSession();
        $field_value_session = $session->get('field_value', null, 'krdonationcodecheck_footer');
        $field_value_head_session = $session->get('field_value_head', null, 'krdonationcodecheck_footer');
        $donation_code_session = $session->get('donation_code', null, 'krdonationcodecheck_footer');

        if($field_value_session == 1 AND ($donation_code == $donation_code_session))
        {
            return;
        }
        elseif(!empty($field_value_session) AND !empty($field_value_head_session) AND ($donation_code == $donation_code_session))
        {
            JoomlaBacklinkCheckerHelper::addHeadData($field_value_head_session);

            return $field_value_session;
        }

        $host = JURI::getInstance()->getHost();

        $field_value = '';
        $donation_code_check = false;

        if($host == 'localhost')
        {
            $field_value = '<div class="'.JoomlaBacklinkCheckerHelper::randomClassName($session).'">'.JTEXT::_('KR_DONATION_CODE_CHECK_DEFAULT_LOCALHOST').'</div>';

            if(!empty($donation_code))
            {
                $field_value .= '<div style="text-align: center; border: 1px solid #F2DB82; border-radius: 2px; padding: 5px; background-color: #F7EECA; font-size: 120%; margin: 10px 0;">'.JTEXT::_('KR_DONATION_CODE_CHECK_ERROR_LOCALHOST').'</div>';
            }
        }
        else
        {
            $donation_code_check = JoomlaBacklinkCheckerHelper::getDonationCodeStatus($host, $donation_code);

            if($donation_code_check != 1)
            {
                $field_value = '<div class="'.JoomlaBacklinkCheckerHelper::randomClassName($session).'">'.JTEXT::sprintf('KR_DONATION_CODE_CHECK_DEFAULT', $host).'</div>';

                if($donation_code_check == -1)
                {
                    $field_value .= '<div style="text-align: center; border: 1px solid #F2DB82; border-radius: 2px; padding: 5px; background-color: #F7EECA; font-size: 120%; margin: 10px 0;">'.JTEXT::_('KR_DONATION_CODE_CHECK_ERROR_SERVER').'</div>';
                }

                if($donation_code_check == -2)
                {
                    $field_value .= '<div style="text-align: center; border: 1px solid #F2DB82; border-radius: 2px; padding: 5px; background-color: #F7EECA; font-size: 120%; margin: 10px 0;">'.JTEXT::_('KR_DONATION_CODE_CHECK_ERROR').'</div>';
                }
            }
        }

        if($donation_code_check == 1)
        {
            $session->set('field_value', 1, 'krdonationcodecheck_footer');
        }
        else
        {
            $session->set('field_value', $field_value, 'krdonationcodecheck_footer');
        }

        $session->set('donation_code', $donation_code, 'krdonationcodecheck_footer');

        return $field_value;
    }

    /**
     * Gets the status of the entered donation code from the donation code script
     *
     * @param string $host
     * @param string $donation_code
     *
     * @return int
     */
    private static function getDonationCodeStatus($host, $donation_code)
    {
        if(!empty($host) AND !empty($donation_code))
        {
            // We need the factory class which helps us to determine all supported adapters by the used server
            if(!class_exists('JHttpFactory'))
            {
                include JPATH_COMPONENT_ADMINISTRATOR.'/helpers/jhttpfactory.php';
            }

            // cURL has always priority - use allow_url_fopen only if selected or as fallback
            $jhttp_factory = JHttpFactory::getHttp();
            $content = $jhttp_factory->get('http://joomla-extensions.kubik-rubik.de/scripts/je_kr_donation_code_check/je_kr_check_code.php?key='.rawurlencode($donation_code).'&host='.rawurlencode($host));

            // Code 200? Everything okay, go on!
            if($content->code == 200)
            {
                if(preg_match('@(error|access denied)@i', $content->body))
                {
                    return -1;
                }

                return $content->body;
            }
            else
            {
                return -2;
            }
        }
        else
        {
            return 0;
        }
    }

    /**
     * Creates a valid, random name for the class selector
     *
     * @param JSession $session
     *
     * @return string
     */
    private static function randomClassName($session)
    {
        $characters = range('a', 'z');
        $class_name = $characters[mt_rand(0, count($characters) - 1)];
        $class_name_length = mt_rand(6, 12);
        $class_name .= @JUserHelper::genRandomPassword($class_name_length);

        $head_data = '<style type="text/css">div.'.$class_name.'{text-align: center; border: 1px solid #DD87A2; border-radius: 2px; padding: 5px; background-color: #F9CAD9; font-size: 120%; margin: 10px 0;}</style>';

        JoomlaBacklinkCheckerHelper::addHeadData($head_data);
        $session->set('field_value_head', $head_data, 'krdonationcodecheck_footer');

        return $class_name;
    }

    /**
     * Adds the style definition to the head of the HTML page
     *
     * @staticvar boolean $data_loaded
     *
     * @param string $data
     */
    private static function addHeadData($data)
    {
        static $data_loaded = false;

        if(empty($data_loaded))
        {
            $document = JFactory::getDocument();
            $document->addCustomTag($data);

            $data_loaded = true;
        }
    }
}
