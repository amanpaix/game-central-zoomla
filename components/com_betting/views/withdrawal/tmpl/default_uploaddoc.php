<?php
defined('_JEXEC') or die("<h1 style='color: red'>This is a restricted area. Will You mind going somewhere else.</h1>");
$withdrawalDocUploadURL = JRoute::_('index.php?task=withdrawal.uploadWithdrawalDocuments');
?>
<script>
    $("div.myaccount_topsection").find("a[href='<?php echo Redirection::WITHDRAWAL_PROCESS; ?>']").parent().addClass('active');
</script>
<div class="myaccount_body_section">
    <div class="">
        <div class="heading">
            <h2><?php echo JText::_("UPLOAD_DOC");?></h2>
        </div>

        <div class="upload_doc_section">
            <div id="upload_doc_tab">
                <ul class="resp-tabs-list">
                    <li><span class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/single_document_icon.png"></span>Single Document</li>
                    <li><span class="icon"><img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/multiple_document_icon.png"></span>Multiple Document</li>
                </ul>
                <div class="resp-tabs-container">
                    <div>
                        <div class="upload_doc">
                            <p class="mbottom15"><?php  echo JText::_("UPLOAD_MSG");?></p>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" style="margin:0 0 5px;font-family: 'ubuntubold';">
                                        <label><?php echo JText::_("PLEASE_SELECT");?></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="select_box" id="single-doc-select" style="display: block">
                                                    <select tabindex="10" class="custome_input" id="upload-single-doc">
                                                        <option value="default"><?php echo JText::_("SELECT");?></option>
                                                        <?php
                                                        foreach(Constants::$withdrawlDoc_singleID As $key => $value) {
                                                            ?>
                                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="error_tooltip manual_tooltip_error" id="error_upload-single-doc"></div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;">
                                                <div class="document_preview" id="single-document-preview" style="display: none">
                                                    <div class="document_details">
                                                        <span class="document_name"></span>
                                                    </div>
                                                    <div class="document_action">
                                                        <a href="javascript:void(0);" style="cursor: pointer" id="single-doc-edit">
                                                            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/edit_icon.png">
                                                        </a>
                                                        <a href="javascript:void(0);" style="cursor: pointer" id="single-doc-delete">
                                                            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/delete_icon.png">
                                                        </a>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="single-document-size-error" id="single-document-size-error" />
                                                <div class="error_tooltip manual_tooltip_error" id="error_single-document-size-error"></div>
                                            </div>
                                            <div class="form-group" style="display: none;" id="single-doc-progress-bar">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
                                                        <span class="sr-only">45% <?php echo JText::_("COMPLETE");?></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group last">
                                                <a class="btn-browse brown_bg" tabindex="13" href="javascript:void(0);" id="single-doc-browse" style="display: none;cursor: pointer">
                                                    <span><?php echo JText::_("BETTING_BROWSE");?></span>
                                                    <form id="single-file-upload-form">
                                                        <input type="file" class="doc_upload btn-browse greenbg_btn" accept="text/plain, application/pdf, image/*" id="single-doc-file" title="Browse">
                                                    </form>
                                                </a>
                                                <a class="btn-browse brown_bg" id="single-doc-upload-btn" style="display: none;cursor: pointer" tabindex="13" href="javascript:void(0);"><span><?php echo JText::_("BETTING_EDIT_AVATAR_UPLOAD");?></span></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!---->

                    <div>
                        <div class="upload_doc">
                            <p><?php echo JText::_("DOC_UPLOAD_MSG");?></p>

                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" style="margin:0 0 5px;font-family: 'ubuntubold';">
                                        <label><?php echo JText::_("PLEASE_SELECT_ID_PRUF");?></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="select_box" id="multi-id-doc-select" style="display: block;">
                                                    <select tabindex="10" class="custome_input" id="upload-multi-id-doc">
                                                        <option value="default"><?php echo JText::_("PLEASE_SELECT");?></option>
                                                        <?php
                                                        foreach(Constants::$withdrawalDoc_multipleID As $key => $value) {
                                                            ?>
                                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="error_tooltip manual_tooltip_error" id="error_upload-multi-id-doc"></div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;">
                                                <div class="document_preview"  id="multi-id-document-preview" style="display: none">
                                                    <div class="document_details">
                                                        <span class="document_name"></span>
                                                    </div>
                                                    <div class="document_action">
                                                        <a href="javascript:void(0);" style="cursor: pointer" id="multi-id-doc-edit">
                                                            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/edit_icon.png">
                                                        </a>
                                                        <a href="javascript:void(0);"  style="cursor: pointer" id="multi-id-doc-delete">
                                                            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/delete_icon.png">
                                                        </a>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="multiple-document-id-size-error" id="multiple-document-id-size-error" />
                                                <div class="error_tooltip manual_tooltip_error" id="error_multiple-document-id-size-error"></div>
                                            </div>
                                            <div class="form-group" style="display: none;" id="multiple-id-doc-progress-bar">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"><span class="sr-only">45% <?php echo JText::_("COMPLETE");?></span></div>
                                                </div>
                                            </div>
                                            <div class="form-group last">
                                                <a id="multi-id-doc-browse" style="display: none;cursor: pointer" class="btn-browse brown_bg" tabindex="13" href="javascript:void(0);">
                                                    <span><?php echo JText::_("BETTING_BROWSE");?></span>
                                                    <form id="multi-id-file-upload-form">
                                                        <input type="file" class="doc_upload btn-browse greenbg_btn" accept="text/plain, application/pdf, image/*" id="multi-id-doc-file">
                                                    </form>
                                                </a>
                                                <!--                                                <a class="btn-browse brown_bg" tabindex="13" href="#"><span>Upload</span></a>-->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider col-md-12 col-xs-12 col-sm-12"> </div>
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" style="margin:0 0 5px;font-family: 'ubuntubold';">
                                        <label><?php echo JText::_("ADDRESS_PROOF_MSG");?></label>
                                    </div>
                                </div>
                                <div class="col-md-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-12">
                                            <div class="form-group">
                                                <div class="select_box"  id="multi-add-doc-select" style="display:block;i">
                                                    <select tabindex="10" class="custome_input" id="upload-multi-add-doc">
                                                        <option value="default"><?php echo JText::_("PLEASE_SELECT");?></option>
                                                        <?php
                                                        foreach(Constants::$withdrawalDoc_multipleAddress As $key => $value) {
                                                            ?>
                                                            <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <div class="error_tooltip manual_tooltip_error" id="error_upload-multi-add-doc"></div>
                                                </div>
                                            </div>
                                            <div class="form-group" style="display: none;">
                                                <div id="multi-add-document-preview" style="display: none" class="document_preview">
                                                    <div class="document_details">
                                                        <span class="document_name"></span>
                                                    </div>
                                                    <div class="document_action">
                                                        <a href="javascript:void(0);" id="multi-add-doc-edit" style="cursor: pointer">
                                                            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/edit_icon.png">
                                                        </a>
                                                        <a href="javascript:void(0);" id="multi-add-doc-delete" style="cursor: pointer">
                                                            <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/delete_icon.png">
                                                        </a>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="multiple-document-add-size-error" id="multiple-document-add-size-error" />
                                                <div class="error_tooltip manual_tooltip_error" id="error_multiple-document-add-size-error"></div>
                                            </div>
                                            <div class="form-group" style="display: none;" id="multiple-add-doc-progress-bar">
                                                <div class="progress">
                                                    <div class="progress-bar" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%"><span class="sr-only">45% Complete</span></div>
                                                </div>
                                            </div>
                                            <div class="form-group last">
                                                <a class="btn-browse brown_bg" tabindex="13" href="javascript:void(0);"  id="multi-add-doc-browse" style="display: none;cursor: pointer">
                                                    <span><?php echo JText::_("BETTING_BROWSE");?></span>
                                                    <form id="multi-add-file-upload-form" >
                                                        <input type="file" class="doc_upload btn-browse greenbg_btn" accept="text/plain, application/pdf, image/*" id="multi-add-doc-file">
                                                    </form>
                                                </a>
                                                <a class="btn-browse brown_bg" tabindex="13" href="javascript:void(0);" id="multi-doc-upload-btn" style="display: none;cursor: pointer">
                                                    <span><?php echo JText::_("BETTING_EDIT_AVATAR_UPLOAD");?></span>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="upload_doc_thankyou text-center" style="display: none;">
                    <img src="<?php echo Redirection::BASE; ?>/templates/shaper_helix3/images/my_account/withdraw/upload_doc_thankyou_icon.png" alt="">
                    <h2><?php echo JText::_("AWESM");?></h2>
                    <p><?php JText::_("YOUR_ID_UPLOADED");?><a href="<?php echo Redirection::URL_TERMS; ?>"><?php echo JText::_("LOYELTY_TERMS_CONFITION");?></a><?php echo JText::_("FOR_WITHDRAWL");?>.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var withdrawalDocUploadURL = <?php echo json_encode($withdrawalDocUploadURL); ?>;
</script>
<?php
Html::addJs(JUri::base()."templates/shaper_helix3/js/custom/withdrawal-uploaddoc.js");
?>
