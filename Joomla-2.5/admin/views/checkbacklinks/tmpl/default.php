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
            }
            else {
                alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')); ?>');
            }
        }
    </script>

    <form action="<?php echo JRoute::_('index.php?option=com_joomlabacklinkchecker'); ?>" method="post" name="adminForm"
          id="joomlabacklinkchecker-form" class="form-validate form-horizontal">
        <div class="width-100 fltlft">
            <?php if(!empty($this->check_result)): ?>
                <table id="articleList" class="adminlist center">
                    <thead>
                    <tr>
                        <th width="20%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL'); ?>
                        </th>
                        <th width="20%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_URL'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_FOLLOW'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_ROBOTS'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_TARGET'); ?>
                        </th>
                        <th width="6%">
                            <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_ROBOTS_META'); ?>
                        </th>
                        <?php if(!empty($this->edit_allowed)) : ?>
                            <th width="2%">
                                <?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_EDIT'); ?>
                            </th>
                        <?php endif; ?>
                    </tr>
                    </thead>
                    <?php foreach($this->check_result as $check_result_single) : ?>
                        <?php $k = 0; ?>
                        <tr class="<?php echo 'row'.$k; ?>">
                            <td>
                            <span class="hasTooltip" title="<?php echo $check_result_single['link_checkurl']; ?>">
                                <?php
                                if(strlen($check_result_single['link_checkurl']) > 80) :
                                    echo mb_substr($check_result_single['link_checkurl'], 0, 80).'...';
                                else :
                                    echo $check_result_single['link_checkurl'];
                                endif; ?>
                                <a href="<?php echo $check_result_single['link_checkurl']; ?>" target="_blank"><img
                                        src="components/com_joomlabacklinkchecker/images/external.png"/></a>
                                <?php // Add cross if domain is not available
                                if($check_result_single['result_error'] == 1) : ?>
                                    <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                         alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL_ERROR'); ?>"
                                         title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_CHECKURL_ERROR'); ?>"/>
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip" title="<?php echo $check_result_single['link_url']; ?>">
                                <?php
                                if(strlen($check_result_single['link_url']) > 80) :
                                    echo mb_substr($check_result_single['link_url'], 0, 80).'...';
                                else :
                                    echo $check_result_single['link_url'];
                                endif; ?>
                                <a href="<?php echo $check_result_single['link_url']; ?>" target="_blank"><img
                                        src="components/com_joomlabacklinkchecker/images/external.png"/></a>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>">
                                <?php if(!empty($check_result_single['result_url'])) : ?>
                                    <img src="components/com_joomlabacklinkchecker/images/check.png"
                                         alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"
                                         title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"/>
                                <?php else : ?>
                                    <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                         alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"
                                         title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"/>
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_FOLLOW'); ?>">
                                <?php if(!empty($check_result_single['result_nofollow'])) : ?>
                                    <?php if($check_result_single['result_nofollow'] == 1) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/check.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_FOLLOW'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_FOLLOW'); ?>"/>
                                    <?php elseif($check_result_single['result_nofollow'] == 2) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_FOLLOW'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_FOLLOW'); ?>"/>
                                    <?php endif; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_TITLETEXT'); ?>">
                                <?php if(!empty($check_result_single['link_titletext'])) : ?>
                                    <?php if($check_result_single['result_titletext'] == 1) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/check.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"/>
                                    <?php elseif($check_result_single['result_titletext'] == 2) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"/>
                                    <?php
                                    else : ?>
                                        -
                                    <?php endif; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT'); ?>">
                                <?php if(!empty($check_result_single['link_linktext'])) : ?>
                                    <?php if($check_result_single['result_linktext'] == 1) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/check.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"/>
                                    <?php elseif($check_result_single['result_linktext'] == 2) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_LINKTEXT'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"/>
                                    <?php
                                    else : ?>
                                        -
                                    <?php endif; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS'); ?>">
                                <?php if(!empty($check_result_single['link_keywords'])) : ?>
                                    <?php if($check_result_single['result_keywords'] == 1) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/check.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"/>
                                    <?php elseif($check_result_single['result_keywords'] == 2) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_KEYWORDS'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"/>
                                    <?php
                                    else : ?>
                                        -
                                    <?php endif; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_ROBOTS'); ?>">
                                <?php if(!empty($check_result_single['result_robots'])) : ?>
                                    <?php if($check_result_single['result_robots'] == 1) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/check.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_ROBOTS'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_SET'); ?>"/>
                                    <?php elseif($check_result_single['result_robots'] == 2) : ?>
                                        <img src="components/com_joomlabacklinkchecker/images/cross.png"
                                             alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_ROBOTS'); ?>"
                                             title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_LINK_NOT_SET'); ?>"/>
                                    <?php endif; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_TARGET'); ?>">
                                <?php if(!empty($check_result_single['result_target'])) : ?>
                                    <?php echo $check_result_single['result_target']; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <td>
                            <span class="hasTooltip"
                                  title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_RESULT_ROBOTS_META'); ?>">
                                <?php if(!empty($check_result_single['result_robots-meta'])) : ?>
                                    <?php echo $check_result_single['result_robots-meta']; ?>
                                <?php else : ?>
                                    -
                                <?php endif; ?>
                            </span>
                            </td>
                            <?php if(!empty($this->edit_allowed)) : ?>
                                <td>
                                <span class="hasTooltip"
                                      title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_EDITENTRY'); ?>">
                                        <a href="index.php?option=com_joomlabacklinkchecker&controller=joomlabacklinkchecker&task=edit&id=<?php echo $check_result_single['id']; ?>"
                                           title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_EDITENTRY').' '.$check_result_single['id']; ?>">
                                            <img src="components/com_joomlabacklinkchecker/images/icon-16-edit.png"
                                                 alt="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_EDITENTRY'); ?>"
                                                 title="<?php echo JText::_('COM_JOOMLABACKLINKCHECKER_EDITENTRY'); ?>"/>
                                        </a>
                                </span>
                                </td>
                            <?php endif; ?>
                        </tr>
                        <?php $k = 1 - $k; ?>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
        <?php if($this->debug) : ?>
            <?php echo $check_result_single['result_debug']; ?>
        <?php endif; ?>
        <div class="clr"></div>
        <input type="hidden" name="option" value="com_joomlabacklinkchecker"/>
        <input type="hidden" name="id" value=""/>
        <input type="hidden" name="task" value=""/>
        <input type="hidden" name="controller" value="checkbacklinks"/>
        <?php echo JHTML::_('form.token'); ?>
    </form>
    <div style="text-align: center; margin-top: 10px; clear: both;">
        <p><?php echo JText::sprintf('COM_JOOMLABACKLINKCHECKER_VERSION', _JOOMLABACKLINKCHECKER_VERSION) ?></p>
    </div>
<?php echo $this->donation_code_message; ?>