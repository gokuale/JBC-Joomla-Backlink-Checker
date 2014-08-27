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
        <div class="width-55 fltlft">
            <fieldset class="adminform">
                <ul class="adminformlist">
                    <li>
                        <label for="name">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORY_NAME'); ?>:</strong>
                        </label>
                        <input class="text_area" type="text" name="name" id="name" size="80"
                               value="<?php echo $this->category->name; ?>"/>
                    </li>
                    <li>
                        <label for="description">
                            <strong><?php echo JText::_('COM_JOOMLABACKLINKCHECKER_CATEGORY_DESCRIPTION'); ?>:</strong>
                        </label>
                        <textarea class="text_area" rows="4" cols="140" id="description"
                                  name="description"><?php echo htmlspecialchars($this->category->description); ?></textarea>
                    </li>
                </ul>
            </fieldset>
        </div>
        <input type="hidden" name="option" value="com_joomlabacklinkchecker"/>
        <input type="hidden" name="id" value="<?php echo $this->category->id; ?>"/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="controller" value="category"/>
        <input type="hidden" name="url_current"
               value="option=com_joomlabacklinkchecker&controller=categories&task=edit"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
    <div style="text-align: center; margin-top: 10px; clear: both;">
        <p><?php echo JText::sprintf('COM_JOOMLABACKLINKCHECKER_VERSION', _JOOMLABACKLINKCHECKER_VERSION) ?></p>
    </div>
<?php echo $this->donation_code_message; ?>