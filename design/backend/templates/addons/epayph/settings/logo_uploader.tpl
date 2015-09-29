<div id="epayph_logo_uploader" class="in collapse{if !$runtime.company_id && !fn_allowed_for('MULTIVENDOR') && !$runtime.simple_ultimate} disable-overlay-wrap{/if}">
    {if !$runtime.company_id && !fn_allowed_for('MULTIVENDOR') && !$runtime.simple_ultimate}
    <div class="disable-overlay" id="pp_logo_disable_overlay"></div>
    {/if}
    <div class="control-group">
        <label class="control-label" for="elm_epayph_logo">{__("epayph_logo")}:</label>
        <div class="controls">
            {include file="common/attach_images.tpl" image_name="epayph_logo" image_object_type="epayph_logo" image_pair=$pp_settings.main_pair no_thumbnail=true}
            {if fn_allowed_for("ULTIMATE") && !$runtime.company_id}
            <div class="right update-for-all">
                {include file="buttons/update_for_all.tpl" display=true object_id="pp_settings" name="pp_settings[pp_logo_update_all_vendors]" hide_element="epayph_logo_uploader"}
            </div>
            {/if}
        </div>
    </div>
</div>
<script type="text/javascript">
    Tygh.$(document).ready(function(){
    var $ = Tygh.$;
    $('.cm-update-for-all-icon[data-ca-hide-id=epayph_logo_uploader]').on('click', function() {
        $('#epayph_logo_uploader').toggleClass('disable-overlay-wrap');
        $('#pp_logo_disable_overlay').toggleClass('disable-overlay');
    });
});
</script>
