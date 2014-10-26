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
?>
    <h1><?php echo JTEXT::_('COM_JOOMLABACKLINKCHECKER'); ?></h1>
    <p><?php echo JTEXT::_('COM_JOOMLABACKLINKCHECKER_ABOUT'); ?></p>
    <p><?php echo JTEXT::_('COM_JOOMLABACKLINKCHECKER_JED'); ?></p>
    <p><strong>Joomla Backlink Checker: <?php echo _JOOMLABACKLINKCHECKER_VERSION; ?></strong></p>
    <div style="text-align: center; margin-top: 10px;">
        <p><?php echo JText::sprintf('COM_JOOMLABACKLINKCHECKER_VERSION', _JOOMLABACKLINKCHECKER_VERSION) ?></p>
    </div>
<?php echo $this->donation_code_message; ?>