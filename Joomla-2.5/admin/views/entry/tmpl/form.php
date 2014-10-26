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
JHtml::_('behavior.formvalidation');
?>
    <script type="text/javascript">
        Joomla.submitbutton = function (task) {
            if (task == 'cancel' || document.formvalidator.isValid(document.id('joomlabacklinkchecker-form'))) {
                Joomla.submitform(task, document.getElementById('joomlabacklinkchecker-form'));
            } else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    </script>
    <form action="<?php echo JRoute::_('index.php?option=com_joomlabacklinkchecker'); ?>" method="post" name="adminForm"
          id="joomlabacklinkchecker-form" class="form-validate form-horizontal">
        <div class="width-50 fltlft">
            <fieldset class="adminform">
                <h2><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKDATA'); ?></h2>

                <p><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKDATA_INFO'); ?></p>
                <ul class="adminformlist">
                    <li>
                        <label for="category">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORY'); ?></strong>
                        </label>
                        <select class="" id="category" name="category_id">
                            <?php foreach($this->categories as $category) : ?>
                                <?php $selected = ($category->id == $this->entry->category_id) ? 'selected="selected"' : ''; ?>
                                <option
                                    value="<?php echo $category->id; ?>" <?php echo $selected; ?>><?php echo $category->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </li>
                    <li>
                        <label for="link_checkurl">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL'); ?></strong>
                            <br/><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL_INFO'); ?>
                        </label>
                        <input class="text_area" type="text" name="link_checkurl" id="link_checkurl" size="80"
                               value="<?php echo $this->entry->link_checkurl; ?>"/>
                    </li>
                    <li>
                        <label for="link_url">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_URL'); ?></strong>
                            <br/><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_URL_INFO'); ?>
                        </label>
                        <input class="text_area" type="text" name="link_url" id="link_url" size="80"
                               value="<?php echo htmlspecialchars($this->entry->link_url); ?>"/>
                    </li>
                    <li>
                        <label for="link_titletext">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT'); ?></strong>
                            <br/><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT_INFO'); ?>
                        </label>
                        <input class="text_area" type="text" name="link_titletext" id="link_titletext" size="80"
                               value="<?php echo htmlspecialchars($this->entry->link_titletext); ?>"/>
                    </li>
                    <li>
                        <label for="link_linktext">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT'); ?></strong>
                            <br/><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT_INFO'); ?>
                        </label>
                        <input class="text_area" type="text" name="link_linktext" id="link_linktext" size="80"
                               maxlength="255" value="<?php echo htmlspecialchars($this->entry->link_linktext); ?>"/>
                    </li>
                    <li>
                        <label for="link_keywords">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS'); ?></strong>
                            <br/><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS_INFO'); ?>
                        </label>
                        <input class="text_area" type="text" name="link_keywords" id="link_keywords" size="80"
                               maxlength="255" value="<?php echo htmlspecialchars($this->entry->link_keywords); ?>"/>
                    </li>
                </ul>
            </fieldset>
        </div>
        <div class="width-50 fltlft">
            <fieldset class="adminform">
                <h2><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_INFORMATION'); ?></h2>

                <p><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_INFORMATION_INFO'); ?></p>
                <ul class="adminformlist">
                    <li>
                        <label for="link_owner">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_OWNER'); ?></strong>
                        </label>
                        <input class="text_area" type="text" name="link_owner" id="link_owner" size="80" maxlength="255"
                               value="<?php echo htmlspecialchars($this->entry->link_owner); ?>"/>
                    </li>
                    <li>
                        <label for="link_email">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_EMAIL'); ?></strong>
                        </label>
                        <input class="text_area" type="text" name="link_email" id="link_email" size="80" maxlength="255"
                               value="<?php echo htmlspecialchars($this->entry->link_email); ?>"/>
                    </li>
                    <li>
                        <label for="linkowner_detail">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINKOWNER_DETAIL'); ?></strong>
                        </label>
                        <textarea class="text_area" rows="4" cols="140" id="linkowner_detail"
                                  name="linkowner_detail"><?php echo htmlspecialchars($this->entry->linkowner_detail); ?></textarea>
                    </li>
                    <li>
                        <label for="back_link">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_BACK_LINK'); ?></strong>
                        </label>
                        <input class="text_area" type="text" name="back_link" id="back_link" size="80" maxlength="255"
                               value="<?php echo htmlspecialchars($this->entry->back_link); ?>"/>
                    </li>
                    <li>
                        <label for="back_linkurl">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_BACK_LINKURL'); ?></strong>
                        </label>
                        <input class="text_area" type="text" name="back_linkurl" id="back_linkurl" size="80"
                               maxlength="255" value="<?php echo htmlspecialchars($this->entry->back_linkurl); ?>"/>
                    </li>
                </ul>
            </fieldset>
        </div>
        <input type="hidden" name="option" value="com_joomlabacklinkchecker"/>
        <input type="hidden" name="id" value="<?php echo $this->entry->id; ?>"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="controller" value="entry"/>
        <input type="hidden" name="url_current"
               value="option=com_joomlabacklinkchecker&controller=joomlabacklinkchecker&task=edit"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
    <div style="text-align: center; margin-top: 10px; clear: both;">
        <p><?php echo JText::sprintf('COM_JOOMLABACKLINKCHECKER_VERSION', _JOOMLABACKLINKCHECKER_VERSION) ?></p>
    </div>
<?php echo $this->donation_code_message; ?>