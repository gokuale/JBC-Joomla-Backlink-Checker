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
    <script type="text/javascript">
        // Load the loading animation after a click on the create button
        window.addEvent('domready', function () {
            var loading = document.id('loading');

            document.id('toolbar-check-complete').addEvent('click', function (event) {
                loading.setStyle('display', '');
            });

            document.id('toolbar-check-selection').addEvent('click', function (event) {
                if (document.adminForm.boxchecked.value != 0) {
                    loading.setStyle('display', '');
                }
                ;
            });
        });
    </script>
    <form action="<?php echo JRoute::_('index.php?option=com_joomlabacklinkchecker'); ?>" method="post" name="adminForm"
          id="adminForm">
        <fieldset id="filter-bar">
            <div class="filter-search fltlft">
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $this->escape($this->_state->get('filter.search')); ?>"
                       title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_FILTERSEARCH'); ?>"/>
                <button type="submit" class="btn"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
                <button type="button" onclick="document.id('filter_search').value = '';
        this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
            </div>
            <div class="filter-select fltrt">
                <select name="filter_category_id" class="inputbox" onchange="this.form.submit()">
                    <option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY'); ?></option>
                    <?php echo JHtml::_('select.options', $this->categories, 'id', 'name', $this->_state->get('filter.category_id')); ?>
                </select>
            </div>
        </fieldset>
        <div class="clr"></div>
        <p id="loading" style="display: none; text-align: center;">
            <img src="components/com_joomlabacklinkchecker/images/loading.gif" style="float: none;"
                 alt="Loading..."/><br/><br/>
            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CHECKPROCESSWAIT'); ?>
        </p>

        <div id="editcell">
            <table id="articleList" class="adminlist center">
                <thead>
                <tr>
                    <th width="2%">
                        <input type="checkbox" name="checkall-toggle" value=""
                               title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)"/>
                    </th>
                    <th width="16%">
                        <span class="hasTip"
                              title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL').'::'.JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL_DESC') ?>">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL'); ?>
                        </span>
                    </th>
                    <th width="16%">
                        <span class="hasTip"
                              title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_URL').'::'.JText::_('COM_JOOMLABACKLINKCHECKER_LINK_URL_DESC') ?>">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_URL'); ?>
                        </span>
                    </th>
                    <th width="15%">
                        <span class="hasTip"
                              title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT').'::'.JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT_DESC') ?>">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT'); ?>
                        </span>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT'); ?>
                    </th>
                    <th width="15%">
                        <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS'); ?>
                    </th>
                    <th width="2%">
                        <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CHECK_STATUS'); ?>
                    </th>
                    <th width="10%">
                        <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CHECK_DATE'); ?>
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
                        <span class="hasTooltip" title="<?php echo $row->link_checkurl; ?>">
                            <?php
                            if(strlen($row->link_checkurl) > 80) :
                                echo mb_substr($row->link_checkurl, 0, 80).'...';
                            else :
                                echo $row->link_checkurl;
                            endif;
                            ?>
                            <a href="<?php echo $row->link_checkurl; ?>" target="_blank"><img
                                    src="components/com_joomlabacklinkchecker/images/external.png"/></a>
                        </span>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->link_url; ?>">
                            <?php
                            if(strlen($row->link_url) > 80) :
                                echo mb_substr($row->link_url, 0, 80).'...';
                            else :
                                echo $row->link_url;
                            endif;
                            ?>
                            <a href="<?php echo $row->link_url; ?>" target="_blank"><img
                                    src="components/com_joomlabacklinkchecker/images/external.png"/></a>
                        </span>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->link_titletext; ?>">
                            <?php
                            if(strlen($row->link_titletext) > 60) :
                                echo mb_substr($row->link_titletext, 0, 60).'...';
                            else :
                                echo $row->link_titletext;
                            endif;
                            ?>
                        </span>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->link_linktext; ?>">
                            <?php
                            if(strlen($row->link_linktext) > 60) :
                                echo mb_substr($row->link_linktext, 0, 60).'...';
                            else :
                                echo $row->link_linktext;
                            endif;
                            ?>
                        </span>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->link_keywords; ?>">
                            <?php
                            if(strlen($row->link_keywords) > 60) :
                                echo mb_substr($row->link_keywords, 0, 60).'...';
                            else :
                                echo $row->link_keywords;
                            endif;
                            ?>
                        </span>
                        </td>
                        <td>
                            <?php if(!empty($row->check_status)) : ?>
                                <img src="components/com_joomlabacklinkchecker/images/check.png"
                                     alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"
                                     title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"/>
                            <?php else : ?>
                                <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                     alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"
                                     title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"/>
                            <?php endif; ?>
                        </td>
                        <td>
                        <span class="hasTooltip" title="<?php echo $row->check_date; ?>">
                            <?php if($row->check_date == '0000-00-00 00:00:00') : ?>
                                <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                     alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CHECK_DATE_EMPTY'); ?>"
                                     title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CHECK_DATE_EMPTY'); ?>"/>
                            <?php else : ?>
                                <?php echo JHTML::_('date', $row->check_date, JText::_('DATE_FORMAT_LC2')); ?>
                            <?php endif; ?>
                        </span>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
                <tfoot>
                <tr>
                    <td colspan="8">
                        <?php echo $this->pagination->getListFooter(); ?>
                        <p class="footer-tip">
                            <?php if(!ini_get('allow_url_fopen') AND !function_exists('curl_init')) : ?>
                                <span
                                    class="disabled"><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_QUERYMETHOD_DEACTIVATED'); ?></span>
                            <?php else : ?>
                                <span
                                    class="enabled"><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_QUERYMETHOD_ACTIVATED'); ?></span>
                            <?php endif; ?>
                        </p>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <input type="hidden" name="option" value="com_joomlabacklinkchecker"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="boxchecked" value="0"/>
        <input type="hidden" name="controller" value="joomlabacklinkchecker"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
    <div style="text-align: center; margin-top: 10px;">
        <p><?php echo JText::sprintf('COM_JOOMLABACKLINKCHECKER_VERSION', _JOOMLABACKLINKCHECKER_VERSION) ?></p>
    </div>
<?php echo $this->donation_code_message; ?>