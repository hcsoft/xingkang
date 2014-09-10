<?php defined('InShopNC') or exit('Access Invalid!'); ?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3><?php echo $lang['member_index_manage'] ?></h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage'] ?></span></a></li>
                <li><a href="index.php?act=member&op=member_add"><span><?php echo $lang['nc_new'] ?></span></a></li>
            </ul>
        </div>
    </div>
    <div class="fixed-empty"></div>
    <form method="get" name="formSearch" id="formSearch">
        <input type="hidden" value="member" name="act">
        <input type="hidden" value="member" name="op">
        <table class="tb-type1 noborder search">
            <tbody>
            <tr>
                <td><select name="search_field_name">
                        <option
                            <?php if ($output['search_field_name'] == 'member_name'){ ?>selected='selected'<?php } ?>
                            value="member_name"><?php echo $lang['member_index_name'] ?></option>
                        <option
                            <?php if ($output['search_field_name'] == 'member_email'){ ?>selected='selected'<?php } ?>
                            value="member_email"><?php echo $lang['member_index_email'] ?></option>
                        <option
                            <?php if ($output['search_field_name'] == 'member_truename'){ ?>selected='selected'<?php } ?>
                            value="member_truename"><?php echo $lang['member_index_true_name'] ?></option>
                    </select></td>
                <td><input type="text" value="<?php echo $output['search_field_value']; ?>" name="search_field_value"
                           class="txt"></td>
                <td><select name="search_sort">
                        <option value=""><?php echo $lang['nc_sort'] ?></option>
                        <option
                            <?php if ($output['search_sort'] == 'member_login_time desc'){ ?>selected='selected'<?php } ?>
                            value="member_login_time desc"><?php echo $lang['member_index_last_login'] ?></option>
                        <option
                            <?php if ($output['search_sort'] == 'member_login_num desc'){ ?>selected='selected'<?php } ?>
                            value="member_login_num desc"><?php echo $lang['member_index_login_time'] ?></option>
                    </select></td>
                <td><select name="search_state">
                        <option <?php if ($_GET['search_state'] == ''){ ?>selected='selected'<?php } ?>
                                value=""><?php echo $lang['member_index_state']; ?></option>
                        <option <?php if ($_GET['search_state'] == 'no_informallow'){ ?>selected='selected'<?php } ?>
                                value="no_informallow"><?php echo $lang['member_index_inform_deny']; ?></option>
                        <option <?php if ($_GET['search_state'] == 'no_isbuy'){ ?>selected='selected'<?php } ?>
                                value="no_isbuy"><?php echo $lang['member_index_buy_deny']; ?></option>
                        <option <?php if ($_GET['search_state'] == 'no_isallowtalk'){ ?>selected='selected'<?php } ?>
                                value="no_isallowtalk"><?php echo $lang['member_index_talk_deny']; ?></option>
                        <option <?php if ($_GET['search_state'] == 'no_memberstate'){ ?>selected='selected'<?php } ?>
                                value="no_memberstate"><?php echo $lang['member_index_login_deny']; ?></option>
                    </select></td>
                <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search "
                       title="<?php echo $lang['nc_query']; ?>">&nbsp;</a>
                    <?php if ($output['search_field_value'] != '' or $output['search_sort'] != '') { ?>
                        <a href="index.php?act=member&op=member"
                           class="btns "><span><?php echo $lang['nc_cancel_search'] ?></span></a>
                    <?php } ?></td>
            </tr>
            </tbody>
        </table>
    </form>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th colspan="12">
                <div class="title">
                    <h5><?php echo $lang['nc_prompts']; ?></h5>
                    <span class="arrow"></span></div>
            </th>
        </tr>
        <tr>
            <td>
                <ul>
                    <li><?php echo $lang['member_index_help1']; ?></li>
                    <li><?php echo $lang['member_index_help2']; ?></li>
                </ul>
            </td>
        </tr>
        </tbody>
    </table>
    <form method="post" id="form_member">
        <input type="hidden" name="form_submit" value="ok"/>
        <table class="table tb-type2 nobdb">
            <thead>
            <tr class="thead">
                <th>&nbsp;</th>
                <th colspan="2"><?php echo $lang['member_index_name'] ?></th>
                <th class="align-center">基本信息</th>
                <th class="align-center">卡情况</th>
                <th class="align-center">办卡渠道</th>
                <th class="align-center">消费情况</th>
                <th class="align-center">账户余额</th>
                <th class="align-center"><?php echo $lang['member_index_login']; ?></th>
                <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
            </tr>
            <tbody>
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <?php foreach ($output['member_list'] as $k => $v) { ?>
                    <tr class="hover member">
                        <td class="w24"></td>
                        <td class="w48 picture">
                            <div class="size-44x44"><span class="thumb size-44x44"><i></i><img
                                        src="<?php if ($v['member_avatar'] != '') {
                                            echo UPLOAD_SITE_URL . DS . ATTACH_AVATAR . DS . $v['member_avatar'];
                                        } else {
                                            echo UPLOAD_SITE_URL . '/' . ATTACH_COMMON . DS . C('default_user_portrait');
                                        } ?>?<?php echo microtime(); ?>"
                                        onload="javascript:DrawImage(this,44,44);"/></span></div>
                        </td>
                        <td><p class="name"><!--会员名:<strong><?php echo $v['member_name']; ?></strong>-->
                                姓名: <?php echo $v['member_truename']; ?></p>

                            <p class="smallfont">电话:&nbsp;<?php echo $v['Mobile']; ?></p>

                            <p class="smallfont">地址:&nbsp;<?php echo $v['sAddress']; ?></p>

                            <div class="im">
                            </div>
                        </td>
                        <td><p class="name">身份证: <?php echo $v['sIDCard']; ?></p>

                            <p class="smallfont">医保卡:&nbsp;<?php echo $v['MediCardID']; ?></p>

                            <p class="smallfont">健康档案:&nbsp;<?php echo $v['HealthCardID']; ?></p>
                        </td>

                        <td><p class="name">卡类型: <?php if ($v['CardType']==0) {echo '普通卡';} elseif ($v['CardType']==1) {echo '储值卡';} ?></p>

                            <p class="smallfont">卡级别: <?php if ($v['CardGrade']==0) {echo '健康卡';} elseif ($v['CardGrade']==1) {echo '健康金卡';}elseif ($v['CardGrade']==2) {echo '健康钻卡';}  ?></p>

                        </td>
                        <td><p class="name">办卡渠道: <?php echo $v['GetWay']; ?></p>

                            <p class="smallfont">推荐人:&nbsp;<?php echo $v['Referrer']; ?></p>

                        </td>
                        <td><p class="name">末次消费日期: <?php echo substr($v['LastPayDate'],0,10); ?></p>

                            <p class="smallfont">末次消费地点: <?php echo $v['LastPayOrgName']; ?></p>

                        </td>
                        <td class=""><p>预存余额:&nbsp;<strong
                                    class="red"><?php echo $v['available_predeposit']; ?></strong>&nbsp;元</p>
                            <!--<p><?php echo $lang['member_index_frozen']; ?>:&nbsp;<strong class="red"><?php echo $v['freeze_predeposit']; ?></strong>&nbsp;元</p>-->
                            <p>赠送余额: <strong class="red"><?php echo number_format($v['fConsumeBalance'], 2); ?></strong>&nbsp;元
                            </p>

                            <p>消费积分: <strong class="red"><?php echo $v['member_points']; ?></strong></p>
                        </td>
                        <td class="align-center"><?php echo $v['member_state'] == 1 ? $lang['member_edit_allow'] : $lang['member_edit_deny']; ?></td>
                        <td class="align-center"><a
                                href="index.php?act=member&op=member_edit&member_id=<?php echo $v['member_id']; ?>"><?php echo $lang['nc_edit'] ?></a>
                            | <a
                                href="index.php?act=notice&op=notice&member_name=<?php echo ltrim(base64_encode($v['member_name']), '='); ?>"><?php echo $lang['member_index_to_message']; ?></a>
                        </td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr class="no_data">
                    <td colspan="11"><?php echo $lang['nc_no_record'] ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot class="tfoot">
            <?php if (!empty($output['member_list']) && is_array($output['member_list'])) { ?>
                <tr>
                    <td colspan="16">
                        <div class="pagination"> <?php echo $output['page']; ?> </div>
                    </td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
    </form>
</div>
<script>
    $(function () {
        $('#ncsubmit').click(function () {
            $('input[name="op"]').val('member');
            $('#formSearch').submit();
        });
    });
</script>
