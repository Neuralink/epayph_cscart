{if fn_allowed_for('ULTIMATE') && !$runtime.company_id || $runtime.simple_ultimate || fn_allowed_for('MULTIVENDOR')}
<div id="text_epayph_status_map" class="in collapse">
    {assign var="statuses" value=$smarty.const.STATUSES_ORDER|fn_get_simple_statuses}
    <div class="control-group">
        <label class="control-label" for="elm_epayph_refunded">{__("refunded")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][refunded]" id="elm_epayph_refunded">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.refunded) && $pp_settings.pp_statuses.refunded == $k) || (!isset($pp_settings.pp_statuses.refunded) && $k == 'I')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_completed">{__("completed")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][completed]" id="elm_epayph_completed">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.completed) && $pp_settings.pp_statuses.completed == $k) || (!isset($pp_settings.pp_statuses.completed) && $k == 'P')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_pending">{__("pending")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][pending]" id="elm_epayph_pending">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.pending) && $pp_settings.pp_statuses.pending == $k) || (!isset($pp_settings.pp_statuses.pending) && $k == 'O')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_canceled_reversal">{__("canceled_reversal")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][canceled_reversal]" id="elm_epayph_canceled_reversal">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.canceled_reversal) && $pp_settings.pp_statuses.canceled_reversal == $k) || (!isset($pp_settings.pp_statuses.canceled_reversal) && $k == 'I')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_created">{__("created")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][created]" id="elm_epayph_created">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.created) && $pp_settings.pp_statuses.created == $k) || (!isset($pp_settings.pp_statuses.created) && $k == 'O')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_denied">{__("denied")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][denied]" id="elm_epayph_denied">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.denied) && $pp_settings.pp_statuses.denied == $k) || (!isset($pp_settings.pp_statuses.denied) && $k == 'I')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_expired">{__("expired")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][expired]" id="elm_epayph_expired">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.expired) && $pp_settings.pp_statuses.expired == $k) || (!isset($pp_settings.pp_statuses.expired) && $k == 'F')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_reversed">{__("reversed")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][reversed]" id="elm_epayph_reversed">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.reversed) && $pp_settings.pp_statuses.reversed == $k) || (!isset($pp_settings.pp_statuses.reversed) && $k == 'I')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_processed">{__("processed")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][processed]" id="elm_epayph_processed">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.processed) && $pp_settings.pp_statuses.processed == $k) || (!isset($pp_settings.pp_statuses.processed) && $k == 'P')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_voided">{__("voided")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][voided]" id="elm_epayph_voided">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.voided) && $pp_settings.pp_statuses.voided == $k) || (!isset($pp_settings.pp_statuses.voided) && $k == 'P')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="elm_epayph_voided">{__("failed")}:</label>
        <div class="controls">
            <select name="pp_settings[pp_statuses][failed]" id="elm_epayph_failed">
                {foreach from=$statuses item="s" key="k"}
                <option value="{$k}" {if (isset($pp_settings.pp_statuses.failed) && $pp_settings.pp_statuses.failed == $k) || (!isset($pp_settings.pp_statuses.failed) && $k == 'F')}selected="selected"{/if}>{$s}</option>
                {/foreach}
            </select>
        </div>
    </div>
</div>
{/if}