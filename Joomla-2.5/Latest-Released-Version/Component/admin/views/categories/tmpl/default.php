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
JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');
?>
    <form action="<?php echo JRoute::_('index.php?option=com_joomlabacklinkchecker&amp;view=categories'); ?>"
          method="post" name="adminForm" id="adminForm">
        <fieldset id="filter-bar">
            <div class="filter-search fltlft">
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $this->escape($this->_state->get('filter.search')); ?>"
                       title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_FILTERSEARCH'); ?>"/>
                <button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button type="button" onclick="document.id('filter_search').value = '';
                    this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
            </div>
        </fieldset>
        <div class="clr"></div>
        <div id="editcell">
            <table id="articleList" class="adminlist center">
                <thead>
                <tr>
                    <th width="2%">
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
                    <th width="25%">
                        <span class="hasTip"
                              title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORIES_NAME').'::'.JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORIES_NAMEDESC') ?>">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORIES_NAME'); ?>
                        </span>
                    </th>
                    <th>
                        <span class="hasTip"
                              title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORIES_DESCRIPTION').'::'.JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORIES_DESCRIPTIONDESC') ?>">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORIES_DESCRIPTION'); ?>
                        </span>
                    </th>
                </tr>
                </thead>
                <?php
                $k = 0;
                $n = count($this->items);

                for($i = 0; $i < $n; $i++)
                {
                    $row = $this->items[$i];
                    $checked = JHTML::_('grid.id', $i, $row->id, false, 'id');
                    ?>
                    <tr class="<?php echo 'row'.$k; ?>">
                        <td>
                            <?php echo $checked; ?>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->name; ?>">
                            <?php
                            if(strlen($row->name) > 60) :
                                echo mb_substr($row->name, 0, 60).'...';
                            else :
                                echo $row->name;
                            endif;
                            ?>
                        </span>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->description; ?>">
                            <?php
                            if(strlen($row->description) > 255) :
                                echo mb_substr($row->description, 0, 255).'...';
                            else :
                                echo $row->description;
                            endif;
                            ?>
                        </span>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
            </table>
        </div>

        <input type="hidden" name="option" value="com_joomlabacklinkchecker"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="controller" value="categories"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
    <div style="text-align: center; margin-top: 10px;">
        <p><?php echo JText::sprintf('COM_JOOMLABACKLINKCHECKER_VERSION', _JOOMLABACKLINKCHECKER_VERSION) ?></p>
    </div>
<?php echo $this->donation_code_message; ?>