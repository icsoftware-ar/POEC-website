<?php
/**
 * @Copyright Copyright (C) 2009-2011
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * Created by:	Ahmad Bilal
 * Company:		Buruj Solutions
 * Contact:		www.burujsolutions.com , ahmad@burujsolutions.com
 * Created on:	Mar 25, 2009
 *
 * Project: 		JS Jobs
 * File Name:	views/applications/view.html.php
 * 
 * Description: HTML view of all applications 
 * 
 * History:		NONE
 * 
 */
defined('_JEXEC') or die('Restricted access');
$version = new JVersion;
$joomla = $version->getShortVersion();
$document = &JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'administrator/components/com_jsjobs/include/css/jsjobsadmin.css');

if (substr($joomla, 0, 3) != '1.5') {
    JHtml::_('behavior.tooltip');
    JHtml::_('behavior.multiselect');
}
?>

<table width="100%">
    <tr>
        <td align="left" width="150" valign="top">
            <table width="100%"><tr><td style="vertical-align:top;">
                        <?php
                        include_once('components/com_jsjobs/views/menu.php');
                        ?>
                    </td>
                </tr></table>
        </td>
        <td width="100%" valign="top">
            <div id="jsjobs_info_heading"><?php echo JText::_('JS_JOB_SHARE'); ?></div>
            <form action="index.php" method="post" name="jobserverserialnumber" id="jobserverserialnumber" method="post" style="margin: 0px;">
                <div id="jsjobs_jobsharing_graybar"><?php echo JText::_('SERVER_SERIAL_NUMBER'); ?></div>
                <div id="jsjobs_jobsharing_wrapper">
                    <?php echo JText::_('PLEASE_TYPE_YOUR_SERVER_SERIAL_NUMBER_AND');?><span id="jsjobs_jobsharing_redinfo"><?php echo JText::_('SUBMIT');?></span>
                    <input type="text" name="server_serialnumber" id="server_serialnumber" />
                    <input type="submit" value="<?php echo JText::_('SUBMIT');?>" id="jsjobs_sharing_serverkeybutton" />
                </div>
                <input type="hidden" name="c" value="jsjobs" />
                <input type="hidden" name="option" value="com_jsjobs" />
                <input type="hidden" name="task" value="saveserverserailnumber" />
            </form>
            <form action="index.php" method="post" name="jobShare" id="jobShare" method="post">
                <div id="jsjobs_jobsharing_subscribe_wrapper">
                    <div id="jsjobs_jobsharing_graybar"><?php echo JText::_('SHARING_SUBSCRIBE'); ?></div>
                    <div id="jsjobs_jobsharing_subscribe_righttext">
                        <span id="jsjobs_jobsharing_subscribe_righttext_heading"><?php echo JText::_('SHARING_SERVICES'); ?></span>
                        <span id="jsjobs_jobsharing_subscribe_righttext_heading_bottom"><?php echo JText::_('SUBSCRIBE_AND_UNSUBSCRIBE_SHARING_SERVICES'); ?></span>
                    </div>
                    <div id="jsjobs_jobsharing_subscribe_button_wrapper">
                        <?php 
                            echo JText::_('YOUR_SHARING_SERVICE');
                            if ($this->isjobsharing) {
                                echo '<span id="jsjobs_jobsharing_subscribe_text" class="subscribe">'.JText::_('SUBSCIRBED').'</span>';
                            }else{
                                echo '<span id="jsjobs_jobsharing_subscribe_text" class="unsubscribe">'.JText::_('UNSUBSCIRBED').'</span>';
                            }
                        ?>
                        <div id="jsjobs_jobsharing_subscribe_button_right_wrapper">
                        <?php
                            if ($this->isjobsharing) {
                                echo '<input type="button" id="jsjobs_jobsharing_unsubscribe" onclick="unsubscribejobsharing();" value="'.JText::_('UNSUBSCIRBED').'" />';
                            }else{
                                echo '<input type="text" name="authenticationkey" placeholder="'.JText::_('ENTER_THE_KEY').'" id="jsjobs_sharing_subcribe_authkey" />';
                                echo '<input type="button" id="jsjobs_jobsharing_subscribe" onclick="submitjobform();" value="'.JText::_('SUBSCIRBED').'" />';
                            }
                        ?>
                            
                        </div>
                    </div>
                </div>
                <div id="jobsharingwait" style="display:none"> 
                    <img src="components/com_jsjobs/include/images/loading.gif" height="32" width="32"></img>
                </div>
                <p id="jobsharingmessage" style="display:none"> <?php echo JText::_("PLEASE_WAIT_YOUR_SYSTEM_SYNCHRONIZE_WITH_SERVER"); ?></p>
                <?php if($this->result!='empty') ?><p id="jobsharingmessageresult" style="display:block"> <?php if($this->result!='empty') echo $this->result;?></p>
                
                
                <input type="hidden" id="task" name="task" value="requestjobsharing">
                <input type="hidden" name="ip" id="ip" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>">
                <input type="hidden" name="domain" id="domain" value="<?php echo $_SERVER["HTTP_HOST"]; ?>">
                <input type="hidden" name="siteurl" id="siteurl" value="<?php echo JURI::	root(); ?>">
                <?php if(isset($this->isjobsharing) AND ($this->isjobsharing!='')) ?> <input type="hidden" name="authkey" id="authkey" value="<?php echo $this->isjobsharing; ?>">
                <input type="hidden" name="layout" value="jobshare">
                <input type="hidden" name="view" value="applications">
                <input type="hidden" name="option" value="com_jsjobs">

            </form>	
        </td>
    </tr>
    <tr>
        <td colspan="2" align="left" width="100%"  valign="top">
            <table width="100%" style="table-layout:fixed;"><tr><td style="vertical-align:top;"><?php echo eval(base64_decode('CQkJZWNobyAnPHRhYmxlIHdpZHRoPSIxMDAlIiBzdHlsZT0idGFibGUtbGF5b3V0OmZpeGVkOyI+DQo8dHI+PHRkIGhlaWdodD0iMTUiPjwvdGQ+PC90cj4NCjx0cj4NCjx0ZCBzdHlsZT0idmVydGljYWwtYWxpZ246bWlkZGxlOyIgYWxpZ249ImNlbnRlciI+DQo8YSBocmVmPSJodHRwOi8vd3d3Lmpvb21za3kuY29tIiB0YXJnZXQ9Il9ibGFuayI+PGltZyBzcmM9Imh0dHA6Ly93d3cuam9vbXNreS5jb20vbG9nby9qc2pvYnNjcmxvZ28ucG5nIiA+PC9hPg0KPGJyPg0KQ29weXJpZ2h0ICZjb3B5OyAyMDA4IC0gJy4gZGF0ZSgnWScpIC4nLCA8YSBocmVmPSJodHRwOi8vd3d3LmJ1cnVqc29sdXRpb25zLmNvbSIgdGFyZ2V0PSJfYmxhbmsiPkJ1cnVqIFNvbHV0aW9uczwvYT4gDQo8L3RkPg0KPC90cj4NCjwvdGFibGU+JzsNCg==')); ?>	</td></tr></table>
        </td>
    </tr>

</table>	
<script type="text/javascript">
    function submitjobform() {
        document.getElementById('task').value = "requestjobsharing"; //retuen value
        document.getElementById('jobsharingwait').style.display = "block"; //retuen value
        document.getElementById('jobsharingmessage').style.display = "block"; //retuen value
        document.jobShare.submit();
    }
    function unsubscribejobsharing() {
        document.getElementById('task').value = "unsubscribejobsharing"; //retuen value
        document.jobShare.submit();
    }
</script>








