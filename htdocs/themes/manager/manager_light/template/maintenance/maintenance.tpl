{{
/**
* CIOpenReview
*
* An Open Source Review Site Script
*
* @package        CIOpenReview
* @subpackage          manager
* @author        CIOpenReview.com
* @copyright           Copyright (c) 2015 MindBrite.com , Portions Copyright (c) 2011-2012, OpenReviewScript.org
* @license        This file is part of CIOpenReview - free software licensed under the GNU General Public License version 2
* @link        http://ciopenreview.com
*/
// ------------------------------------------------------------------------
//
/**    This file is part of CIOpenReview.
*
*    CIOpenReview is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 2 of the License, or
*    (at your option) any later version.
*
*    CIOpenReview is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with CIOpenReview.  If not, see <http://www.gnu.org/licenses/>.
*/

}}
<div id="content">
    {{ if ($message!==''): }}
    <p>&nbsp;</p>

    <h3>{{= $message }}</h3>

    <p>&nbsp;</p>
    {{ endif }}
    <div class="myform">
        <div class="header_row">{{= lang('manager_maintenance_title') }}</div>
        <form id="form" class="myform" name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/maintenance' }}">
            <div class="formblock">
                <div class="formleftwide">
                    <label>{{= lang('manager_maintenance_update_sitemap') }}
                        <span class="small">{{= lang('manager_maintenance_update_sitemap_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="submit" name="update_sitemap" id="button"
                           value="{{= lang('manager_maintenance_update_sitemap_button') }}"/>
                </div>
            </div>
        </form>
        <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
              name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/maintenance' }}">
            <div class="formblock">
                <div class="formleftwide">
                    <label>{{= lang('manager_maintenance_delete_session') }}
                        <span class="small">{{= lang('manager_maintenance_delete_session_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="submit" name="session_submit" id="button"
                           value="{{= lang('manager_maintenance_delete_session_button') }}"/>
                </div>
            </div>
        </form>
        <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
              name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/maintenance' }}">
            <div class="formblock">
                <div class="formleftwide">
                    <label>{{= lang('manager_maintenance_delete_log') }}
                        <span class="small">{{= lang('manager_maintenance_delete_log_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="submit" name="log_submit" id="button"
                           value="{{= lang('manager_maintenance_delete_log_button') }}"/>
                </div>
            </div>
        </form>
        <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
              name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/maintenance' }}">
            <div class="formblock">
                <div class="formleftwide">
                    <label>{{= lang('manager_maintenance_delete_cache') }}
                        <span class="small">{{= lang('manager_maintenance_delete_cache_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="submit" name="cache_submit" id="button"
                           value="{{= lang('manager_maintenance_delete_cache_button') }}"/>
                </div>
            </div>
        </form>
        <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
              name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/maintenance' }}">
            <div class="formblock">
                <div class="formleftwide">
                    <label>{{= lang('manager_maintenance_repair') }}
                        <span class="small">{{= lang('manager_maintenance_repair_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="submit" name="repair_submit" id="button"
                           value="{{= lang('manager_maintenance_repair_button') }}"/>
                </div>
            </div>
        </form>
        <form id="form" class="myform" onSubmit="return confirm('{{= lang('manager_maintenance_confirm') }}')"
              name="form" method="post" enctype="multipart/form-data"
              action="{{= base_url() . 'manager/maintenance' }}">
            <div class="formblock">
                <div class="formleftwide">
                    <label>{{= lang('manager_maintenance_update_db') }}
                        <span class="small">{{= lang('manager_maintenance_update_db_info') }}</span>
                    </label>
                </div>
                <div class="formright">
                    <input type="submit" name="db_update_submit" id="button"
                           value="{{= lang('manager_maintenance_update_db_button') }}"/>
                </div>
            </div>
        </form>
    </div>
</div>