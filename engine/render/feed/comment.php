<?php
if($resCom["result"]){
    foreach($resCom as $comKey=>$comValue){
        if(is_int($comKey)){
            $comDisplayName=$configClass->name($comValue,false);
            $comImgAltText="";
            $comImg="";
            if($comValue["image_profile"]==""){
                $comImg=WEB_URL."inc/img/empty-avatar.png";
                $comImgAltText="No Image Avatar";
            }else{
                $comImg=WEB_URL."img/profile/tb/".$comValue["image_profile"];
                $comImgAltText=$displayName;
            }
            $comTime=strtotime($comValue["date_pc"]);
            ?>
            <div id="iMPostComment_<?php echo $comValue["id_pc"]; ?>" class="iMPostComment">
                <div class="iMPostCommentHeader clearfix">
                    <span class="iMPostCommentUserImage"><a href="<?php echo WEB_URL.$comValue["username_profile"]; ?>"><img src="<?php echo $comImg; ?>" alt="<?php echo $comImgAltText; ?>" title="<?php echo $comValue["username_profile"]; ?>" class="iMPostCommentImage" /></a></span>
                    <h6 class="iMPostCommentUserName"><a href="<?php echo WEB_URL.$comValue["username_profile"]; ?>"><?php echo $comDisplayName; ?></a></h6>
                    <span class="iMPostCommentTime"><?php echo $configClass->ago($comTime); ?></span>
                    <?php
                    if(USER_ID>0){
                        if(USER_ID==$comValue["id_profile"]){
                        ?>
                            <span class="iMPostCommentDelete"><a href="#" onclick="return confirmDelComment('<?php echo $comValue["id_pc"]; ?>');"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/delete.png" alt="X" /></a></span>
                        <?php
                        }else{
                        ?>
                            <span class="iMPostCommentReport"><a href="#" onclick="return reportComment('<?php echo $comValue["id_pc"]; ?>');"><img src="<?php echo WEB_URL; ?>inc/img/v2/base/report.png" alt="Warning" /></a></span>
                        <?php
                        }
                        if (isset($comValue['vote_pct']) && $comValue['vote_pct'] != '') {
                        ?>
                        <span id="iMPostCommentLove_<?php echo $comValue["id_pc"]; ?>" onclick="removeCommentVote('<?php echo $comValue["id_pc"]; ?>');" class="iMPostCommentLove">
                            <span id="iMPostCommentLoveTxt_<?php echo $comValue["id_pc"]; ?>" class="iMPostCommentLoveTxt" <?php if($comValue["thumb_up_pc"]<1){ echo 'style="display:none;"';} ?>>(<?php echo $comValue["thumb_up_pc"]; ?>)</span>
                            <img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart-red.png" alt="Red Heart" />
                        </span>
                        <?php
                        } else {
                        ?>
                        <span id="iMPostCommentLove_<?php echo $comValue["id_pc"]; ?>" onclick="iCommentVote('<?php echo $comValue["id_pc"]; ?>');" class="iMPostCommentLove">
                            <span id="iMPostCommentLoveTxt_<?php echo $comValue["id_pc"]; ?>" class="iMPostCommentLoveTxt" <?php if($comValue["thumb_up_pc"]<1){ echo 'style="display:none;"';} ?>>(<?php echo $comValue["thumb_up_pc"]; ?>)</span>
                            <img src="<?php echo WEB_URL; ?>inc/img/v2/base/heart.png" alt="Heart" />
                        </span>
                    <?php
                        }
                    }
                    ?>
                </div>
                <p class="iMPostCommentText">
                    <?php echo $comValue["text_pc"]; ?>
                </p>
            </div>
            <?php
        }
    }
}